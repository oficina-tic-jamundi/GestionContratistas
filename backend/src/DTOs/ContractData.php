<?php

declare(strict_types=1);

namespace Sigcon\DTOs;

use DateTimeImmutable;
use Sigcon\Validators\Validator;

/** Datos de un contrato en borrador (crear o modificar). */
final class ContractData
{
    public function __construct(
        public readonly string $contractNumber,
        public readonly string $object,
        public readonly string $contractorUuid,
        public readonly string $departmentUuid,
        public readonly ?string $supervisorUuid,
        public readonly ?DateTimeImmutable $signedAt,
        public readonly DateTimeImmutable $startDate,
        public readonly DateTimeImmutable $endDate,
        public readonly string $totalValue,
        /** Cuántos pagos se pactaron en el contrato; null si no se registró. */
        public readonly ?int $paymentCount,
        public readonly ?string $secopUrl,
    ) {
    }

    /** @param array<mixed>|object|null $body */
    public static function fromBody(array|object|null $body): self
    {
        $v = Validator::fromBody($body);
        $number = $v->string('contract_number', 50, 3);
        $object = $v->string('object', 5000, 10);
        $contractor = $v->uuid('contractor');
        $department = $v->uuid('department');
        $supervisor = $v->uuid('supervisor', required: false);
        $signedAt = $v->date('signed_at', required: false);
        $start = $v->date('start_date');
        $end = $v->date('end_date');
        $value = $v->money('total_value');
        $secop = $v->httpsUrl('secop_url');

        $paymentCount = null;
        $rawCount = is_array($body) ? ($body['payment_count'] ?? null) : null;
        if ($rawCount !== null && $rawCount !== '') {
            $parsed = filter_var($rawCount, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]);
            if ($parsed === false) {
                $v->add('payment_count', 'invalid', 'Los pagos pactados deben ser un número entero entre 1 y 120.');
            } else {
                $paymentCount = $parsed;
            }
        }

        if ($start !== null && $end !== null && $end < $start) {
            $v->add('end_date', 'before_start', 'La fecha de terminación no puede ser anterior a la de inicio.');
        }
        if ($signedAt !== null && $start !== null && $signedAt > $start) {
            $v->add('signed_at', 'after_start', 'La fecha de suscripción no puede ser posterior a la fecha de inicio.');
        }
        $v->throwIfInvalid();

        return new self(
            Validator::present($number),
            Validator::present($object),
            Validator::present($contractor),
            Validator::present($department),
            $supervisor,
            $signedAt,
            Validator::present($start),
            Validator::present($end),
            Validator::present($value),
            $paymentCount,
            $secop,
        );
    }
}
