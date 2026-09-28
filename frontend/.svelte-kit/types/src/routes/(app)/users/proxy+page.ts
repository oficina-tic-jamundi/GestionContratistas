// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listRoles } from '$lib/features/roles/api';
import { listUsers, type UserQuery } from '$lib/features/users/api';
import type { PageLoad } from './$types';

export const load = async ({ url, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.UsersView);

  const params = url.searchParams;
  const query: UserQuery = {
    page: Number(params.get('page')) || 1,
    search: params.get('search') ?? '',
    status: params.get('status') ?? '',
    role: params.get('role') ?? '',
    sort: params.get('sort') ?? 'name',
    direction: params.get('direction') === 'desc' ? 'desc' : 'asc'
  };

  const [result, roles] = await Promise.all([
    listUsers(query),
    session.can(Permission.RolesView) ? listRoles() : Promise.resolve([])
  ]);

  return { result, roles, query };
};
