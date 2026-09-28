<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\FrozenClock;
use Sigcon\Tests\Support\IntegrationTestCase;

final class ReportsTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $contractor;
    private Browser $supervisor;
    private Browser $otherSupervisor;
    private string $contract;
    /** @var list<string> */
    private array $obligations = [];

    protected function setUp(): void
    {
        parent::setUp();
        // 10 de marzo de 2026, 10:00 en Bogotá.
        $this->clock = new FrozenClock(new DateTimeImmutable('2026-03-10 15:00:00', new DateTimeZone('UTC')));
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '33333333', 'name' => 'Carla Contratista', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-020-2026', 'object' => 'Prestación de servicios de prueba de informes.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-15', 'end_date' => '2026-12-15', 'total_value' => '36000000',
        ]))['data']['uuid'];
        foreach (['Apoyar la planeación institucional', 'Elaborar informes técnicos'] as $title) {
            $r = $this->admin->post("/contracts/{$this->contract}/obligations", ['title' => $title, 'weight' => '1']);
            self::assertStatus(201, $r);
            $this->obligations[] = self::json($r)['data']['uuid'];
        }
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));

        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
        $this->otherSupervisor = $this->browser();
        $this->otherSupervisor->login('otro.supervisor@example.test');
    }

    private function createReport(string $start = '2026-03-01', string $end = '2026-03-31'): ResponseInterface
    {
        return $this->contractor->post("/contracts/{$this->contract}/reports", ['period_start' => $start, 'period_end' => $end]);
    }

    private function draft(): string
    {
        $r = $this->createReport();
        self::assertStatus(201, $r);

        return self::json($r)['data']['uuid'];
    }

    private function fill(string $report, string $summary = 'Resumen de las actividades ejecutadas durante el período.'): ResponseInterface
    {
        return $this->contractor->put("/reports/{$report}", [
            'summary' => $summary,
            'contractor_notes' => null,
            'items' => [['obligation' => $this->obligations[0], 'description' => 'Participé en las mesas de planeación.']],
        ]);
    }

    /** Informe enviado y con revisión iniciada. */
    private function inReview(): string
    {
        $report = $this->draft();
        self::assertStatus(200, $this->fill($report));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));

        return $report;
    }

    /** @return array<string, mixed> */
    private function detail(Browser $as, string $report): array
    {
        $r = $as->get("/reports/{$report}");
        self::assertStatus(200, $r);

        return self::json($r)['data'];
    }

    public function testContractorCreatesNumberedReportsWithinTheContract(): void
    {
        $first = $this->createReport('2026-01-15', '2026-02-14');
        self::assertStatus(201, $first);
        self::assertSame(1, self::json($first)['data']['number']);
        self::assertSame('draft', self::json($first)['data']['status']);
        self::assertSame(2, self::json($this->createReport('2026-02-15', '2026-03-14'))['data']['number']);

        self::assertErrorCode('validation_error', $this->createReport('2026-03-01', '2026-03-20'));   // se cruza
        self::assertErrorCode('validation_error', $this->createReport('2026-12-01', '2026-12-31'));   // fuera del plazo
        self::assertErrorCode('validation_error', $this->createReport('2026-05-10', '2026-05-01'));   // fin antes del inicio
        self::assertContains('report.created', $this->auditActions());
    }

    public function testOnlyTheContractorOfAnActiveContractCreatesReports(): void
    {
        self::assertStatus(403, $this->supervisor->post("/contracts/{$this->contract}/reports", ['period_start' => '2026-03-01', 'period_end' => '2026-03-31']));
        self::assertStatus(403, $this->admin->post("/contracts/{$this->contract}/reports", ['period_start' => '2026-03-01', 'period_end' => '2026-03-31']));

        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'suspended', 'comment' => 'Suspensión de prueba.']));
        self::assertErrorCode('business_rule_violation', $this->createReport());
    }

    public function testDraftsArePrivateToTheContractor(): void
    {
        $report = $this->draft();

        self::assertStatus(404, $this->supervisor->get("/reports/{$report}"));
        self::assertSame(0, self::json($this->supervisor->get('/reports'))['meta']['pagination']['total']);
        self::assertSame(1, self::json($this->contractor->get('/reports'))['meta']['pagination']['total']);
        self::assertStatus(200, $this->admin->get("/reports/{$report}"), 'Quien ve todos los contratos ve también los borradores');
        self::assertStatus(404, $this->otherSupervisor->get("/reports/{$report}"));
    }

    public function testSubmitRequiresContentAndFreezesAnImmutableVersion(): void
    {
        $report = $this->draft();
        $empty = $this->contractor->post("/reports/{$report}/submit");
        self::assertErrorCode('validation_error', $empty);
        self::assertEqualsCanonicalizing(['summary', 'items'], array_column(self::json($empty)['errors'], 'field'));

        // El avance declarado en el período queda en el contenido congelado.
        self::assertStatus(200, $this->supervisor->post("/activities/{$this->obligations[0]}/progress", ['progress' => 40, 'note' => 'Primeras mesas de planeación verificadas.']));
        self::assertStatus(200, $this->fill($report));
        $submitted = $this->contractor->post("/reports/{$report}/submit");
        self::assertStatus(200, $submitted);
        self::assertSame('submitted', self::json($submitted)['data']['status']);
        self::assertSame(1, self::json($submitted)['data']['current_version']);

        $version = self::json($this->supervisor->get("/reports/{$report}/versions/1"))['data'];
        self::assertTrue($version['hash_valid']);
        self::assertSame('Participé en las mesas de planeación.', $version['content']['items'][0]['description']);
        self::assertSame('40.00', $version['content']['items'][0]['progress']);
        self::assertCount(1, $version['content']['items'][0]['progress_updates']);
        self::assertSame([], $version['content']['items'][1]['progress_updates']);

        // Ya enviado: no se edita ni se reenvía.
        self::assertErrorCode('business_rule_violation', $this->fill($report, 'Intento de cambio después de enviar el informe.'));
        self::assertErrorCode('business_rule_violation', $this->contractor->post("/reports/{$report}/submit"));

        // Una alteración directa de la base de datos se detecta.
        $this->pdo()->exec("UPDATE report_versions SET content = JSON_SET(content, '$.summary', 'alterado')");
        self::assertFalse(self::json($this->supervisor->get("/reports/{$report}/versions/1"))['data']['hash_valid']);
    }

    public function testProgressOutsideThePeriodIsNotIncluded(): void
    {
        $report = $this->draft();
        $this->clock->advance('+30 days'); // 9 de abril: fuera del período de marzo
        $this->contractor->login('contratista@example.test'); // la sesión anterior expiró
        $this->supervisor->login('supervisor@example.test');
        self::assertStatus(200, $this->supervisor->post("/activities/{$this->obligations[0]}/progress", ['progress' => 10, 'note' => 'Avance verificado en abril.']));
        self::assertStatus(200, $this->fill($report));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));

        $content = self::json($this->supervisor->get("/reports/{$report}/versions/1"))['data']['content'];
        self::assertSame([], $content['items'][0]['progress_updates']);
    }

    public function testObservationCycleCreatesANewVersionAndTracksChanges(): void
    {
        $report = $this->inReview();

        self::assertErrorCode('validation_error', $this->supervisor->post("/reports/{$report}/observe", ['observations' => []]));
        $observed = $this->supervisor->post("/reports/{$report}/observe", [
            'comment' => 'Faltan soportes.',
            'observations' => [
                ['obligation' => $this->obligations[1], 'text' => 'Describa los informes técnicos entregados.'],
                ['obligation' => null, 'text' => 'Anexe las actas de las mesas.'],
            ],
        ]);
        self::assertStatus(200, $observed);
        self::assertSame('observed', self::json($observed)['data']['status']);

        $detail = $this->detail($this->contractor, $report);
        self::assertTrue($detail['can']['edit']);
        self::assertSame('observed', $detail['reviews'][0]['decision']);
        self::assertSame('Elaborar informes técnicos', $detail['reviews'][0]['observations'][0]['activity_title']);
        self::assertNull($detail['reviews'][0]['observations'][1]['activity_uuid']);

        self::assertStatus(200, $this->contractor->put("/reports/{$report}", [
            'summary' => 'Resumen de las actividades ejecutadas durante el período.',
            'items' => [
                ['obligation' => $this->obligations[0], 'description' => 'Participé en las mesas de planeación.'],
                ['obligation' => $this->obligations[1], 'description' => 'Entregué dos informes técnicos.'],
            ],
        ]));
        $resubmitted = $this->contractor->post("/reports/{$report}/submit");
        self::assertSame('resubmitted', self::json($resubmitted)['data']['status']);
        self::assertSame(2, self::json($resubmitted)['data']['current_version']);

        $changes = self::json($this->supervisor->get("/reports/{$report}/versions/2"))['data']['changes'];
        self::assertFalse($changes['summary']);
        self::assertSame(['Elaborar informes técnicos'], $changes['items']);

        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve", ['comment' => 'Cumple.']));
        $approved = $this->detail($this->contractor, $report);
        self::assertSame('approved', $approved['status']);
        self::assertSame(2, $approved['approved_version']);
        self::assertCount(2, $approved['versions']);
    }

    public function testOnlyTheAssignedSupervisorDecides(): void
    {
        $report = $this->draft();
        self::assertStatus(200, $this->fill($report));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));

        // Otro supervisor no ve el informe (no es su contrato); el contratista no tiene el permiso.
        self::assertStatus(404, $this->otherSupervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(403, $this->contractor->post("/reports/{$report}/start-review"));
        // No se aprueba sin iniciar la revisión.
        self::assertErrorCode('business_rule_violation', $this->supervisor->post("/reports/{$report}/approve"));

        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));
        // Una segunda aprobación (otra pestaña) no duplica nada.
        self::assertErrorCode('business_rule_violation', $this->supervisor->post("/reports/{$report}/approve"));
        self::assertSame(1, (int) $this->pdo()->query("SELECT COUNT(*) FROM report_reviews WHERE decision = 'approved'")->fetchColumn());
    }

    public function testSupervisorChangeMovesTheReviewResponsibility(): void
    {
        $report = $this->inReview();
        $newSupervisor = self::json($this->admin->get('/supervisors'))['data'];
        $other = array_values(array_filter($newSupervisor, static fn (array $s) => $s['email'] === 'otro.supervisor@example.test'))[0]['uuid'];
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/supervisor", ['supervisor' => $other, 'comment' => 'Reasignación.']));

        self::assertStatus(404, $this->supervisor->post("/reports/{$report}/approve"));
        self::assertStatus(200, $this->otherSupervisor->post("/reports/{$report}/approve"));
    }

    public function testRejectionRequiresAReasonAndFreesThePeriod(): void
    {
        $report = $this->inReview();

        self::assertErrorCode('validation_error', $this->supervisor->post("/reports/{$report}/reject", ['comment' => '']));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/reject", ['comment' => 'El informe no corresponde al período.']));
        self::assertFalse($this->detail($this->contractor, $report)['can']['edit']);

        self::assertStatus(201, $this->createReport(), 'Un informe rechazado no ocupa el período');
    }

    public function testReopeningKeepsTheApprovalHistory(): void
    {
        $report = $this->inReview();
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));

        self::assertStatus(403, $this->supervisor->post("/reports/{$report}/reopen", ['reason' => 'Intento sin permiso de reapertura.']));
        self::assertErrorCode('validation_error', $this->admin->post("/reports/{$report}/reopen", ['reason' => 'corto']));
        $reopened = $this->admin->post("/reports/{$report}/reopen", ['reason' => 'Se detectó un error en las fechas reportadas.']);
        self::assertStatus(200, $reopened);
        self::assertSame('observed', self::json($reopened)['data']['status']);
        self::assertNull(self::json($reopened)['data']['approved_version']);

        $decisions = array_column($this->detail($this->admin, $report)['reviews'], 'decision');
        self::assertSame(['reopened', 'approved'], $decisions);
        self::assertTrue($this->detail($this->contractor, $report)['can']['submit']);
    }

    public function testCapabilitiesReflectWhoCanActNow(): void
    {
        $report = $this->draft();
        self::assertStatus(200, $this->fill($report));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));

        $forSupervisor = $this->detail($this->supervisor, $report)['can'];
        self::assertTrue($forSupervisor['start_review']);
        self::assertFalse($forSupervisor['approve']);
        self::assertFalse($forSupervisor['edit']);
        $forContractor = $this->detail($this->contractor, $report)['can'];
        self::assertSame([false], array_values(array_unique($forContractor)));
    }

    public function testReportAnnexesFollowTheReportLifecycle(): void
    {
        $report = $this->draft();
        $pdf = "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\ntrailer << /Root 1 0 R >>\n%%EOF\n";

        self::assertStatus(403, $this->supervisor->upload("/reports/{$report}/documents", $pdf, 'acta.pdf', ['type' => 'report_annex']));
        $uploaded = $this->contractor->upload("/reports/{$report}/documents", $pdf, 'acta.pdf', ['type' => 'report_annex']);
        self::assertStatus(201, $uploaded);
        $document = self::json($uploaded)['data']['uuid'];
        self::assertErrorCode('validation_error', $this->contractor->upload("/reports/{$report}/documents", $pdf, 'acta.pdf', ['type' => 'signed_contract']));

        self::assertStatus(200, $this->fill($report));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        $content = self::json($this->supervisor->get("/reports/{$report}/versions/1"))['data']['content'];
        self::assertSame('acta.pdf', $content['documents'][0]['name']);

        // Enviado: los anexos quedan congelados.
        self::assertErrorCode('business_rule_violation', $this->contractor->upload("/reports/{$report}/documents", $pdf . ' ', 'otra.pdf', ['type' => 'report_annex']));
        self::assertErrorCode('business_rule_violation', $this->contractor->post("/documents/{$document}/withdraw", ['reason' => 'Ya no aplica al informe.']));
        self::assertFalse(self::json($this->contractor->get("/reports/{$report}/documents"))['data']['items'][0]['can_withdraw']);

        // El supervisor asignado descarga el anexo; la descarga queda auditada.
        self::assertStatus(200, $this->supervisor->get("/documents/{$document}/download"));
        self::assertStatus(404, $this->otherSupervisor->get("/documents/{$document}/download"));
        self::assertContains('document.downloaded', $this->auditActions());
    }

    public function testAnnexesOfADraftAreHiddenFromTheSupervisor(): void
    {
        $report = $this->draft();
        $pdf = "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\ntrailer << /Root 1 0 R >>\n%%EOF\n";
        $document = self::json($this->contractor->upload("/reports/{$report}/documents", $pdf, 'borrador.pdf', ['type' => 'report_annex']))['data']['uuid'];

        self::assertStatus(404, $this->supervisor->get("/reports/{$report}/documents"));
        self::assertStatus(404, $this->supervisor->get("/documents/{$document}/download"));
    }

    public function testTheWholeFlowIsAuditedAndInTheHistory(): void
    {
        $report = $this->inReview();
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/observe", ['comment' => 'Ajustar el resumen.']));
        self::assertStatus(200, $this->fill($report, 'Resumen ajustado de las actividades ejecutadas en el período.'));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));

        $actions = $this->auditActions();
        foreach (['report.created', 'report.submitted', 'report.review_started', 'report.observed', 'report.approved'] as $expected) {
            self::assertContains($expected, $actions);
        }
        $events = array_column(self::json($this->contractor->get("/reports/{$report}/history"))['data'], 'event');
        self::assertSame(['approved', 'review_started', 'submitted', 'observed', 'review_started', 'submitted', 'created'], $events);
    }

    public function testPendingReviewFilterListsWhatAwaitsTheSupervisor(): void
    {
        $inReview = $this->inReview();
        $approved = $this->createReport('2026-01-15', '2026-02-14');
        $approvedUuid = self::json($approved)['data']['uuid'];
        self::assertStatus(200, $this->fill($approvedUuid));
        self::assertStatus(200, $this->contractor->post("/reports/{$approvedUuid}/submit"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$approvedUuid}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$approvedUuid}/approve"));
        $this->createReport('2026-04-01', '2026-04-30'); // borrador: invisible para el supervisor

        $pending = self::json($this->supervisor->get('/reports?status=pending_review'))['data'];
        self::assertSame([$inReview], array_column($pending, 'uuid'));
        self::assertCount(2, self::json($this->supervisor->get('/reports'))['data']);
        self::assertCount(1, self::json($this->supervisor->get('/reports?status=approved'))['data']);
    }

    public function testInvalidInputIsRejected(): void
    {
        $report = $this->draft();
        self::assertErrorCode('validation_error', $this->contractor->put("/reports/{$report}", ['items' => 'texto']));
        self::assertErrorCode('validation_error', $this->contractor->put("/reports/{$report}", ['items' => [['obligation' => 'x', 'description' => 'y']]]));
        // Una obligación de otro contrato (uuid válido pero ajeno).
        self::assertErrorCode('validation_error', $this->contractor->put("/reports/{$report}", ['items' => [['obligation' => '11111111-1111-4111-8111-111111111111', 'description' => 'y']]]));
        self::assertStatus(404, $this->contractor->get('/reports/no-es-uuid'));
        self::assertErrorCode('validation_error', $this->contractor->get("/reports/{$report}/versions/0"));
        self::assertStatus(404, $this->contractor->get("/reports/{$report}/versions/1"));
    }
}
