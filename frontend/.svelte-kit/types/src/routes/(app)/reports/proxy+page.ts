// @ts-nocheck
import { error } from '@sveltejs/kit';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listReports, type ReportQuery } from '$lib/features/reports/api';
import type { ReportStatus } from '$lib/types/reports';
import type { PageLoad } from './$types';

export const load = async ({ url, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  if (
    !session.canAny(
      Permission.ContractsViewAll,
      Permission.ContractsViewAssigned,
      Permission.ContractsViewOwn
    )
  ) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }

  const query: ReportQuery = {
    page: Number(url.searchParams.get('page')) || 1,
    status: (url.searchParams.get('status') ?? '') as ReportStatus | 'pending_review' | ''
  };

  return { result: await listReports(query), query };
};
