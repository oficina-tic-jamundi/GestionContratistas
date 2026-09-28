// @ts-nocheck
import { error } from '@sveltejs/kit';
import { ApiError } from '$lib/api';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { getActivityTree } from '$lib/features/activities/api';
import { listContractDocuments } from '$lib/features/documents/api';
import { getContract, getContractHistory, listSupervisors } from '$lib/features/contracts/api';
import { listReports } from '$lib/features/reports/api';
import { listEvidences } from '$lib/features/evidence/api';
import { getBudget, listPayments } from '$lib/features/payments/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  depends('app:contract');

  try {
    const [
      contract,
      history,
      supervisors,
      activities,
      documents,
      reports,
      evidences,
      budget,
      payments
    ] = await Promise.all([
      getContract(params.uuid),
      getContractHistory(params.uuid),
      session.can(Permission.ContractsManage) ? listSupervisors() : Promise.resolve([]),
      getActivityTree(params.uuid),
      listContractDocuments(params.uuid),
      listReports({ contract: params.uuid, per_page: 100, sort: 'period', direction: 'desc' }),
      listEvidences(params.uuid),
      getBudget(params.uuid),
      listPayments({ contract: params.uuid, per_page: 100 })
    ]);
    return {
      contract,
      history,
      supervisors,
      activities,
      documents,
      reports: reports.items,
      evidences,
      budget,
      payments: payments.items
    };
  } catch (e) {
    // Fuera de alcance o inexistente: el backend responde 404 en ambos casos.
    if (e instanceof ApiError && (e.status === 404 || e.status === 403)) {
      error(404, 'El contrato no existe o no tiene acceso a él.');
    }
    throw e;
  }
};
