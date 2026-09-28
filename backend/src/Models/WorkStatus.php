<?php

declare(strict_types=1);

namespace Sigcon\Models;

/**
 * Estado de una obligación, tarea o subtarea en el panel del contratista (ADR-021). No se
 * guarda: se deriva de los informes y los avances (WorkStatusResolver).
 */
enum WorkStatus: string
{
    case Approved = 'approved';
    case InReview = 'in_review';
    case Observed = 'observed';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Approved => 'Aprobada',
            self::InReview => 'En revisión',
            self::Observed => 'Con observaciones',
            self::Pending => 'Pendiente',
        };
    }
}
