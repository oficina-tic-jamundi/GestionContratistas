<?php

declare(strict_types=1);

namespace Sigcon\Services\Activities;

/**
 * Propone las obligaciones de un contrato a partir de su texto (ADR-022).
 *
 * Es un análisis determinista, sin IA ni servicios externos (ADR-008): busca la sección de
 * obligaciones del contratista y separa su lista numerada. El resultado es una PROPUESTA: la
 * administración la revisa, corrige y confirma antes de que se registre cualquier obligación.
 *
 * Qué reconoce:
 * - Encabezados "OBLIGACIONES ESPECÍFICAS (DEL CONTRATISTA)" (preferido) u
 *   "OBLIGACIONES DEL CONTRATISTA" (si no hay específicas).
 * - Fin de la sección: obligaciones de la entidad/contratante/supervisor, las generales si
 *   vienen después, o el siguiente encabezado de cláusula (CLÁUSULA, VALOR, PLAZO, FORMA DE
 *   PAGO, SUPERVISIÓN, GARANTÍAS...).
 * - Ítems numerados "1." "1)" "1-" en orden consecutivo (así no confunde valores ni fechas),
 *   o con letras "a)" "b." si no hay números.
 */
final class ObligationExtractor
{
    public const MAX_ITEMS = 60;
    public const TITLE_MAX = 180;

    /** Resultado sin ítems cuando no se halla la sección. */
    public const SECTION_NOT_FOUND = 'section_not_found';
    /** La sección existe pero no tiene una lista reconocible. */
    public const NO_ITEMS = 'no_items';
    /** Se usó la sección general porque no hay "específicas": puede incluir obligaciones genéricas. */
    public const GENERAL_SECTION = 'general_section';
    /** Había más ítems que el máximo permitido; se cortó la lista. */
    public const TRUNCATED = 'truncated';

    private const SPECIFIC = '/OBLIGACIONES\s+ESPEC[IÍ]FICAS(?:\s+DEL\s+CONTRATISTA)?/iu';
    private const GENERAL = '/OBLIGACIONES\s+(?:GENERALES\s+)?DEL\s+CONTRATISTA/iu';

    /** Encabezados que cierran la sección (se buscan al comienzo de una línea). */
    private const STOP = '/^\s*(?:(?:CL[AÁ]USULA\b[^\n]{0,40})|(?:(?:PRIMERA|SEGUNDA|TERCERA|CUARTA|QUINTA|SEXTA|S[EÉ]PTIMA|OCTAVA|NOVENA|D[EÉ]CIMA|UND[EÉ]CIMA|DUOD[EÉ]CIMA|VIG[EÉ]SIMA)[\s\.\-:–]))'
        . '|^\s*(?:[\d\.\-\)\s]{0,6})?(?:OBLIGACIONES\s+(?:DE\s+LA\s+ENTIDAD|DEL\s+CONTRATANTE|DEL\s+MUNICIPIO|DE\s+LA\s+ALCALD[IÍ]A|DEL\s+SUPERVISOR|GENERALES)'
        . '|VALOR\s+(?:DEL\s+CONTRATO|Y\s+FORMA)|FORMA\s+DE\s+PAGO|PLAZO\s+(?:DE\s+EJECUCI[OÓ]N|DEL\s+CONTRATO)|SUPERVISI[OÓ]N\s*[:\.\-]|GARANT[IÍ]AS?\s*[:\.\-]|DOMICILIO|INDEMNIDAD|PERFECCIONAMIENTO)/mu';

    /** Encabezados en mayúscula dentro de la misma línea (PDF que entregan el texto sin saltos). */
    private const INLINE_STOP = '/[\.;:]\s+(?=CL[AÁ]USULA\b|OBLIGACIONES\s+(?:DE\s+LA\s+ENTIDAD|DEL\s+CONTRATANTE|DEL\s+MUNICIPIO|DEL\s+SUPERVISOR|GENERALES))/u';

    /**
     * @return array{items: list<array{title: string, description: ?string}>, warnings: list<string>}
     */
    public function extract(string $text): array
    {
        $text = self::normalize($text);
        $warnings = [];

        $start = self::sectionStart($text, self::SPECIFIC);
        if ($start === null) {
            $start = self::sectionStart($text, self::GENERAL);
            if ($start !== null) {
                $warnings[] = self::GENERAL_SECTION;
            }
        }
        if ($start === null) {
            return ['items' => [], 'warnings' => [self::SECTION_NOT_FOUND]];
        }

        $section = mb_substr($text, $start);
        $end = strlen($section);
        if (preg_match(self::STOP, $section, $m, PREG_OFFSET_CAPTURE, 1) === 1) {
            $end = $m[0][1];
        }
        if (preg_match(self::INLINE_STOP, $section, $m, PREG_OFFSET_CAPTURE) === 1) {
            $end = min($end, $m[0][1] + 1); // conserva el punto final del último ítem
        }
        $section = substr($section, 0, $end);

        $raw = self::numbered($section);
        if ($raw === []) {
            $raw = self::lettered($section);
        }
        if ($raw === []) {
            return ['items' => [], 'warnings' => [...$warnings, self::NO_ITEMS]];
        }
        if (count($raw) > self::MAX_ITEMS) {
            $raw = array_slice($raw, 0, self::MAX_ITEMS);
            $warnings[] = self::TRUNCATED;
        }

        $items = [];
        foreach ($raw as $entry) {
            $clean = self::clean($entry);
            if (mb_strlen($clean) >= 10) {
                $items[] = self::split($clean);
            }
        }

        return ['items' => $items, 'warnings' => $items === [] ? [...$warnings, self::NO_ITEMS] : $warnings];
    }

    private static function normalize(string $text): string
    {
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        }
        $text = str_replace(["\r\n", "\r", "\f", "\u{00A0}", "\t"], ["\n", "\n", "\n", ' ', ' '], $text);
        // Palabras cortadas con guion al final de la línea.
        $text = (string) preg_replace('/(\p{L})-\n(\p{Ll})/u', '$1$2', $text);
        $text = (string) preg_replace('/[ ]{2,}/u', ' ', $text);

        return (string) preg_replace('/\n{3,}/u', "\n\n", $text);
    }

    /** Posición (en caracteres) justo después del encabezado, o null. */
    private static function sectionStart(string $text, string $pattern): ?int
    {
        if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE) !== 1) {
            return null;
        }
        $end = $m[0][1] + strlen($m[0][0]);

        return mb_strlen(substr($text, 0, $end));
    }

    /**
     * Ítems "1." "2)" "3-" consecutivos. Un número que no sigue la secuencia se toma como parte
     * del texto (ej. "1.000.000" o una fecha).
     *
     * @return list<string>
     */
    private static function numbered(string $section): array
    {
        $parts = preg_split('/(?:^|\n|(?<=[;:\.]))\s*(\d{1,2})\s*[\.\)\-–]\s+(?=\p{L})/u', $section, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false || count($parts) < 3) {
            return [];
        }

        $items = [];
        $expected = 1;
        $current = null;
        for ($i = 1; $i < count($parts); $i += 2) {
            $number = (int) $parts[$i];
            $body = $parts[$i + 1] ?? '';
            if ($number === $expected) {
                if ($current !== null) {
                    $items[] = $current;
                }
                $current = $body;
                $expected++;
            } elseif ($current !== null) {
                $current .= ' ' . $parts[$i] . '. ' . $body;
            }
        }
        if ($current !== null) {
            $items[] = $current;
        }

        return count($items) >= 1 ? $items : [];
    }

    /** @return list<string> */
    private static function lettered(string $section): array
    {
        $parts = preg_split('/(?:^|\n)\s*([a-zñ])\s*[\.\)]\s+(?=\p{L})/u', $section, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false || count($parts) < 3) {
            return [];
        }
        $items = [];
        $expected = 'a';
        for ($i = 1; $i < count($parts); $i += 2) {
            if ($parts[$i] === $expected) {
                $items[] = $parts[$i + 1] ?? '';
                $expected = chr(ord($expected) + 1);
            } elseif ($items !== []) {
                $items[count($items) - 1] .= ' ' . $parts[$i] . ') ' . ($parts[$i + 1] ?? '');
            }
        }

        return $items;
    }

    private static function clean(string $item): string
    {
        $item = (string) preg_replace('/\s+/u', ' ', $item);
        $item = trim($item);
        // Conectores y puntuación de cierre de la enumeración ("...; y", "...;").
        $item = (string) preg_replace('/(?:[;,]\s*(?:y|e|o)?|\s+y)\s*$/u', '', $item);

        return rtrim($item, " ;,.:\u{2013}-");
    }

    /**
     * Título corto (para listas y tarjetas) y la obligación completa como descripción.
     *
     * @return array{title: string, description: ?string}
     */
    private static function split(string $text): array
    {
        $text = mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
        if (mb_strlen($text) <= self::TITLE_MAX) {
            return ['title' => $text, 'description' => null];
        }
        $cut = mb_substr($text, 0, self::TITLE_MAX);
        $space = mb_strrpos($cut, ' ');
        $title = rtrim(mb_substr($cut, 0, $space !== false && $space > 60 ? $space : self::TITLE_MAX), " ,;:.") . '…';

        return ['title' => $title, 'description' => mb_substr($text, 0, 5000)];
    }
}
