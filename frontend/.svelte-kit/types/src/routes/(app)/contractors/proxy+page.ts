// @ts-nocheck
import { requireAnyPermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { listContractors, type ContractorQuery } from '$lib/features/contractors/api';
import type { PageLoad } from './$types';

export const load = async ({ url, parent }: Parameters<PageLoad>[0]) => {
  await parent();
  // La administración ve todos; el supervisor, solo los de los contratos que supervisa (ADR-021).
  requireAnyPermission(Permission.ContractorsView, Permission.ContractsViewAssigned);

  const query: ContractorQuery = {
    page: Number(url.searchParams.get('page')) || 1,
    search: url.searchParams.get('search') ?? '',
    status: url.searchParams.get('status') ?? ''
  };

  return { result: await listContractors(query), query };
};
