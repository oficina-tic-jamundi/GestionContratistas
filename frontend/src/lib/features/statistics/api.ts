import { api } from '$lib/api';
import type { PaymentStatistics } from '$lib/types/statistics';

/** Esquema de pagos por contrato, dentro del alcance del usuario (ADR-021). */
export async function getPaymentStatistics(): Promise<PaymentStatistics> {
  return (await api.get<PaymentStatistics>('/statistics/payments')).data;
}
