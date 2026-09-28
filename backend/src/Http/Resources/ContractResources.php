<?php

declare(strict_types=1);

namespace Sigcon\Http\Resources;

use Sigcon\Helpers\DateTimes;
use Sigcon\Models\Contract;
use Sigcon\Models\Contractor;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\Department;
use Sigcon\Models\HistoryEntry;
use Sigcon\Models\User;

/**
 * Representación pública de dependencias, contratistas, contratos e historial.
 * Nunca incluye ids internos.
 */
final class ContractResources
{
    /** @return array<string, mixed> */
    public static function department(Department $d): array
    {
        return [
            'uuid' => $d->uuid,
            'code' => $d->code,
            'name' => $d->name,
            'status' => $d->status->value,
            'status_label' => $d->status->label(),
            'active_contracts' => $d->activeContracts,
        ];
    }

    /** @return array<string, mixed> */
    public static function contractor(Contractor $c): array
    {
        return [
            'uuid' => $c->uuid,
            'person_type' => $c->personType->value,
            'person_type_label' => $c->personType->label(),
            'document_type' => $c->documentType->value,
            'document_number' => $c->documentNumber,
            'verification_digit' => $c->verificationDigit,
            'document' => $c->displayDocument(),
            'name' => $c->name,
            'email' => $c->email,
            'phone' => $c->phone,
            'address' => $c->address,
            'status' => $c->status->value,
            'status_label' => $c->status->label(),
            'user' => $c->userUuid === null ? null : ['uuid' => $c->userUuid, 'email' => $c->userEmail],
            'created_at' => DateTimes::toApi($c->createdAt),
            'updated_at' => DateTimes::toApi($c->updatedAt),
        ];
    }

    /**
     * @param bool $canManage si el usuario puede gestionar contratos (determina las acciones disponibles)
     * @return array<string, mixed>
     */
    public static function contract(Contract $c, bool $canManage = false): array
    {
        return [
            'uuid' => $c->uuid,
            'contract_number' => $c->contractNumber,
            'object' => $c->object,
            'status' => $c->status->value,
            'status_label' => $c->status->label(),
            'signed_at' => $c->signedAt?->format('Y-m-d'),
            'start_date' => $c->startDate->format('Y-m-d'),
            'end_date' => $c->endDate->format('Y-m-d'),
            'total_value' => $c->totalValue,
            'payment_count' => $c->paymentCount,
            'secop_url' => $c->secopUrl,
            'contractor' => ['uuid' => $c->contractorUuid, 'name' => $c->contractorName, 'document' => $c->contractorDocument],
            'department' => ['uuid' => $c->departmentUuid, 'code' => $c->departmentCode, 'name' => $c->departmentName],
            'supervisor' => $c->supervisorUuid === null ? null : ['uuid' => $c->supervisorUuid, 'name' => $c->supervisorName],
            // Acciones que el backend aceptaría ahora mismo (la interfaz no repite las reglas).
            'actions' => $canManage ? [
                'edit' => $c->status->isEditable(),
                'delete' => $c->status === ContractStatus::Draft,
                'change_supervisor' => $c->status->allowsSupervisorChange(),
                'transitions' => array_map(static fn (ContractStatus $s) => [
                    'status' => $s->value,
                    'label' => $s->label(),
                    'requires_comment' => $c->status->transitionRequiresComment($s),
                ], $c->status->allowedTransitions()),
            ] : ['edit' => false, 'delete' => false, 'change_supervisor' => false, 'transitions' => []],
            'created_at' => DateTimes::toApi($c->createdAt),
            'updated_at' => DateTimes::toApi($c->updatedAt),
        ];
    }

    /** @return array<string, mixed> */
    public static function history(HistoryEntry $h): array
    {
        return [
            'id' => $h->id,
            'event' => $h->event,
            'summary' => $h->summary,
            'comment' => $h->comment,
            'from_status' => $h->fromStatus,
            'to_status' => $h->toStatus,
            'user' => $h->userName,
            'occurred_at' => DateTimes::toApi($h->occurredAt),
            'metadata' => $h->metadata,
        ];
    }

    /** @return array{uuid: string, name: string, email: string, department: ?string} */
    public static function supervisor(User $u): array
    {
        return ['uuid' => $u->uuid, 'name' => $u->fullName(), 'email' => $u->email, 'department' => $u->departmentName];
    }
}
