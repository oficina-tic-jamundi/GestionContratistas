<?php

declare(strict_types=1);

namespace Sigcon\Models;

use DateTimeImmutable;

/**
 * Contrato con los datos resumidos de sus relaciones (contratista, dependencia, supervisor),
 * obtenidos en la misma consulta para evitar N+1.
 */
final class Contract
{
    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly string $contractNumber,
        public readonly string $object,
        public readonly ContractStatus $status,
        public readonly ?DateTimeImmutable $signedAt,
        public readonly DateTimeImmutable $startDate,
        public readonly DateTimeImmutable $endDate,
        public readonly string $totalValue,
        public readonly ?int $paymentCount,
        public readonly ?string $secopUrl,
        public readonly int $contractorId,
        public readonly string $contractorUuid,
        public readonly string $contractorName,
        public readonly string $contractorDocument,
        public readonly ?int $contractorUserId,
        public readonly int $departmentId,
        public readonly string $departmentUuid,
        public readonly string $departmentCode,
        public readonly string $departmentName,
        public readonly ?int $supervisorId,
        public readonly ?string $supervisorUuid,
        public readonly ?string $supervisorName,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }
}
