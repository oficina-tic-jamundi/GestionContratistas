// @ts-nocheck
import { fetchHealth } from '$features/system/health';
import { getDashboard } from '$lib/features/notifications/api';
import type { PageLoad } from './$types';

export const load = async ({ parent }: Parameters<PageLoad>[0]) => {
  await parent();
  return {
    // Promesas sin esperar: la página se muestra de inmediato y cada bloque se resuelve con {#await}.
    dashboard: getDashboard(),
    health: fetchHealth()
  };
};
