<?php

declare(strict_types=1);

namespace Sigcon\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sigcon\DTOs\ActivityData;
use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\JsonResponder;
use Sigcon\Models\Activity;
use Sigcon\Models\ActivityPriority;
use Sigcon\Services\Activities\ActivityService;
use Sigcon\Services\Activities\ObligationExtractor;
use Sigcon\Services\Activities\ObligationImportService;
use Sigcon\Validators\Validator;

final class ActivityController
{
    public function __construct(
        private readonly ActivityService $activities,
        private readonly ObligationImportService $import,
        private readonly JsonResponder $responder,
    ) {
    }

    /** @param array{uuid: string} $args */
    public function tree(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tree = $this->activities->tree($args['uuid']);

        return $this->responder->success($response, [
            'progress' => $tree['progress'],
            'can' => $tree['can'],
            'items' => $this->nest($tree['activities']),
        ]);
    }

    /** @param array{uuid: string} $args */
    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $detail = $this->activities->detail($args['uuid']);
        $contract = $detail['contract'];

        return $this->responder->success($response, [
            'activity' => self::present($detail['activity']),
            'obligation' => self::present($detail['obligation']),
            'parent' => $detail['parent'] === null ? null : self::present($detail['parent']),
            'children' => array_map(self::present(...), $detail['children']),
            'contract' => [
                'uuid' => $contract->uuid,
                'contract_number' => $contract->contractNumber,
                'object' => $contract->object,
                'status' => $contract->status->value,
                'status_label' => $contract->status->label(),
                'start_date' => $contract->startDate->format('Y-m-d'),
                'end_date' => $contract->endDate->format('Y-m-d'),
                'department' => $contract->departmentName,
                'supervisor' => $contract->supervisorName,
            ],
        ]);
    }

    /** @param array{uuid: string} $args */
    public function createObligation(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $activity = $this->activities->createObligation($args['uuid'], ActivityData::fromBody($request->getParsedBody()));

        return $this->responder->success($response, self::present($activity), 'Obligación registrada.', 201);
    }

    /**
     * Propuesta de obligaciones leída del contrato firmado (no registra nada).
     *
     * @param array{uuid: string} $args
     */
    public function suggestObligations(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $result = $this->import->suggest($args['uuid']);
        $document = $result['document'];

        return $this->responder->success($response, [
            'document' => [
                'uuid' => $document->uuid,
                'name' => $document->originalName,
                'uploaded_at' => DateTimes::toApi($document->createdAt),
            ],
            'items' => $result['items'],
            'warnings' => array_map(static fn (string $code) => [
                'code' => $code,
                'message' => match ($code) {
                    ObligationExtractor::SECTION_NOT_FOUND => 'No se encontró la sección "Obligaciones específicas del contratista" en el documento. Regístrelas a mano.',
                    ObligationExtractor::NO_ITEMS => 'Se encontró la sección de obligaciones, pero no una lista numerada. Regístrelas a mano.',
                    ObligationExtractor::GENERAL_SECTION => 'El contrato no tiene "obligaciones específicas": se tomaron las del contratista, que pueden incluir obligaciones generales. Quite las que no correspondan.',
                    ObligationExtractor::TRUNCATED => sprintf('El contrato tiene más de %d obligaciones; se tomaron las primeras.', ObligationExtractor::MAX_ITEMS),
                    default => $code,
                },
            ], $result['warnings']),
            'existing_obligations' => $result['existing'],
        ]);
    }

    /** @param array{uuid: string} $args */
    public function createObligations(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $raw = is_array($body) ? ($body['items'] ?? null) : null;
        if (!is_array($raw) || $raw === [] || !array_is_list($raw)) {
            throw ValidationException::field('items', 'required', 'Incluya al menos una obligación.');
        }
        if (count($raw) > ObligationExtractor::MAX_ITEMS) {
            throw ValidationException::field('items', 'too_many', sprintf('Máximo %d obligaciones por envío.', ObligationExtractor::MAX_ITEMS));
        }

        $items = [];
        $errors = [];
        foreach ($raw as $index => $item) {
            try {
                $items[] = ActivityData::fromBody(is_array($item) ? $item : null);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $error) {
                    $errors[] = [
                        'field' => sprintf('items.%d.%s', $index, $error['field'] ?? ''),
                        'code' => $error['code'],
                        'message' => sprintf('Obligación %d: %s', $index + 1, $error['message']),
                    ];
                }
            }
        }
        if ($errors !== []) {
            throw ValidationException::withErrors($errors);
        }

        $created = $this->activities->createObligations($args['uuid'], $items);

        return $this->responder->success(
            $response,
            array_map(self::present(...), $created),
            sprintf('%d %s registradas.', count($created), count($created) === 1 ? 'obligación' : 'obligaciones'),
            201,
        );
    }

    /** @param array{uuid: string} $args */
    public function createChild(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $activity = $this->activities->createChild($args['uuid'], ActivityData::fromBody($request->getParsedBody()));

        return $this->responder->success($response, self::present($activity), sprintf('%s registrada.', $activity->level->label()), 201);
    }

    /** @param array{uuid: string} $args */
    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $activity = $this->activities->update($args['uuid'], ActivityData::fromBody($request->getParsedBody()));

        return $this->responder->success($response, self::present($activity), 'Cambios guardados.');
    }

    /** @param array{uuid: string} $args */
    public function destroy(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->activities->delete($args['uuid']);

        return $this->responder->success($response, null, 'Elemento eliminado.');
    }

    /** @param array{uuid: string} $args */
    public function recordProgress(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $v = Validator::fromBody($request->getParsedBody());
        $body = $request->getParsedBody();
        $raw = is_array($body) ? ($body['progress'] ?? null) : null;
        $progress = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);
        if ($progress === false) {
            $v->add('progress', 'invalid', 'Indique un porcentaje entero entre 0 y 100.');
        }
        $note = $v->string('note', 2000, 10);
        $v->throwIfInvalid();

        $activity = $this->activities->recordProgress($args['uuid'], (int) $progress, Validator::present($note));

        return $this->responder->success($response, self::present($activity), 'Avance registrado.');
    }

    /** @param array{uuid: string} $args */
    public function setPriority(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $raw = is_array($body) ? ($body['priority'] ?? null) : null;
        $priority = null;
        if ($raw !== null && $raw !== '') {
            $priority = is_string($raw) ? ActivityPriority::tryFrom($raw) : null;
            if ($priority === null) {
                $v = Validator::fromBody($body);
                $v->add('priority', 'invalid', 'Seleccione una prioridad válida: alta, media o baja.');
                $v->throwIfInvalid();
            }
        }
        $activity = $this->activities->setPriority($args['uuid'], $priority);

        return $this->responder->success($response, self::present($activity), 'Prioridad guardada.');
    }

    /** @param array{uuid: string} $args */
    public function progressHistory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $result = $this->activities->progressHistory($args['uuid']);

        return $this->responder->success($response, [
            'activity' => self::present($result['activity']),
            'history' => array_map(static fn (array $h) => [
                'uuid' => $h['uuid'],
                'previous_progress' => $h['previous_progress'],
                'new_progress' => $h['new_progress'],
                'note' => $h['note'],
                'user' => $h['user'],
                'recorded_at' => DateTimes::toApi($h['recorded_at']),
            ], $result['history']),
        ]);
    }

    /** @return array<string, mixed> */
    private static function present(Activity $a): array
    {
        return [
            'uuid' => $a->uuid,
            'level' => $a->level->value,
            'level_label' => $a->level->label(),
            'position' => $a->position,
            'title' => $a->title,
            'description' => $a->description,
            'weight' => $a->weight,
            'priority' => $a->priority?->value,
            'priority_label' => $a->priority?->label(),
            'progress' => $a->progress,
            // Hoja = recibe avances declarados; si tiene hijos, el avance es calculado.
            'progress_is_computed' => !$a->isLeaf(),
            'due_date' => $a->dueDate?->format('Y-m-d'),
            'has_progress_updates' => $a->updateCount > 0,
            'updated_at' => DateTimes::toApi($a->updatedAt),
        ];
    }

    /**
     * Convierte la lista plana en árbol (obligación → tareas → subtareas).
     *
     * @param list<Activity> $activities
     * @return list<array<string, mixed>>
     */
    private function nest(array $activities): array
    {
        $byParent = [];
        foreach ($activities as $activity) {
            $byParent[$activity->parentId ?? 0][] = $activity;
        }
        $build = static function (int $parentId) use (&$build, $byParent): array {
            return array_map(
                static fn (Activity $a) => self::present($a) + ['children' => $build($a->id)],
                $byParent[$parentId] ?? [],
            );
        };

        return $build(0);
    }
}
