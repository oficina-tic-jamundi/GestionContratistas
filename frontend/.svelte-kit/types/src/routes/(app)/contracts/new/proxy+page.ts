// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listSupervisors } from '$lib/features/contracts/api';
import { listDepartments } from '$lib/features/departments/api';
import type { PageLoad } from './$types';

export const load = async ({ parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.ContractsManage);

  const [departments, supervisors] = await Promise.all([
    listDepartments('active'),
    listSupervisors()
  ]);
  return { departments, supervisors };
};
