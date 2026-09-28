<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Sigcon\Repositories\NotificationRepository;
use Sigcon\Services\Jobs\JobQueue;
use Sigcon\Services\Jobs\JobRunner;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\FrozenClock;
use Sigcon\Tests\Support\IntegrationTestCase;

final class NotificationsDashboardTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $approver;
    private Browser $treasurer;
    private Browser $contractor;
    private Browser $supervisor;
    private Browser $otherSupervisor;
    private string $contract;
    private string $obligation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clock = new FrozenClock(new DateTimeImmutable('2026-04-10 15:00:00', new DateTimeZone('UTC')));
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        self::assertStatus(201, $this->admin->post('/roles', ['code' => 'ordenador', 'name' => 'Ordenador', 'permissions' => ['payments.approve', 'contracts.view_all']]));
        self::assertStatus(201, $this->admin->post('/roles', ['code' => 'tesoreria', 'name' => 'Tesorería', 'permissions' => ['payments.register', 'contracts.view_all']]));
        $this->createUser('ordenador@example.test', ['ordenador']);
        $this->createUser('tesoreria@example.test', ['tesoreria']);

        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '77777777', 'name' => '=HYPERLINK("http://malo")', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-060-2026', 'object' => 'Servicios; con "comillas" y punto y coma.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-01', 'end_date' => '2026-05-01', 'total_value' => '3000000.50',
        ]))['data']['uuid'];
        $this->obligation = self::json($this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Apoyar la gestión', 'weight' => '1']))['data']['uuid'];
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));
        // El pago exige 100 % de avance (ADR-021); aquí se prueban notificaciones y tablero.
        self::assertStatus(200, $this->admin->put('/payment-rules/min_progress', ['enabled' => false]));

        foreach (['contractor' => 'contratista', 'supervisor' => 'supervisor', 'otherSupervisor' => 'otro.supervisor', 'approver' => 'ordenador', 'treasurer' => 'tesoreria'] as $property => $user) {
            $this->{$property} = $this->browser();
            $this->{$property}->login("{$user}@example.test");
        }
    }

    private function submittedReport(): string
    {
        $report = self::json($this->contractor->post("/contracts/{$this->contract}/reports", ['period_start' => '2026-03-01', 'period_end' => '2026-03-31']))['data']['uuid'];
        self::assertStatus(200, $this->contractor->put("/reports/{$report}", [
            'summary' => 'Resumen de las actividades del período reportado.',
            'items' => [['obligation' => $this->obligation, 'description' => 'Actividades ejecutadas.']],
        ]));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));

        return $report;
    }

    /** @return list<array<string, mixed>> */
    private function notifications(Browser $as): array
    {
        return self::json($as->get('/notifications'))['data'];
    }

    private function unread(Browser $as): int
    {
        return self::json($as->get('/notifications/unread-count'))['data']['unread'];
    }

    public function testReportEventsNotifyTheOtherParty(): void
    {
        $report = $this->submittedReport();

        self::assertSame(1, $this->unread($this->supervisor));
        self::assertSame(0, $this->unread($this->contractor), 'Quien actúa no se notifica a sí mismo');
        self::assertSame(0, $this->unread($this->otherSupervisor));
        $note = $this->notifications($this->supervisor)[0];
        self::assertSame('report.submitted', $note['type']);
        self::assertSame("/reports/{$report}", $note['link']);
        self::assertStringContainsString('CPS-060-2026: informe N.° 1 enviado para revisión', $note['title']);

        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/observe", ['comment' => 'Ajustar el resumen.']));
        self::assertSame('report.observed', $this->notifications($this->contractor)[0]['type']);

        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        self::assertSame('report.resubmitted', $this->notifications($this->supervisor)[0]['type']);
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));
        self::assertSame('report.approved', $this->notifications($this->contractor)[0]['type']);
        self::assertSame(2, $this->unread($this->contractor));
    }

    public function testNotificationsArePrivateToTheirRecipient(): void
    {
        $this->submittedReport();
        $uuid = $this->notifications($this->supervisor)[0]['uuid'];

        self::assertStatus(404, $this->contractor->post("/notifications/{$uuid}/read"));
        self::assertStatus(404, $this->contractor->post('/notifications/no-es-uuid/read'));
        self::assertStatus(200, $this->supervisor->post("/notifications/{$uuid}/read"));
        self::assertSame(0, $this->unread($this->supervisor));
        self::assertTrue($this->notifications($this->supervisor)[0]['read']);
        self::assertStatus(200, $this->supervisor->post("/notifications/{$uuid}/read"), 'Idempotente');
        self::assertCount(0, self::json($this->supervisor->get('/notifications?unread=1'))['data']);
    }

    public function testPaymentEventsNotifyApproversTreasuryAndContractor(): void
    {
        $report = $this->submittedReport();
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));
        $payment = self::json($this->admin->post('/payments', ['report' => $report, 'amount' => '1000000']))['data']['uuid'];
        self::assertStatus(200, $this->admin->post("/payments/{$payment}/submit"));

        self::assertSame('payment.ready', $this->notifications($this->approver)[0]['type']);
        self::assertSame(0, $this->unread($this->admin), 'El administrador envió el pago: no se notifica a sí mismo');
        self::assertSame(0, $this->unread($this->treasurer));

        self::assertStatus(200, $this->approver->post("/payments/{$payment}/approve"));
        self::assertSame('payment.approved', $this->notifications($this->treasurer)[0]['type']);
        self::assertSame('payment.approved', $this->notifications($this->contractor)[0]['type']);
        self::assertSame("/payments/{$payment}", $this->notifications($this->contractor)[0]['link']);

        self::assertStatus(200, $this->treasurer->post("/payments/{$payment}/paid", ['payment_date' => '2026-04-10', 'payment_reference' => 'CE-001']));
        $paid = $this->notifications($this->contractor)[0];
        self::assertSame('payment.paid', $paid['type']);
        self::assertStringContainsString('$1.000.000 el 10/04/2026 (comprobante CE-001)', (string) $paid['body']);
    }

    public function testPermanentJobFailuresNotifyAdministration(): void
    {
        $queue = $this->container()->get(JobQueue::class);
        self::assertInstanceOf(JobQueue::class, $queue);
        $queue->push('desconocido', []);
        $runner = $this->container()->get(JobRunner::class);
        self::assertInstanceOf(JobRunner::class, $runner);
        $runner->run();

        $note = $this->notifications($this->admin)[0];
        self::assertSame('job.failed', $note['type']);
        self::assertSame('/jobs?status=failed', $note['link']);
        self::assertSame(0, $this->unread($this->supervisor));
    }

    public function testDashboardSectionsFollowPermissionsAndScope(): void
    {
        $report = $this->submittedReport();

        $supervisor = self::json($this->supervisor->get('/dashboard'))['data'];
        self::assertSame([$report], array_column($supervisor['reports']['awaiting_my_review'], 'uuid'));
        self::assertArrayNotHasKey('system', $supervisor);
        self::assertArrayNotHasKey('awaiting_my_action', $supervisor['reports']);
        $byStatus = array_column($supervisor['contracts']['by_status'], 'count', 'status');
        self::assertSame(1, $byStatus['active']);
        self::assertSame('CPS-060-2026', $supervisor['contracts']['ending_soon'][0]['contract_number'], 'Termina el 1 de mayo: dentro de 30 días');

        $other = self::json($this->otherSupervisor->get('/dashboard'))['data'];
        self::assertSame(0, array_sum(array_column($other['contracts']['by_status'], 'count')), 'Otro supervisor no cuenta contratos ajenos');
        self::assertSame([], $other['reports']['awaiting_my_review']);

        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/observe", ['comment' => 'Ajustar.']));
        $contractor = self::json($this->contractor->get('/dashboard'))['data'];
        self::assertSame('observed', $contractor['reports']['awaiting_my_action'][0]['status']);
        self::assertNull($contractor['payments']['to_approve']);

        $admin = self::json($this->admin->get('/dashboard'))['data'];
        self::assertArrayHasKey('system', $admin);
        self::assertTrue($admin['system']['jobs_stale']);
        self::assertSame(0, $admin['payments']['to_approve']);
    }

    public function testCsvExportsAreScopedSafeAndAudited(): void
    {
        self::assertStatus(403, $this->contractor->get('/exports/contracts'));
        self::assertStatus(403, $this->supervisor->get('/exports/payments'));

        $response = $this->admin->get('/exports/contracts');
        self::assertStatus(200, $response);
        self::assertSame('text/csv; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertMatchesRegularExpression('/filename="sigcon-contratos-20260410-100000\.csv"/', $response->getHeaderLine('Content-Disposition'));
        $csv = (string) $response->getBody();
        self::assertStringStartsWith("\xEF\xBB\xBFNúmero;Objeto;Contratista;", $csv, 'BOM + separador ";" para Excel');
        self::assertStringContainsString('"Servicios; con ""comillas"" y punto y coma."', $csv);
        self::assertStringContainsString(';"\'=HYPERLINK(""http://malo"")";', $csv, 'Fórmula neutralizada con apóstrofo');
        self::assertStringContainsString(';3000000,50;0,00;0,00;3000000,50;0,00', $csv, 'Coma decimal');
        self::assertStringContainsString("\r\n", $csv);
        self::assertContains('export.generated', $this->auditActions());

        $payments = (string) $this->admin->get('/exports/payments?status=paid')->getBody();
        self::assertSame(1, substr_count($payments, "\r\n"), 'Solo el encabezado: no hay pagos pagados');
    }

    public function testCleanupRemovesOldReadNotifications(): void
    {
        $this->submittedReport();
        self::assertStatus(200, $this->supervisor->post('/notifications/read-all'));
        $this->clock->advance('+181 days');

        $repository = $this->container()->get(NotificationRepository::class);
        self::assertInstanceOf(NotificationRepository::class, $repository);
        self::assertSame(1, $repository->deleteReadBefore($this->clock->now()->modify('-180 days')));
    }
}
