<?php

declare(strict_types=1);

namespace Sigcon\Models;

use DateTimeImmutable;

final class Activity
{
    /**
     * @param string $weight   decimal como texto ("1.00")
     * @param string $progress decimal como texto ("37.50")
     */
    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly int $contractId,
        public readonly ?int $parentId,
        public readonly ActivityLevel $level,
        public readonly int $position,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $weight,
        public readonly ?ActivityPriority $priority,
        public readonly string $progress,
        public readonly ?DateTimeImmutable $dueDate,
        public readonly int $childCount,
        public readonly int $updateCount,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }

    /** Una hoja recibe avances declarados; un nodo con hijos tiene avance calculado. */
    public function isLeaf(): bool
    {
        return $this->childCount === 0;
    }
}
