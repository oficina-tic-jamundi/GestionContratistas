// @ts-nocheck
import { error } from '@sveltejs/kit';
import { ApiError } from '$lib/api';
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listDepartments } from '$lib/features/departments/api';
import { listRoles } from '$lib/features/roles/api';
import { getUser } from '$lib/features/users/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.UsersView);

  try {
    const canAssign = session.can(Permission.UsersAssignRoles, Permission.RolesView);
    const [user, roles, departments] = await Promise.all([
      getUser(params.uuid),
      canAssign ? listRoles() : Promise.resolve([]),
      session.can(Permission.UsersUpdate) ? listDepartments('active') : Promise.resolve([])
    ]);
    return { user, roles, departments };
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) error(404, 'El usuario no existe.');
    throw e;
  }
};
