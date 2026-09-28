<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\IntegrationTestCase;

final class DocumentsTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $contractor;
    private Browser $supervisor;
    private string $contract;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $account = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '22222222', 'name' => 'Carla Contratista', 'user' => $account,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-010-2026', 'object' => 'Prestación de servicios de prueba documental.',
            'contractor' => $contractor, 'department' => $department, 'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-15', 'end_date' => '2026-12-15', 'total_value' => '30000000',
        ]))['data']['uuid'];
        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
    }

    private static function pdf(string $text = 'SIGCON'): string
    {
        return "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n% {$text}\ntrailer << /Root 1 0 R >>\n%%EOF\n";
    }

    /**
     * PNG mínimo (unos cientos de bytes) que DECLARA 30000×30000 píxeles: decodificarlo exigiría
     * unos 3,6 GB de memoria. Debe rechazarse antes de decodificar.
     */
    private static function pngBomb(): string
    {
        $chunk = static fn (string $type, string $data): string => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $ihdr = pack('NN', 30000, 30000) . chr(8) . chr(2) . chr(0) . chr(0) . chr(0);

        return "\x89PNG\r\n\x1a\n" . $chunk('IHDR', $ihdr) . $chunk('IDAT', (string) gzcompress(str_repeat("\0", 1000))) . $chunk('IEND', '');
    }

    private static function png(): string
    {
        $image = imagecreatetruecolor(4, 4);
        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }

    private function uploadPdf(Browser $as, string $name = 'contrato.pdf', string $type = 'signed_contract', string $text = 'uno'): \Psr\Http\Message\ResponseInterface
    {
        return $as->upload("/contracts/{$this->contract}/documents", self::pdf($text), $name, ['type' => $type, 'description' => 'Documento de prueba']);
    }

    public function testUploadStoresFileOutsidePublicAndRecordsMetadata(): void
    {
        $response = $this->uploadPdf($this->admin, 'Contrato firmado ñ.pdf');

        self::assertStatus(201, $response);
        $data = self::json($response)['data'];
        self::assertSame('application/pdf', $data['mime_type']);
        self::assertSame(hash('sha256', self::pdf('uno')), $data['sha256']);
        self::assertSame('Contrato firmado ñ.pdf', $data['original_name']);
        self::assertArrayNotHasKey('storage_path', $data, 'La ruta física nunca se expone');

        $stored = glob($this->storageDir() . '/private/documents/*/*/*.pdf') ?: [];
        self::assertCount(1, $stored);
        self::assertMatchesRegularExpression('/[0-9a-f-]{36}\.pdf$/', $stored[0], 'Nombre interno aleatorio, no el del usuario');
        self::assertContains('document.uploaded', $this->auditActions());
    }

    /** @return iterable<string, array{string, string, string}> */
    public static function invalidFiles(): iterable
    {
        yield 'ejecutable renombrado a pdf' => ["MZ\x90\x00 programa", 'factura.pdf', 'content_mismatch'];
        yield 'extensión no permitida' => [self::pdf(), 'script.php', 'extension_not_allowed'];
        yield 'html disfrazado de imagen' => ['<html><script>alert(1)</script></html>', 'foto.png', 'content_mismatch'];
        yield 'doble extensión' => [self::pdf(), 'contrato.pdf.exe', 'extension_not_allowed'];
        yield 'archivo vacío' => ['', 'vacio.pdf', 'empty'];
        yield 'bomba de descompresión (PNG de 30000×30000)' => [self::pngBomb(), 'plano.png', 'too_many_pixels'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidFiles')]
    public function testDangerousOrInvalidFilesAreRejected(string $contents, string $name, string $code): void
    {
        $response = $this->admin->upload("/contracts/{$this->contract}/documents", $contents, $name, ['type' => 'other']);

        self::assertStatus(422, $response);
        self::assertSame($code, self::json($response)['errors'][0]['code']);
        self::assertSame([], glob($this->storageDir() . '/private/documents/*/*/*') ?: [], 'Nada se guarda si la validación falla');
    }

    public function testSizeLimitAndDuplicates(): void
    {
        $big = self::pdf() . str_repeat('x', 1024 * 1024 + 10);
        $tooBig = $this->admin->upload("/contracts/{$this->contract}/documents", $big, 'grande.pdf', ['type' => 'other']);
        self::assertSame('too_large', self::json($tooBig)['errors'][0]['code']);

        self::assertStatus(201, $this->uploadPdf($this->admin));
        $duplicate = $this->uploadPdf($this->admin, 'otra-copia.pdf');
        self::assertStatus(422, $duplicate);
        self::assertSame('duplicate', self::json($duplicate)['errors'][0]['code']);
        self::assertCount(1, glob($this->storageDir() . '/private/documents/*/*/*.pdf') ?: [], 'El duplicado no deja archivos huérfanos');

        self::assertStatus(201, $this->admin->upload("/contracts/{$this->contract}/documents", self::png(), 'foto.PNG', ['type' => 'other']));
    }

    public function testDownloadIsAuthorizedStreamedAndAudited(): void
    {
        $uuid = self::json($this->uploadPdf($this->admin, 'Acta "inicio".pdf', 'start_minute'))['data']['uuid'];

        $download = $this->supervisor->get("/documents/{$uuid}/download");
        self::assertStatus(200, $download);
        self::assertSame(self::pdf('uno'), (string) $download->getBody());
        self::assertSame('application/pdf', $download->getHeaderLine('Content-Type'));
        self::assertStringStartsWith('attachment; filename="Acta _inicio_.pdf"', $download->getHeaderLine('Content-Disposition'));
        // Las comillas se eliminan al sanear el nombre (evita romper la cabecera).
        self::assertStringContainsString("filename*=UTF-8''Acta%20_inicio_.pdf", $download->getHeaderLine('Content-Disposition'));
        self::assertSame('nosniff', $download->getHeaderLine('X-Content-Type-Options'));
        self::assertStringContainsString('sandbox', $download->getHeaderLine('Content-Security-Policy'));
        self::assertStringStartsWith('inline', $this->supervisor->get("/documents/{$uuid}/download?inline=1")->getHeaderLine('Content-Disposition'), 'PDF: el visor integrado lo muestra en la aplicación');
        self::assertStringStartsWith('attachment', $this->supervisor->get("/documents/{$uuid}/download")->getHeaderLine('Content-Disposition'), 'Sin inline=1 se descarga');
        self::assertContains('document.downloaded', $this->auditActions());

        // Otro contratista no puede descargar conociendo el identificador.
        $this->createUser('otro@example.test', ['contractor']);
        $other = $this->browser();
        $other->login('otro@example.test');
        self::assertStatus(404, $other->get("/documents/{$uuid}/download"));
        self::assertStatus(401, $this->send('GET', "/documents/{$uuid}/download"), 'Sin sesión no hay descarga');
    }

    public function testWhoCanUploadAndWithdraw(): void
    {
        // El contratista solo ve (y carga en) contratos formalizados, no borradores.
        self::assertStatus(404, $this->uploadPdf($this->contractor));
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));

        self::assertStatus(201, $this->uploadPdf($this->supervisor, 'contrato-del-supervisor.pdf'), 'El supervisor del contrato sube el contrato y sus soportes (ADR-021)');
        $mine = self::json($this->uploadPdf($this->contractor, 'mi-rut.pdf', 'other', 'contratista'))['data']['uuid'];
        $adminDoc = self::json($this->uploadPdf($this->admin, 'contrato.pdf', 'signed_contract', 'admin'))['data']['uuid'];

        $flags = array_column(self::json($this->contractor->get("/contracts/{$this->contract}/documents"))['data']['items'], 'can_withdraw', 'uuid');
        self::assertSame([$adminDoc => false, $mine => true], [$adminDoc => $flags[$adminDoc], $mine => $flags[$mine]]);
        self::assertStatus(403, $this->contractor->post("/documents/{$adminDoc}/withdraw", ['reason' => 'No es mío']));
        self::assertStatus(422, $this->contractor->post("/documents/{$mine}/withdraw", ['reason' => 'x']));
        $withdrawn = $this->contractor->post("/documents/{$mine}/withdraw", ['reason' => 'Se cargó una versión desactualizada.']);
        self::assertStatus(200, $withdrawn);
        self::assertSame('withdrawn', self::json($withdrawn)['data']['status']);
        self::assertSame('Se cargó una versión desactualizada.', self::json($withdrawn)['data']['withdrawn']['reason']);

        // Sigue visible (trazabilidad) y el archivo sigue existiendo; se puede volver a cargar.
        $list = self::json($this->supervisor->get("/contracts/{$this->contract}/documents"))['data'];
        self::assertCount(3, $list['items']);
        self::assertTrue($list['can_upload'], 'El supervisor del contrato también carga documentos (ADR-021)');
        self::assertStatus(201, $this->uploadPdf($this->contractor, 'mi-rut.pdf', 'other', 'contratista'));
        self::assertCount(4, glob($this->storageDir() . '/private/documents/*/*/*.pdf') ?: []);
    }

    public function testTheSupervisorUploadsTheContractAndTheViewerShowsItInline(): void
    {
        // El supervisor sube el contrato firmado del contratista que supervisa (ADR-021).
        $uploaded = $this->uploadPdf($this->supervisor, 'contrato-firmado.pdf');
        self::assertStatus(201, $uploaded);
        $uuid = self::json($uploaded)['data']['uuid'];
        self::assertTrue(self::json($this->supervisor->get("/contracts/{$this->contract}/documents"))['data']['can_upload']);

        // Visor integrado: se muestra dentro de la aplicación, aislado y sin descargarse.
        $inline = $this->supervisor->get("/documents/{$uuid}/download?inline=1");
        self::assertStatus(200, $inline);
        self::assertStringStartsWith('inline;', $inline->getHeaderLine('Content-Disposition'));
        $inlineCsp = $inline->getHeaderLine('Content-Security-Policy');
        self::assertStringContainsString("frame-ancestors 'self'", $inlineCsp, 'Solo se enmarca dentro de SIGCON');
        self::assertStringContainsString("default-src 'none'", $inlineCsp, 'El archivo no puede cargar nada');
        self::assertStringNotContainsString('sandbox', $inlineCsp, 'sandbox desactiva el visor de PDF del navegador');
        self::assertSame('nosniff', $inline->getHeaderLine('X-Content-Type-Options'));

        // La descarga normal no se puede enmarcar en ningún sitio.
        $attachment = $this->supervisor->get("/documents/{$uuid}/download");
        self::assertStringStartsWith('attachment;', $attachment->getHeaderLine('Content-Disposition'));
        self::assertStringContainsString("frame-ancestors 'none'", $attachment->getHeaderLine('Content-Security-Policy'));

        // Un supervisor ajeno al contrato no sube nada.
        $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $other = $this->browser();
        $other->login('otro.supervisor@example.test');
        self::assertStatus(404, $other->upload("/contracts/{$this->contract}/documents", self::pdf(), 'x.pdf', ['type' => 'signed_contract']));
    }

    public function testInvalidTypeAndArchivedContract(): void
    {
        self::assertSame('type', self::json($this->uploadPdf($this->admin, 'x.pdf', 'tipo-inventado'))['errors'][0]['field']);

        $this->pdo()->exec("UPDATE contracts SET status = 'archived'");
        self::assertStatus(409, $this->uploadPdf($this->admin));
        self::assertFalse(self::json($this->admin->get("/contracts/{$this->contract}/documents"))['data']['can_upload']);
    }
}
