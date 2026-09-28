<?php

declare(strict_types=1);

namespace Sigcon\Services\Reports\Compose;

/**
 * Formatos del documento que el contratista crea desde su actividad (ADR-023).
 *
 * Cada formato tiene sus propios campos: un acta registra una reunión (lugar, asistentes,
 * compromisos) y un informe registra lo ejecutado (actividades, resultados). El código coincide
 * con el tipo de documento de informe con que se guarda (`acta` o `informe`).
 *
 * Son formatos PROVISIONALES de SIGCON: cuando la Alcaldía entregue sus planillas oficiales se
 * ajustan aquí los campos y en el renderizador el diseño, sin cambiar el flujo.
 */
enum ReportTemplate: string
{
    case Acta = 'acta';
    case Informe = 'informe';

    public function label(): string
    {
        return match ($this) {
            self::Acta => 'Acta',
            self::Informe => 'Informe de actividades',
        };
    }

    /**
     * Campos del formato, en el orden en que se imprimen.
     *
     * @return array<string, array{label: string, kind: 'date'|'time'|'line'|'text', required: bool, min: int, max: int}>
     */
    public function fields(): array
    {
        return match ($this) {
            self::Informe => [
                'fecha' => ['label' => 'Fecha del informe', 'kind' => 'date', 'required' => true, 'min' => 0, 'max' => 0],
                'actividades' => ['label' => 'Actividades realizadas', 'kind' => 'text', 'required' => true, 'min' => 20, 'max' => 10000],
                'resultados' => ['label' => 'Resultados obtenidos', 'kind' => 'text', 'required' => false, 'min' => 0, 'max' => 5000],
                'observaciones' => ['label' => 'Dificultades u observaciones', 'kind' => 'text', 'required' => false, 'min' => 0, 'max' => 5000],
            ],
            self::Acta => [
                'fecha' => ['label' => 'Fecha', 'kind' => 'date', 'required' => true, 'min' => 0, 'max' => 0],
                'hora' => ['label' => 'Hora', 'kind' => 'time', 'required' => false, 'min' => 0, 'max' => 0],
                'lugar' => ['label' => 'Lugar', 'kind' => 'line', 'required' => true, 'min' => 3, 'max' => 200],
                'tema' => ['label' => 'Tema u objetivo', 'kind' => 'line', 'required' => true, 'min' => 3, 'max' => 300],
                'asistentes' => ['label' => 'Asistentes', 'kind' => 'text', 'required' => false, 'min' => 0, 'max' => 3000],
                'desarrollo' => ['label' => 'Desarrollo', 'kind' => 'text', 'required' => true, 'min' => 20, 'max' => 10000],
                'compromisos' => ['label' => 'Compromisos', 'kind' => 'text', 'required' => false, 'min' => 0, 'max' => 5000],
            ],
        };
    }

    /** Campo principal: es el que resume lo hecho en la obligación dentro del informe del período. */
    public function mainField(): string
    {
        return match ($this) {
            self::Acta => 'desarrollo',
            self::Informe => 'actividades',
        };
    }
}
