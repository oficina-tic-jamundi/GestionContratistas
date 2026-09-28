<?php

declare(strict_types=1);

namespace Sigcon\Tests\Unit\Activities;

use PHPUnit\Framework\TestCase;
use Sigcon\Services\Activities\ObligationExtractor;

/**
 * Textos ficticios con la estructura habitual de un contrato de prestación de servicios.
 * No provienen de contratos reales.
 */
final class ObligationExtractorTest extends TestCase
{
    private const CONTRACT = <<<'TXT'
        CONTRATO DE PRESTACIÓN DE SERVICIOS No. DEMO-CPS-099-2026
        CLÁUSULA PRIMERA. OBJETO: Prestar servicios profesionales de apoyo a la gestión.
        CLÁUSULA SEGUNDA. VALOR: El valor del contrato es de 1.500.000 pesos mensuales.
        CLÁUSULA TERCERA. OBLIGACIONES GENERALES DEL CONTRATISTA: 1. Cumplir el objeto del
        contrato. 2. Afiliarse al sistema de seguridad social.
        CLÁUSULA CUARTA. OBLIGACIONES ESPECÍFICAS DEL CONTRATISTA:
        1. Apoyar la formulación de los proyectos de inversión de la Secretaría de
        Planeación, conforme a la metodología vigente;
        2. Realizar el seguimiento mensual a los indicadores del plan de desarrollo y
        reportarlo a la supervisión;
        3. Acompañar 2 jornadas comunitarias por mes en la zona rural;
        4. Elaborar las actas de las reuniones en que participe; y
        5. Las demás que le asigne el supervisor acordes con el objeto del contrato.
        CLÁUSULA QUINTA. OBLIGACIONES DEL MUNICIPIO: 1. Pagar el valor pactado.
        TXT;

    public function testTakesTheSpecificObligationsInOrder(): void
    {
        $result = (new ObligationExtractor())->extract(self::CONTRACT);

        self::assertSame([], $result['warnings']);
        self::assertCount(5, $result['items']);
        self::assertStringStartsWith('Apoyar la formulación de los proyectos', $result['items'][0]['title']);
        self::assertStringEndsWith('metodología vigente', $result['items'][0]['title']);
        self::assertSame('Elaborar las actas de las reuniones en que participe', $result['items'][3]['title']);
        self::assertStringStartsWith('Las demás que le asigne', $result['items'][4]['title']);
    }

    public function testKeepsNumbersThatAreNotListItems(): void
    {
        $items = (new ObligationExtractor())->extract(self::CONTRACT)['items'];

        // "2 jornadas" no abre un ítem nuevo: sigue la secuencia 1, 2, 3...
        self::assertStringContainsString('Acompañar 2 jornadas comunitarias', $items[2]['title']);
    }

    public function testStopsAtTheNextClause(): void
    {
        $items = (new ObligationExtractor())->extract(self::CONTRACT)['items'];

        foreach ($items as $item) {
            self::assertStringNotContainsString('Pagar el valor pactado', $item['title']);
            self::assertStringNotContainsString('CLÁUSULA', $item['title']);
        }
    }

    public function testLongObligationGetsShortTitleAndFullDescription(): void
    {
        $long = 'Diseñar, implementar y hacer seguimiento a la estrategia de participación ciudadana '
            . 'en los procesos de planeación territorial, articulando a las juntas de acción comunal, '
            . 'las organizaciones sociales y las dependencias de la administración municipal';
        $text = "OBLIGACIONES ESPECÍFICAS:\n1. {$long}.\n2. Presentar informes mensuales de actividades.\nCLÁUSULA SEXTA. PLAZO";

        $items = (new ObligationExtractor())->extract($text)['items'];

        self::assertCount(2, $items);
        self::assertLessThanOrEqual(ObligationExtractor::TITLE_MAX + 1, mb_strlen($items[0]['title']));
        self::assertStringEndsWith('…', $items[0]['title']);
        self::assertSame($long, $items[0]['description']);
        self::assertNull($items[1]['description']);
    }

    public function testLetteredListsWhenThereAreNoNumbers(): void
    {
        $text = "OBLIGACIONES ESPECÍFICAS DEL CONTRATISTA:\na) Revisar los expedientes asignados.\nb) Proyectar las respuestas a los derechos de petición.\nc) Asistir a los comités de la dependencia.\nFORMA DE PAGO: mensual.";

        $items = (new ObligationExtractor())->extract($text)['items'];

        self::assertCount(3, $items);
        self::assertSame('Proyectar las respuestas a los derechos de petición', $items[1]['title']);
    }

    public function testFlattenedPdfTextWithoutLineBreaks(): void
    {
        // Algunos PDF entregan la lista en una sola línea.
        $text = 'OBLIGACIONES ESPECÍFICAS: 1. Visitar los predios asignados; 2. Registrar las visitas en el sistema; 3. Entregar el informe final. CLÁUSULA SÉPTIMA. GARANTÍAS';

        $items = (new ObligationExtractor())->extract($text)['items'];

        self::assertSame(['Visitar los predios asignados', 'Registrar las visitas en el sistema', 'Entregar el informe final'], array_column($items, 'title'));
    }

    public function testFallsBackToContractorObligationsWithAWarning(): void
    {
        $text = "OBLIGACIONES DEL CONTRATISTA:\n1. Apoyar la atención al ciudadano.\n2. Organizar el archivo de gestión.\nCLÁUSULA OCTAVA. SUPERVISIÓN";

        $result = (new ObligationExtractor())->extract($text);

        self::assertCount(2, $result['items']);
        self::assertSame([ObligationExtractor::GENERAL_SECTION], $result['warnings']);
    }

    public function testReportsWhenThereIsNoSection(): void
    {
        $result = (new ObligationExtractor())->extract('Acta de inicio del contrato. Las partes se reúnen para iniciar.');

        self::assertSame([], $result['items']);
        self::assertSame([ObligationExtractor::SECTION_NOT_FOUND], $result['warnings']);
    }

    public function testReportsWhenTheSectionHasNoList(): void
    {
        $result = (new ObligationExtractor())->extract("OBLIGACIONES ESPECÍFICAS: las establecidas en los estudios previos.\nCLÁUSULA NOVENA. DOMICILIO");

        self::assertSame([], $result['items']);
        self::assertContains(ObligationExtractor::NO_ITEMS, $result['warnings']);
    }

    public function testCapsTheNumberOfItems(): void
    {
        $lines = [];
        for ($i = 1; $i <= ObligationExtractor::MAX_ITEMS + 5; $i++) {
            $lines[] = "{$i}. Obligación ficticia número {$i} del contrato";
        }
        $result = (new ObligationExtractor())->extract("OBLIGACIONES ESPECÍFICAS:\n" . implode("\n", $lines));

        self::assertCount(ObligationExtractor::MAX_ITEMS, $result['items']);
        self::assertContains(ObligationExtractor::TRUNCATED, $result['warnings']);
    }
}
