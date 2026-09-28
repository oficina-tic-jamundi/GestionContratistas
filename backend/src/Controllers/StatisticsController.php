<?php

declare(strict_types=1);

namespace Sigcon\Controllers;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sigcon\Http\JsonResponder;
use Sigcon\Models\ContractStatus;
use Sigcon\Repositories\ContractScope;
use Sigcon\Repositories\StatisticsRepository;
use Sigcon\Security\AuthContext;

/**
 * Estadísticas de pagos por contrato (ADR-021): meses de plazo, pagos pactados, pagados y
 * pendientes. "Pendientes" solo se calcula si el contrato declara cuántos pagos se pactaron.
 */
final class StatisticsController
{
    public function __construct(
        private readonly StatisticsRepository $statistics,
        private readonly AuthContext $auth,
        private readonly JsonResponder $responder,
    ) {
    }

    public function payments(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $rows = $this->statistics->paymentSchedule(ContractScope::forUser($this->auth));

        $contracts = array_map(static function (array $r): array {
            $status = ContractStatus::from($r['status']);
            $pending = $r['payment_count'] === null ? null : max(0, $r['payment_count'] - $r['paid_count']);

            return [
                'uuid' => $r['uuid'],
                'contract_number' => $r['contract_number'],
                'contractor' => $r['contractor'],
                'department' => $r['department'],
                'status' => $status->value,
                'status_label' => $status->label(),
                'start_date' => $r['start_date'],
                'end_date' => $r['end_date'],
                'months' => self::months($r['start_date'], $r['end_date']),
                'total_value' => $r['total_value'],
                'progress' => $r['progress'],
                'payments' => [
                    'agreed' => $r['payment_count'],
                    'paid' => $r['paid_count'],
                    'in_process' => $r['in_process_count'],
                    'pending' => $pending,
                    'paid_amount' => $r['paid_amount'],
                    'committed_amount' => $r['committed_amount'],
                ],
            ];
        }, $rows);

        $totals = [
            'contracts' => count($contracts),
            'agreed' => array_sum(array_map(static fn (array $c) => $c['payments']['agreed'] ?? 0, $contracts)),
            'paid' => array_sum(array_map(static fn (array $c) => $c['payments']['paid'], $contracts)),
            'in_process' => array_sum(array_map(static fn (array $c) => $c['payments']['in_process'], $contracts)),
            'paid_amount' => self::sum(array_map(static fn (array $c) => $c['payments']['paid_amount'], $contracts)),
            'total_value' => self::sum(array_map(static fn (array $c) => $c['total_value'], $contracts)),
            // Contratos sin el dato de pagos pactados: su columna "faltan" queda vacía.
            'without_agreed' => count(array_filter($contracts, static fn (array $c) => $c['payments']['agreed'] === null)),
        ];

        return $this->responder->success($response, ['contracts' => $contracts, 'totals' => $totals]);
    }

    /** Meses que cubre el plazo, contando el mes de inicio y el de terminación. */
    private static function months(string $start, string $end): int
    {
        $from = new DateTimeImmutable($start);
        $to = new DateTimeImmutable($end);
        $diff = $from->diff($to);

        return max(1, ($diff->y * 12) + $diff->m + ($diff->d > 0 ? 1 : 0));
    }

    /** @param list<string> $amounts */
    private static function sum(array $amounts): string
    {
        $total = 0;
        foreach ($amounts as $amount) {
            $total += (int) round(((float) $amount) * 100);
        }

        return number_format($total / 100, 2, '.', '');
    }
}
