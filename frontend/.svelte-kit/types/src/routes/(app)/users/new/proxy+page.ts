// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listDepartments } from '$lib/features/departments/api';
import { listRoles } from '$lib/features/roles/api';
import type { PageLoad } from './$types';

export const load = async ({ parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.UsersCreate);

  const canAssign = session.can(Permission.UsersAssignRoles, Permission.RolesView);
  const [roles, departments] = await Promise.all([
    canAssign ? listRoles() : Promise.resolve([]),
    listDepartments('active')
  ]);
  return { roles, departments };
};
