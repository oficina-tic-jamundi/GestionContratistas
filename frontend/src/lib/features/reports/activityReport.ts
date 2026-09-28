import type { ActivityDetail } from '$lib/types/activities';
import type { ReportDetail, ReportSummary } from '$lib/types/reports';
import { createReport, getReport, saveReport } from './api';
import { suggestPeriod } from './period';

/**
 * Pasos comunes de "Subir informe" y "Crear informe" desde la actividad (ADR-021, ADR-023).
 * Todo queda en el informe del período: lo escrito es la descripción de la obligación y los
 * archivos son anexos del informe.
 */

/** Período en el que caerá el documento: el del informe abierto o el siguiente sugerido. */
export function periodFor(
  detail: ActivityDetail,
  reports: ReportSummary[],
  report: ReportDetail | null
): { start: string; end: string } | null {
  return report
    ? { start: report.period_start, end: report.period_end }
    : suggestPeriod(detail.contract.start_date, detail.contract.end_date, reports);
}

/** Informe abierto del período; se crea si todavía no existe. */
export async function ensureReport(
  detail: ActivityDetail,
  reports: ReportSummary[],
  report: ReportDetail | null
): Promise<ReportDetail> {
  if (report) return report;
  const period = periodFor(detail, reports, report);
  if (!period) throw new Error('No hay un período pendiente para este contrato.');
  return await getReport((await createReport(detail.contract.uuid, period.start, period.end)).uuid);
}

/**
 * Guarda lo realizado como descripción de la obligación. Si ya había texto de otro documento de
 * la misma obligación, se agrega debajo en lugar de reemplazarlo.
 */
export async function saveObligationText(
  report: ReportDetail,
  obligationUuid: string,
  text: string
): Promise<void> {
  const value = text.trim();
  const merge = (current: string | null) =>
    current && current.trim() !== '' && !current.includes(value)
      ? `${current.trim()}\n\n${value}`
      : value;

  const items = report.content.items.map((item) => ({
    obligation: item.obligation_uuid,
    description:
      item.obligation_uuid === obligationUuid ? merge(item.description) : (item.description ?? '')
  }));
  if (!items.some((i) => i.obligation === obligationUuid)) {
    items.push({ obligation: obligationUuid, description: value });
  }

  await saveReport(report.uuid, {
    // El resumen del informe se propone con este texto mientras nadie haya escrito otro.
    summary: report.summary ?? value,
    contractor_notes: report.contractor_notes,
    items
  });
}

/** Formatos del documento creado desde la actividad (espejo de ReportTemplate en el backend). */
export type TemplateCode = 'acta' | 'informe';

export interface TemplateField {
  code: string;
  label: string;
  kind: 'date' | 'time' | 'line' | 'text';
  required: boolean;
  min?: number;
  max: number;
  hint?: string;
}

export const TEMPLATES: Record<
  TemplateCode,
  { label: string; description: string; main: string; fields: TemplateField[] }
> = {
  informe: {
    label: 'Informe de actividades',
    description: 'Lo que ejecutó en la actividad, sus resultados y las dificultades.',
    main: 'actividades',
    fields: [
      { code: 'fecha', label: 'Fecha del informe', kind: 'date', required: true, max: 10 },
      {
        code: 'actividades',
        label: 'Actividades realizadas',
        kind: 'text',
        required: true,
        min: 20,
        max: 10000
      },
      {
        code: 'resultados',
        label: 'Resultados obtenidos',
        kind: 'text',
        required: false,
        max: 5000
      },
      {
        code: 'observaciones',
        label: 'Dificultades u observaciones',
        kind: 'text',
        required: false,
        max: 5000
      }
    ]
  },
  acta: {
    label: 'Acta',
    description: 'Registro de una reunión o jornada: lugar, asistentes, desarrollo y compromisos.',
    main: 'desarrollo',
    fields: [
      { code: 'fecha', label: 'Fecha', kind: 'date', required: true, max: 10 },
      { code: 'hora', label: 'Hora', kind: 'time', required: false, max: 5 },
      { code: 'lugar', label: 'Lugar', kind: 'line', required: true, min: 3, max: 200 },
      { code: 'tema', label: 'Tema u objetivo', kind: 'line', required: true, min: 3, max: 300 },
      {
        code: 'asistentes',
        label: 'Asistentes',
        kind: 'text',
        required: false,
        max: 3000,
        hint: 'Un asistente por línea.'
      },
      {
        code: 'desarrollo',
        label: 'Desarrollo',
        kind: 'text',
        required: true,
        min: 20,
        max: 10000
      },
      { code: 'compromisos', label: 'Compromisos', kind: 'text', required: false, max: 5000 }
    ]
  }
};
