<?php

declare(strict_types=1);

namespace Sigcon\Services\Activities;

use Sigcon\Exceptions\BusinessRuleException;
use Smalot\PdfParser\Config;
use Smalot\PdfParser\Parser;
use Throwable;

/**
 * Texto de un contrato en PDF, leído en el servidor con una librería PHP (sin servicios
 * externos ni binarios del sistema: funciona en hosting compartido).
 *
 * Solo lee PDF con texto. Un PDF escaneado (imagen) no tiene texto: se informa y la
 * administración registra las obligaciones a mano. No hay OCR (ADR-022).
 */
final class ContractTextReader
{
    /** Menos texto que esto en todo el documento indica un escaneo sin capa de texto. */
    private const MIN_TEXT = 200;

    public function read(string $contents): string
    {
        $config = new Config();
        $config->setRetainImageContent(false); // no se necesitan las imágenes: menos memoria
        $config->setDecodeMemoryLimit(64 * 1024 * 1024);

        try {
            $text = (new Parser([], $config))->parseContent($contents)->getText();
        } catch (Throwable $e) {
            throw new BusinessRuleException(
                'No fue posible leer el texto del contrato. Puede estar protegido con contraseña o dañado. Registre las obligaciones a mano.',
                [],
                'Lectura de PDF fallida: ' . $e->getMessage(),
                $e,
            );
        }

        if (mb_strlen(trim((string) preg_replace('/\s+/u', ' ', $text))) < self::MIN_TEXT) {
            throw new BusinessRuleException('El contrato parece escaneado (es una imagen): no tiene texto que se pueda leer. Registre las obligaciones a mano.');
        }

        return $text;
    }
}
