<script lang="ts">
  import { goto, invalidate } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import Button from '$lib/components/ui/Button.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import { markAllRead, markRead } from '$lib/features/notifications/api';
  import { unread } from '$lib/features/notifications/unread.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { AppNotification } from '$lib/types/notifications';
  import { buildQuery, formatDateTime } from '$lib/utils/format';
  import { safeRedirect } from '$lib/utils/redirect';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  function hrefFor(page: number): ResolvedPathname {
    return resolve(
      `/notifications?${buildQuery({ page: page === 1 ? null : page, unread: data.unreadOnly ? 1 : null })}`
    );
  }

  async function open(notification: AppNotification): Promise<void> {
    if (!notification.read) {
      await markRead(notification.uuid);
      void unread.refresh();
    }
    if (notification.link) {
      // El enlace viene del servidor, pero se valida igual (solo rutas internas). Es una ruta de
      // la aplicación sin la ruta base del despliegue: resolve() la antepone. El tipo de resolve()
      // solo admite rutas conocidas en compilación, de ahí la conversión.
      await goto(resolve(safeRedirect(notification.link) as '/'));
    } else {
      await invalidate('app:notifications');
    }
  }

  async function readAll(): Promise<void> {
    await markAllRead();
    await Promise.all([invalidate('app:notifications'), unread.refresh()]);
    toasts.show('Notificaciones marcadas como leídas.');
  }
</script>

<PageHeader
  title="Notificaciones"
  description="Avisos sobre informes, pagos y tareas que le conciernen."
>
  {#snippet actions()}
    <Button
      variant="secondary"
      href={resolve(data.unreadOnly ? '/notifications' : '/notifications?unread=1')}
      >{data.unreadOnly ? 'Ver todas' : 'Solo sin leer'}</Button
    >
    <Button variant="secondary" onclick={readAll}>Marcar todas como leídas</Button>
  {/snippet}
</PageHeader>

<ul
  class="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-surface shadow-card"
>
  {#each data.result.items as notification (notification.uuid)}
    <li>
      <button
        type="button"
        class="flex w-full gap-3 px-4 py-3 text-left text-sm hover:bg-canvas"
        onclick={() => open(notification)}
      >
        <span
          class="mt-1.5 size-2 shrink-0 rounded-full {notification.read
            ? 'bg-transparent'
            : 'bg-primary'}"
          aria-hidden="true"
        ></span>
        <span class="min-w-0 flex-1">
          <span class="block {notification.read ? 'text-ink' : 'font-semibold text-ink'}">
            {notification.title}
            {#if !notification.read}<span class="sr-only">(sin leer)</span>{/if}
          </span>
          {#if notification.body}<span class="block text-muted">{notification.body}</span>{/if}
          <span class="mt-0.5 block text-xs text-muted"
            >{formatDateTime(notification.created_at)}</span
          >
        </span>
      </button>
    </li>
  {:else}
    <li class="px-4 py-8 text-center text-sm text-muted">
      {data.unreadOnly ? 'No tiene notificaciones sin leer.' : 'No tiene notificaciones.'}
    </li>
  {/each}
</ul>

<Pagination pagination={data.result.pagination} {hrefFor} />
