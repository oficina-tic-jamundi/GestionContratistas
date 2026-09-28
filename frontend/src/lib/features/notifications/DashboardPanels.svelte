<script lang="ts">
  import { resolve } from '$app/paths';
  import { Permission } from '$lib/auth/permissions';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import DownloadLink from '$lib/components/ui/DownloadLink.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import DeadlineAlerts from '$lib/features/dashboard/DeadlineAlerts.svelte';
  import type { Dashboard, DashboardReport, StatusCount } from '$lib/types/notifications';
  import { formatDate, formatDateTime, formatMoney } from '$lib/utils/format';
  import { exportUrl } from './api';

  /** Tablero de inicio: cifras y pendientes calculados por el backend según el alcance. */
  let { dashboard }: { dashboard: Dashboard } = $props();

  const nonZero = (items: StatusCount[]) => items.filter((i) => i.count > 0);

  /**
   * Semáforo de entregables: agrupa los informes por el estado en que los ve la supervisión
   * (ADR-021). Las cifras son las mismas del backend, solo agrupadas.
   */
  const SEMAPHORE: {
    label: string;
    statuses: string[];
    text: string;
    dot: string;
    soft: string;
  }[] = [
    {
      label: 'Revisados',
      statuses: ['approved'],
      text: 'text-success',
      dot: 'bg-success',
      soft: 'border-success/30 bg-success-soft'
    },
    {
      label: 'En revisión',
      statuses: ['in_review'],
      text: 'text-warning',
      dot: 'bg-warning',
      soft: 'border-warning/30 bg-warning-soft'
    },
    {
      label: 'Con observaciones',
      statuses: ['observed'],
      text: 'text-attention',
      dot: 'bg-attention',
      soft: 'border-attention/30 bg-attention-soft'
    },
    {
      label: 'Pendientes por revisar',
      statuses: ['submitted', 'resubmitted'],
      text: 'text-danger',
      dot: 'bg-danger',
      soft: 'border-danger/30 bg-danger-soft'
    }
  ];

  const semaphore = $derived.by(() => {
    const byStatus = Object.fromEntries(
      (dashboard.reports?.by_status ?? []).map((s) => [s.status, s.count])
    );
    return SEMAPHORE.map((group) => ({
      ...group,
      count: group.statuses.reduce((total, status) => total + (byStatus[status] ?? 0), 0)
    }));
  });
</script>

{#snippet reportList(items: DashboardReport[], empty: string)}
  <ul class="divide-y divide-border">
    {#each items as report (report.uuid)}
      <li>
        <a
          href={resolve('/(app)/reports/[uuid]', { uuid: report.uuid })}
          class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm hover:text-primary"
        >
          <span>
            <span class="font-medium">{report.contract_number} · Informe N.° {report.number}</span>
            <span class="block text-xs text-muted"
              >{report.contractor} · {report.status_label} · {formatDateTime(
                report.updated_at
              )}</span
            >
          </span>
        </a>
      </li>
    {:else}
      <li class="py-2 text-sm text-muted">{empty}</li>
    {/each}
  </ul>
{/snippet}

{#snippet counts(items: StatusCount[], empty: string)}
  {#if nonZero(items).length > 0}
    <ul class="flex flex-wrap gap-2 text-sm">
      {#each nonZero(items) as item (item.status)}
        <li class="rounded-md border border-border px-3 py-1.5">
          <span class="text-muted">{item.label}:</span>
          <span class="font-semibold">{item.count}</span>
        </li>
      {/each}
    </ul>
  {:else}
    <p class="text-sm text-muted">{empty}</p>
  {/if}
{/snippet}

<div class="space-y-6">
  {#if dashboard.deadlines}
    <DeadlineAlerts
      tasks={dashboard.deadlines.tasks}
      withinDays={dashboard.deadlines.within_days}
      showContractor={true}
    />
  {/if}

  {#if dashboard.reports}
    <Card title="Estado de los entregables" description="Informes por estado de revisión.">
      <ul class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        {#each semaphore as group (group.label)}
          <li class="rounded-xl border p-4 {group.soft}">
            <p class="text-3xl font-bold tabular-nums {group.text}">{group.count}</p>
            <p class="mt-1 flex items-center gap-1.5 text-sm text-muted">
              <span class="size-2 shrink-0 rounded-full {group.dot}" aria-hidden="true"></span>
              {group.label}
            </p>
          </li>
        {/each}
      </ul>
    </Card>
  {/if}

  {#if dashboard.system && (dashboard.system.jobs_stale || dashboard.system.jobs_failed > 0)}
    <Alert variant="danger">
      {#if dashboard.system.jobs_stale}El procesador de tareas en segundo plano (Cron) no se está
        ejecutando.
      {/if}
      {#if dashboard.system.jobs_failed > 0}Hay {dashboard.system.jobs_failed} tarea(s) fallida(s).{/if}
      <a class="font-medium underline" href={resolve('/jobs')}>Revisar tareas programadas</a>
    </Alert>
  {/if}

  {#if dashboard.reports?.awaiting_my_action || dashboard.reports?.awaiting_my_review || dashboard.payments?.to_approve || dashboard.payments?.to_register}
    <Card title="Pendientes">
      <div class="space-y-4">
        {#if dashboard.reports?.awaiting_my_action}
          <section>
            <h3 class="text-sm font-semibold text-ink">Informes por completar o corregir</h3>
            {@render reportList(
              dashboard.reports.awaiting_my_action,
              'No tiene informes pendientes.'
            )}
          </section>
        {/if}
        {#if dashboard.reports?.awaiting_my_review}
          <section>
            <h3 class="text-sm font-semibold text-ink">Informes por revisar</h3>
            {@render reportList(
              dashboard.reports.awaiting_my_review,
              'No hay informes esperando su revisión.'
            )}
          </section>
        {/if}
        {#if dashboard.payments?.to_approve}
          <a
            class="block rounded-md border border-border p-3 text-sm hover:border-primary"
            href={resolve('/payments?status=ready_for_approval')}
          >
            <span class="font-semibold">{dashboard.payments.to_approve}</span> pago(s) listos para aprobación
          </a>
        {/if}
        {#if dashboard.payments?.to_register}
          <a
            class="block rounded-md border border-border p-3 text-sm hover:border-primary"
            href={resolve('/payments?status=approved')}
          >
            <span class="font-semibold">{dashboard.payments.to_register}</span> pago(s) aprobados por
            registrar como pagados
          </a>
        {/if}
      </div>
    </Card>
  {/if}

  {#if dashboard.contracts}
    <Card title="Contratos">
      <div class="space-y-4">
        {@render counts(dashboard.contracts.by_status, 'No hay contratos en su alcance.')}
        <section>
          <h3 class="text-sm font-semibold text-ink">
            Terminan en los próximos {dashboard.contracts.ending_within_days} días
          </h3>
          <ul class="divide-y divide-border">
            {#each dashboard.contracts.ending_soon as contract (contract.uuid)}
              <li>
                <a
                  href={resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid })}
                  class="flex flex-wrap justify-between gap-2 py-2 text-sm hover:text-primary"
                >
                  <span class="font-medium">{contract.contract_number}</span>
                  <span class="text-muted"
                    >{contract.contractor} · termina {formatDate(contract.end_date)}</span
                  >
                </a>
              </li>
            {:else}
              <li class="py-2 text-sm text-muted">Ningún contrato activo termina en ese plazo.</li>
            {/each}
          </ul>
        </section>
      </div>
    </Card>
  {/if}

  {#if dashboard.reports || dashboard.payments}
    <div class="grid gap-6 sm:grid-cols-2">
      {#if dashboard.reports}
        <Card title="Informes">
          {@render counts(dashboard.reports.by_status, 'Aún no hay informes.')}
        </Card>
      {/if}
      {#if dashboard.payments}
        <Card title="Pagos">
          {#if dashboard.payments.by_status.some((p) => p.count > 0)}
            <dl class="space-y-1 text-sm">
              {#each dashboard.payments.by_status.filter((p) => p.count > 0) as item (item.status)}
                <div class="flex justify-between gap-2">
                  <dt class="text-muted">{item.label} ({item.count})</dt>
                  <dd class="font-medium">{formatMoney(item.amount)}</dd>
                </div>
              {/each}
            </dl>
          {:else}
            <p class="text-sm text-muted">Aún no hay pagos.</p>
          {/if}
        </Card>
      {/if}
    </div>
  {/if}

  {#if session.can(Permission.ExportsRun)}
    <Card
      title="Reportes"
      description="Archivos CSV para Excel, dentro de su alcance. Cada descarga queda en la auditoría."
    >
      <div class="flex flex-wrap gap-2">
        <DownloadLink href={exportUrl('contracts')}>Contratos con avance y saldo</DownloadLink>
        <DownloadLink href={exportUrl('payments')}>Pagos</DownloadLink>
      </div>
    </Card>
  {/if}
</div>
