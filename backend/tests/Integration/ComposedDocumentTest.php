<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use DateTimeImmutable;
use DateTimeZone;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\FrozenClock;
use Sigcon\Tests\Support\IntegrationTestCase;
use Smalot\PdfParser\Parser;

/** Acta o informe creado desde la actividad, con texto y fotos (ADR-023). */
final class ComposedDocumentTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $contractor;
    private Browser $supervisor;
    private string $contract;
    private string $obligation;
    private string $report;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clock = new FrozenClock(new DateTimeImmutable('2026-03-10 15:00:00', new DateTimeZone('UTC')));
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $department = self::json($this->admin->post('/departments', ['code' => 'SAMB', 'name' => 'Ambiente']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '44444444', 'name' => 'Carla Contratista', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-030-2026', 'object' => 'Prestación de servicios de prueba de actas.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-15', 'end_date' => '2026-12-15', 'total_value' => '36000000',
        ]))['data']['uuid'];
        $this->obligation = self::json($this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Acompañar las jornadas comunitarias']))['data']['uuid'];
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));

        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
        $this->report = self::json($this->contractor->post("/contracts/{$this->contract}/reports", ['period_start' => '2026-03-01', 'period_end' => '2026-03-31']))['data']['uuid'];
    }

    private static function jpeg(int $seed = 1): string
    {
        $image = imagecreatetruecolor(1600, 1200); // mayor que el lado máximo: se reduce en el PDF
        imagefilledrectangle($image, 0, 0, 1599, 1199, (int) imagecolorallocate($image, 30 * $seed % 255, 120, 90));
        ob_start();
        imagejpeg($image, null, 85);

        return (string) ob_get_clean();
    }

    private function photo(int $seed = 1): string
    {
        $r = $this->contractor->upload("/reports/{$this->report}/documents", self::jpeg($seed), "foto{$seed}.jpg", ['type' => 'report_annex']);
        self::assertStatus(201, $r);

        return self::json($r)['data']['uuid'];
    }

    /** @return array<string, mixed> */
    private function acta(array $photos = []): array
    {
        return [
            'template' => 'acta',
            'activity' => $this->obligation,
            'fields' => [
                'fecha' => '2026-03-09', 'hora' => '09:30', 'lugar' => 'Salón comunal vereda La Liberia',
                'tema' => 'Socialización del programa ambiental', 'asistentes' => "Persona Uno\nPersona Dos",
                'desarrollo' => 'Se presentó el programa a la comunidad y se resolvieron sus inquietudes.',
                'compromisos' => 'Enviar el cronograma de visitas.',
            ],
            'photos' => $photos,
        ];
    }

    private function pdfText(string $documentUuid): string
    {
        $download = $this->contractor->get("/documents/{$documentUuid}/download");
        self::assertStatus(200, $download);

        return (new Parser())->parseContent((string) $download->getBody())->getText();
    }

    public function testCreatesAnActaWithItsOwnFieldsAndPhotos(): void
    {
        $photo = $this->photo();

        $response = $this->contractor->post("/reports/{$this->report}/composed-documents", $this->acta([['uuid' => $photo, 'caption' => 'Asistentes a la socialización']]));

        self::assertStatus(201, $response);
        $data = self::json($response)['data'];
        self::assertSame('acta', $data['type']['code']);
        self::assertSame('application/pdf', $data['mime_type']);
        self::assertSame('Acta - Acompañar las jornadas comunitarias - 2026-03-09.pdf', $data['original_name']);
        self::assertSame('Acta creado.', self::json($response)['message']);

        $text = $this->pdfText($data['uuid']);
        foreach (['ACTA', 'CPS-030-2026', 'Carla Contratista', 'Salón comunal vereda La Liberia', 'Socialización del programa ambiental', 'Persona Dos', 'Enviar el cronograma de visitas', 'Registro fotográfico', 'Asistentes a la socialización', 'Formato provisional'] as $expected) {
            self::assertStringContainsString($expected, $text, "Falta en el acta: {$expected}");
        }
    }

    public function testCreatesAnInformeWithDifferentFields(): void
    {
        $response = $this->contractor->post("/reports/{$this->report}/composed-documents", [
            'template' => 'informe',
            'activity' => $this->obligation,
            'fields' => ['fecha' => '2026-03-09', 'actividades' => 'Acompañé tres jornadas comunitarias en la zona rural.', 'resultados' => 'Ciento veinte personas informadas.'],
        ]);

        self::assertStatus(201, $response);
        $data = self::json($response)['data'];
        self::assertSame('informe', $data['type']['code']);
        $text = $this->pdfText($data['uuid']);
        self::assertStringContainsString('INFORME DE ACTIVIDADES', $text);
        self::assertStringContainsString('Ciento veinte personas informadas', $text);
        self::assertStringNotContainsString('Compromisos', $text, 'Los campos del acta no aparecen en el informe');
        self::assertStringNotContainsString('Registro fotográfico', $text);
    }

    public function testEachTemplateValidatesItsRequiredFields(): void
    {
        $acta = $this->acta();
        unset($acta['fields']['lugar']);
        $acta['fields']['desarrollo'] = 'Corto';

        $response = $this->contractor->post("/reports/{$this->report}/composed-documents", $acta);

        self::assertStatus(422, $response);
        $fields = array_column(self::json($response)['errors'], 'field');
        self::assertContains('fields.lugar', $fields);
        self::assertContains('fields.desarrollo', $fields);
    }

    public function testRejectsUnknownTemplatesAndBadTimes(): void
    {
        self::assertStatus(422, $this->contractor->post("/reports/{$this->report}/composed-documents", ['template' => 'oficio', 'activity' => $this->obligation]));
        $acta = $this->acta();
        $acta['fields']['hora'] = '25:99';
        self::assertStatus(422, $this->contractor->post("/reports/{$this->report}/composed-documents", $acta));
    }

    public function testOnlyPhotosOfThisReport(): void
    {
        $contractDoc = $this->admin->upload("/contracts/{$this->contract}/documents", self::jpeg(3), 'otra.jpg', ['type' => 'other']);
        self::assertStatus(201, $contractDoc);

        $response = $this->contractor->post("/reports/{$this->report}/composed-documents", $this->acta([['uuid' => self::json($contractDoc)['data']['uuid'], 'caption' => '']]));

        self::assertStatus(422, $response);
        self::assertSame('photos', self::json($response)['errors'][0]['field']);
    }

    public function testActivityMustBelongToTheReportContract(): void
    {
        $acta = $this->acta();
        $acta['activity'] = '6f1c2b8e-0000-4000-8000-000000000000';

        self::assertStatus(422, $this->contractor->post("/reports/{$this->report}/composed-documents", $acta));
    }

    public function testOnlyTheContractorWhileTheReportIsEditable(): void
    {
        self::assertStatus(403, $this->supervisor->post("/reports/{$this->report}/composed-documents", $this->acta()));

        $this->contractor->put("/reports/{$this->report}", [
            'summary' => 'Resumen de las actividades ejecutadas durante el período.', 'contractor_notes' => null,
            'items' => [['obligation' => $this->obligation, 'description' => 'Acompañé las jornadas comunitarias.']],
        ]);
        self::assertStatus(200, $this->contractor->post("/reports/{$this->report}/submit"));

        self::assertStatus(409, $this->contractor->post("/reports/{$this->report}/composed-documents", $this->acta()));
    }
}
