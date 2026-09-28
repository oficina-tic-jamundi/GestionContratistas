// @ts-nocheck
import { error } from '@sveltejs/kit';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listPaymentRules } from '$lib/features/payments/api';
import type { PageLoad } from './$types';

export const load = async ({ parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  if (
    !session.canAny(
      Permission.PaymentsConfigure,
      Permission.PaymentsManage,
      Permission.PaymentsApprove
    )
  ) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }
  depends('app:payment-rules');

  return { rules: await listPaymentRules() };
};
