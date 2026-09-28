<?php

declare(strict_types=1);

namespace Sigcon\Services\Documents;

use PDOException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Sigcon\Database\Database;
use Sigcon\Exceptions\AuthorizationException;
use Sigcon\Exceptions\BusinessRuleException;
use Sigcon\Exceptions\NotFoundException;
use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\Clock;
use Sigcon\Helpers\Uuid;
use Sigcon\Models\Contract;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\Document;
use Sigcon\Repositories\DocumentRepository;
use Sigcon\Repositories\ReportRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Security\Permission;
use Sigcon\Services\Audit\AuditAction;
use Sigcon\Services\Audit\AuditLogger;
use Sigcon\Services\Contracts\ContractService;
use Sigcon\Services\Reports\ReportAccess;
use Sigcon\Storage\FileStorage;

/**
 * Documentos de contratos (ADR-014) y anexos de informes (ADR-015).
 *
 * - Lectura y descarga: mismo alcance que la entidad dueña (fuera de alcance = 404). Cada
 *   descarga queda en la auditoría.
 * - Contratos: carga quien gestiona contratos o el contratista en su contrato (no archivados);
 *   retira quien gestiona contratos o quien cargó el documento, con motivo.
 * - Informes: solo el contratista autor, y solo mientras el informe es editable (borrador o con
 *   observaciones). Una vez enviado, los anexos forman parte de la versión y no se tocan.
 * - Un mismo archivo (SHA-256) no puede estar vigente dos veces en la misma entidad.
 */
final class DocumentService
{
    public const ENTITY_CONTRACT = 'contract';
    public const ENTITY_REPORT = 'report';

    public function __construct(
        private readonly DocumentRepository $documents,
        private readonly ContractService $contracts,
        private readonly ReportRepository $reports,
        private readonly ReportAccess $reportAccess,
        private readonly UploadValidator $validator,
        private readonly FileStorage $storage,
        private readonly AuditLogger $audit,
        private readonly AuthContext $auth,
        private readonly Database $db,
        private readonly Clock $clock,
    ) {
    }

    public function contractOwner(string $contractUuid): DocumentOwner
    {
        $contract = $this->contracts->get($contractUuid);

        return new DocumentOwner(self::ENTITY_CONTRACT, $contract->id, $contract->uuid, $contract);
    }

    public function reportOwner(string $reportUuid): DocumentOwner
    {
        $report = (Uuid::isValid($reportUuid) ? $this->reports->findByUuid($reportUuid) : null)
            ?? throw new NotFoundException('El informe no existe.');

        return new DocumentOwner(self::ENTITY_REPORT, $report->id, $report->uuid, $this->reportAccess->contractFor($report), $report);
    }

    /**
     * @return array{documents: list<Document>, withdrawable: list<string>, types: list<array{code: string, name: string}>, can_upload: bool, max_mb: int}
     */
    public function list(DocumentOwner $owner, int $maxBytes): array
    {
        $documents = $this->documents->forEntity($owner->entityType, $owner->entityId);

        return [
            'documents' => $documents,
            // Documentos que el usuario actual puede retirar (la interfaz no repite la regla).
            'withdrawable' => array_values(array_map(
                static fn (Document $d) => $d->uuid,
                array_filter($documents, fn (Document $d) => $d->isActive() && $this->canWithdraw($d, $owner)),
            )),
            'types' => $this->documents->types($owner->entityType),
            'can_upload' => $this->canUpload($owner),
            'max_mb' => intdiv($maxBytes, 1024 * 1024),
        ];
    }

    public function upload(DocumentOwner $owner, string $typeCode, ?string $description, ?UploadedFileInterface $file): Document
    {
        if (!$this->canUpload($owner)) {
            throw $this->uploadDenied($owner);
        }
        $typeId = $this->documents->typeId($owner->entityType, $typeCode)
            ?? throw ValidationException::field('type', 'invalid', 'Seleccione un tipo de documento válido.');
        $info = $this->validator->validate($file);
        $file = $file ?? throw new \LogicException('Archivo validado ausente.');

        return $this->persist($owner, $typeId, $typeCode, $description, $info, static fn (FileStorage $storage, string $path) => $storage->storeUpload($file, $path));
    }

    /**
     * Guarda un PDF que generó el sistema (p. ej. el acta o el informe creado desde la actividad,
     * ADR-023) con las mismas reglas que una carga: permiso, estado del informe, duplicados y
     * auditoría. El contenido no viene del usuario como archivo, así que no pasa por el
     * validador de cargas.
     */
    public function storeGenerated(DocumentOwner $owner, string $typeCode, ?string $description, string $pdf, string $originalName): Document
    {
        if (!$this->canUpload($owner)) {
            throw $this->uploadDenied($owner);
        }
        $typeId = $this->documents->typeId($owner->entityType, $typeCode)
            ?? throw new \LogicException("Tipo de documento {$typeCode} no configurado.");
        $info = [
            'extension' => 'pdf',
            'mime' => 'application/pdf',
            'size' => strlen($pdf),
            'sha256' => hash('sha256', $pdf),
            'original_name' => $originalName,
        ];

        return $this->persist($owner, $typeId, $typeCode, $description, $info, static fn (FileStorage $storage, string $path) => $storage->storeContents($pdf, $path));
    }

    /**
     * @param array{extension: string, mime: string, size: int, sha256: string, original_name: string} $info
     * @param callable(FileStorage, string): void $write
     */
    private function persist(DocumentOwner $owner, int $typeId, string $typeCode, ?string $description, array $info, callable $write): Document
    {
        $uuid = Uuid::v4();
        $now = $this->clock->now();
        $path = sprintf('private/documents/%s/%s.%s', $now->format('Y/m'), $uuid, $info['extension']);

        try {
            $this->db->transaction(function () use ($owner, $typeId, $typeCode, $description, $info, $uuid, $path, $now, $write): void {
                if ($owner->report !== null && !($this->reports->lockStatus($owner->report->id)?->isEditable() ?? false)) {
                    throw new BusinessRuleException('El informe cambió de estado y ya no admite anexos.');
                }
                if ($this->documents->activeHashExists($owner->entityType, $owner->entityId, $info['sha256'])) {
                    throw $this->duplicate($owner);
                }
                try {
                    $this->documents->create($uuid, $owner->entityType, $owner->entityId, $typeId, $description, $info, $path, (int) $this->auth->userId(), $now);
                } catch (PDOException $e) {
                    throw Database::isUniqueViolation($e) ? $this->duplicate($owner) : $e;
                }
                // Se guarda el archivo dentro de la transacción: si falla, no queda el registro.
                $write($this->storage, $path);
                $this->audit->record(AuditAction::DocumentUploaded, 'document', $uuid, $owner->auditContext() + [
                    'type' => $typeCode,
                    'name' => $info['original_name'],
                    'size' => $info['size'],
                    'sha256' => $info['sha256'],
                ]);
            });
        } catch (\Throwable $e) {
            // Compensación: si el archivo alcanzó a guardarse pero la transacción no se confirmó.
            if ($this->storage->exists($path) && $this->documents->findByUuid($uuid) === null) {
                $this->storage->delete($path);
            }
            throw $e;
        }

        return $this->documents->findByUuid($uuid) ?? throw new \LogicException('Documento no encontrado tras la carga.');
    }

    /** Falla si el usuario no puede agregar documentos a este dueño (mismo criterio que cargar). */
    public function assertCanUpload(DocumentOwner $owner): void
    {
        if (!$this->canUpload($owner)) {
            throw $this->uploadDenied($owner);
        }
    }

    /**
     * Anexos vigentes de un informe, con su contenido, para incrustarlos en un documento
     * generado. Solo imágenes del mismo informe (nunca de otro).
     *
     * @param list<string> $uuids
     * @return array<string, string> uuid => bytes
     */
    public function reportImages(DocumentOwner $owner, array $uuids): array
    {
        $images = [];
        foreach ($uuids as $uuid) {
            $document = $this->documents->findByUuid($uuid);
            if ($document === null || $document->entityType !== $owner->entityType || $document->entityId !== $owner->entityId
                || $document->status !== 'active' || !in_array($document->mimeType, ['image/jpeg', 'image/png'], true)) {
                throw ValidationException::field('photos', 'invalid', 'Una de las fotos no pertenece a este informe o ya no está vigente.');
            }
            $images[$uuid] = $this->storage->readStream($document->storagePath)->getContents();
        }

        return $images;
    }

    /** @return array{document: Document, stream: StreamInterface} */
    public function download(string $uuid): array
    {
        $document = $this->find($uuid);
        $owner = $this->ownerOf($document);
        $this->audit->record(AuditAction::DocumentDownloaded, 'document', $document->uuid, $owner->auditContext());

        return ['document' => $document, 'stream' => $this->storage->readStream($document->storagePath)];
    }

    public function withdraw(string $uuid, string $reason): Document
    {
        $document = $this->find($uuid);
        $owner = $this->ownerOf($document);
        if (!$document->isActive()) {
            return $document;
        }
        if (!$this->canWithdraw($document, $owner)) {
            throw $this->withdrawDenied($document, $owner);
        }

        $this->db->transaction(function () use ($document, $owner, $reason): void {
            if ($owner->report !== null && !($this->reports->lockStatus($owner->report->id)?->isEditable() ?? false)) {
                throw new BusinessRuleException('El informe cambió de estado; sus anexos ya no se modifican.');
            }
            $this->documents->withdraw($document->id, $reason, (int) $this->auth->userId(), $this->clock->now());
            $this->audit->record(AuditAction::DocumentWithdrawn, 'document', $document->uuid, $owner->auditContext() + ['reason' => $reason]);
        });

        return $this->find($uuid);
    }

    private function canUpload(DocumentOwner $owner): bool
    {
        if ($owner->report !== null) {
            return $this->reportAccess->canEdit($owner->report, $owner->contract);
        }

        return $owner->contract->status !== ContractStatus::Archived && $this->canActOnContract($owner);
    }

    private function canWithdraw(Document $document, DocumentOwner $owner): bool
    {
        if ($owner->report !== null) {
            return $this->reportAccess->canEdit($owner->report, $owner->contract) && $document->uploadedBy === $this->auth->userId();
        }

        return $owner->contract->status !== ContractStatus::Archived
            && ($this->auth->can(Permission::ContractsManage)
                || ($document->uploadedBy === $this->auth->userId() && $this->canActOnContract($owner)));
    }

    /**
     * Gestiona contratos, supervisa ESTE contrato (sube el contrato firmado y sus soportes,
     * ADR-021) o es el contratista del contrato.
     */
    private function canActOnContract(DocumentOwner $owner): bool
    {
        return $this->auth->can(Permission::ContractsManage)
            || $this->isSupervisor($owner->contract)
            || ($this->auth->can(Permission::ActivitiesExecute) && $this->contracts->isOwnContract($owner->contract));
    }

    private function isSupervisor(Contract $contract): bool
    {
        return $this->auth->can(Permission::ContractsSupervise)
            && $contract->supervisorId !== null
            && $contract->supervisorId === $this->auth->userId();
    }

    private function uploadDenied(DocumentOwner $owner): \RuntimeException
    {
        if ($owner->report !== null) {
            return $this->reportAccess->isAuthor($owner->contract)
                ? new BusinessRuleException(sprintf('Un informe "%s" no admite anexos nuevos.', $owner->report->status->label()))
                : new AuthorizationException('Solo el contratista del contrato puede anexar documentos al informe.');
        }

        return $owner->contract->status === ContractStatus::Archived && $this->canActOnContract($owner)
            ? new BusinessRuleException('No se cargan documentos en un contrato archivado.')
            : new AuthorizationException('No tiene permiso para cargar documentos en este contrato.');
    }

    private function withdrawDenied(Document $document, DocumentOwner $owner): \RuntimeException
    {
        if ($owner->report !== null) {
            return $document->uploadedBy === $this->auth->userId()
                ? new BusinessRuleException(sprintf('Los anexos de un informe "%s" no se modifican.', $owner->report->status->label()))
                : new AuthorizationException('Solo quien anexó el documento puede retirarlo.');
        }

        return $owner->contract->status === ContractStatus::Archived && $this->canActOnContract($owner)
            ? new BusinessRuleException('Los documentos de un contrato archivado no se modifican.')
            : new AuthorizationException('Solo quien gestiona el contrato o quien cargó el documento puede retirarlo.');
    }

    private function find(string $uuid): Document
    {
        return (Uuid::isValid($uuid) ? $this->documents->findByUuid($uuid) : null)
            ?? throw new NotFoundException('El documento no existe.');
    }

    /** Dueño del documento con el alcance del usuario; fuera de alcance = 404 del documento. */
    private function ownerOf(Document $document): DocumentOwner
    {
        try {
            if ($document->entityType === self::ENTITY_REPORT) {
                $report = $this->reports->findById($document->entityId) ?? throw new NotFoundException('');

                return new DocumentOwner(self::ENTITY_REPORT, $report->id, $report->uuid, $this->reportAccess->contractFor($report), $report);
            }
            $contract = $this->contracts->getById($document->entityId);

            return new DocumentOwner(self::ENTITY_CONTRACT, $contract->id, $contract->uuid, $contract);
        } catch (NotFoundException) {
            throw new NotFoundException('El documento no existe.');
        }
    }

    private function duplicate(DocumentOwner $owner): ValidationException
    {
        return ValidationException::field('file', 'duplicate', $owner->report !== null
            ? 'Este archivo ya está anexado al informe.'
            : 'Este archivo ya está cargado en el contrato.');
    }
}
