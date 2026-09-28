<?php

declare(strict_types=1);

namespace Sigcon\Services\Reports\Compose;

use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\Clock;
use Sigcon\Models\Activity;
use Sigcon\Models\Document;
use Sigcon\Repositories\ActivityRepository;
use Sigcon\Services\Documents\DocumentService;

/**
 * Crea el acta o el informe desde la actividad del contratista (ADR-023).
 *
 * Genera un PDF con el formato elegido, lo que el contratista escribió o dictó y sus fotos, y
 * lo guarda como anexo del informe del período, clasificado como "Acta" o "Informe de
 * actividades". Aplica las mismas reglas que cargar un anexo: solo el contratista del
 * contrato, con el informe en borrador o con observaciones.
 */
final class ComposedDocumentService
{
    public function __construct(
        private readonly DocumentService $documents,
        private readonly ActivityRepository $activities,
        private readonly ComposedDocumentRenderer $renderer,
        private readonly Clock $clock,
    ) {
    }

    public function compose(string $reportUuid, ComposedDocumentData $data): Document
    {
        $owner = $this->documents->reportOwner($reportUuid);
        $report = $owner->report ?? throw new \LogicException('El dueño debe ser un informe.');
        $this->documents->assertCanUpload($owner);

        $activity = $this->activities->findByUuid($data->activityUuid);
        if ($activity === null || $activity->contractId !== $owner->contract->id) {
            throw ValidationException::field('activity', 'invalid', 'La actividad no pertenece al contrato de este informe.');
        }
        $obligation = $this->obligationOf($activity);

        // Primero los permisos y la pertenencia de las fotos; después, el trabajo pesado.
        $images = $this->documents->reportImages($owner, array_column($data->photos, 'uuid'));
        $photos = array_map(static fn (array $p) => ['bytes' => $images[$p['uuid']], 'caption' => $p['caption']], $data->photos);

        $contract = $owner->contract;
        $pdf = $this->renderer->render($data, [
            'contract_number' => $contract->contractNumber,
            'contractor' => $contract->contractorName,
            'supervisor' => $contract->supervisorName,
            'department' => $contract->departmentName,
            'activity' => $activity->title,
            'obligation' => $obligation->title,
            'period' => $report->periodStart->format('d/m/Y') . ' – ' . $report->periodEnd->format('d/m/Y'),
        ], $photos, $this->clock->now());

        $date = $data->fields['fecha'] ?? $this->clock->now()->format('Y-m-d');
        $name = sprintf('%s - %s - %s.pdf', $data->template->label(), self::safeName($obligation->title), $date);

        return $this->documents->storeGenerated(
            $owner,
            $data->template->value,
            mb_substr(sprintf('Creado en SIGCON desde la actividad: %s', $activity->title), 0, 255),
            $pdf,
            $name,
        );
    }

    private function obligationOf(Activity $activity): Activity
    {
        $current = $activity;
        while ($current->parentId !== null) {
            $current = $this->activities->findById($current->parentId) ?? throw new \LogicException('Actividad padre ausente.');
        }

        return $current;
    }

    /** Nombre de archivo legible y seguro (el nombre original nunca se usa como ruta). */
    private static function safeName(string $title): string
    {
        $clean = trim((string) preg_replace('/[^\p{L}\p{N} ,._()-]+/u', '', $title));
        $clean = rtrim(mb_substr($clean, 0, 60), ' .,');

        return $clean !== '' ? $clean : 'actividad';
    }
}
