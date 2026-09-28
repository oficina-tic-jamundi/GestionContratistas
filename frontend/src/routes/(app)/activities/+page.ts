import { getActivityTree } from '$lib/features/activities/api';
import { listContracts } from '$lib/features/contracts/api';
import type { PageLoad } from './$types';

// Contratos en ejecución primero; los cerrados quedan al final, solo para consulta.
const ORDER = ['active', 'suspended', 'terminated', 'liquidated', 'archived', 'draft'];

export const load: PageLoad = async ({ parent, depends }) => {
  await parent();
  depends('app:activities');

  // El backend limita el listado a los contratos propios del contratista.
  const contracts = (await listContracts({ per_page: 50, sort: 'end_date', direction: 'desc' }))
    .items;
  contracts.sort((a, b) => ORDER.indexOf(a.status) - ORDER.indexOf(b.status));

  return {
    sections: await Promise.all(
      contracts.map(async (contract) => ({ contract, tree: await getActivityTree(contract.uuid) }))
    )
  };
};
