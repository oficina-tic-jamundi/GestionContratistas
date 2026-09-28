// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listDepartments } from '$lib/features/departments/api';
import type { PageLoad } from './$types';

export const load = async ({ parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.DepartmentsView);
  depends('app:departments');

  return { departments: await listDepartments() };
};
