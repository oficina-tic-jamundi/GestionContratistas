// @ts-nocheck
import { error } from '@sveltejs/kit';
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listPermissions, listRoles } from '$lib/features/roles/api';
import type { PageLoad } from './$types';

export const load = async ({ params, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.RolesView);

  const [roles, permissions] = await Promise.all([listRoles(), listPermissions()]);
  const role = roles.find((r) => r.code === params.code);
  if (!role) error(404, 'El rol no existe.');

  return { role, permissions };
};
