// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listPermissions } from '$lib/features/roles/api';
import type { PageLoad } from './$types';

export const load = async ({ parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.RolesManage);

  return { permissions: await listPermissions() };
};
