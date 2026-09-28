// @ts-nocheck
import { error } from '@sveltejs/kit';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listContracts, type ContractQuery } from '$lib/features/contracts/api';
import { listDepartments } from '$lib/features/departments/api';
import type { PageLoad } from './$types';

export const load = async ({ url, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  if (
    !session.canAny(
      Permission.ContractsViewAll,
      Permission.ContractsViewAssigned,
      Permission.ContractsViewOwn
    )
  ) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }

  const params = url.searchParams;
  const query: ContractQuery = {
    page: Number(params.get('page')) || 1,
    search: params.get('search') ?? '',
    status: params.get('status') ?? '',
    department: params.get('department') ?? '',
    ending_before: params.get('ending_before') ?? ''
  };

  const canFilterDepartments = session.canAny(
    Permission.DepartmentsView,
    Permission.ContractsManage,
    Permission.UsersView
  );
  const [result, departments] = await Promise.all([
    listContracts(query),
    canFilterDepartments ? listDepartments() : Promise.resolve([])
  ]);

  return { result, departments, query };
};
