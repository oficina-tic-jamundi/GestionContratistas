// @ts-nocheck
import { error } from '@sveltejs/kit';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { getPaymentStatistics } from '$lib/features/statistics/api';
import type { PageLoad } from './$types';

export const load = async ({ parent }: Parameters<PageLoad>[0]) => {
  await parent();
  // Las estadísticas de pagos son para la administración y la supervisión (ADR-021).
  if (!session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }

  return { statistics: await getPaymentStatistics() };
};
