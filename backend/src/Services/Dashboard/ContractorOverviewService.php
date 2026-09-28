<?php

declare(strict_types=1);

namespace Sigcon\Services\Dashboard;

use DateTimeImmutable;
use DateTimeZone;
use Sigcon\Models\Activity;
use Sigcon\Models\ActivityLevel;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\ReportStatus;
use Sigcon\Models\WorkStatus;
use Sigcon\Repositories\ActivityRepository;
use Sigcon\Repositories\ContractorOverviewRepository;
use Sigcon\Repositories\PaymentRuleSettingsRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Services\Activities\ProgressCalculator;
use Sigcon\Services\Activities\WorkStatusResolver;

/**
 * Panel del contratista (ADR-021): avance de cada contrato propio, estado derivado de cada
 * obligación y actividad, observaciones vigentes y el aviso de pago según las reglas de
 * elegibilidad que la Alcaldía tenga activas. No inventa plazos ni reglas.
 */
final class ContractorOverviewService
{
    /** Estados de un informe presentado que el supervisor aún no ha decidido. */
    private const UNDER_REVIEW = ['submitted', 'resubmitted', 'in_review'];

    public function __construct(
        private readonly ContractorOverviewRepository $overview,
        private readonly ActivityRepository $activities,
        private readonly PaymentRuleSettingsRepository $rules,
        private readonly AuthContext $auth,
    ) {
    }

    /** @return list<array<string, mixed>> */
    public function build(): array
    {
        $userId = $this->auth->userId();
        if ($userId === null) {
            return [];
        }
        $minProgress = $this->minProgressRule();

        return array_map(fn (array $contract) => $this->contract($contract, $minProgress), $this->overview->contractsFor($userId));
    }

    /**
     * @param array{id: int, uuid: string, contract_number: string, object: string, status: string, department: string, end_date: string} $contract
     * @return array<string, mixed>
     */
    private function contract(array $contract, ?int $minProgress): array
    {
        $activities = $this->activities->forContract($contract['id']);
        $progress = ProgressCalculator::weightedAverage($this->activities->obligationValues($contract['id']));
        $latest = $this->overview->latestSubmittedReport($contract['id']);

        $observations = [];
        $inReviewIds = [];
        if ($latest !== null && $latest['status'] === ReportStatus::Observed->value) {
            $observations = $this->overview->latestObservations($latest['id']);
        } elseif ($latest !== null && in_array($latest['status'], self::UNDER_REVIEW, true)) {
            $inReviewIds = $this->overview->updatedBetween($contract['id'], self::dayStart($latest['period_start']), self::dayStart($latest['period_end'])->modify('+1 day'));
        }
        $approvedUntil = $this->overview->approvedUntil($contract['id']);
        $statuses = WorkStatusResolver::resolve(
            $activities,
            array_values(array_unique(array_filter(array_column($observations, 'activity_id'), static fn (?int $id) => $id !== null))),
            $inReviewIds,
            $approvedUntil === null ? null : self::dayStart($approvedUntil)->modify('+1 day'),
            $this->overview->lastCompletedAt($contract['id']),
        );

        $children = [];
        foreach ($activities as $activity) {
            if ($activity->parentId !== null) {
                $children[$activity->parentId][] = $activity;
            }
        }
        $leavesOf = static function (Activity $node) use (&$leavesOf, $children): array {
            if (!isset($children[$node->id])) {
                return [$node];
            }
            $leaves = [];
            foreach ($children[$node->id] as $child) {
                array_push($leaves, ...$leavesOf($child));
            }

            return $leaves;
        };
        $present = static fn (Activity $a) => [
            'uuid' => $a->uuid,
            'title' => $a->title,
            'level' => $a->level->value,
            'level_label' => $a->level->label(),
            'priority' => $a->priority?->value,
            'priority_label' => $a->priority?->label(),
            'progress' => $a->progress,
            'status' => ($statuses[$a->id] ?? WorkStatus::Pending)->value,
            'status_label' => ($statuses[$a->id] ?? WorkStatus::Pending)->label(),
        ];

        $obligations = [];
        $obligationCounts = self::emptyCounts();
        $itemCounts = self::emptyCounts();
        foreach ($activities as $activity) {
            if ($activity->level !== ActivityLevel::Obligation) {
                continue;
            }
            $leaves = $leavesOf($activity);
            $items = isset($children[$activity->id]) ? array_map($present, $leaves) : [];
            $obligations[] = $present($activity) + [
                'observations' => array_values(array_map(
                    static fn (array $o) => $o['text'],
                    array_filter($observations, static fn (array $o) => $o['activity_id'] === $activity->id),
                )),
                'items' => $items,
                'items_approved' => count(array_filter($leaves, static fn (Activity $l) => ($statuses[$l->id] ?? null) === WorkStatus::Approved)),
            ];
            self::count($obligationCounts, $statuses[$activity->id] ?? WorkStatus::Pending);
            foreach ($leaves as $leaf) {
                self::count($itemCounts, $statuses[$leaf->id] ?? WorkStatus::Pending);
            }
        }

        $status = ContractStatus::from($contract['status']);

        return [
            'contract' => [
                'uuid' => $contract['uuid'],
                'contract_number' => $contract['contract_number'],
                'object' => $contract['object'],
                'department' => $contract['department'],
                'status' => $status->value,
                'status_label' => $status->label(),
                'end_date' => $contract['end_date'],
            ],
            'progress' => $progress,
            'latest_report' => $latest === null ? null : [
                'uuid' => $latest['uuid'],
                'number' => $latest['number'],
                'status' => $latest['status'],
                'status_label' => ReportStatus::from($latest['status'])->label(),
            ],
            'general_observations' => array_values(array_map(
                static fn (array $o) => $o['text'],
                array_filter($observations, static fn (array $o) => $o['activity_id'] === null),
            )),
            'payment_notice' => $minProgress === null ? null : [
                'rule' => 'min_progress',
                'required' => $minProgress,
                'met' => (float) $progress >= $minProgress,
            ],
            'counts' => ['obligations' => $obligationCounts, 'items' => $itemCounts],
            'obligations' => $obligations,
        ];
    }

    /** Avance mínimo exigido para pagar, solo si la Alcaldía activó esa regla. */
    private function minProgressRule(): ?int
    {
        $rule = $this->rules->all()['min_progress'] ?? null;
        if ($rule === null || !$rule['enabled']) {
            return null;
        }
        $min = $rule['params']['min_percent'] ?? null;

        return is_int($min) || (is_string($min) && ctype_digit($min)) ? (int) $min : null;
    }

    /** Inicio del día local (zona de la aplicación) de una fecha 'Y-m-d', en UTC. */
    private static function dayStart(string $date): DateTimeImmutable
    {
        return (new DateTimeImmutable($date . ' 00:00:00', new DateTimeZone(date_default_timezone_get())))
            ->setTimezone(new DateTimeZone('UTC'));
    }

    /** @return array{total: int, approved: int, in_review: int, observed: int, pending: int} */
    private static function emptyCounts(): array
    {
        return ['total' => 0, 'approved' => 0, 'in_review' => 0, 'observed' => 0, 'pending' => 0];
    }

    /** @param array{total: int, approved: int, in_review: int, observed: int, pending: int} $counts */
    private static function count(array &$counts, WorkStatus $status): void
    {
        $counts['total']++;
        $counts[$status->value]++;
    }
}
