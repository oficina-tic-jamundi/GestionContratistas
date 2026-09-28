<?php

declare(strict_types=1);

namespace Sigcon\Repositories;

use DateTimeImmutable;
use Sigcon\Database\Database;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\PageRequest;

/**
 * Historial cronológico (ADR-021): avances, evidencias y cambios de contratos, informes y
 * pagos. Aplica SIEMPRE el alcance del usuario (ContractScope):
 *
 * - administración (contracts.view_all): todos los contratos, con el nombre de quien actuó;
 * - supervisor (contracts.view_assigned): los contratos que supervisa, es decir lo suyo y lo
 *   de sus contratistas;
 * - contratista (contracts.view_own): sus contratos, lo que él hace y lo que el supervisor
 *   decide sobre sus informes.
 */
final class HistoryFeedRepository
{
    /** Tipos de evento que se pueden filtrar. */
    public const KINDS = ['progress', 'evidence', 'report', 'payment', 'contract'];

    public function __construct(private readonly Database $db)
    {
    }

    /**
     * @return array{items: list<array{kind: string, occurred_at: DateTimeImmutable, summary: string, title: ?string, comment: ?string, from_progress: ?string, to_progress: ?string, ref_number: ?int, contract_number: string, contract_uuid: string, contractor: string, target_uuid: ?string, actor: ?string}>, total: int}
     */
    public function feed(ContractScope $scope, ?string $kind, PageRequest $page): array
    {
        if ($scope->isEmpty()) {
            return ['items' => [], 'total' => 0];
        }
        $parts = [
            'progress' => "SELECT 'progress' AS kind, pu.recorded_at AS occurred_at, 'Avance registrado' AS summary, a.title,
                    pu.note AS comment, pu.previous_progress AS from_progress, pu.new_progress AS to_progress, NULL AS ref_number,
                    c.contract_number, c.uuid AS contract_uuid, ct.name AS contractor, NULL AS target_uuid, pu.user_id AS actor_id
                FROM progress_updates pu JOIN activities a ON a.id = pu.activity_id
                JOIN contracts c ON c.id = a.contract_id JOIN contractors ct ON ct.id = c.contractor_id",
            'evidence' => "SELECT 'evidence', e.captured_at, 'Evidencia fotográfica registrada', a.title,
                    e.description, NULL, NULL, NULL, c.contract_number, c.uuid, ct.name, NULL, e.captured_by
                FROM evidences e JOIN activities a ON a.id = e.activity_id
                JOIN contracts c ON c.id = e.contract_id JOIN contractors ct ON ct.id = c.contractor_id",
            'report' => "SELECT 'report', bh.occurred_at, bh.summary, NULL, bh.comment, NULL, NULL, r.number,
                    c.contract_number, c.uuid, ct.name, r.uuid, bh.user_id
                FROM business_history bh JOIN reports r ON bh.entity_type = 'report' AND r.id = bh.entity_id
                JOIN contracts c ON c.id = r.contract_id JOIN contractors ct ON ct.id = c.contractor_id",
            'payment' => "SELECT 'payment', bh.occurred_at, bh.summary, NULL, bh.comment, NULL, NULL, p.number,
                    c.contract_number, c.uuid, ct.name, p.uuid, bh.user_id
                FROM business_history bh JOIN payments p ON bh.entity_type = 'payment' AND p.id = bh.entity_id
                JOIN contracts c ON c.id = p.contract_id JOIN contractors ct ON ct.id = c.contractor_id",
            'contract' => "SELECT 'contract', bh.occurred_at, bh.summary, NULL, bh.comment, NULL, NULL, NULL,
                    c.contract_number, c.uuid, ct.name, NULL, bh.user_id
                FROM business_history bh JOIN contracts c ON bh.entity_type = 'contract' AND c.id = bh.entity_id
                JOIN contractors ct ON ct.id = c.contractor_id",
        ];
        if ($kind !== null) {
            $parts = [$kind => $parts[$kind]];
        }

        // Cada subconsulta lleva el alcance con marcadores propios: PDO no admite repetirlos.
        [$condition, $scopeParams] = $scope->toSql();
        $params = [];
        $selects = [];
        foreach ($parts as $key => $sql) {
            $suffix = '_' . $key;
            $selects[] = $sql . ' WHERE c.deleted_at IS NULL AND ' . str_replace(':scope_', ":scope{$suffix}_", $condition);
            foreach ($scopeParams as $name => $value) {
                $params[str_replace('scope_', "scope{$suffix}_", $name)] = $value;
            }
        }
        $union = implode("\nUNION ALL\n", $selects);

        $total = (int) ($this->db->fetchAll("SELECT COUNT(*) AS n FROM ({$union}) h", $params)[0]['n'] ?? 0);
        $rows = $this->db->fetchAll(
            sprintf(
                "SELECT h.*, CONCAT(u.first_name, ' ', u.last_name) AS actor FROM ({$union}) h
                 LEFT JOIN users u ON u.id = h.actor_id
                 ORDER BY h.occurred_at DESC, h.kind LIMIT %d OFFSET %d",
                $page->perPage,
                $page->offset(),
            ),
            $params,
        );

        return [
            'items' => array_map(static fn (array $r) => [
                'kind' => (string) $r['kind'],
                'occurred_at' => DateTimes::fromDb((string) $r['occurred_at']),
                'summary' => (string) $r['summary'],
                'title' => $r['title'] === null ? null : (string) $r['title'],
                'comment' => $r['comment'] === null ? null : (string) $r['comment'],
                'from_progress' => $r['from_progress'] === null ? null : (string) $r['from_progress'],
                'to_progress' => $r['to_progress'] === null ? null : (string) $r['to_progress'],
                'ref_number' => $r['ref_number'] === null ? null : (int) $r['ref_number'],
                'contract_number' => (string) $r['contract_number'],
                'contract_uuid' => (string) $r['contract_uuid'],
                'contractor' => (string) $r['contractor'],
                'target_uuid' => $r['target_uuid'] === null ? null : (string) $r['target_uuid'],
                'actor' => $r['actor'] === null ? null : (string) $r['actor'],
            ], $rows),
            'total' => $total,
        ];
    }
}
