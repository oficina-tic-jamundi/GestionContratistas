<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import Icon, { type IconName } from '$lib/components/ui/Icon.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import { Permission } from '$lib/auth/permissions';
  import { session } from '$lib/features/auth/session.svelte';
  import type { HistoryEvent, HistoryKind } from '$lib/types/notifications';
  import { buildQuery, formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  /** Historial del contratista (ADR-021): lo que ocurrió en sus contratos, del más reciente al más antiguo. */
  let { data }: PageProps = $props();

  const FILTERS: { value: string; label: string }[] = [
    { value: 'progress', label: 'Avances' },
    { value: 'evidence', label: 'Evidencias' },
    { value: 'report', label: 'Informes' },
    { value: 'payment', label: 'Pagos' },
    { value: 'contract', label: 'Contrato' }
  ];

  const ICONS: Record<HistoryKind, { icon: IconName; tone: string }> = {
    progress: { icon: 'chart', tone: 'bg-primary-soft text-primary' },
    evidence: { icon: 'camera', tone: 'bg-info-soft text-info' },
    report: { icon: 'clipboard', tone: 'bg-warning-soft text-warning' },
    payment: { icon: 'wallet', tone: 'bg-success-soft text-success' },
    contract: { icon: 'shield', tone: 'bg-canvas text-muted' }
  };

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/history?${buildQuery({ page: page === 1 ? null : page, type: data.type })}`);
  }

  async function filter(value: string): Promise<void> {
    await goto(resolve(`/history?${buildQuery({ type: value || null })}`));
  }

  // Lo que ve cada rol lo decide el backend (alcance); aquí solo se explica.
  const scopeDescription = $derived(
    session.can(Permission.ContractsViewAll)
      ? 'Todo lo que ocurre en los contratos: avances y evidencias de los contratistas, revisiones de los supervisores y pagos.'
      : session.can(Permission.ContractsViewAssigned)
        ? 'Lo que ocurre en los contratos que usted supervisa: avances y evidencias de sus contratistas y sus propias revisiones.'
        : 'Sus avances, evidencias e informes, y lo que el supervisor decide sobre ellos.'
  );

  const pct = (v: string) => `${Number(v).toLocaleString('es-CO', { maximumFractionDigits: 2 })} %`;

  function detail(event: HistoryEvent): string | null {
    if (event.kind === 'progress' && event.from_progress !== null && event.to_progress !== null) {
      return `${event.title}: ${pct(event.from_progress)} → ${pct(event.to_progress)}`;
    }
    if (event.kind === 'report' && event.ref_number !== null)
      return `Informe N.° ${event.ref_number}`;
    if (event.kind === 'payment' && event.ref_number !== null)
      return `Pago N.° ${event.ref_number}`;
    return event.title;
  }
</script>

<PageHeader title="Historial" description={scopeDescription} />

<div class="mb-6 max-w-xs">
  <SelectField
    label="Mostrar"
    value={data.type ?? ''}
    placeholder="Todos los eventos"
    options={FILTERS}
    onchange={(e) => filter((e.currentTarget as HTMLSelectElement).value)}
  />
</div>

<ol class="space-y-3">
  {#each data.result.items as event, i (i)}
    <li class="flex gap-4 rounded-2xl border border-border bg-surface p-4">
      <span
        class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl {ICONS[
          event.kind
        ].tone}"
        aria-hidden="true"
      >
        <Icon name={ICONS[event.kind].icon} />
      </span>
      <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
          <p class="font-semibold text-ink">{event.summary}</p>
          <time class="text-xs text-muted" datetime={event.occurred_at}
            >{formatDateTime(event.occurred_at)}</time
          >
        </div>
        {#if detail(event)}
          <p class="mt-0.5 text-sm text-ink">
            {#if event.target?.type === 'report'}
              <a
                href={resolve('/(app)/reports/[uuid]', { uuid: event.target.uuid })}
                class="text-primary hover:underline">{detail(event)}</a
              >
            {:else if event.target?.type === 'payment'}
              <a
                href={resolve('/(app)/payments/[uuid]', { uuid: event.target.uuid })}
                class="text-primary hover:underline">{detail(event)}</a
              >
            {:else}
              {detail(event)}
            {/if}
          </p>
        {/if}
        {#if event.comment}
          <p class="mt-1 text-sm whitespace-pre-line text-muted">{event.comment}</p>
        {/if}
        <p class="mt-1 text-xs text-muted">
          {event.kind_label} ·
          <a
            href={resolve('/(app)/contracts/[uuid]', { uuid: event.contract.uuid })}
            class="text-primary hover:underline">Contrato {event.contract.contract_number}</a
          >
          · {event.contract.contractor}
          {#if event.actor}· <span class="text-ink">{event.actor}</span>{/if}
        </p>
      </div>
    </li>
  {:else}
    <li class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">
      {data.type ? 'No hay eventos de este tipo.' : 'Aún no hay eventos en sus contratos.'}
    </li>
  {/each}
</ol>

<div class="mt-6">
  <Pagination pagination={data.result.pagination} {hrefFor} />
</div>
