// @ts-nocheck
import { error } from '@sveltejs/kit';
import { ApiError } from '$lib/api';
import { getPayment, getPaymentHistory } from '$lib/features/payments/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  depends('app:payment');

  try {
    const [payment, history] = await Promise.all([
      getPayment(params.uuid),
      getPaymentHistory(params.uuid)
    ]);
    return { payment, history };
  } catch (e) {
    if (e instanceof ApiError && (e.status === 404 || e.status === 403)) {
      error(404, 'El pago no existe o no tiene acceso a él.');
    }
    throw e;
  }
};
