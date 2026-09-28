// @ts-nocheck
import { error, redirect } from '@sveltejs/kit';
import { resolve } from '$app/paths';
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { getContract, listSupervisors } from '$lib/features/contracts/api';
import { listDepartments } from '$lib/features/departments/api';
import { ApiError } from '$lib/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.ContractsManage);

  try {
    const [contract, departments, supervisors] = await Promise.all([
      getContract(params.uuid),
      listDepartments('active'),
      listSupervisors()
    ]);
    if (!contract.actions.edit) {
      // Solo los borradores se editan; el backend lo impide igualmente.
      redirect(307, resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid }));
    }
    return { contract, departments, supervisors };
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) error(404, 'El contrato no existe.');
    throw e;
  }
};
