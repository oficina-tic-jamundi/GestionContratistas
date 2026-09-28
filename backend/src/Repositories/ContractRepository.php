<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\DTOs\ContractData;
use Sigcon\DTOs\ContractFilters;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\Page;
use Sigcon\Http\PageRequest;
use Sigcon\Models\Contract;
use Sigcon\Models\ContractStatus;

/**
 * Toda consulta de lectura recibe un ContractScope: no existe forma de leer contratos
 * sin aplicar el alcance del usuario (salvo ContractScope::unrestricted() para procesos internos).
 */
final class ContractRepository
{
    private const FROM = 'FROM contracts c
        JOIN contractors ct ON ct.id = c.contractor_id
        JOIN departments d ON d.id = c.department_id
        LEFT JOIN users s ON s.id = c.supervisor_user_id';

    private const COLUMNS = "c.id, c.uuid, c.contract_number, c.object, c.status, c.signed_at, c.start_date, c.end_date,
        c.total_value, c.payment_count, c.secop_url, c.created_at, c.updated_at,
        ct.id AS contractor_id, ct.uuid AS contractor_uuid, ct.name AS contractor_name, ct.document_type, ct.document_number,
        ct.verification_digit, ct.user_id AS contractor_user_id,
        d.id AS department_id, d.uuid AS department_uuid, d.code AS department_code, d.name AS department_name,
        s.id AS supervisor_id, s.uuid AS supervisor_uuid, CONCAT(s.first_name, ' ', s.last_name) AS supervisor_name";

    public const SORTABLE = [
        'number' => 'c.contract_number',
        'start_date' => 'c.start_date',
        'end_date' => 'c.end_date',
        'value' => 'c.total_value',
        'contractor' => 'ct.name',
        'created_at' => 'c.created_at',
    ];

    public function __construct(private readonly Database $db)
    {
    }

    /** @return Page<Contract> */
    public function paginate(ContractFilters $filters, PageRequest $page, ContractScope $scope): Page
    {
        [$scopeSql, $params] = $scope->toSql();
        $where = ['c.deleted_at IS NULL', $scopeSql];

        if ($filters->search !== null) {
            $where[] = '(c.contract_number LIKE :s1 OR c.object LIKE :s2 OR ct.name LIKE :s3 OR ct.document_number LIKE :s4)';
            $like = '%' . addcslashes($filters->search, '%_\\') . '%';
            $params += ['s1' => $like, 's2' => $like, 's3' => $like, 's4' => $like];
        }
        if ($filters->status !== null) {
            $where[] = 'c.status = :status';
            $params['status'] = $filters->status->value;
        }
        if ($filters->departmentUuid !== null) {
            $where[] = 'd.uuid = :department';
            $params['department'] = $filters->departmentUuid;
        }
        if ($filters->contractorUuid !== null) {
            $where[] = 'ct.uuid = :contractor';
            $params['contractor'] = $filters->contractorUuid;
        }
        if ($filters->supervisorUuid !== null) {
            $where[] = 's.uuid = :supervisor';
            $params['supervisor'] = $filters->supervisorUuid;
        }
        if ($filters->endingBefore !== null) {
            $where[] = 'c.end_date <= :ending';
            $params['ending'] = $filters->endingBefore->format('Y-m-d');
        }
        $whereSql = implode(' AND ', $where);

        $total = (int) ($this->db->fetchAll('SELECT COUNT(*) AS total ' . self::FROM . " WHERE {$whereSql}", $params)[0]['total'] ?? 0);
        $rows = $this->db->fetchAll(sprintf(
            'SELECT %s %s WHERE %s ORDER BY %s LIMIT %d OFFSET %d',
            self::COLUMNS,
            self::FROM,
            $whereSql,
            $page->orderBy('c.id'),
            $page->perPage,
            $page->offset(),
        ), $params);

        return new Page(array_map($this->hydrate(...), $rows), $total, $page);
    }

    public function findByUuid(string $uuid, ContractScope $scope): ?Contract
    {
        [$scopeSql, $params] = $scope->toSql();
        $rows = $this->db->fetchAll(
            sprintf('SELECT %s %s WHERE c.uuid = :uuid AND c.deleted_at IS NULL AND %s', self::COLUMNS, self::FROM, $scopeSql),
            ['uuid' => $uuid] + $params,
        );

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    public function findById(int $id, ContractScope $scope): ?Contract
    {
        [$scopeSql, $params] = $scope->toSql();
        $rows = $this->db->fetchAll(
            sprintf('SELECT %s %s WHERE c.id = :id AND c.deleted_at IS NULL AND %s', self::COLUMNS, self::FROM, $scopeSql),
            ['id' => $id] + $params,
        );

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    public function numberExists(string $number, ?int $exceptId = null): bool
    {
        // Incluye contratos retirados: un número no se reutiliza.
        return $this->db->fetchAll(
            'SELECT 1 FROM contracts WHERE contract_number = :number AND (:except IS NULL OR id <> :except2)',
            ['number' => $number, 'except' => $exceptId, 'except2' => $exceptId],
        ) !== [];
    }

    /** @param array{contractor_id: int, department_id: int, supervisor_id: ?int} $refs */
    public function create(string $uuid, ContractData $data, array $refs, DateTimeImmutable $now, ?int $actorId): int
    {
        $this->db->pdo()->prepare(
            'INSERT INTO contracts (uuid, contract_number, object, contractor_id, department_id, supervisor_user_id, signed_at,
                start_date, end_date, total_value, payment_count, status, secop_url, created_at, updated_at, created_by, updated_by)
             VALUES (:uuid, :number, :object, :contractor, :department, :supervisor, :signed, :start, :end, :value, :payment_count,
                :status, :secop, :now, :now2, :actor, :actor2)',
        )->execute($this->params($data, $refs) + [
            'uuid' => $uuid,
            'status' => ContractStatus::Draft->value,
            'now' => DateTimes::toDb($now),
            'now2' => DateTimes::toDb($now),
            'actor' => $actorId,
            'actor2' => $actorId,
        ]);

        return (int) $this->db->pdo()->lastInsertId();
    }

    /** @param array{contractor_id: int, department_id: int, supervisor_id: ?int} $refs */
    public function update(int $id, ContractData $data, array $refs, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare(
            'UPDATE contracts SET contract_number = :number, object = :object, contractor_id = :contractor,
                department_id = :department, supervisor_user_id = :supervisor, signed_at = :signed, start_date = :start,
                end_date = :end, total_value = :value, payment_count = :payment_count, secop_url = :secop,
                updated_at = :now, updated_by = :actor
             WHERE id = :id',
        )->execute($this->params($data, $refs) + ['now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function setStatus(int $id, ContractStatus $status, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE contracts SET status = :status, updated_at = :now, updated_by = :actor WHERE id = :id')
            ->execute(['status' => $status->value, 'now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function setSupervisor(int $id, int $supervisorId, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE contracts SET supervisor_user_id = :sup, updated_at = :now, updated_by = :actor WHERE id = :id')
            ->execute(['sup' => $supervisorId, 'now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function softDelete(int $id, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE contracts SET deleted_at = :now, updated_at = :now2, updated_by = :actor WHERE id = :id')
            ->execute(['now' => DateTimes::toDb($now), 'now2' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    /**
     * Bloquea la fila y devuelve el estado ACTUAL (releído dentro de la transacción).
     * Evita que dos usuarios cambien el estado al mismo tiempo sobre un dato desactualizado.
     */
    public function lockStatus(int $id): ?ContractStatus
    {
        $rows = $this->db->fetchAll('SELECT status FROM contracts WHERE id = :id AND deleted_at IS NULL FOR UPDATE', ['id' => $id]);

        return isset($rows[0]) ? ContractStatus::from((string) $rows[0]['status']) : null;
    }

    public function countActiveForContractor(int $contractorId): int
    {
        return (int) ($this->db->fetchAll(
            "SELECT COUNT(*) AS total FROM contracts WHERE contractor_id = :id AND deleted_at IS NULL AND status IN ('active', 'suspended')",
            ['id' => $contractorId],
        )[0]['total'] ?? 0);
    }

    /**
     * @param array{contractor_id: int, department_id: int, supervisor_id: ?int} $refs
     * @return array<string, mixed>
     */
    private function params(ContractData $data, array $refs): array
    {
        return [
            'number' => $data->contractNumber,
            'object' => $data->object,
            'contractor' => $refs['contractor_id'],
            'department' => $refs['department_id'],
            'supervisor' => $refs['supervisor_id'],
            'signed' => $data->signedAt?->format('Y-m-d'),
            'start' => $data->startDate->format('Y-m-d'),
            'end' => $data->endDate->format('Y-m-d'),
            'value' => $data->totalValue,
            'payment_count' => $data->paymentCount,
            'secop' => $data->secopUrl,
        ];
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Contract
    {
        $date = static fn (mixed $v): DateTimeImmutable => new DateTimeImmutable((string) $v, new \DateTimeZone('UTC'));
        $dv = $row['verification_digit'] === null ? '' : '-' . $row['verification_digit'];

        return new Contract(
            id: (int) $row['id'],
            uuid: (string) $row['uuid'],
            contractNumber: (string) $row['contract_number'],
            object: (string) $row['object'],
            status: ContractStatus::from((string) $row['status']),
            signedAt: $row['signed_at'] === null ? null : $date($row['signed_at']),
            startDate: $date($row['start_date']),
            endDate: $date($row['end_date']),
            totalValue: (string) $row['total_value'],
            paymentCount: $row['payment_count'] === null ? null : (int) $row['payment_count'],
            secopUrl: $row['secop_url'] === null ? null : (string) $row['secop_url'],
            contractorId: (int) $row['contractor_id'],
            contractorUuid: (string) $row['contractor_uuid'],
            contractorName: (string) $row['contractor_name'],
            contractorDocument: $row['document_type'] . ' ' . $row['document_number'] . $dv,
            contractorUserId: $row['contractor_user_id'] === null ? null : (int) $row['contractor_user_id'],
            departmentId: (int) $row['department_id'],
            departmentUuid: (string) $row['department_uuid'],
            departmentCode: (string) $row['department_code'],
            departmentName: (string) $row['department_name'],
            supervisorId: $row['supervisor_id'] === null ? null : (int) $row['supervisor_id'],
            supervisorUuid: $row['supervisor_uuid'] === null ? null : (string) $row['supervisor_uuid'],
            supervisorName: $row['supervisor_name'] === null ? null : (string) $row['supervisor_name'],
            createdAt: DateTimes::fromDb((string) $row['created_at']),
            updatedAt: DateTimes::fromDb((string) $row['updated_at']),
        );
    }
}
