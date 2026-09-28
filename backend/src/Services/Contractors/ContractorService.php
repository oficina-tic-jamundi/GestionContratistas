<?php

declare(strict_types=1);

namespace Sigcon\Services\Contractors;

use PDOException;
use Sigcon\Database\Database;
use Sigcon\DTOs\ContractorData;
use Sigcon\DTOs\ContractorFilters;
use Sigcon\Exceptions\NotFoundException;
use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\Clock;
use Sigcon\Helpers\Uuid;
use Sigcon\Http\Page;
use Sigcon\Http\PageRequest;
use Sigcon\Models\ActiveStatus;
use Sigcon\Models\Contractor;
use Sigcon\Repositories\ContractorRepository;
use Sigcon\Repositories\ContractRepository;
use Sigcon\Repositories\UserRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Security\Permission;
use Sigcon\Services\Audit\AuditAction;
use Sigcon\Services\Audit\AuditLogger;

/**
 * Contratistas.
 *
 * Reglas:
 * - Documento único por tipo (también frente a inactivos). El NIT exige dígito de verificación correcto.
 * - La cuenta vinculada debe estar activa, tener permiso para ver sus propios contratos
 *   (contracts.view_own, rol Contratista) y no estar vinculada a otro contratista.
 * - No se eliminan: se desactivan. Un contratista inactivo no puede recibir contratos nuevos.
 */
final class ContractorService
{
    public function __construct(
        private readonly ContractorRepository $contractors,
        private readonly ContractRepository $contracts,
        private readonly UserRepository $users,
        private readonly AuditLogger $audit,
        private readonly AuthContext $auth,
        private readonly Database $db,
        private readonly Clock $clock,
    ) {
    }

    /** @return Page<Contractor> */
    public function list(ContractorFilters $filters, PageRequest $page): Page
    {
        return $this->contractors->paginate($filters, $page, $this->supervisorScope());
    }

    public function get(string $uuid): Contractor
    {
        return (Uuid::isValid($uuid) ? $this->contractors->findByUuid($uuid, $this->supervisorScope()) : null)
            ?? throw new NotFoundException('El contratista no existe.');
    }

    /**
     * Quien solo supervisa contratos ve únicamente a sus contratistas (ADR-021). La
     * administración (contractors.view) los ve todos.
     */
    private function supervisorScope(): ?int
    {
        return !$this->auth->can(Permission::ContractorsView) && $this->auth->can(Permission::ContractsViewAssigned)
            ? $this->auth->userId()
            : null;
    }

    public function requireActive(string $uuid): Contractor
    {
        $contractor = Uuid::isValid($uuid) ? $this->contractors->findByUuid($uuid) : null;
        if ($contractor === null || !$contractor->isActive()) {
            throw ValidationException::field('contractor', 'invalid_contractor', 'Seleccione un contratista activo.');
        }

        return $contractor;
    }

    public function create(ContractorData $data): Contractor
    {
        return $this->db->transaction(function () use ($data): Contractor {
            $userId = $this->resolveUser($data->userUuid, null);
            $this->assertDocumentAvailable($data, null);
            $uuid = Uuid::v4();
            try {
                $this->contractors->create($uuid, $data, $userId, $this->clock->now(), $this->auth->userId());
            } catch (PDOException $e) {
                throw Database::isUniqueViolation($e) ? $this->duplicate() : $e;
            }
            $this->audit->record(AuditAction::ContractorCreated, 'contractor', $uuid, [
                'document' => $data->documentType->value . ' ' . $data->documentNumber,
                'name' => $data->name,
                'linked_user' => $data->userUuid,
            ]);

            return $this->get($uuid);
        });
    }

    public function update(string $uuid, ContractorData $data): Contractor
    {
        $contractor = $this->get($uuid);

        return $this->db->transaction(function () use ($contractor, $data): Contractor {
            $this->contractors->lockById($contractor->id);
            $userId = $this->resolveUser($data->userUuid, $contractor->id);
            $this->assertDocumentAvailable($data, $contractor->id);
            try {
                $this->contractors->update($contractor->id, $data, $userId, $this->clock->now(), $this->auth->userId());
            } catch (PDOException $e) {
                throw Database::isUniqueViolation($e) ? $this->duplicate() : $e;
            }
            $this->audit->record(AuditAction::ContractorUpdated, 'contractor', $contractor->uuid, [
                'changes' => $this->diff($contractor, $data),
            ]);

            return $this->get($contractor->uuid);
        });
    }

    /** @return array{contractor: Contractor, active_contracts: int} */
    public function setActive(string $uuid, bool $active): array
    {
        $contractor = $this->get($uuid);
        $status = $active ? ActiveStatus::Active : ActiveStatus::Inactive;
        if ($contractor->status !== $status) {
            $this->db->transaction(function () use ($contractor, $status, $active): void {
                $this->contractors->setStatus($contractor->id, $status, $this->clock->now(), $this->auth->userId());
                $this->audit->record($active ? AuditAction::ContractorActivated : AuditAction::ContractorDeactivated, 'contractor', $contractor->uuid);
            });
        }

        // Desactivar no afecta los contratos en ejecución; se informa cuántos hay para que el
        // usuario lo sepa (la decisión sobre ellos corresponde a la supervisión).
        return ['contractor' => $this->get($uuid), 'active_contracts' => $this->contracts->countActiveForContractor($contractor->id)];
    }

    private function resolveUser(?string $userUuid, ?int $contractorId): ?int
    {
        if ($userUuid === null) {
            return null;
        }
        $user = $this->users->findByUuid($userUuid);
        if ($user === null || !$user->isActive()) {
            throw ValidationException::field('user', 'invalid_user', 'La cuenta seleccionada no existe o está inactiva.');
        }
        if (!in_array(Permission::ContractsViewOwn->value, $this->users->permissionsFor($user->id), true)) {
            throw ValidationException::field('user', 'not_contractor', 'La cuenta debe tener un rol que permita consultar sus propios contratos (ej. Contratista).');
        }
        if ($this->contractors->userLinked($user->id, $contractorId)) {
            throw ValidationException::field('user', 'already_linked', 'Esta cuenta ya está vinculada a otro contratista.');
        }

        return $user->id;
    }

    private function assertDocumentAvailable(ContractorData $data, ?int $exceptId): void
    {
        if ($this->contractors->documentExists($data->documentType, $data->documentNumber, $exceptId)) {
            throw $this->duplicate();
        }
    }

    private function duplicate(): ValidationException
    {
        return ValidationException::field('document_number', 'taken', 'Ya existe un contratista con este tipo y número de documento.');
    }

    /** @return array<string, array{from: mixed, to: mixed}> */
    private function diff(Contractor $before, ContractorData $after): array
    {
        $pairs = [
            'person_type' => [$before->personType->value, $after->personType->value],
            'document_type' => [$before->documentType->value, $after->documentType->value],
            'document_number' => [$before->documentNumber, $after->documentNumber],
            'verification_digit' => [$before->verificationDigit, $after->verificationDigit],
            'name' => [$before->name, $after->name],
            'email' => [$before->email, $after->email],
            'phone' => [$before->phone, $after->phone],
            'address' => [$before->address, $after->address],
            'linked_user' => [$before->userUuid, $after->userUuid],
        ];
        $changes = [];
        foreach ($pairs as $field => [$from, $to]) {
            if ($from !== $to) {
                $changes[$field] = ['from' => $from, 'to' => $to];
            }
        }

        return $changes;
    }
}
