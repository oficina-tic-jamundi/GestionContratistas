<?php

declare(strict_types=1);

namespace Sigcon\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sigcon\Config\Settings;
use Sigcon\Helpers\DateTimes;
use Sigcon\Http\JsonResponder;
use Sigcon\Models\Document;
use Sigcon\Services\Documents\DocumentOwner;
use Sigcon\Services\Documents\DocumentService;
use Sigcon\Services\Reports\Compose\ComposedDocumentData;
use Sigcon\Services\Reports\Compose\ComposedDocumentService;
use Sigcon\Validators\Validator;

final class DocumentController
{
    /**
     * Tipos que el navegador puede mostrar en la pestaña, aislados con CSP sandbox.
     * Los PDF se entregan solo como descarga: el visor de PDF del navegador no funciona
     * dentro de un documento aislado.
     */
    /** Tipos que el visor integrado puede mostrar dentro de la aplicación. */
    private const INLINE_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

    public function __construct(
        private readonly DocumentService $documents,
        private readonly ComposedDocumentService $composer,
        private readonly JsonResponder $responder,
        private readonly Settings $settings,
    ) {
    }

    /** @param array{uuid: string} $args */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->listFor($response, $this->documents->contractOwner($args['uuid']));
    }

    /** @param array{uuid: string} $args */
    public function reportIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->listFor($response, $this->documents->reportOwner($args['uuid']));
    }

    /** @param array{uuid: string} $args */
    public function store(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->storeFor($request, $response, fn () => $this->documents->contractOwner($args['uuid']));
    }

    /** @param array{uuid: string} $args */
    public function reportStore(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->storeFor($request, $response, fn () => $this->documents->reportOwner($args['uuid']));
    }

    /**
     * Acta o informe creado desde la actividad, con lo escrito o dictado y las fotos (ADR-023).
     *
     * @param array{uuid: string} $args
     */
    public function reportCompose(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = ComposedDocumentData::fromBody($request->getParsedBody());
        $document = $this->composer->compose($args['uuid'], $data);

        return $this->responder->success($response, self::present($document), sprintf('%s creado.', $data->template->label()), 201);
    }

    private function listFor(ResponseInterface $response, DocumentOwner $owner): ResponseInterface
    {
        $result = $this->documents->list($owner, $this->settings->uploadMaxBytes);

        return $this->responder->success($response, [
            'can_upload' => $result['can_upload'],
            'max_mb' => $result['max_mb'],
            'types' => $result['types'],
            'items' => array_map(
                static fn (Document $d) => self::present($d) + ['can_withdraw' => in_array($d->uuid, $result['withdrawable'], true)],
                $result['documents'],
            ),
        ]);
    }

    /** @param callable(): DocumentOwner $owner se resuelve después de validar el formulario */
    private function storeFor(ServerRequestInterface $request, ResponseInterface $response, callable $owner): ResponseInterface
    {
        $body = $request->getParsedBody();
        $v = Validator::fromBody($body);
        $type = $v->string('type', 50);
        $description = $v->string('description', 255, required: false);
        $v->throwIfInvalid();

        $file = $request->getUploadedFiles()['file'] ?? null;
        $document = $this->documents->upload(
            $owner(),
            Validator::present($type),
            $description,
            $file instanceof \Psr\Http\Message\UploadedFileInterface ? $file : null,
        );

        return $this->responder->success($response, self::present($document), 'Documento cargado.', 201);
    }

    /** @param array{uuid: string} $args */
    public function download(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $result = $this->documents->download($args['uuid']);
        $document = $result['document'];
        $inline = ($request->getQueryParams()['inline'] ?? '') === '1' && in_array($document->mimeType, self::INLINE_TYPES, true);

        return $response
            ->withBody($result['stream'])
            ->withHeader('Content-Type', $document->mimeType)
            ->withHeader('Content-Length', (string) $document->sizeBytes)
            ->withHeader('Content-Disposition', self::disposition($inline ? 'inline' : 'attachment', $document->originalName))
            ->withHeader('Cache-Control', 'private, no-store')
            ->withHeader('Content-Security-Policy', self::csp($inline, $document->mimeType))
            ->withHeader('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Política de seguridad del archivo servido:
     *
     * - Descarga: aislado por completo y sin poder enmarcarse en ningún sitio.
     * - Visor integrado (inline=1): solo se puede enmarcar en el propio SIGCON. Para el PDF no
     *   se usa `sandbox` porque desactiva el visor de PDF del navegador; el archivo se sirve con
     *   `default-src 'none'` y `nosniff`, así que no puede cargar nada ni interpretarse como HTML.
     */
    private static function csp(bool $inline, string $mimeType): string
    {
        if (!$inline) {
            return "default-src 'none'; img-src 'self'; style-src 'unsafe-inline'; frame-ancestors 'none'; sandbox";
        }

        return $mimeType === 'application/pdf'
            ? "default-src 'none'; object-src 'self'; style-src 'unsafe-inline'; frame-ancestors 'self'"
            : "default-src 'none'; img-src 'self'; style-src 'unsafe-inline'; frame-ancestors 'self'; sandbox";
    }

    /** @param array{uuid: string} $args */
    public function withdraw(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $v = Validator::fromBody($request->getParsedBody());
        $reason = $v->string('reason', 500, 5);
        $v->throwIfInvalid();

        $document = $this->documents->withdraw($args['uuid'], Validator::present($reason));

        return $this->responder->success($response, self::present($document), 'Documento retirado.');
    }

    /** Content-Disposition con nombre ASCII de respaldo y nombre UTF-8 (RFC 6266 / 5987). */
    public static function disposition(string $type, string $name): string
    {
        $ascii = (string) preg_replace('/[^A-Za-z0-9._ -]/', '_', (string) iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name));
        $ascii = $ascii === '' ? 'documento' : $ascii;

        return sprintf('%s; filename="%s"; filename*=UTF-8\'\'%s', $type, str_replace('"', '', $ascii), rawurlencode($name));
    }

    /** @return array<string, mixed> */
    private static function present(Document $d): array
    {
        return [
            'uuid' => $d->uuid,
            'type' => ['code' => $d->typeCode, 'name' => $d->typeName],
            'description' => $d->description,
            'original_name' => $d->originalName,
            'extension' => $d->extension,
            'mime_type' => $d->mimeType,
            'size_bytes' => $d->sizeBytes,
            'sha256' => $d->sha256,
            'status' => $d->status,
            'uploaded_by' => $d->uploadedByName,
            'created_at' => DateTimes::toApi($d->createdAt),
            'withdrawn' => $d->isActive() ? null : [
                'reason' => $d->withdrawnReason,
                'by' => $d->withdrawnByName,
                'at' => DateTimes::toApi($d->withdrawnAt),
            ],
        ];
    }
}
