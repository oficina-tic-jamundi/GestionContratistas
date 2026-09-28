<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\DTOs\ContractorData;
use Sigcon\DTOs\ContractorFilters;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\Page;
use Sigcon\Http\PageRequest;
use Sigcon\Models\ActiveStatus;
use Sigcon\Models\Contractor;
use Sigcon\Models\DocumentType;
use Sigcon\Models\PersonType;

final class ContractorRepository
{
    private const SELECT = 'SELECT ct.id, ct.uuid, ct.person_type, ct.document_type, ct.document_number, ct.verification_digit,
        ct.name, ct.email, ct.phone, ct.address, ct.status, ct.user_id, u.uuid AS user_uuid, u.email AS user_email,
        ct.created_at, ct.updated_at
        FROM contractors ct LEFT JOIN users u ON u.id = ct.user_id';

    /** Contratistas con al menos un contrato vigente a cargo de ese supervisor. */
    private const SUPERVISED = "EXISTS (SELECT 1 FROM contracts c WHERE c.contractor_id = ct.id
        AND c.supervisor_user_id = :scope_supervisor AND c.deleted_at IS NULL AND c.status <> 'draft')";

    public const SORTABLE = [
        'name' => 'ct.name',
        'document' => 'ct.document_number',
        'created_at' => 'ct.created_at',
    ];

    public function __construct(private readonly Database $db)
    {
    }

    /**
     * @param ?int $supervisorUserId si no es null, solo los contratistas con un contrato que
     *                               esa persona supervisa (alcance del supervisor, ADR-021)
     * @return Page<Contractor>
     */
    public function paginate(ContractorFilters $filters, PageRequest $page, ?int $supervisorUserId = null): Page
    {
        $where = ['1 = 1'];
        $params = [];
        if ($supervisorUserId !== null) {
            $where[] = self::SUPERVISED;
            $params['scope_supervisor'] = $supervisorUserId;
        }
        if ($filters->search !== null) {
            $where[] = '(ct.name LIKE :s1 OR ct.document_number LIKE :s2 OR ct.email LIKE :s3)';
            $like = '%' . addcslashes($filters->search, '%_\\') . '%';
            $params += ['s1' => $like, 's2' => $like, 's3' => $like];
        }
        if ($filters->status !== null) {
            $where[] = 'ct.status = :status';
            $params['status'] = $filters->status->value;
        }
        $whereSql = implode(' AND ', $where);

        $total = (int) ($this->db->fetchAll("SELECT COUNT(*) AS total FROM contractors ct WHERE {$whereSql}", $params)[0]['total'] ?? 0);
        $rows = $this->db->fetchAll(sprintf(
            '%s WHERE %s ORDER BY %s LIMIT %d OFFSET %d',
            self::SELECT,
            $whereSql,
            $page->orderBy('ct.id'),
            $page->perPage,
            $page->offset(),
        ), $params);

        return new Page(array_map($this->hydrate(...), $rows), $total, $page);
    }

    public function findByUuid(string $uuid, ?int $supervisorUserId = null): ?Contractor
    {
        $contractor = $this->findOne('ct.uuid = :value', $uuid);
        if ($contractor === null || $supervisorUserId === null) {
            return $contractor;
        }
        $supervised = $this->db->fetchAll(
            "SELECT 1 FROM contracts c WHERE c.contractor_id = :id AND c.supervisor_user_id = :sup
                AND c.deleted_at IS NULL AND c.status <> 'draft' LIMIT 1",
            ['id' => $contractor->id, 'sup' => $supervisorUserId],
        );

        return $supervised === [] ? null : $contractor;
    }

    public function findById(int $id): ?Contractor
    {
        return $this->findOne('ct.id = :value', $id);
    }

    public function findByDocument(DocumentType $type, string $number): ?Contractor
    {
        $rows = $this->db->fetchAll(self::SELECT . ' WHERE ct.document_type = :type AND ct.document_number = :number', ['type' => $type->value, 'number' => $number]);

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    public function documentExists(DocumentType $type, string $number, ?int $exceptId = null): bool
    {
        return $this->db->fetchAll(
            'SELECT 1 FROM contractors WHERE document_type = :type AND document_number = :number AND (:except IS NULL OR id <> :except2)',
            ['type' => $type->value, 'number' => $number, 'except' => $exceptId, 'except2' => $exceptId],
        ) !== [];
    }

    public function userLinked(int $userId, ?int $exceptId = null): bool
    {
        return $this->db->fetchAll(
            'SELECT 1 FROM contractors WHERE user_id = :user AND (:except IS NULL OR id <> :except2)',
            ['user' => $userId, 'except' => $exceptId, 'except2' => $exceptId],
        ) !== [];
    }

    public function create(string $uuid, ContractorData $data, ?int $userId, DateTimeImmutable $now, ?int $actorId): int
    {
        $this->db->pdo()->prepare(
            'INSERT INTO contractors (uuid, person_type, document_type, document_number, verification_digit, name, email, phone,
                address, user_id, status, created_at, updated_at, created_by, updated_by)
             VALUES (:uuid, :ptype, :dtype, :dnumber, :dv, :name, :email, :phone, :address, :user, :status, :now, :now2, :actor, :actor2)',
        )->execute($this->params($data, $userId) + [
            'uuid' => $uuid,
            'status' => ActiveStatus::Active->value,
            'now' => DateTimes::toDb($now),
            'now2' => DateTimes::toDb($now),
            'actor' => $actorId,
            'actor2' => $actorId,
        ]);

        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, ContractorData $data, ?int $userId, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare(
            'UPDATE contractors SET person_type = :ptype, document_type = :dtype, document_number = :dnumber, verification_digit = :dv,
                name = :name, email = :email, phone = :phone, address = :address, user_id = :user,
                updated_at = :now, updated_by = :actor WHERE id = :id',
        )->execute($this->params($data, $userId) + ['now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function setStatus(int $id, ActiveStatus $status, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE contractors SET status = :status, updated_at = :now, updated_by = :actor WHERE id = :id')
            ->execute(['status' => $status->value, 'now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function lockById(int $id): void
    {
        $this->db->pdo()->prepare('SELECT id FROM contractors WHERE id = :id FOR UPDATE')->execute(['id' => $id]);
    }

    /** @return array<string, mixed> */
    private function params(ContractorData $data, ?int $userId): array
    {
        return [
            'ptype' => $data->personType->value,
            'dtype' => $data->documentType->value,
            'dnumber' => $data->documentNumber,
            'dv' => $data->verificationDigit,
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'address' => $data->address,
            'user' => $userId,
        ];
    }

    private function findOne(string $condition, int|string $value): ?Contractor
    {
        $rows = $this->db->fetchAll(self::SELECT . ' WHERE ' . $condition, ['value' => $value]);

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Contractor
    {
        $nullable = static fn (mixed $v): ?string => $v === null ? null : (string) $v;

        return new Contractor(
            id: (int) $row['id'],
            uuid: (string) $row['uuid'],
            personType: PersonType::from((string) $row['person_type']),
            documentType: DocumentType::from((string) $row['document_type']),
            documentNumber: (string) $row['document_number'],
            verificationDigit: $row['verification_digit'] === null ? null : (int) $row['verification_digit'],
            name: (string) $row['name'],
            email: $nullable($row['email']),
            phone: $nullable($row['phone']),
            address: $nullable($row['address']),
            status: ActiveStatus::from((string) $row['status']),
            userId: $row['user_id'] === null ? null : (int) $row['user_id'],
            userUuid: $nullable($row['user_uuid']),
            userEmail: $nullable($row['user_email']),
            createdAt: DateTimes::fromDb((string) $row['created_at']),
            updatedAt: DateTimes::fromDb((string) $row['updated_at']),
        );
    }
}
