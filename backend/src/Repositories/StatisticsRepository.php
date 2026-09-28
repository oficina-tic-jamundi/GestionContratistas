<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use Sigcon\Database\Database;

/**
 * Esquema de pagos por contrato (ADR-021): cuántos pagos se pactaron, cuántos se han pagado y
 * cuánto falta. Aplica el alcance del usuario; no estima pagos que el contrato no declare.
 */
final class StatisticsRepository
{
    public function __construct(private readonly Database $db)
    {
    }

    /**
     * @return list<array{uuid: string, contract_number: string, contractor: string, department: string,
     *     status: string, start_date: string, end_date: string, total_value: string, payment_count: ?int,
     *     paid_count: int, paid_amount: string, committed_amount: string, in_process_count: int, progress: string}>
     */
    public function paymentSchedule(ContractScope $scope): array
    {
        if ($scope->isEmpty()) {
            return [];
        }
        [$condition, $params] = $scope->toSql();

        return array_map(static fn (array $r) => [
            'uuid' => (string) $r['uuid'],
            'contract_number' => (string) $r['contract_number'],
            'contractor' => (string) $r['contractor'],
            'department' => (string) $r['department'],
            'status' => (string) $r['status'],
            'start_date' => (string) $r['start_date'],
            'end_date' => (string) $r['end_date'],
            'total_value' => (string) $r['total_value'],
            'payment_count' => $r['payment_count'] === null ? null : (int) $r['payment_count'],
            'paid_count' => (int) $r['paid_count'],
            'paid_amount' => (string) $r['paid_amount'],
            'committed_amount' => (string) $r['committed_amount'],
            'in_process_count' => (int) $r['in_process_count'],
            'progress' => number_format((float) $r['progress'], 2, '.', ''),
        ], $this->db->fetchAll(
            "SELECT c.uuid, c.contract_number, ct.name AS contractor, d.name AS department, c.status,
                    c.start_date, c.end_date, c.total_value, c.payment_count,
                    (SELECT COUNT(*) FROM payments p WHERE p.contract_id = c.id AND p.status = 'paid') AS paid_count,
                    (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.contract_id = c.id AND p.status = 'paid') AS paid_amount,
                    (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.contract_id = c.id AND p.status <> 'cancelled') AS committed_amount,
                    (SELECT COUNT(*) FROM payments p WHERE p.contract_id = c.id
                        AND p.status IN ('draft', 'ready_for_approval', 'approved')) AS in_process_count,
                    COALESCE((SELECT SUM(a.progress * a.weight) / NULLIF(SUM(a.weight), 0) FROM activities a
                        WHERE a.contract_id = c.id AND a.parent_id IS NULL), 0) AS progress
             FROM contracts c
             JOIN contractors ct ON ct.id = c.contractor_id
             JOIN departments d ON d.id = c.department_id
             WHERE c.deleted_at IS NULL AND {$condition}
             ORDER BY FIELD(c.status, 'active', 'suspended', 'terminated', 'liquidated', 'draft', 'archived'), c.end_date, c.id",
            $params,
        ));
    }
}
