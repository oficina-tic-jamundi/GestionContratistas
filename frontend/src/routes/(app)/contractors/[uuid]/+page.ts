import { error } from '@sveltejs/kit';
import { ApiError } from '$lib/api';
import { requireAnyPermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { getActivityTree } from '$lib/features/activities/api';
import { getContractor } from '$lib/features/contractors/api';
import { listContracts } from '$lib/features/contracts/api';
import { listReports } from '$lib/features/reports/api';
import { listUsers } from '$lib/features/users/api';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ params, parent, depends }) => {
  await parent();
  requireAnyPermission(Permission.ContractorsView, Permission.ContractsViewAssigned);
  depends('app:contractor');

  try {
    const [contractor, contracts, accounts] = await Promise.all([
      getContractor(params.uuid),
      listContracts({ contractor: params.uuid, per_page: 50 }),
      session.can(Permission.ContractorsManage, Permission.UsersView)
        ? listUsers({ role: 'contractor', status: 'active', per_page: 100 }).then((r) => r.items)
        : Promise.resolve([])
    ]);

    // Por cada contrato: sus actividades y sus informes, para ver avance y entregables.
    const files = await Promise.all(
      contracts.items.map(async (contract) => ({
        contract,
        tree: await getActivityTree(contract.uuid),
        reports: (
          await listReports({
            contract: contract.uuid,
            per_page: 50,
            sort: 'period',
            direction: 'desc'
          })
        ).items
      }))
    );

    return { contractor, contracts, accounts, files };
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) error(404, 'El contratista no existe.');
    throw e;
  }
};
