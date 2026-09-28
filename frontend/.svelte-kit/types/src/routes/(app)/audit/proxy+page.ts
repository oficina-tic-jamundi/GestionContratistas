// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listAuditActions, listAuditLogs, type AuditQuery } from '$lib/features/audit/api';
import type { PageLoad } from './$types';

const FILTERS = ['action', 'user', 'entity_type', 'entity_id', 'from', 'to'] as const;

export const load = async ({ url, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.AuditView);

  const query: AuditQuery = { page: Number(url.searchParams.get('page')) || 1 };
  for (const key of FILTERS) {
    const value = url.searchParams.get(key);
    if (value) query[key] = value;
  }

  const [result, actions] = await Promise.all([listAuditLogs(query), listAuditActions()]);
  return { result, actions, query };
};
