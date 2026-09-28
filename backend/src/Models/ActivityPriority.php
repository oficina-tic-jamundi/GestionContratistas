<?php

declare(strict_types=1);

namespace Sigcon\Models;

/** Prioridad que la Alcaldía asigna a una obligación, tarea o subtarea (ADR-021). */
enum ActivityPriority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function label(): string
    {
        return match ($this) {
            self::High => 'Alta',
            self::Medium => 'Media',
            self::Low => 'Baja',
        };
    }
}
