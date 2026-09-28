<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\FrozenClock;
use Sigcon\Tests\Support\IntegrationTestCase;

final class PaymentsTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $approver;
    private Browser $treasurer;
    private Browser $contractor;
    private Browser $supervisor;
    private string $contract;
    private string $obligation;

    protected function setUp(): void
    {
        parent::setUp();
        // 10 de abril de 2026, 10:00 en Bogotá.
        $this->clock = new FrozenClock(new DateTimeImmutable('2026-04-10 15:00:00', new DateTimeZone('UTC')));
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');

        // Roles que la Alcaldía definiría: quien aprueba pagos y quien registra el pago.
        self::assertStatus(201, $this->admin->post('/roles', ['code' => 'ordenador', 'name' => 'Ordenador del gasto', 'permissions' => ['payments.approve', 'contracts.view_all']]));
        self::assertStatus(201, $this->admin->post('/roles', ['code' => 'tesoreria', 'name' => 'Tesorería', 'permissions' => ['payments.register', 'contracts.view_all']]));
        $this->createUser('ordenador@example.test', ['ordenador']);
        $this->createUser('tesoreria@example.test', ['tesoreria']);

        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '66666666', 'name' => 'Carla Contratista', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-050-2026', 'object' => 'Prestación de servicios de prueba de pagos.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'total_value' => '3000000',
        ]))['data']['uuid'];
        $this->obligation = self::json($this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Apoyar la gestión', 'weight' => '1']))['data']['uuid'];
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));
        // La Alcaldía exige 100 % de avance para pagar (ADR-021). Estas pruebas verifican otras
        // reglas, así que esa se desactiva aquí; tiene su propia prueba en ContractorPanelTest.
        self::assertStatus(200, $this->admin->put('/payment-rules/min_progress', ['enabled' => false]));

        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
        $this->approver = $this->browser();
        $this->approver->login('ordenador@example.test');
        $this->treasurer = $this->browser();
        $this->treasurer->login('tesoreria@example.test');
    }

    /** Informe del período enviado y, si se pide, aprobado por el supervisor. */
    private function report(string $start, string $end, bool $approve = true): string
    {
        $report = self::json($this->contractor->post("/contracts/{$this->contract}/reports", ['period_start' => $start, 'period_end' => $end]))['data']['uuid'];
        self::assertStatus(200, $this->contractor->put("/reports/{$report}", [
            'summary' => 'Resumen de las actividades del período para el pago.',
            'items' => [['obligation' => $this->obligation, 'description' => 'Actividades ejecutadas.']],
        ]));
        self::assertStatus(200, $this->contractor->post("/reports/{$report}/submit"));
        if ($approve) {
            self::assertStatus(200, $this->supervisor->post("/reports/{$report}/start-review"));
            self::assertStatus(200, $this->supervisor->post("/reports/{$report}/approve"));
        }

        return $report;
    }

    private function createPayment(string $report, string $amount = '250000'): ResponseInterface
    {
        return $this->admin->post('/payments', ['report' => $report, 'amount' => $amount, 'notes' => 'Cuenta de cobro de prueba']);
    }

    private function payment(string $report, string $amount = '250000'): string
    {
        $r = $this->createPayment($report, $amount);
        self::assertStatus(201, $r);

        return self::json($r)['data']['uuid'];
    }

    /** @return array<string, mixed> */
    private function detail(Browser $as, string $payment): array
    {
        $r = $as->get("/payments/{$payment}");
        self::assertStatus(200, $r);

        return self::json($r)['data'];
    }

    public function testPaymentsRequireAnApprovedReportAndRespectTheContractValue(): void
    {
        $pending = $this->report('2026-01-01', '2026-01-31', approve: false);
        $invalid = $this->createPayment($pending);
        self::assertErrorCode('validation_error', $invalid);
        self::assertSame('not_approved', self::json($invalid)['errors'][0]['code']);

        $report = $this->report('2026-02-01', '2026-02-28');
        self::assertSame('exceeds_contract', self::json($this->createPayment($report, '3000000.01'))['errors'][0]['code']);
        self::assertStatus(403, $this->contractor->post('/payments', ['report' => $report, 'amount' => '1000']));

        $created = $this->createPayment($report, '2500000');
        self::assertStatus(201, $created);
        self::assertSame(1, self::json($created)['data']['number']);
        self::assertSame('draft', self::json($created)['data']['status']);
        self::assertSame('duplicate', self::json($this->createPayment($report, '1000'))['errors'][0]['code']);

        $budget = self::json($this->admin->get("/contracts/{$this->contract}/budget"))['data'];
        self::assertSame(['total' => '3000000.00', 'committed' => '2500000.00', 'paid' => '0.00', 'available' => '500000.00'], $budget);

        $other = $this->report('2026-03-01', '2026-03-31');
        self::assertSame('exceeds_contract', self::json($this->createPayment($other, '500000.01'))['errors'][0]['code'], 'El saldo considera los pagos anteriores');
        self::assertStatus(201, $this->createPayment($other, '500000'));
        self::assertContains('payment.created', $this->auditActions());
    }

    public function testFullFlowWithSegregationOfDuties(): void
    {
        $payment = $this->payment($this->report('2026-01-01', '2026-01-31'));

        $submitted = $this->admin->post("/payments/{$payment}/submit");
        self::assertStatus(200, $submitted);
        self::assertSame('ready_for_approval', self::json($submitted)['data']['status']);

        $detail = $this->detail($this->admin, $payment);
        self::assertTrue($detail['evaluation']['eligible']);
        self::assertSame('submit', $detail['evaluation']['trigger']);
        $byCode = array_column($detail['evaluation']['results'], null, 'code');
        self::assertTrue($byCode['report_approved']['passed']);
        self::assertFalse($byCode['min_evidences']['enabled'], 'Reglas desactivadas quedan registradas como tales');
        self::assertFalse($detail['can']['approve'], 'Quien registró el pago no lo aprueba');

        self::assertStatus(403, $this->admin->post("/payments/{$payment}/approve"));
        self::assertStatus(403, $this->treasurer->post("/payments/{$payment}/approve"));
        self::assertTrue($this->detail($this->approver, $payment)['can']['approve']);
        self::assertStatus(200, $this->approver->post("/payments/{$payment}/approve"));

        self::assertErrorCode('validation_error', $this->treasurer->post("/payments/{$payment}/paid", ['payment_date' => '2026-04-11', 'payment_reference' => 'CE-2026-0145']));
        self::assertStatus(403, $this->approver->post("/payments/{$payment}/paid", ['payment_date' => '2026-04-10', 'payment_reference' => 'CE-2026-0145']));
        $paid = $this->treasurer->post("/payments/{$payment}/paid", ['payment_date' => '2026-04-10', 'payment_reference' => 'CE-2026-0145']);
        self::assertStatus(200, $paid);
        self::assertSame('paid', self::json($paid)['data']['status']);

        $final = $this->detail($this->admin, $payment);
        self::assertSame('CE-2026-0145', $final['payment_reference']);
        self::assertSame('2026-04-10', $final['payment_date']);
        self::assertSame('250000.00', $final['budget']['paid']);
        self::assertErrorCode('business_rule_violation', $this->admin->post("/payments/{$payment}/cancel", ['reason' => 'Intento de anular un pago pagado.']));

        $events = array_column(self::json($this->admin->get("/payments/{$payment}/history"))['data'], 'event');
        self::assertSame(['paid', 'approved', 'submitted', 'created'], $events);
        foreach (['payment.submitted', 'payment.approved', 'payment.paid', 'payment.evaluated'] as $action) {
            self::assertContains($action, $this->auditActions());
        }
    }

    public function testIneligiblePaymentsAreNotSubmittedButTheEvaluationIsKept(): void
    {
        $payment = $this->payment($this->report('2026-01-01', '2026-01-31'));
        self::assertStatus(200, $this->admin->put('/payment-rules/min_evidences', ['enabled' => true, 'params' => ['min' => 1]]));

        $response = $this->admin->post("/payments/{$payment}/submit");
        self::assertErrorCode('business_rule_violation', $response);
        self::assertStringContainsString('Solo hay 0 evidencia(s) en el período; se requieren 1.', self::json($response)['message']);

        $detail = $this->detail($this->admin, $payment);
        self::assertSame('draft', $detail['status']);
        self::assertFalse($detail['last_eligible']);
        self::assertFalse($detail['evaluation']['eligible'], 'La evaluación fallida queda registrada');
        self::assertSame(1, (int) $this->pdo()->query('SELECT COUNT(*) FROM payment_evaluations')->fetchColumn());
    }

    public function testApprovalReEvaluatesEligibility(): void
    {
        $payment = $this->payment($this->report('2026-01-01', '2026-01-31'));
        self::assertStatus(200, $this->admin->post("/payments/{$payment}/submit"));
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'suspended', 'comment' => 'Suspensión por fuerza mayor.']));

        $response = $this->approver->post("/payments/{$payment}/approve");
        self::assertErrorCode('business_rule_violation', $response);
        self::assertStringContainsString('El contrato está suspendido.', self::json($response)['message']);
        self::assertSame('ready_for_approval', $this->detail($this->admin, $payment)['status']);
    }

    public function testReturnCancelAndEdit(): void
    {
        $report = $this->report('2026-01-01', '2026-01-31');
        $payment = $this->payment($report);
        self::assertStatus(200, $this->admin->put("/payments/{$payment}", ['amount' => '300000', 'notes' => null]));
        self::assertSame('300000.00', $this->detail($this->admin, $payment)['amount']);

        self::assertStatus(200, $this->admin->post("/payments/{$payment}/submit"));
        self::assertErrorCode('business_rule_violation', $this->admin->put("/payments/{$payment}", ['amount' => '1000']));
        self::assertErrorCode('validation_error', $this->approver->post("/payments/{$payment}/return", ['reason' => 'corto']));
        self::assertStatus(200, $this->approver->post("/payments/{$payment}/return", ['reason' => 'Ajustar el valor según el acta de supervisión.']));
        self::assertSame('draft', $this->detail($this->admin, $payment)['status']);

        self::assertStatus(200, $this->admin->post("/payments/{$payment}/cancel", ['reason' => 'Se registró con el informe equivocado.']));
        self::assertSame('0.00', self::json($this->admin->get("/contracts/{$this->contract}/budget"))['data']['committed']);
        self::assertStatus(201, $this->createPayment($report), 'Anulado, el informe admite un pago nuevo');
    }

    public function testAReportBackingAnApprovedPaymentCannotBeReopened(): void
    {
        $report = $this->report('2026-01-01', '2026-01-31');
        $payment = $this->payment($report);
        self::assertStatus(200, $this->admin->post("/payments/{$payment}/submit"));
        self::assertStatus(200, $this->approver->post("/payments/{$payment}/approve"));

        $reopen = $this->admin->post("/reports/{$report}/reopen", ['reason' => 'Se detectó un error en el período.']);
        self::assertErrorCode('business_rule_violation', $reopen);
        self::assertStringContainsString('sustenta el pago N.° 1', self::json($reopen)['message']);
    }

    public function testVisibilityFollowsTheContractScope(): void
    {
        $payment = $this->payment($this->report('2026-01-01', '2026-01-31'));

        $forContractor = $this->detail($this->contractor, $payment);
        self::assertSame(['edit' => false, 'evaluate' => false, 'submit' => false, 'approve' => false, 'return' => false, 'register_paid' => false, 'cancel' => false], $forContractor['can']);
        self::assertSame(1, self::json($this->contractor->get('/payments'))['meta']['pagination']['total']);
        self::assertStatus(403, $this->contractor->post("/payments/{$payment}/submit"));

        $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $other = $this->browser();
        $other->login('otro.supervisor@example.test');
        self::assertStatus(404, $other->get("/payments/{$payment}"));
        self::assertSame(0, self::json($other->get('/payments'))['meta']['pagination']['total']);
    }

    public function testRuleConfigurationIsValidatedAndAudited(): void
    {
        self::assertStatus(403, $this->approver->put('/payment-rules/min_evidences', ['enabled' => true, 'params' => ['min' => 2]]));
        self::assertStatus(404, $this->admin->put('/payment-rules/inventada', ['enabled' => true]));
        self::assertErrorCode('validation_error', $this->admin->put('/payment-rules/min_evidences', ['enabled' => true, 'params' => ['min' => 0]]));
        self::assertErrorCode('validation_error', $this->admin->put('/payment-rules/contract_active', ['enabled' => true, 'params' => ['statuses' => ['draft']]]));
        self::assertErrorCode('validation_error', $this->admin->put('/payment-rules/required_documents', ['enabled' => true, 'params' => ['types' => ['no_existe']]]));
        self::assertErrorCode('validation_error', $this->admin->put('/payment-rules/min_progress', ['params' => []]));

        self::assertStatus(200, $this->admin->put('/payment-rules/contract_active', ['enabled' => true, 'params' => ['statuses' => ['active', 'terminated']]]));
        $rules = array_column(self::json($this->approver->get('/payment-rules'))['data'], null, 'code');
        self::assertSame(['statuses' => ['active', 'terminated']], $rules['contract_active']['params']);
        self::assertSame('choices', $rules['contract_active']['parameters'][0]['type']);
        self::assertFalse($rules['required_documents']['enabled']);
        self::assertContains('payment.rule_configured', $this->auditActions());
    }

    public function testRequiredDocumentsAndProgressRulesWhenEnabled(): void
    {
        $payment = $this->payment($this->report('2026-01-01', '2026-01-31'));
        self::assertStatus(200, $this->admin->put('/payment-rules/required_documents', ['enabled' => true, 'params' => ['types' => ['signed_contract']]]));
        self::assertStatus(200, $this->admin->put('/payment-rules/min_progress', ['enabled' => true, 'params' => ['min_percent' => 50]]));

        $result = self::json($this->admin->post("/payments/{$payment}/evaluate"))['data'];
        self::assertFalse($result['eligible']);
        $byCode = array_column($result['results'], null, 'code');
        self::assertSame('Faltan documentos: Contrato firmado.', $byCode['required_documents']['reason']);
        self::assertStringContainsString('se requiere al menos 50 %', $byCode['min_progress']['reason']);

        $pdf = "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\ntrailer << /Root 1 0 R >>\n%%EOF\n";
        self::assertStatus(201, $this->admin->upload("/contracts/{$this->contract}/documents", $pdf, 'contrato.pdf', ['type' => 'signed_contract']));
        self::assertStatus(200, $this->supervisor->post("/activities/{$this->obligation}/progress", ['progress' => 60, 'note' => 'Avance verificado del primer trimestre.']));

        self::assertTrue(self::json($this->admin->post("/payments/{$payment}/evaluate"))['data']['eligible']);
    }
}
