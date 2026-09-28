<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\Helpers\Money;

/**
 * Consultas agregadas para el tablero. Todas aplican el alcance del usuario (ContractScope):
 * cada persona solo cuenta lo que puede ver.
 */
final class DashboardRepository
{
    private const FROM = 'FROM contracts c JOIN contractors ct ON ct.id = c.contractor_id';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, int> estado => cantidad */
    public function contractsByStatus(ContractScope $scope): array
    {
        [$sql, $params] = $scope->toSql();

        return $this->counts('SELECT c.status AS k, COUNT(*) AS n ' . self::FROM . " WHERE c.deleted_at IS NULL AND {$sql} GROUP BY c.status", $params);
    }

    /**
     * Contratos activos que terminan en los próximos días.
     *
     * @return list<array{uuid: string, contract_number: string, contractor: string, end_date: string}>
     */
    public function contractsEndingBefore(ContractScope $scope, string $today, string $limitDate, int $limit = 10): array
    {
        [$sql, $params] = $scope->toSql();

        return array_map(static fn (array $r) => [
            'uuid' => (string) $r['uuid'],
            'contract_number' => (string) $r['contract_number'],
            'contractor' => (string) $r['name'],
            'end_date' => (string) $r['end_date'],
        ], $this->db->fetchAll(
            sprintf("SELECT c.uuid, c.contract_number, ct.name, c.end_date %s WHERE c.deleted_at IS NULL AND c.status = 'active'
                 AND c.end_date >= :today AND c.end_date <= :limit AND %s ORDER BY c.end_date, c.id LIMIT %d", self::FROM, $sql, $limit),
            $params + ['today' => $today, 'limit' => $limitDate],
        ));
    }

    /**
     * Tareas con fecha objetivo próxima o vencida, dentro del alcance del usuario (ADR-021).
     * La fecha objetivo la registra la Alcaldía al planear la tarea: no se inventa ningún plazo.
     *
     * @return list<array{activity_uuid: string, title: string, due_date: string, progress: string,
     *     contract_uuid: string, contract_number: string, contractor: string, department: string, days_left: int}>
     */
    public function tasksDueBefore(ContractScope $scope, string $today, string $limitDate, int $limit = 15): array
    {
        [$sql, $params] = $scope->toSql();

        return array_map(static fn (array $r) => [
            'activity_uuid' => (string) $r['uuid'],
            'title' => (string) $r['title'],
            'due_date' => (string) $r['due_date'],
            'progress' => (string) $r['progress'],
            'contract_uuid' => (string) $r['contract_uuid'],
            'contract_number' => (string) $r['contract_number'],
            'contractor' => (string) $r['contractor'],
            'department' => (string) $r['department'],
            'days_left' => (int) $r['days_left'],
        ], $this->db->fetchAll(
            sprintf(
                "SELECT a.uuid, a.title, a.due_date, a.progress, c.uuid AS contract_uuid, c.contract_number,
                        ct.name AS contractor, d.name AS department, DATEDIFF(a.due_date, :today2) AS days_left
                 FROM activities a
                 JOIN contracts c ON c.id = a.contract_id
                 JOIN contractors ct ON ct.id = c.contractor_id
                 JOIN departments d ON d.id = c.department_id
                 WHERE c.deleted_at IS NULL AND c.status = 'active' AND a.due_date IS NOT NULL
                   AND a.progress < 100 AND a.due_date <= :limit AND %s
                 ORDER BY a.due_date, a.id LIMIT %d",
                $sql,
                $limit,
            ),
            $params + ['today2' => $today, 'limit' => $limitDate],
        ));
    }

    /**
     * @param ?int $draftOwner si no es null, los borradores solo cuentan si son del contratista con esa cuenta
     * @return array<string, int>
     */
    public function reportsByStatus(ContractScope $scope, ?int $draftOwner): array
    {
        [$sql, $params] = $scope->toSql();
        $draft = '';
        if ($draftOwner !== null) {
            $draft = " AND (r.status <> 'draft' OR ct.user_id = :draft_owner)";
            $params['draft_owner'] = $draftOwner;
        }

        return $this->counts(
            'SELECT r.status AS k, COUNT(*) AS n FROM reports r JOIN contracts c ON c.id = r.contract_id JOIN contractors ct ON ct.id = c.contractor_id'
            . " WHERE c.deleted_at IS NULL AND {$sql}{$draft} GROUP BY r.status",
            $params,
        );
    }

    /**
     * Informes que esperan una acción, con su contrato.
     *
     * @param list<string> $statuses
     * @return list<array{uuid: string, number: int, contract_number: string, contractor: string, status: string, updated_at: DateTimeImmutable}>
     */
    public function reportsInStatus(ContractScope $scope, array $statuses, ?int $supervisorUserId, ?int $contractorUserId, int $limit = 10): array
    {
        [$sql, $params] = $scope->toSql();
        $in = [];
        foreach ($statuses as $i => $status) {
            $in[] = ":st{$i}";
            $params["st{$i}"] = $status;
        }
        $extra = '';
        if ($supervisorUserId !== null) {
            $extra .= ' AND c.supervisor_user_id = :only_supervisor';
            $params['only_supervisor'] = $supervisorUserId;
        }
        if ($contractorUserId !== null) {
            $extra .= ' AND ct.user_id = :only_contractor';
            $params['only_contractor'] = $contractorUserId;
        }

        return array_map(static fn (array $r) => [
            'uuid' => (string) $r['uuid'],
            'number' => (int) $r['number'],
            'contract_number' => (string) $r['contract_number'],
            'contractor' => (string) $r['name'],
            'status' => (string) $r['status'],
            'updated_at' => new DateTimeImmutable((string) $r['updated_at'], new \DateTimeZone('UTC')),
        ], $this->db->fetchAll(sprintf(
            'SELECT r.uuid, r.number, r.status, r.updated_at, c.contract_number, ct.name
             FROM reports r JOIN contracts c ON c.id = r.contract_id JOIN contractors ct ON ct.id = c.contractor_id
             WHERE c.deleted_at IS NULL AND %s AND r.status IN (%s)%s ORDER BY r.updated_at, r.id LIMIT %d',
            $sql,
            implode(', ', $in),
            $extra,
            $limit,
        ), $params));
    }

    /**
     * Pagos por estado, con cantidad y valor (texto decimal).
     *
     * @return array<string, array{count: int, amount: string}>
     */
    public function paymentsByStatus(ContractScope $scope): array
    {
        [$sql, $params] = $scope->toSql();
        $result = [];
        foreach ($this->db->fetchAll(
            'SELECT p.status, p.amount FROM payments p JOIN contracts c ON c.id = p.contract_id JOIN contractors ct ON ct.id = c.contractor_id'
            . " WHERE c.deleted_at IS NULL AND {$sql}",
            $params,
        ) as $row) {
            $status = (string) $row['status'];
            $result[$status] ??= ['count' => 0, 'cents' => 0];
            $result[$status]['count']++;
            $result[$status]['cents'] += Money::toCents((string) $row['amount']);
        }

        return array_map(static fn (array $v) => ['count' => $v['count'], 'amount' => Money::fromCents($v['cents'])], $result);
    }

    /**
     * @param array<string, int|string> $params
     * @return array<string, int>
     */
    private function counts(string $sql, array $params): array
    {
        $result = [];
        foreach ($this->db->fetchAll($sql, $params) as $row) {
            $result[(string) $row['k']] = (int) $row['n'];
        }

        return $result;
    }
}
