<?php

declare(strict_types=1);

namespace Sigcon\Services\Reports\Compose;

use DateTimeImmutable;
use DateTimeZone;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sigcon\Config\Settings;

/**
 * PDF del acta o del informe creado desde la actividad (ADR-023).
 *
 * Mismas garantías que el PDF de versiones (ADR-017): dompdf sin acceso remoto, sin PHP ni
 * JavaScript, todo el texto escapado y las fotos incrustadas como data URI reducidas.
 * El diseño es un formato PROVISIONAL de SIGCON hasta que la Alcaldía entregue el oficial.
 */
final class ComposedDocumentRenderer
{
    /** Lado mayor de las fotos dentro del PDF: suficiente para leerlas, sin inflar el archivo. */
    private const PHOTO_MAX_SIDE = 1200;

    public function __construct(private readonly Settings $settings)
    {
    }

    /**
     * @param array{contract_number: string, contractor: string, supervisor: ?string, department: string, activity: string, obligation: string, period: string} $context
     * @param list<array{bytes: string, caption: string}> $photos
     */
    public function render(ComposedDocumentData $data, array $context, array $photos, DateTimeImmutable $generatedAt): string
    {
        $tmp = $this->settings->storagePath . DIRECTORY_SEPARATOR . 'tmp';
        if (!is_dir($tmp)) {
            @mkdir($tmp, 0750, true);
        }
        $options = new Options();
        $options->setIsRemoteEnabled(false);
        $options->setIsPhpEnabled(false);
        $options->setIsJavascriptEnabled(false);
        $options->setDefaultFont('DejaVu Sans');
        $options->setTempDir($tmp);
        $options->setChroot([$tmp]);
        $options->setDefaultPaperSize('letter');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->html($data, $context, $photos, $generatedAt), 'UTF-8');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans') ?? throw new \RuntimeException('Fuente del PDF no disponible.');
        $canvas->page_text($canvas->get_width() - 150, $canvas->get_height() - 32, 'Página {PAGE_NUM} de {PAGE_COUNT}', $font, 8, [0.35, 0.39, 0.45]);

        return (string) $dompdf->output();
    }

    /**
     * @param array{contract_number: string, contractor: string, supervisor: ?string, department: string, activity: string, obligation: string, period: string} $context
     * @param list<array{bytes: string, caption: string}> $photos
     */
    public function html(ComposedDocumentData $data, array $context, array $photos, DateTimeImmutable $generatedAt): string
    {
        $e = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $text = static fn (string $value): string => nl2br($e($value), false);
        $date = static fn (string $ymd): string => preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $ymd, $m) === 1 ? "{$m[3]}/{$m[2]}/{$m[1]}" : $ymd;
        $generated = $generatedAt->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('d/m/Y H:i');
        $template = $data->template;

        // Datos cortos arriba (tabla); textos largos como secciones.
        $meta = '';
        $sections = '';
        foreach ($template->fields() as $code => $spec) {
            $value = $data->fields[$code] ?? '';
            if ($spec['kind'] === 'text') {
                if ($value === '' && !$spec['required']) {
                    continue;
                }
                if ($code === 'asistentes') {
                    $people = array_values(array_filter(array_map('trim', preg_split('/\R/u', $value) ?: [])));
                    $body = '<ol>' . implode('', array_map(static fn (string $p) => '<li>' . $e($p) . '</li>', $people)) . '</ol>';
                } else {
                    $body = '<p>' . $text($value) . '</p>';
                }
                $sections .= sprintf('<h2>%s</h2>%s', $e($spec['label']), $body);
            } else {
                $shown = $spec['kind'] === 'date' ? $date($value) : $value;
                $meta .= sprintf('<tr><td class="k">%s</td><td>%s</td></tr>', $e($spec['label']), $shown !== '' ? $e($shown) : '—');
            }
        }

        $photoRows = '';
        $cells = [];
        foreach ($photos as $i => $photo) {
            $jpeg = self::shrink($photo['bytes']);
            $img = $jpeg !== null
                ? sprintf('<img src="data:image/jpeg;base64,%s" alt="">', base64_encode($jpeg))
                : '<div class="noimg">Imagen no disponible</div>';
            $cells[] = sprintf('<td class="ph">%s<div class="cap">Foto %d%s</div></td>', $img, $i + 1, $photo['caption'] !== '' ? '. ' . $e($photo['caption']) : '');
        }
        foreach (array_chunk($cells, 2) as $row) { // dos fotos por fila: se leen mejor impresas
            $photoRows .= '<tr>' . implode('', $row) . str_repeat('<td class="ph"></td>', 2 - count($row)) . '</tr>';
        }
        $photoSection = $photoRows !== '' ? '<h2>Registro fotográfico</h2><table class="photos">' . $photoRows . '</table>' : '';

        $title = $template === ReportTemplate::Acta ? 'ACTA' : 'INFORME DE ACTIVIDADES';
        $signature = $template === ReportTemplate::Acta
            ? '<table class="sign"><tr><td><div class="line"></div>Elaboró: ' . $e($context['contractor']) . '<br><span class="muted">Contratista</span></td><td><div class="line"></div>Revisó: ' . $e($context['supervisor'] ?? '') . '<br><span class="muted">Supervisor del contrato</span></td></tr></table>'
            : '<table class="sign"><tr><td><div class="line"></div>' . $e($context['contractor']) . '<br><span class="muted">Contratista</span></td><td></td></tr></table>';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="es"><head><meta charset="UTF-8"><title>{$e($template->label())} — {$e($context['contract_number'])}</title>
        <style>
          @page { margin: 26mm 18mm 20mm 18mm; }
          body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; color: #1f2937; line-height: 1.45; }
          header { position: fixed; top: -18mm; left: 0; right: 0; border-bottom: 2px solid #1b365d; padding-bottom: 4px; }
          header .org { font-size: 10pt; font-weight: bold; color: #1b365d; }
          header .sys { font-size: 8pt; color: #5b6472; }
          footer { position: fixed; bottom: -12mm; left: 0; right: 0; font-size: 7.5pt; color: #5b6472; border-top: 1px solid #d5dae1; padding-top: 3px; padding-right: 32mm; }
          h1 { font-size: 15pt; color: #1b365d; margin: 0 0 8px; text-align: center; letter-spacing: 1px; }
          h2 { font-size: 11pt; color: #1b365d; border-bottom: 1px solid #d5dae1; padding-bottom: 2px; margin: 14px 0 6px; page-break-after: avoid; }
          table.meta { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
          table.meta td { padding: 3px 6px; border: 1px solid #d5dae1; vertical-align: top; }
          table.meta td.k { width: 30%; background: #f3f5f8; color: #5b6472; }
          table.photos { width: 100%; border-collapse: separate; border-spacing: 6px; }
          td.ph { width: 50%; vertical-align: top; page-break-inside: avoid; }
          td.ph img { width: 100%; }
          .cap { font-size: 8pt; color: #5b6472; margin-top: 2px; }
          .noimg { border: 1px dashed #d5dae1; padding: 24px 4px; text-align: center; color: #5b6472; }
          table.sign { width: 100%; margin-top: 36px; page-break-inside: avoid; }
          table.sign td { width: 50%; padding: 0 12px; vertical-align: top; font-size: 9pt; }
          .line { border-top: 1px solid #1f2937; margin-bottom: 4px; height: 36px; }
          .muted { color: #5b6472; }
          .note { font-size: 7.5pt; color: #5b6472; margin-top: 18px; }
        </style></head>
        <body>
        <header><div class="org">ALCALDÍA MUNICIPAL DE JAMUNDÍ · VALLE DEL CAUCA</div><div class="sys">SIGCON · Sistema de Gestión de Contratistas</div></header>
        <footer>Creado en SIGCON el {$e($generated)} (hora de Colombia) · Formato provisional de SIGCON</footer>

        <h1>{$title}</h1>
        <table class="meta">
          <tr><td class="k">Contrato</td><td>{$e($context['contract_number'])}</td></tr>
          <tr><td class="k">Contratista</td><td>{$e($context['contractor'])}</td></tr>
          <tr><td class="k">Supervisor</td><td>{$e($context['supervisor'] ?? 'Sin asignar')}</td></tr>
          <tr><td class="k">Dependencia</td><td>{$e($context['department'])}</td></tr>
          <tr><td class="k">Obligación</td><td>{$e($context['obligation'])}</td></tr>
          <tr><td class="k">Período del informe</td><td>{$e($context['period'])}</td></tr>
          {$meta}
        </table>
        {$sections}
        {$photoSection}
        {$signature}
        <p class="note">Documento creado por el contratista desde su actividad en SIGCON. Formato provisional mientras la Alcaldía adopta su planilla oficial.</p>
        </body></html>
        HTML;
    }

    /** Reduce la foto a JPEG de lado máximo PHOTO_MAX_SIDE. Null si no se puede leer. */
    private static function shrink(string $bytes): ?string
    {
        $source = @imagecreatefromstring($bytes);
        if ($source === false) {
            return null;
        }
        $w = imagesx($source);
        $h = imagesy($source);
        $scale = min(1, self::PHOTO_MAX_SIDE / max($w, $h, 1));
        $target = $source;
        if ($scale < 1) {
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));
            $target = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($target, $source, 0, 0, 0, 0, $nw, $nh, $w, $h);
        }
        ob_start();
        imagejpeg($target, null, 80);

        return (string) ob_get_clean();
    }
}
