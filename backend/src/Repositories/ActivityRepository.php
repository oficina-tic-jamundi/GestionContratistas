<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\Helpers\DateTimes;
use Sigcon\Models\Activity;
use Sigcon\Models\ActivityLevel;
use Sigcon\Models\ActivityPriority;

final class ActivityRepository
{
    private const SELECT = 'SELECT a.id, a.uuid, a.contract_id, a.parent_id, a.level, a.position, a.title, a.description,
        a.weight, a.priority, a.progress, a.due_date, a.updated_at,
        (SELECT COUNT(*) FROM activities c WHERE c.parent_id = a.id) AS child_count,
        (SELECT COUNT(*) FROM progress_updates pu WHERE pu.activity_id = a.id) AS update_count
        FROM activities a';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return list<Activity> todos los nodos del contrato, ordenados por padre y posición */
    public function forContract(int $contractId): array
    {
        return array_map(
            $this->hydrate(...),
            $this->db->fetchAll(self::SELECT . ' WHERE a.contract_id = :id ORDER BY a.parent_id IS NOT NULL, a.parent_id, a.position, a.id', ['id' => $contractId]),
        );
    }

    public function findByUuid(string $uuid): ?Activity
    {
        $rows = $this->db->fetchAll(self::SELECT . ' WHERE a.uuid = :uuid', ['uuid' => $uuid]);

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    public function findById(int $id): ?Activity
    {
        $rows = $this->db->fetchAll(self::SELECT . ' WHERE a.id = :id', ['id' => $id]);

        return isset($rows[0]) ? $this->hydrate($rows[0]) : null;
    }

    public function create(
        string $uuid,
        int $contractId,
        ?int $parentId,
        ActivityLevel $level,
        string $title,
        ?string $description,
        string $weight,
        ?DateTimeImmutable $dueDate,
        DateTimeImmutable $now,
        ?int $actorId,
    ): int {
        $position = (int) ($this->db->fetchAll(
            'SELECT COALESCE(MAX(position), 0) + 1 AS next FROM activities WHERE contract_id = :contract AND parent_id <=> :parent',
            ['contract' => $contractId, 'parent' => $parentId],
        )[0]['next'] ?? 1);

        $this->db->pdo()->prepare(
            'INSERT INTO activities (uuid, contract_id, parent_id, level, position, title, description, weight, progress, due_date,
                created_at, updated_at, created_by, updated_by)
             VALUES (:uuid, :contract, :parent, :level, :position, :title, :description, :weight, 0, :due, :now, :now2, :actor, :actor2)',
        )->execute([
            'uuid' => $uuid,
            'contract' => $contractId,
            'parent' => $parentId,
            'level' => $level->value,
            'position' => $position,
            'title' => $title,
            'description' => $description,
            'weight' => $weight,
            'due' => $dueDate?->format('Y-m-d'),
            'now' => DateTimes::toDb($now),
            'now2' => DateTimes::toDb($now),
            'actor' => $actorId,
            'actor2' => $actorId,
        ]);

        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, string $title, ?string $description, string $weight, ?DateTimeImmutable $dueDate, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare(
            'UPDATE activities SET title = :title, description = :description, weight = :weight, due_date = :due,
                updated_at = :now, updated_by = :actor WHERE id = :id',
        )->execute([
            'title' => $title,
            'description' => $description,
            'weight' => $weight,
            'due' => $dueDate?->format('Y-m-d'),
            'now' => DateTimes::toDb($now),
            'actor' => $actorId,
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->pdo()->prepare('DELETE FROM activities WHERE id = :id')->execute(['id' => $id]);
    }

    public function setProgress(int $id, string $progress, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE activities SET progress = :p, updated_at = :now, updated_by = :actor WHERE id = :id')
            ->execute(['p' => $progress, 'now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    public function setPriority(int $id, ?ActivityPriority $priority, DateTimeImmutable $now, ?int $actorId): void
    {
        $this->db->pdo()->prepare('UPDATE activities SET priority = :priority, updated_at = :now, updated_by = :actor WHERE id = :id')
            ->execute(['priority' => $priority?->value, 'now' => DateTimes::toDb($now), 'actor' => $actorId, 'id' => $id]);
    }

    /** Actualiza solo el valor calculado (caché) de un nodo padre, sin cambiar su autoría. */
    public function setComputedProgress(int $id, string $progress): void
    {
        $this->db->pdo()->prepare('UPDATE activities SET progress = :p WHERE id = :id')->execute(['p' => $progress, 'id' => $id]);
    }

    /** @return list<array{progress: string, weight: string}> */
    public function childrenValues(int $parentId): array
    {
        return array_map(
            static fn (array $r) => ['progress' => (string) $r['progress'], 'weight' => (string) $r['weight']],
            $this->db->fetchAll('SELECT progress, weight FROM activities WHERE parent_id = :id', ['id' => $parentId]),
        );
    }

    /** @return list<array{progress: string, weight: string}> */
    public function obligationValues(int $contractId): array
    {
        return array_map(
            static fn (array $r) => ['progress' => (string) $r['progress'], 'weight' => (string) $r['weight']],
            $this->db->fetchAll('SELECT progress, weight FROM activities WHERE contract_id = :id AND parent_id IS NULL', ['id' => $contractId]),
        );
    }

    /**
     * Serializa los cambios de un mismo contrato: bloquea la fila del contrato hasta el fin de
     * la transacción. Dos avances simultáneos no pueden dejar un promedio desactualizado.
     */
    public function lockContract(int $contractId): void
    {
        $this->db->pdo()->prepare('SELECT id FROM contracts WHERE id = :id FOR UPDATE')->execute(['id' => $contractId]);
    }

    public function insertProgressUpdate(string $uuid, int $activityId, string $previous, string $new, string $note, int $userId, DateTimeImmutable $now): void
    {
        $this->db->pdo()->prepare(
            'INSERT INTO progress_updates (uuid, activity_id, previous_progress, new_progress, note, user_id, recorded_at)
             VALUES (:uuid, :activity, :prev, :new, :note, :user, :now)',
        )->execute([
            'uuid' => $uuid,
            'activity' => $activityId,
            'prev' => $previous,
            'new' => $new,
            'note' => $note,
            'user' => $userId,
            'now' => DateTimes::toDb($now),
        ]);
    }

    /**
     * @return list<array{uuid: string, previous_progress: string, new_progress: string, note: string, user: string, recorded_at: DateTimeImmutable}>
     */
    public function progressHistory(int $activityId): array
    {
        return array_map(static fn (array $r) => [
            'uuid' => (string) $r['uuid'],
            'previous_progress' => (string) $r['previous_progress'],
            'new_progress' => (string) $r['new_progress'],
            'note' => (string) $r['note'],
            'user' => trim($r['first_name'] . ' ' . $r['last_name']),
            'recorded_at' => DateTimes::fromDb((string) $r['recorded_at']),
        ], $this->db->fetchAll(
            'SELECT pu.uuid, pu.previous_progress, pu.new_progress, pu.note, pu.recorded_at, u.first_name, u.last_name
             FROM progress_updates pu JOIN users u ON u.id = pu.user_id
             WHERE pu.activity_id = :id ORDER BY pu.recorded_at DESC, pu.id DESC',
            ['id' => $activityId],
        ));
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Activity
    {
        return new Activity(
            id: (int) $row['id'],
            uuid: (string) $row['uuid'],
            contractId: (int) $row['contract_id'],
            parentId: $row['parent_id'] === null ? null : (int) $row['parent_id'],
            level: ActivityLevel::from((string) $row['level']),
            position: (int) $row['position'],
            title: (string) $row['title'],
            description: $row['description'] === null ? null : (string) $row['description'],
            weight: (string) $row['weight'],
            priority: $row['priority'] === null ? null : ActivityPriority::from((string) $row['priority']),
            progress: (string) $row['progress'],
            dueDate: $row['due_date'] === null ? null : new DateTimeImmutable((string) $row['due_date'], new \DateTimeZone('UTC')),
            childCount: (int) $row['child_count'],
            updateCount: (int) $row['update_count'],
            updatedAt: DateTimes::fromDb((string) $row['updated_at']),
        );
    }
}
