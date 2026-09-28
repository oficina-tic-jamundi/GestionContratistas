<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use Dompdf\Dompdf;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\IntegrationTestCase;

/**
 * Obligaciones propuestas desde el contrato firmado y su registro en bloque (ADR-022).
 * Los contratos se generan aquí con texto ficticio; no son documentos reales.
 */
final class ObligationImportTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $supervisor;
    private string $contract;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '33333333', 'name' => 'Contratista de Prueba',
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-077-2026', 'object' => 'Prestación de servicios de prueba de lectura del contrato.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-02-01', 'end_date' => '2026-11-30', 'total_value' => '24000000',
        ]))['data']['uuid'];
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
    }

    /** PDF real con capa de texto, generado con la misma librería que usa el sistema. */
    private static function contractPdf(string $body): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('<html><body style="font-family: DejaVu Sans; font-size: 11px">' . $body . '</body></html>');
        $dompdf->render();

        return (string) $dompdf->output();
    }

    private const CPS = '<p>CONTRATO DE PRESTACIÓN DE SERVICIOS No. CPS-077-2026</p>'
        . '<p>CLÁUSULA PRIMERA. OBJETO: Prestación de servicios de apoyo a la gestión de la Secretaría de Planeación del municipio.</p>'
        . '<p>CLÁUSULA SEGUNDA. OBLIGACIONES ESPECÍFICAS DEL CONTRATISTA:</p>'
        . '<p>1. Apoyar la formulación de los proyectos de inversión de la dependencia.</p>'
        . '<p>2. Realizar el seguimiento mensual a los indicadores del plan de desarrollo.</p>'
        . '<p>3. Elaborar las actas de las reuniones en las que participe.</p>'
        . '<p>CLÁUSULA TERCERA. VALOR: veinticuatro millones de pesos pagaderos en mensualidades vencidas.</p>';

    private function uploadContract(string $pdf, string $type = 'signed_contract'): void
    {
        self::assertStatus(201, $this->admin->upload("/contracts/{$this->contract}/documents", $pdf, 'contrato.pdf', ['type' => $type]));
    }

    public function testProposesTheObligationsWrittenInTheSignedContract(): void
    {
        $this->uploadContract(self::contractPdf(self::CPS));

        $response = $this->admin->get("/contracts/{$this->contract}/obligations/suggestions");

        self::assertStatus(200, $response);
        $data = self::json($response)['data'];
        self::assertSame('contrato.pdf', $data['document']['name']);
        self::assertSame([], $data['warnings']);
        self::assertSame(0, $data['existing_obligations']);
        self::assertSame([
            'Apoyar la formulación de los proyectos de inversión de la dependencia',
            'Realizar el seguimiento mensual a los indicadores del plan de desarrollo',
            'Elaborar las actas de las reuniones en las que participe',
        ], array_column($data['items'], 'title'));
    }

    public function testSuggestingDoesNotRegisterAnything(): void
    {
        $this->uploadContract(self::contractPdf(self::CPS));
        $this->admin->get("/contracts/{$this->contract}/obligations/suggestions");

        $tree = self::json($this->admin->get("/contracts/{$this->contract}/activities"))['data'];
        self::assertSame([], $tree['items']);
    }

    public function testExplainsWhenThereIsNoSignedContract(): void
    {
        $this->uploadContract(self::contractPdf(self::CPS), 'start_minute');

        $response = $this->admin->get("/contracts/{$this->contract}/obligations/suggestions");

        self::assertStatus(409, $response);
        self::assertStringContainsString('Cargue primero el contrato firmado', self::json($response)['message']);
    }

    public function testWarnsWhenTheDocumentHasNoObligationsSection(): void
    {
        $this->uploadContract(self::contractPdf('<p>' . str_repeat('Texto del documento sin la sección esperada. ', 12) . '</p>'));

        $data = self::json($this->admin->get("/contracts/{$this->contract}/obligations/suggestions"))['data'];

        self::assertSame([], $data['items']);
        self::assertSame('section_not_found', $data['warnings'][0]['code']);
        self::assertStringContainsString('Regístrelas a mano', $data['warnings'][0]['message']);
    }

    public function testExplainsWhenThePdfHasNoText(): void
    {
        // Un PDF sin capa de texto equivale a un contrato escaneado.
        $this->uploadContract(self::contractPdf('<div style="height: 20px"></div>'));

        $response = $this->admin->get("/contracts/{$this->contract}/obligations/suggestions");

        self::assertStatus(409, $response);
        self::assertStringContainsString('escaneado', self::json($response)['message']);
    }

    public function testRegistersTheConfirmedObligationsTogether(): void
    {
        $response = $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => [
            ['title' => 'Apoyar la formulación de proyectos', 'description' => 'Texto completo de la obligación.'],
            ['title' => 'Hacer seguimiento a indicadores', 'weight' => '2'],
        ]]);

        self::assertStatus(201, $response);
        self::assertSame('2 obligaciones registradas.', self::json($response)['message']);
        $tree = self::json($this->admin->get("/contracts/{$this->contract}/activities"))['data'];
        self::assertSame(['Apoyar la formulación de proyectos', 'Hacer seguimiento a indicadores'], array_column($tree['items'], 'title'));
        self::assertSame('2.00', $tree['items'][1]['weight']);
        self::assertContains('activity.created', $this->auditActions());
    }

    public function testBulkIsAllOrNothing(): void
    {
        $response = $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => [
            ['title' => 'Obligación válida'],
            ['title' => 'x'],
        ]]);

        self::assertStatus(422, $response);
        self::assertSame('items.1.title', self::json($response)['errors'][0]['field']);
        self::assertStringStartsWith('Obligación 2:', self::json($response)['errors'][0]['message']);
        self::assertSame([], self::json($this->admin->get("/contracts/{$this->contract}/activities"))['data']['items']);
    }

    public function testRejectsEmptyOrOversizedLists(): void
    {
        self::assertStatus(422, $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => []]));
        $many = array_fill(0, 61, ['title' => 'Obligación repetida']);
        self::assertStatus(422, $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => $many]));
    }

    public function testOnlyWhileTheContractIsADraft(): void
    {
        $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => [['title' => 'Obligación inicial']]]);
        $this->uploadContract(self::contractPdf(self::CPS));
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));

        self::assertStatus(409, $this->admin->get("/contracts/{$this->contract}/obligations/suggestions"));
        self::assertStatus(409, $this->admin->post("/contracts/{$this->contract}/obligations/bulk", ['items' => [['title' => 'Otra obligación']]]));
    }

    public function testTheSupervisorCannotImportObligations(): void
    {
        $this->uploadContract(self::contractPdf(self::CPS));

        self::assertStatus(403, $this->supervisor->get("/contracts/{$this->contract}/obligations/suggestions"));
        self::assertStatus(403, $this->supervisor->post("/contracts/{$this->contract}/obligations/bulk", ['items' => [['title' => 'Obligación']]]));
    }
}
