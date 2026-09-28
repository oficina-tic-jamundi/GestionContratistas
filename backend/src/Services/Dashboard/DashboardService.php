<?php

declare(strict_types=1);

namespace Sigcon\Services\Dashboard;

use DateTimeZone;
use Sigcon\Helpers\Clock;
use Sigcon\Helpers\DateTimes;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\PaymentStatus;
use Sigcon\Models\ReportStatus;
use Sigcon\Repositories\ContractScope;
use Sigcon\Repositories\DashboardRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Security\Permission;
use Sigcon\Services\Jobs\JobAdminService;

/**
 * Tablero de inicio (ADR-019). Cada sección aparece según los permisos del usuario y sus
 * cifras respetan su alcance: el contratista ve lo suyo, el supervisor lo que supervisa y
 * la administración, todo.
 */
final class DashboardService
{
    /** Días hacia adelante para "contratos por terminar". */
    public const ENDING_WITHIN_DAYS = 30;

    /** Días hacia adelante para avisar de tareas con fecha objetivo próxima (ADR-021). */
    public const DUE_WITHIN_DAYS = 15;

    public function __construct(
        private readonly DashboardRepository $dashboard,
        private readonly JobAdminService $jobs,
        private readonly ContractorOverviewService $contractorOverview,
        private readonly AuthContext $auth,
        private readonly Clock $clock,
    ) {
    }

    /** @return array<string, mixed> */
    public function build(): array
    {
        $scope = ContractScope::forUser($this->auth);
        $result = [];

        if (!$scope->isEmpty()) {
            $today = $this->clock->now()->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $result['contracts'] = [
                'by_status' => $this->labelled($this->dashboard->contractsByStatus($scope), ContractStatus::cases()),
                'ending_soon' => $this->dashboard->contractsEndingBefore(
                    $scope,
                    $today->format('Y-m-d'),
                    $today->modify('+' . self::ENDING_WITHIN_DAYS . ' days')->format('Y-m-d'),
                ),
                'ending_within_days' => self::ENDING_WITHIN_DAYS,
            ];
            $draftOwner = $this->auth->can(Permission::ContractsViewAll) ? null : $this->auth->userId();
            $result['reports'] = [
                'by_status' => $this->labelled($this->dashboard->reportsByStatus($scope, $draftOwner), ReportStatus::cases()),
            ];
            if ($this->auth->can(Permission::ReportsReview)) {
                // Lo que espera al supervisor en los contratos que ÉL supervisa.
                $result['reports']['awaiting_my_review'] = $this->serializeReports($this->dashboard->reportsInStatus(
                    $scope,
                    [ReportStatus::Submitted->value, ReportStatus::Resubmitted->value, ReportStatus::InReview->value],
                    $this->auth->userId(),
                    null,
                ));
            }
            if ($this->auth->can(Permission::ReportsCreate)) {
                // Lo que espera al contratista: borradores sin enviar y correcciones pedidas.
                $result['reports']['awaiting_my_action'] = $this->serializeReports($this->dashboard->reportsInStatus(
                    $scope,
                    [ReportStatus::Draft->value, ReportStatus::Observed->value],
                    null,
                    $this->auth->userId(),
                ));
            }
            // Alertas preventivas: tareas con fecha objetivo próxima o ya vencida.
            $result['deadlines'] = [
                'within_days' => self::DUE_WITHIN_DAYS,
                'tasks' => $this->dashboard->tasksDueBefore(
                    $scope,
                    $today->format('Y-m-d'),
                    $today->modify('+' . self::DUE_WITHIN_DAYS . ' days')->format('Y-m-d'),
                ),
            ];

            $payments = $this->dashboard->paymentsByStatus($scope);
            $result['payments'] = [
                'by_status' => array_map(
                    static fn (PaymentStatus $s) => ['status' => $s->value, 'label' => $s->label()] + ($payments[$s->value] ?? ['count' => 0, 'amount' => '0.00']),
                    PaymentStatus::cases(),
                ),
                'to_approve' => $this->auth->can(Permission::PaymentsApprove) ? ($payments[PaymentStatus::ReadyForApproval->value]['count'] ?? 0) : null,
                'to_register' => $this->auth->can(Permission::PaymentsRegister) ? ($payments[PaymentStatus::Approved->value]['count'] ?? 0) : null,
            ];
        }

        if ($this->auth->can(Permission::ActivitiesExecute)) {
            // Panel del contratista: avance y estado de sus propios contratos (ADR-021).
            $result['my_contracts'] = $this->contractorOverview->build();
        }

        if ($this->auth->can(Permission::JobsManage)) {
            $status = $this->jobs->status();
            $result['system'] = [
                'jobs_stale' => $status['stale'],
                'jobs_failed' => $status['counts']['failed'] ?? 0,
                'jobs_pending' => $status['counts']['pending'] ?? 0,
            ];
        }

        return $result;
    }

    /**
     * @template T of \BackedEnum
     * @param array<string, int> $counts
     * @param list<T> $cases
     * @return list<array{status: string, label: string, count: int}>
     */
    private function labelled(array $counts, array $cases): array
    {
        return array_map(static fn (\BackedEnum $s) => [
            'status' => (string) $s->value,
            'label' => method_exists($s, 'label') ? (string) $s->label() : (string) $s->value,
            'count' => $counts[(string) $s->value] ?? 0,
        ], $cases);
    }

    /**
     * @param list<array{uuid: string, number: int, contract_number: string, contractor: string, status: string, updated_at: \DateTimeImmutable}> $reports
     * @return list<array<string, mixed>>
     */
    private function serializeReports(array $reports): array
    {
        return array_map(static fn (array $r) => [
            'uuid' => $r['uuid'],
            'number' => $r['number'],
            'contract_number' => $r['contract_number'],
            'contractor' => $r['contractor'],
            'status' => $r['status'],
            'status_label' => ReportStatus::from($r['status'])->label(),
            'updated_at' => DateTimes::toApi($r['updated_at']),
        ], $reports);
    }
}
