// @ts-nocheck
import { redirect } from '@sveltejs/kit';
import { session } from '$lib/features/auth/session.svelte';
import { safeRedirect } from '$lib/utils/redirect';
import type { PageLoad } from './$types';

export const load = async ({ url }: Parameters<PageLoad>[0]) => {
  // Si ya hay una sesión válida, no tiene sentido mostrar el formulario.
  if (await session.ensure().catch(() => null)) {
    redirect(307, safeRedirect(url.searchParams.get('redirect')));
  }

  return {
    expired: url.searchParams.has('expired'),
    redirectTo: safeRedirect(url.searchParams.get('redirect'))
  };
};
