// @ts-nocheck
import { listNotifications } from '$lib/features/notifications/api';
import type { PageLoad } from './$types';

export const load = async ({ url, parent, depends }: Parameters<PageLoad>[0]) => {
  await parent();
  depends('app:notifications');
  const page = Number(url.searchParams.get('page')) || 1;
  const unreadOnly = url.searchParams.get('unread') === '1';
  return { result: await listNotifications({ page, unread: unreadOnly }), unreadOnly };
};
