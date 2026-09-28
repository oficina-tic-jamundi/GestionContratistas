// @ts-nocheck
import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { getQueueStatus, listJobs, type JobQuery } from '$lib/features/jobs/api';
import type { JobStatus } from '$lib/types/jobs';
import type { PageLoad } from './$types';

export const load = async ({ url, parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  requirePermission(Permission.JobsManage);
  depends('app:jobs');

  const query: JobQuery = {
    page: Number(url.searchParams.get('page')) || 1,
    status: (url.searchParams.get('status') ?? '') as JobStatus | ''
  };
  const [result, status] = await Promise.all([listJobs(query), getQueueStatus()]);
  return { result, status, query };
};
