// @ts-nocheck
import { redirect } from '@sveltejs/kit';
import { session } from '$lib/features/auth/session.svelte';
import type { LayoutLoad } from './$types';

/**
 * Todas las páginas del grupo (app) exigen sesión. Si no hay sesión se envía al login
 * conservando el destino; si la contraseña es temporal, solo se permite cambiarla.
 */
export const load = async ({ url }: Parameters<LayoutLoad>[0]) => {
  const profile = await session.ensure();

  if (!profile) {
    redirect(307, `/login?redirect=${encodeURIComponent(url.pathname + url.search)}`);
  }
  if (profile.user.must_change_password && url.pathname !== '/account/password') {
    redirect(307, '/account/password');
  }

  return {};
};
