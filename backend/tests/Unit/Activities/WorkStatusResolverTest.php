<?php

declare(strict_types=1);

namespace Sigcon\Tests\Unit\Activities;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Sigcon\Models\Activity;
use Sigcon\Models\ActivityLevel;
use Sigcon\Models\WorkStatus;
use Sigcon\Services\Activities\WorkStatusResolver;

final class WorkStatusResolverTest extends TestCase
{
    private static function activity(int $id, ?int $parentId, string $progress, int $children = 0): Activity
    {
        return new Activity(
            id: $id,
            uuid: sprintf('00000000-0000-4000-8000-%012d', $id),
            contractId: 1,
            parentId: $parentId,
            level: $parentId === null ? ActivityLevel::Obligation : ActivityLevel::Task,
            position: $id,
            title: "Elemento {$id}",
            description: null,
            weight: '1.00',
            priority: null,
            progress: $progress,
            dueDate: null,
            childCount: $children,
            updateCount: 0,
            updatedAt: new DateTimeImmutable('2026-03-01', new DateTimeZone('UTC')),
        );
    }

    private static function at(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date, new DateTimeZone('UTC'));
    }

    /** Obligación 1 con tareas 11 y 12; obligación 2 sin tareas. */
    private static function tree(string $p11 = '100.00', string $p12 = '50.00', string $p2 = '0.00'): array
    {
        return [
            self::activity(1, null, '75.00', 2),
            self::activity(11, 1, $p11),
            self::activity(12, 1, $p12),
            self::activity(2, null, $p2),
        ];
    }

    public function testEverythingIsPendingWithoutReports(): void
    {
        $statuses = WorkStatusResolver::resolve(self::tree(), [], [], null, [11 => self::at('2026-03-10')]);

        self::assertSame(WorkStatus::Pending, $statuses[1]);
        self::assertSame(WorkStatus::Pending, $statuses[11], 'Al 100 % pero sin informe aprobado');
        self::assertSame(WorkStatus::Pending, $statuses[2]);
    }

    public function testItemsWithProgressInTheReportUnderReviewAreInReview(): void
    {
        $statuses = WorkStatusResolver::resolve(self::tree(), [], [11, 12], null, []);

        self::assertSame(WorkStatus::InReview, $statuses[11]);
        self::assertSame(WorkStatus::InReview, $statuses[1], 'Un hijo en revisión basta para la obligación');
        self::assertSame(WorkStatus::Pending, $statuses[2], 'Sin avances en el período');
    }

    public function testAnObservedObligationMarksItsUnapprovedItems(): void
    {
        $statuses = WorkStatusResolver::resolve(
            self::tree(),
            [1],
            [],
            self::at('2026-03-01'),                   // aprobado hasta febrero
            [11 => self::at('2026-02-20')],           // la tarea 11 llegó al 100 % en febrero
        );

        self::assertSame(WorkStatus::Approved, $statuses[11], 'Lo ya aprobado se mantiene');
        self::assertSame(WorkStatus::Observed, $statuses[12]);
        self::assertSame(WorkStatus::Observed, $statuses[1]);
        self::assertSame(WorkStatus::Pending, $statuses[2]);
    }

    public function testApprovalRequiresCompletionWithinAnApprovedPeriod(): void
    {
        $until = self::at('2026-04-01');
        $statuses = WorkStatusResolver::resolve(self::tree('100.00', '100.00', '100.00'), [], [], $until, [
            11 => self::at('2026-03-10'),
            12 => self::at('2026-04-05'),             // terminó después del período aprobado
            2 => self::at('2026-03-31 23:00:00'),
        ]);

        self::assertSame(WorkStatus::Approved, $statuses[11]);
        self::assertSame(WorkStatus::Pending, $statuses[12]);
        self::assertSame(WorkStatus::Pending, $statuses[1], 'Aprobada solo si todas sus tareas lo están');
        self::assertSame(WorkStatus::Approved, $statuses[2]);
    }

    public function testAnObservedObligationStaysObservedEvenIfItsItemsWereApproved(): void
    {
        $statuses = WorkStatusResolver::resolve(self::tree('100.00', '100.00'), [1], [], self::at('2026-04-01'), [
            11 => self::at('2026-03-10'),
            12 => self::at('2026-03-11'),
        ]);

        self::assertSame(WorkStatus::Approved, $statuses[11]);
        self::assertSame(WorkStatus::Observed, $statuses[1]);
    }
}
