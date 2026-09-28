<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\Helpers\DateTimes;

/**
 * Consultas del panel del contratista (ADR-021). Todas parten de los contratos cuya cuenta
 * vinculada es la del usuario: nunca exponen contratos ajenos.
 */
final class ContractorOverviewRepository
{
    public function __construct(private readonly Database $db)
    {
    }

    /**
     * Contratos del contratista que se siguen en el panel (en ejecución, suspendidos o por cerrar).
     *
     * @return list<array{id: int, uuid: string, contract_number: string, object: string, status: string, department: string, end_date: string}>
     */
    public function contractsFor(int $userId): array
    {
        return array_map(static fn (array $r) => [
            'id' => (int) $r['id'],
            'uuid' => (string) $r['uuid'],
            'contract_number' => (string) $r['contract_number'],
            'object' => (string) $r['object'],
            'status' => (string) $r['status'],
            'department' => (string) $r['department'],
            'end_date' => (string) $r['end_date'],
        ], $this->db->fetchAll(
            "SELECT c.id, c.uuid, c.contract_number, c.object, c.status, c.end_date, d.name AS department
             FROM contracts c
             JOIN contractors ct ON ct.id = c.contractor_id
             JOIN departments d ON d.id = c.department_id
             WHERE ct.user_id = :user AND c.deleted_at IS NULL AND c.status IN ('active', 'suspended', 'terminated')
             ORDER BY FIELD(c.status, 'active', 'suspended', 'terminated'), c.end_date, c.id",
            ['user' => $userId],
        ));
    }

    /**
     * Último informe presentado (sin contar borradores ni rechazados): define qué está en
     * revisión o con observaciones.
     *
     * @return ?array{id: int, uuid: string, number: int, status: string, period_start: string, period_end: string}
     */
    public function latestSubmittedReport(int $contractId): ?array
    {
        $rows = $this->db->fetchAll(
            "SELECT id, uuid, number, status, period_start, period_end FROM reports
             WHERE contract_id = :c AND status NOT IN ('draft', 'rejected')
             ORDER BY period_end DESC, number DESC LIMIT 1",
            ['c' => $contractId],
        );
        if (!isset($rows[0])) {
            return null;
        }
        $r = $rows[0];

        return [
            'id' => (int) $r['id'],
            'uuid' => (string) $r['uuid'],
            'number' => (int) $r['number'],
            'status' => (string) $r['status'],
            'period_start' => (string) $r['period_start'],
            'period_end' => (string) $r['period_end'],
        ];
    }

    /**
     * Observaciones de la última revisión del informe, si esa revisión pidió correcciones
     * (o reabrió el informe). activity_id null = observación general.
     *
     * @return list<array{activity_id: ?int, text: string}>
     */
    public function latestObservations(int $reportId): array
    {
        return array_map(static fn (array $r) => [
            'activity_id' => $r['activity_id'] === null ? null : (int) $r['activity_id'],
            'text' => (string) $r['text'],
        ], $this->db->fetchAll(
            "SELECT o.activity_id, o.text FROM report_observations o
             JOIN report_reviews rv ON rv.id = o.review_id
             WHERE rv.id = (SELECT id FROM report_reviews WHERE report_id = :r ORDER BY created_at DESC, id DESC LIMIT 1)
               AND rv.decision IN ('observed', 'reopened')
             ORDER BY o.id",
            ['r' => $reportId],
        ));
    }

    /** Fin del último período con informe aprobado (fecha local 'Y-m-d'), o null. */
    public function approvedUntil(int $contractId): ?string
    {
        $value = $this->db->fetchAll(
            "SELECT MAX(period_end) AS until FROM reports WHERE contract_id = :c AND status = 'approved'",
            ['c' => $contractId],
        )[0]['until'] ?? null;

        return $value === null ? null : (string) $value;
    }

    /**
     * Momento en que cada elemento llegó por última vez al 100 %.
     *
     * @return array<int, DateTimeImmutable> id de actividad => fecha (UTC)
     */
    public function lastCompletedAt(int $contractId): array
    {
        $result = [];
        foreach ($this->db->fetchAll(
            'SELECT pu.activity_id, MAX(pu.recorded_at) AS at FROM progress_updates pu
             JOIN activities a ON a.id = pu.activity_id
             WHERE a.contract_id = :c AND pu.new_progress >= 100
             GROUP BY pu.activity_id',
            ['c' => $contractId],
        ) as $row) {
            $result[(int) $row['activity_id']] = DateTimes::fromDb((string) $row['at']);
        }

        return $result;
    }

    /** @return list<int> actividades con avances registrados en el intervalo [desde, hasta) (UTC) */
    public function updatedBetween(int $contractId, DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        return array_map(static fn (array $r) => (int) $r['activity_id'], $this->db->fetchAll(
            'SELECT DISTINCT pu.activity_id FROM progress_updates pu
             JOIN activities a ON a.id = pu.activity_id
             WHERE a.contract_id = :c AND pu.recorded_at >= :from AND pu.recorded_at < :to',
            ['c' => $contractId, 'from' => DateTimes::toDb($from), 'to' => DateTimes::toDb($to)],
        ));
    }
}
