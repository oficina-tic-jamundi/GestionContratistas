<?php

declare(strict_types=1);

namespace Sigcon\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\JsonResponder;
use Sigcon\Http\Page;
use Sigcon\Http\PageRequest;
use Sigcon\Repositories\ContractScope;
use Sigcon\Repositories\HistoryFeedRepository;
use Sigcon\Security\AuthContext;

/** Historial cronológico, con el alcance del usuario (ADR-021). */
final class HistoryFeedController
{
    private const KIND_LABELS = [
        'progress' => 'Avance',
        'evidence' => 'Evidencia',
        'report' => 'Informe',
        'payment' => 'Pago',
        'contract' => 'Contrato',
    ];

    public function __construct(
        private readonly HistoryFeedRepository $history,
        private readonly AuthContext $auth,
        private readonly JsonResponder $responder,
    ) {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $query = $request->getQueryParams();
        $kind = is_string($query['type'] ?? null) && $query['type'] !== '' ? $query['type'] : null;
        if ($kind !== null && !in_array($kind, HistoryFeedRepository::KINDS, true)) {
            throw ValidationException::field('type', 'invalid', 'Tipo de evento no válido.');
        }
        $pageRequest = PageRequest::fromQuery($query, ['occurred_at' => 'occurred_at'], 'occurred_at', 'desc');
        $result = $this->history->feed(ContractScope::forUser($this->auth), $kind, $pageRequest);
        $page = new Page($result['items'], $result['total'], $pageRequest);

        return $this->responder->success($response, $page->map(static fn (array $e) => [
            'kind' => $e['kind'],
            'kind_label' => self::KIND_LABELS[$e['kind']] ?? $e['kind'],
            'occurred_at' => DateTimes::toApi($e['occurred_at']),
            'summary' => $e['summary'],
            'title' => $e['title'],
            'comment' => $e['comment'],
            'from_progress' => $e['from_progress'],
            'to_progress' => $e['to_progress'],
            'ref_number' => $e['ref_number'],
            'contract' => [
                'uuid' => $e['contract_uuid'],
                'contract_number' => $e['contract_number'],
                'contractor' => $e['contractor'],
            ],
            // Enlace al informe o pago cuando el evento es de uno de ellos.
            'target' => $e['target_uuid'] === null ? null : ['type' => $e['kind'], 'uuid' => $e['target_uuid']],
            'actor' => $e['actor'],
        ]), meta: $page->meta());
    }
}
