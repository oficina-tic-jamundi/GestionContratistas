// @ts-nocheck
import { error } from '@sveltejs/kit';
import { ApiError } from '$lib/api';
import { listDocuments } from '$lib/features/documents/api';
import { getReport, getReportHistory, getReportVersion } from '$lib/features/reports/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  depends('app:report');

  try {
    const [report, history, documents] = await Promise.all([
      getReport(params.uuid),
      getReportHistory(params.uuid),
      listDocuments({ kind: 'report', uuid: params.uuid })
    ]);
    // Si no se puede editar, se muestra lo que realmente se envió (la última versión congelada).
    const frozen =
      !report.can.edit && report.current_version > 0
        ? await getReportVersion(params.uuid, report.current_version)
        : null;
    return { report, history, documents, frozen };
  } catch (e) {
    if (e instanceof ApiError && (e.status === 404 || e.status === 403)) {
      error(404, 'El informe no existe o no tiene acceso a él.');
    }
    throw e;
  }
};
