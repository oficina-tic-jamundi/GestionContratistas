import { listHistory } from '$lib/features/notifications/api';
import type { HistoryKind } from '$lib/types/notifications';
import type { PageLoad } from './$types';

const KINDS: HistoryKind[] = ['progress', 'evidence', 'report', 'payment', 'contract'];

export const load: PageLoad = async ({ url, parent }) => {
  await parent();
  const page = Number(url.searchParams.get('page')) || 1;
  const raw = url.searchParams.get('type');
  const type = KINDS.find((k) => k === raw);
  return { result: await listHistory({ page, type }), type: type ?? null };
};
