<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\FrozenClock;
use Sigcon\Tests\Support\IntegrationTestCase;

/** Prioridad, panel del contratista e historial (ADR-021). */
final class ContractorPanelTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $contractor;
    private Browser $supervisor;
    private string $contract;
    private string $o1;
    private string $o2;
    private string $t1;
    private string $t2;

    protected function setUp(): void
    {
        parent::setUp();
        // 10 de marzo de 2026, 10:00 en Bogotá.
        $this->clock = new FrozenClock(new DateTimeImmutable('2026-03-10 15:00:00', new DateTimeZone('UTC')));
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->createUser('otro.contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '44444444', 'name' => 'Carla Contratista', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-030-2026', 'object' => 'Prestación de servicios de prueba del panel.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-15', 'end_date' => '2026-12-15', 'total_value' => '36000000',
        ]))['data']['uuid'];
        $this->o1 = self::json($this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Apoyar la planeación institucional', 'weight' => '1']))['data']['uuid'];
        $this->o2 = self::json($this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Elaborar informes técnicos', 'weight' => '1']))['data']['uuid'];
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));

        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
        // Las tareas las planea el supervisor; el contratista solo las consulta (ADR-021).
        $this->t1 = self::json($this->supervisor->post("/activities/{$this->o1}/children", ['title' => 'Revisar fichas MGA']))['data']['uuid'];
        $this->t2 = self::json($this->supervisor->post("/activities/{$this->o1}/children", ['title' => 'Consolidar indicadores']))['data']['uuid'];
    }

    /** El avance lo verifica y registra el supervisor (ADR-021). */
    private function progress(string $activity, int $value): void
    {
        self::assertStatus(200, $this->supervisor->post("/activities/{$activity}/progress", ['progress' => $value, 'note' => 'Avance verificado en la prueba del panel.']));
    }

    /** @return array<string, mixed> el contrato de la prueba en el panel del contratista */
    private function panel(): array
    {
        $contracts = self::json($this->contractor->get('/dashboard'))['data']['my_contracts'];
        self::assertCount(1, $contracts);

        return $contracts[0];
    }

    /**
     * @param array<string, mixed> $panel
     * @return array<string, string> título => estado, de obligaciones y sus actividades
     */
    private static function statuses(array $panel): array
    {
        $result = [];
        foreach ($panel['obligations'] as $obligation) {
            $result[$obligation['title']] = $obligation['status'];
            foreach ($obligation['items'] as $item) {
                $result[$item['title']] = $item['status'];
            }
        }

        return $result;
    }

    private function submitMarchReport(): string
    {
        $report = self::json($this->contractor->post("/contracts/{$this->contract}/reports", ['period_start' => '2026-03-01', 'period_end' => '2026-03-31']))['data']['uuid'];
        self::assertStatus(200, $this->contractor->put("/reports/{$report}", [
            'summary' => 'Resumen de las actividades ejecutadas durante marzo.',
            'contractor_notes' => null,
            'items' => [['obligation' => $this->o1, 'description' => 'Revisé las fichas y consolidé indicadores.']],
        ]));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));

        return $report;
    }

    public function testOnlyTheMunicipalityAssignsPriority(): void
    {
        $set = $this->admin->put("/activities/{$this->o1}/priority", ['priority' => 'high']);
        self::assertStatus(200, $set);
        self::assertSame('high', self::json($set)['data']['priority']);
        self::assertSame('Alta', self::json($set)['data']['priority_label']);
        self::assertContains('activity.priority_changed', $this->auditActions());

        self::assertStatus(403, $this->contractor->put("/activities/{$this->t1}/priority", ['priority' => 'low']));
        self::assertStatus(403, $this->contractor->post("/activities/{$this->o1}/children", ['title' => 'Tarea del contratista']), 'El contratista no crea tareas');
        self::assertStatus(403, $this->contractor->post("/activities/{$this->t1}/progress", ['progress' => 10, 'note' => 'El contratista no registra avance.']));
        self::assertErrorCode('validation_error', $this->admin->put("/activities/{$this->o1}/priority", ['priority' => 'urgente']));
        self::assertStatus(200, $this->admin->put("/activities/{$this->t1}/priority", ['priority' => 'medium']), 'También en tareas del contratista, con el contrato activo');

        $tree = self::json($this->contractor->get("/contracts/{$this->contract}/activities"))['data'];
        self::assertSame('high', $tree['items'][0]['priority'], 'El contratista la consulta');
        self::assertFalse($tree['can']['set_priority']);
        self::assertTrue(self::json($this->admin->get("/contracts/{$this->contract}/activities"))['data']['can']['set_priority']);
        self::assertSame('Alta', $this->panel()['obligations'][0]['priority_label']);

        self::assertNull(self::json($this->admin->put("/activities/{$this->o1}/priority", ['priority' => null]))['data']['priority'], 'Se puede quitar');
    }

    public function testStatusesAreDerivedFromReports(): void
    {
        $this->progress($this->t1, 100);
        $this->progress($this->t2, 50);
        $panel = $this->panel();
        self::assertSame('37.50', $panel['progress']);
        self::assertSame(['Apoyar la planeación institucional' => 'pending', 'Revisar fichas MGA' => 'pending', 'Consolidar indicadores' => 'pending', 'Elaborar informes técnicos' => 'pending'], self::statuses($panel));
        self::assertSame(['rule' => 'min_progress', 'required' => 100, 'met' => false], $panel['payment_notice'], 'La Alcaldía exige 100 % de avance para pagar');

        // Informe enviado: lo que avanzó en el período queda en revisión.
        $report = $this->submitMarchReport();
        $panel = $this->panel();
        self::assertSame('in_review', self::statuses($panel)['Apoyar la planeación institucional']);
        self::assertSame('in_review', self::statuses($panel)['Revisar fichas MGA']);
        self::assertSame('pending', self::statuses($panel)['Elaborar informes técnicos'], 'Sin avances en el período');

        // El supervisor observa la obligación 1.
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/observe", [
            'comment' => 'Faltan soportes.',
            'observations' => [
                ['obligation' => $this->o1, 'text' => 'Adjunte las fichas revisadas.'],
                ['obligation' => null, 'text' => 'Anexe las actas.'],
            ],
        ]));
        $panel = $this->panel();
        self::assertSame('observed', self::statuses($panel)['Apoyar la planeación institucional']);
        self::assertSame('observed', self::statuses($panel)['Consolidar indicadores']);
        self::assertSame(['Adjunte las fichas revisadas.'], $panel['obligations'][0]['observations']);
        self::assertSame(['Anexe las actas.'], $panel['general_observations']);
        self::assertSame(1, $panel['counts']['obligations']['observed']);

        // Corrige, reenvía y el supervisor aprueba: lo que estaba al 100 % queda aprobado.
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));
        $panel = $this->panel();
        self::assertSame('approved', self::statuses($panel)['Revisar fichas MGA']);
        self::assertSame('pending', self::statuses($panel)['Consolidar indicadores'], 'Al 50 %');
        self::assertSame('pending', self::statuses($panel)['Apoyar la planeación institucional']);
        self::assertSame(1, $panel['obligations'][0]['items_approved']);
        self::assertSame(['total' => 3, 'approved' => 1, 'in_review' => 0, 'observed' => 0, 'pending' => 2], $panel['counts']['items']);
    }

    public function testPaymentNoticeFollowsTheConfiguredRule(): void
    {
        // Por defecto la Alcaldía exige el 100 % del avance para pagar (ADR-021).
        $this->progress($this->t1, 100);
        self::assertSame(
            ['rule' => 'min_progress', 'required' => 100, 'met' => false],
            $this->panel()['payment_notice'],
            'Todavía falta avance',
        );

        $this->progress($this->t2, 100);
        $this->progress($this->o2, 100);
        $panel = $this->panel();
        self::assertSame('100.00', $panel['progress']);
        self::assertTrue($panel['payment_notice']['met']);

        self::assertStatus(200, $this->admin->put('/payment-rules/min_progress', ['enabled' => false]));
        self::assertNull($this->panel()['payment_notice'], 'Sin la regla activa no hay aviso');
    }

    public function testHistoryAppliesTheScopeOfEachRole(): void
    {
        $this->progress($this->t1, 40);
        $report = $this->submitMarchReport();
        self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));

        // El contratista ve lo suyo y lo que el supervisor decide sobre sus informes.
        $history = self::json($this->contractor->get('/history'));
        $kinds = array_column($history['data'], 'kind');
        self::assertContains('progress', $kinds);
        self::assertContains('report', $kinds);
        self::assertContains('contract', $kinds);
        $progress = $history['data'][array_search('progress', $kinds, true)];
        self::assertSame(['Revisar fichas MGA', '0.00', '40.00'], [$progress['title'], $progress['from_progress'], $progress['to_progress']]);
        self::assertSame('Carla Contratista', $progress['contract']['contractor']);
        $reviewStarted = array_values(array_filter(
            $history['data'],
            static fn (array $e) => $e['kind'] === 'report' && str_contains($e['summary'], 'revisión'),
        ));
        self::assertNotSame([], $reviewStarted, 'Ve que el supervisor inició la revisión de su informe');
        self::assertSame('Prueba Supervisor', $reviewStarted[0]['actor'], 'Queda registrado quién actuó');

        $onlyProgress = self::json($this->contractor->get('/history?type=progress'))['data'];
        self::assertSame(['progress'], array_values(array_unique(array_column($onlyProgress, 'kind'))));
        self::assertErrorCode('validation_error', $this->contractor->get('/history?type=inventado'));

        // El supervisor ve el contrato que supervisa, incluidos los avances del contratista.
        $supervisorHistory = self::json($this->supervisor->get('/history'))['data'];
        self::assertContains('progress', array_column($supervisorHistory, 'kind'));

        // La administración ve todo; un contratista sin contratos no ve nada.
        self::assertGreaterThan(0, self::json($this->admin->get('/history'))['meta']['pagination']['total']);
        $other = $this->browser();
        $other->login('otro.contratista@example.test');
        self::assertSame(0, self::json($other->get('/history'))['meta']['pagination']['total'], 'Nunca eventos de contratos ajenos');
        self::assertSame([], self::json($other->get('/dashboard'))['data']['my_contracts']);
    }

    public function testDeadlineAlertsUseTheTargetDateOfEachTask(): void
    {
        // Hoy es el 10 de marzo de 2026 (reloj fijo del setUp).
        self::assertStatus(200, $this->supervisor->put("/activities/{$this->t1}", [
            'title' => 'Revisar fichas MGA', 'weight' => '1', 'due_date' => '2026-03-18',
        ]));
        self::assertStatus(200, $this->supervisor->put("/activities/{$this->t2}", [
            'title' => 'Consolidar indicadores', 'weight' => '1', 'due_date' => '2026-06-30',
        ]));

        $deadlines = self::json($this->contractor->get('/dashboard'))['data']['deadlines'];
        self::assertSame(15, $deadlines['within_days']);
        self::assertCount(1, $deadlines['tasks'], 'Solo la que vence dentro de la ventana');
        $task = $deadlines['tasks'][0];
        self::assertSame(['Revisar fichas MGA', '2026-03-18', 8], [$task['title'], $task['due_date'], $task['days_left']]);
        self::assertSame('CPS-030-2026', $task['contract_number']);

        // El supervisor ve la misma alerta en los contratos que supervisa.
        self::assertCount(1, self::json($this->supervisor->get('/dashboard'))['data']['deadlines']['tasks']);

        // Al completarla deja de avisar.
        $this->progress($this->t1, 100);
        self::assertSame([], self::json($this->contractor->get('/dashboard'))['data']['deadlines']['tasks']);
    }

    public function testPaymentStatisticsCountAgreedAndPaidInstallments(): void
    {
        $data = self::json($this->admin->get("/contracts/{$this->contract}"))['data'];
        // Los pagos pactados se registran con el contrato en borrador.
        $second = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-031-2026', 'object' => 'Segundo contrato de prueba de estadísticas.',
            'contractor' => $data['contractor']['uuid'], 'department' => $data['department']['uuid'],
            'start_date' => '2026-02-01', 'end_date' => '2026-12-31', 'total_value' => '11000000', 'payment_count' => 11,
        ]))['data'];
        self::assertSame(11, $second['payment_count']);

        $rows = self::json($this->admin->get('/statistics/payments'))['data'];
        $byNumber = array_column($rows['contracts'], null, 'contract_number');
        self::assertSame(11, $byNumber['CPS-030-2026']['months'], 'Del 15 de enero al 15 de diciembre');
        self::assertSame(
            ['agreed' => null, 'paid' => 0, 'in_process' => 0, 'pending' => null, 'paid_amount' => '0.00', 'committed_amount' => '0.00'],
            $byNumber['CPS-030-2026']['payments'],
            'Sin pagos pactados no se estima cuántos faltan',
        );
        self::assertSame(11, $byNumber['CPS-031-2026']['payments']['agreed']);
        self::assertSame(11, $byNumber['CPS-031-2026']['payments']['pending']);
        self::assertSame(1, $rows['totals']['without_agreed']);
        self::assertSame(11, $rows['totals']['agreed']);

        self::assertStatus(403, $this->contractor->get('/statistics/payments'), 'El contratista no consulta las estadísticas de pagos');
    }
}
