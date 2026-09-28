<?php

declare(strict_types=1);

namespace Sigcon\Services\Activities;

use Sigcon\Database\Database;
use Sigcon\DTOs\ActivityData;
use Sigcon\Exceptions\AuthorizationException;
use Sigcon\Exceptions\BusinessRuleException;
use Sigcon\Exceptions\NotFoundException;
use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\Clock;
use Sigcon\Helpers\Uuid;
use Sigcon\Models\Activity;
use Sigcon\Models\ActivityLevel;
use Sigcon\Models\ActivityPriority;
use Sigcon\Models\Contract;
use Sigcon\Models\ContractStatus;
use Sigcon\Repositories\ActivityRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Security\Permission;
use Sigcon\Services\Audit\AuditAction;
use Sigcon\Services\Audit\AuditLogger;
use Sigcon\Services\Contracts\ContractService;

/**
 * Obligaciones, tareas, subtareas y avance (ADR-013, ajustado en ADR-021).
 *
 * El contratista NO edita nada: solo consulta sus tareas, sube evidencias y presenta informes.
 *
 * Quién puede qué:
 * - Obligaciones: quien gestiona contratos (contracts.manage), solo con el contrato en borrador.
 * - Tareas y subtareas (activities.plan): la administración (contracts.manage) y el supervisor
 *   asignado, con el contrato en borrador o activo.
 * - Avance (activities.progress): el supervisor asignado al contrato, con el contrato activo y
 *   solo en hojas. Es una verificación, no una declaración del contratista. Los niveles
 *   superiores se calculan (promedio ponderado).
 * - Prioridad: quien gestiona contratos, con el contrato en borrador, activo o suspendido.
 * - Lectura: cualquiera que pueda ver el contrato (mismo alcance que el contrato).
 *
 * Coherencia:
 * - No se agregan hijos a un elemento que ya tiene avance declarado.
 * - No se eliminan elementos con hijos ni con avances registrados.
 * - Toda modificación recalcula el avance de los ancestros en la misma transacción, con el
 *   contrato bloqueado (sin actualizaciones concurrentes inconsistentes).
 */
final class ActivityService
{
    /** Estados del contrato en los que la Alcaldía puede asignar prioridad. */
    private const PRIORITY_STATUSES = [ContractStatus::Draft, ContractStatus::Active, ContractStatus::Suspended];

    public function __construct(
        private readonly ActivityRepository $activities,
        private readonly ContractService $contracts,
        private readonly AuditLogger $audit,
        private readonly AuthContext $auth,
        private readonly Database $db,
        private readonly Clock $clock,
    ) {
    }

    /**
     * @return array{contract: Contract, activities: list<Activity>, progress: string, can: array{manage_obligations: bool, plan: bool, record_progress: bool, set_priority: bool}}
     */
    public function tree(string $contractUuid): array
    {
        $contract = $this->contracts->get($contractUuid);

        return [
            'contract' => $contract,
            'activities' => $this->activities->forContract($contract->id),
            'progress' => ProgressCalculator::weightedAverage($this->activities->obligationValues($contract->id)),
            'can' => [
                'manage_obligations' => $this->canManage() && $contract->status === ContractStatus::Draft,
                'plan' => $this->canPlan($contract),
                'record_progress' => $this->canRecordProgress($contract) && $contract->status === ContractStatus::Active,
                'set_priority' => $this->canManage() && in_array($contract->status, self::PRIORITY_STATUSES, true),
            ],
        ];
    }

    /**
     * Una actividad con su contexto: para abrirla y ver qué hay que hacer (ADR-021).
     *
     * @return array{activity: Activity, contract: Contract, parent: ?Activity, obligation: Activity, children: list<Activity>}
     */
    public function detail(string $uuid): array
    {
        $activity = $this->find($uuid);
        $contract = $this->contractOf($activity);
        $all = $this->activities->forContract($contract->id);
        $byId = [];
        foreach ($all as $item) {
            $byId[$item->id] = $item;
        }
        $obligation = $activity;
        while ($obligation->parentId !== null && isset($byId[$obligation->parentId])) {
            $obligation = $byId[$obligation->parentId];
        }

        return [
            'activity' => $activity,
            'contract' => $contract,
            'parent' => $activity->parentId === null ? null : ($byId[$activity->parentId] ?? null),
            'obligation' => $obligation,
            'children' => array_values(array_filter($all, static fn (Activity $i) => $i->parentId === $activity->id)),
        ];
    }

    public function createObligation(string $contractUuid, ActivityData $data): Activity
    {
        $contract = $this->contracts->get($contractUuid);
        if (!$this->canManage()) {
            throw $this->forbidden();
        }
        if ($contract->status !== ContractStatus::Draft) {
            throw new BusinessRuleException('Las obligaciones se registran mientras el contrato está en borrador. Cambiarlas en un contrato en ejecución requiere una modificación contractual (pendiente, ADR-012).');
        }

        return $this->db->transaction(function () use ($contract, $data): Activity {
            $this->activities->lockContract($contract->id);
            $uuid = Uuid::v4();
            $this->activities->create($uuid, $contract->id, null, ActivityLevel::Obligation, $data->title, $data->description, $data->weight, $data->dueDate, $this->clock->now(), $this->auth->userId());
            $this->audit->record(AuditAction::ActivityCreated, 'activity', $uuid, ['contract' => $contract->uuid, 'level' => 'obligation', 'title' => $data->title]);

            return $this->find($uuid);
        });
    }

    /**
     * Registra varias obligaciones de una vez (las confirmadas desde el contrato, ADR-022).
     * Todo o nada: si una falla, no queda ninguna registrada.
     *
     * @param list<ActivityData> $items
     * @return list<Activity>
     */
    public function createObligations(string $contractUuid, array $items): array
    {
        $contract = $this->contracts->get($contractUuid);
        if (!$this->canManage()) {
            throw $this->forbidden();
        }
        if ($contract->status !== ContractStatus::Draft) {
            throw new BusinessRuleException('Las obligaciones se registran mientras el contrato está en borrador. Cambiarlas en un contrato en ejecución requiere una modificación contractual (pendiente, ADR-012).');
        }

        return $this->db->transaction(function () use ($contract, $items): array {
            $this->activities->lockContract($contract->id);
            $created = [];
            foreach ($items as $data) {
                $uuid = Uuid::v4();
                $this->activities->create($uuid, $contract->id, null, ActivityLevel::Obligation, $data->title, $data->description, $data->weight, $data->dueDate, $this->clock->now(), $this->auth->userId());
                $created[] = $uuid;
            }
            $this->audit->record(AuditAction::ActivityCreated, 'contract', $contract->uuid, ['level' => 'obligation', 'count' => count($created), 'source' => 'contract_document']);

            return array_map($this->find(...), $created);
        });
    }

    public function createChild(string $parentUuid, ActivityData $data): Activity
    {
        $parent = $this->find($parentUuid);
        $contract = $this->contractOf($parent);
        $level = $parent->level->childLevel()
            ?? throw new BusinessRuleException('Las subtareas no admiten más niveles.');
        $this->assertCanEditStructure($contract, $level);

        return $this->db->transaction(function () use ($parent, $contract, $level, $data): Activity {
            $this->activities->lockContract($contract->id);
            $parent = $this->find($parent->uuid); // releído bajo bloqueo
            if ($parent->isLeaf() && $parent->updateCount > 0) {
                throw new BusinessRuleException(sprintf(
                    'No se pueden agregar %s a "%s" porque ya tiene avances registrados. Planee las %s antes de reportar avance.',
                    $level === ActivityLevel::Task ? 'tareas' : 'subtareas',
                    $parent->title,
                    $level === ActivityLevel::Task ? 'tareas' : 'subtareas',
                ));
            }
            $uuid = Uuid::v4();
            $this->activities->create($uuid, $contract->id, $parent->id, $level, $data->title, $data->description, $data->weight, $data->dueDate, $this->clock->now(), $this->auth->userId());
            $this->recomputeFrom($parent->id);
            $this->audit->record(AuditAction::ActivityCreated, 'activity', $uuid, ['contract' => $contract->uuid, 'level' => $level->value, 'parent' => $parent->uuid, 'title' => $data->title]);

            return $this->find($uuid);
        });
    }

    public function update(string $uuid, ActivityData $data): Activity
    {
        $activity = $this->find($uuid);
        $contract = $this->contractOf($activity);
        $this->assertCanEditStructure($contract, $activity->level);

        return $this->db->transaction(function () use ($activity, $contract, $data): Activity {
            $this->activities->lockContract($contract->id);
            $this->activities->update($activity->id, $data->title, $data->description, $data->weight, $data->dueDate, $this->clock->now(), $this->auth->userId());
            if ($activity->weight !== $data->weight && $activity->parentId !== null) {
                $this->recomputeFrom($activity->parentId);
            }
            $this->audit->record(AuditAction::ActivityUpdated, 'activity', $activity->uuid, array_filter([
                'title' => $activity->title !== $data->title ? ['from' => $activity->title, 'to' => $data->title] : null,
                'weight' => $activity->weight !== $data->weight ? ['from' => $activity->weight, 'to' => $data->weight] : null,
            ]));

            return $this->find($activity->uuid);
        });
    }

    public function delete(string $uuid): void
    {
        $activity = $this->find($uuid);
        $contract = $this->contractOf($activity);
        $this->assertCanEditStructure($contract, $activity->level);

        $this->db->transaction(function () use ($activity, $contract): void {
            $this->activities->lockContract($contract->id);
            $activity = $this->find($activity->uuid);
            if (!$activity->isLeaf()) {
                throw new BusinessRuleException('Elimine primero los elementos que dependen de este.');
            }
            if ($activity->updateCount > 0) {
                throw new BusinessRuleException('No se puede eliminar un elemento con avances registrados: forman parte de la trazabilidad del contrato.');
            }
            $this->activities->delete($activity->id);
            if ($activity->parentId !== null) {
                $this->recomputeFrom($activity->parentId);
            }
            $this->audit->record(AuditAction::ActivityDeleted, 'activity', $activity->uuid, ['contract' => $contract->uuid, 'title' => $activity->title]);
        });
    }

    public function recordProgress(string $uuid, int $progress, string $note): Activity
    {
        $activity = $this->find($uuid);
        $contract = $this->contractOf($activity);
        if (!$this->canRecordProgress($contract)) {
            throw new AuthorizationException('El avance de las tareas lo registra el supervisor del contrato.');
        }
        if ($contract->status !== ContractStatus::Active) {
            throw new BusinessRuleException(sprintf('No se registran avances en un contrato %s.', mb_strtolower($contract->status->label())));
        }
        if ($progress < 0 || $progress > 100) {
            throw ValidationException::field('progress', 'out_of_range', 'El avance debe estar entre 0 y 100.');
        }

        return $this->db->transaction(function () use ($activity, $contract, $progress, $note): Activity {
            $this->activities->lockContract($contract->id);
            $activity = $this->find($activity->uuid);
            if (!$activity->isLeaf()) {
                throw new BusinessRuleException('El avance de este elemento se calcula a partir de sus tareas o subtareas; registre el avance en ellas.');
            }
            $new = ProgressCalculator::fromHundredths($progress * 100);
            $now = $this->clock->now();
            $this->activities->insertProgressUpdate(Uuid::v4(), $activity->id, $activity->progress, $new, $note, (int) $this->auth->userId(), $now);
            $this->activities->setProgress($activity->id, $new, $now, $this->auth->userId());
            if ($activity->parentId !== null) {
                $this->recomputeFrom($activity->parentId);
            }
            $this->audit->record(AuditAction::ActivityProgressRecorded, 'activity', $activity->uuid, [
                'contract' => $contract->uuid,
                'from' => $activity->progress,
                'to' => $new,
            ]);

            return $this->find($activity->uuid);
        });
    }

    /**
     * Prioridad (ADR-021): la asigna la Alcaldía (contracts.manage), también con el contrato en
     * ejecución, porque no altera las obligaciones pactadas. El contratista solo la consulta.
     */
    public function setPriority(string $uuid, ?ActivityPriority $priority): Activity
    {
        $activity = $this->find($uuid);
        $contract = $this->contractOf($activity);
        if (!$this->canManage()) {
            throw new AuthorizationException('Solo la Alcaldía asigna la prioridad de obligaciones y tareas.');
        }
        if (!in_array($contract->status, self::PRIORITY_STATUSES, true)) {
            throw new BusinessRuleException(sprintf('No se asigna prioridad en un contrato %s.', mb_strtolower($contract->status->label())));
        }
        if ($activity->priority === $priority) {
            return $activity;
        }

        return $this->db->transaction(function () use ($activity, $priority): Activity {
            $this->activities->setPriority($activity->id, $priority, $this->clock->now(), $this->auth->userId());
            $this->audit->record(AuditAction::ActivityPriorityChanged, 'activity', $activity->uuid, [
                'from' => $activity->priority?->value,
                'to' => $priority?->value,
            ]);

            return $this->find($activity->uuid);
        });
    }

    /**
     * @return array{activity: Activity, history: list<array{uuid: string, previous_progress: string, new_progress: string, note: string, user: string, recorded_at: \DateTimeImmutable}>}
     */
    public function progressHistory(string $uuid): array
    {
        $activity = $this->find($uuid);
        $this->contractOf($activity); // aplica el alcance de lectura del contrato

        return ['activity' => $activity, 'history' => $this->activities->progressHistory($activity->id)];
    }

    /** Recalcula el avance del nodo y de todos sus ancestros. */
    private function recomputeFrom(int $nodeId): void
    {
        $current = $this->activities->findById($nodeId);
        while ($current !== null) {
            $children = $this->activities->childrenValues($current->id);
            if ($children !== []) {
                $this->activities->setComputedProgress($current->id, ProgressCalculator::weightedAverage($children));
            }
            $current = $current->parentId === null ? null : $this->activities->findById($current->parentId);
        }
    }

    private function assertCanEditStructure(Contract $contract, ActivityLevel $level): void
    {
        if ($level === ActivityLevel::Obligation) {
            if (!$this->canManage()) {
                throw $this->forbidden();
            }
            if ($contract->status !== ContractStatus::Draft) {
                throw new BusinessRuleException('Las obligaciones solo se modifican mientras el contrato está en borrador.');
            }

            return;
        }
        if (!$this->canPlan($contract)) {
            if (!$this->auth->can(Permission::ActivitiesPlan) || (!$this->canManage() && !$this->isSupervisor($contract))) {
                throw new AuthorizationException('Las tareas y subtareas las gestionan la administración y el supervisor del contrato.');
            }
            throw new BusinessRuleException(sprintf('No se pueden planear tareas en un contrato %s.', mb_strtolower($contract->status->label())));
        }
    }

    /** Tareas y subtareas: administración o supervisor del contrato, en borrador o en ejecución. */
    private function canPlan(Contract $contract): bool
    {
        return $this->auth->can(Permission::ActivitiesPlan)
            && ($this->canManage() || $this->isSupervisor($contract))
            && in_array($contract->status, [ContractStatus::Draft, ContractStatus::Active], true);
    }

    /** El avance lo verifica y registra el supervisor asignado al contrato (ADR-021). */
    private function canRecordProgress(Contract $contract): bool
    {
        return $this->auth->can(Permission::ActivitiesProgress) && $this->isSupervisor($contract);
    }

    private function isSupervisor(Contract $contract): bool
    {
        return $contract->supervisorId !== null && $contract->supervisorId === $this->auth->userId();
    }

    private function canManage(): bool
    {
        return $this->auth->can(Permission::ContractsManage);
    }

    private function find(string $uuid): Activity
    {
        return (Uuid::isValid($uuid) ? $this->activities->findByUuid($uuid) : null)
            ?? throw new NotFoundException('El elemento no existe.');
    }

    /** Contrato del elemento, aplicando el alcance del usuario (fuera de alcance = 404). */
    private function contractOf(Activity $activity): Contract
    {
        try {
            return $this->contracts->getById($activity->contractId);
        } catch (NotFoundException) {
            throw new NotFoundException('El elemento no existe.');
        }
    }

    private function forbidden(): AuthorizationException
    {
        return new AuthorizationException('No tiene permiso para modificar las obligaciones de este contrato.');
    }
}
