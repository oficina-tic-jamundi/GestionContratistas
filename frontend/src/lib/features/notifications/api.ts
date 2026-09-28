import { api } from '$lib/api';
import { toPaginated } from '$lib/api/pagination';
import type { Paginated } from '$lib/types/api';
import type {
  AppNotification,
  Dashboard,
  HistoryEvent,
  HistoryKind
} from '$lib/types/notifications';

export async function listNotifications(query: {
  page?: number;
  unread?: boolean;
}): Promise<Paginated<AppNotification>> {
  return toPaginated(
    await api.get<AppNotification[]>('/notifications', {
      query: { page: query.page, unread: query.unread ? 1 : undefined }
    })
  );
}

export async function unreadCount(): Promise<number> {
  return (await api.get<{ unread: number }>('/notifications/unread-count')).data.unread;
}

export async function markRead(uuid: string): Promise<void> {
  await api.post(`/notifications/${encodeURIComponent(uuid)}/read`);
}

export async function markAllRead(): Promise<void> {
  await api.post('/notifications/read-all');
}

export async function getDashboard(): Promise<Dashboard> {
  return (await api.get<Dashboard>('/dashboard')).data;
}

/** Historial cronológico con el alcance del usuario: propio, supervisado o todo (ADR-021). */
export async function listHistory(query: {
  page?: number;
  type?: HistoryKind;
}): Promise<Paginated<HistoryEvent>> {
  return toPaginated(
    await api.get<HistoryEvent[]>('/history', {
      query: { page: query.page, type: query.type }
    })
  );
}

const base = import.meta.env.VITE_API_BASE_URL || '/api/v1';

/** Descargas CSV (archivo de la API; no pasan por resolve()). */
export function exportUrl(kind: 'contracts' | 'payments', status?: string): string {
  return `${base}/exports/${kind}${status ? `?status=${encodeURIComponent(status)}` : ''}`;
}
