<?php

declare(strict_types=1);

namespace Sigcon\Services\Activities;

use DateTimeImmutable;
use Sigcon\Models\Activity;
use Sigcon\Models\WorkStatus;

/**
 * Deriva el estado de cada obligación, tarea y subtarea a partir de los informes (ADR-021).
 * No existe aprobación por subtarea: el supervisor aprueba u observa el informe completo, con
 * observaciones por obligación. Reglas:
 *
 * Elementos que reciben avance (hojas):
 * 1. Aprobada: está al 100 % y llegó al 100 % dentro de un período ya cubierto por un informe
 *    aprobado.
 * 2. Con observaciones: su obligación fue observada en la última revisión del último informe.
 * 3. En revisión: tuvo avances dentro del período del informe que está esperando revisión.
 * 4. Pendiente: en cualquier otro caso.
 *
 * Elementos con hijos: con observaciones si alguna hoja lo está; en revisión si alguna lo
 * está; aprobada si todas lo están; pendiente en otro caso. Una obligación observada en la
 * última revisión se muestra siempre "Con observaciones".
 */
final class WorkStatusResolver
{
    /**
     * @param list<Activity> $activities       todos los elementos del contrato
     * @param list<int> $observedObligationIds obligaciones observadas en la última revisión
     * @param list<int> $inReviewIds            elementos con avances en el período en revisión
     * @param ?DateTimeImmutable $approvedUntil fin (exclusivo, UTC) del último período aprobado
     * @param array<int, DateTimeImmutable> $lastCompletedAt id => última vez que llegó al 100 %
     * @return array<int, WorkStatus> id de actividad => estado
     */
    public static function resolve(
        array $activities,
        array $observedObligationIds,
        array $inReviewIds,
        ?DateTimeImmutable $approvedUntil,
        array $lastCompletedAt,
    ): array {
        $byId = [];
        $children = [];
        foreach ($activities as $activity) {
            $byId[$activity->id] = $activity;
            if ($activity->parentId !== null) {
                $children[$activity->parentId][] = $activity->id;
            }
        }
        $observed = array_flip($observedObligationIds);
        $inReview = array_flip($inReviewIds);

        $obligationOf = static function (Activity $activity) use ($byId): int {
            while ($activity->parentId !== null && isset($byId[$activity->parentId])) {
                $activity = $byId[$activity->parentId];
            }

            return $activity->id;
        };

        $statuses = [];
        $resolveNode = static function (int $id) use (&$resolveNode, &$statuses, $byId, $children, $observed, $inReview, $approvedUntil, $lastCompletedAt, $obligationOf): WorkStatus {
            $activity = $byId[$id];
            if (!isset($children[$id])) {
                $completedAt = $lastCompletedAt[$id] ?? null;
                $status = match (true) {
                    (float) $activity->progress >= 100 && $approvedUntil !== null && $completedAt !== null && $completedAt < $approvedUntil => WorkStatus::Approved,
                    isset($observed[$obligationOf($activity)]) => WorkStatus::Observed,
                    isset($inReview[$id]) => WorkStatus::InReview,
                    default => WorkStatus::Pending,
                };
            } else {
                $childStatuses = array_map($resolveNode, $children[$id]);
                $status = match (true) {
                    in_array(WorkStatus::Observed, $childStatuses, true) => WorkStatus::Observed,
                    in_array(WorkStatus::InReview, $childStatuses, true) => WorkStatus::InReview,
                    array_filter($childStatuses, static fn (WorkStatus $s) => $s !== WorkStatus::Approved) === [] => WorkStatus::Approved,
                    default => WorkStatus::Pending,
                };
            }
            if ($activity->parentId === null && isset($observed[$id])) {
                $status = WorkStatus::Observed;
            }

            return $statuses[$id] = $status;
        };

        foreach ($activities as $activity) {
            if ($activity->parentId === null) {
                $resolveNode($activity->id);
            }
        }

        return $statuses;
    }
}
