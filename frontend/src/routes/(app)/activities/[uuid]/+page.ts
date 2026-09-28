import { session } from '$lib/features/auth/session.svelte';
import { Permission } from '$lib/auth/permissions';
import { getActivity } from '$lib/features/activities/api';
import { listDocuments } from '$lib/features/documents/api';
import { getReport, listReports } from '$lib/features/reports/api';
import type { ReportDetail } from '$lib/types/reports';
import type { PageLoad } from './$types';

/** Estados en los que el contratista todavía puede escribir en el informe (ADR-015). */
const EDITABLE = ['draft', 'observed'];
/** Estados en los que el informe está en manos del supervisor. */
const REVIEW = ['submitted', 'in_review', 'resubmitted'];

export const load: PageLoad = async ({ params, parent, depends }) => {
  await parent();
  depends('app:activities');

  const detail = await getActivity(params.uuid);
  const reports = (
    await listReports({ contract: detail.contract.uuid, per_page: 50, sort: 'period' })
  ).items;

  // Al supervisor le interesa primero lo que debe revisar; al contratista, su informe abierto.
  // Si no hay ninguno, se muestra el último presentado como consulta.
  const reviewer = session.can(Permission.ReportsReview);
  const pending = reports.find((r) => REVIEW.includes(r.status));
  const open = reports.find((r) => EDITABLE.includes(r.status));
  const current = reviewer ? (pending ?? open) : (open ?? pending);
  const chosen = current ?? reports.at(-1);
  const report: ReportDetail | null = chosen ? await getReport(chosen.uuid) : null;
  // Anexos del informe, para previsualizarlos sin salir de la actividad.
  const documents = report ? await listDocuments({ kind: 'report', uuid: report.uuid }) : null;

  return { detail, reports, report, documents };
};
