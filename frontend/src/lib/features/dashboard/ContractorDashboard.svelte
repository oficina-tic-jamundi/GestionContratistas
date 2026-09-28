<script lang="ts">
  import { resolve } from '$app/paths';
  import Icon, { type IconName } from '$lib/components/ui/Icon.svelte';
  import StatCard from '$lib/components/ui/StatCard.svelte';
  import PriorityBadge from '$lib/features/activities/PriorityBadge.svelte';
  import DeadlineAlerts from '$lib/features/dashboard/DeadlineAlerts.svelte';
  import { getBudget } from '$lib/features/payments/api';
  import type {
    ContractOverview,
    Dashboard,
    DashboardReport,
    WorkStatus
  } from '$lib/types/notifications';
  import { formatDate, formatMoney } from '$lib/utils/format';

  /**
   * Panel del contratista (ADR-021): avance de cada contrato, estado de obligaciones y
   * actividades derivado de los informes, observaciones vigentes y el aviso de pago según las
   * reglas activas. El estado siempre se expresa con texto; el color solo lo refuerza.
   */
  let { dashboard }: { dashboard: Dashboard } = $props();

  const contracts = $derived(dashboard.my_contracts ?? []);
  const pendingReports = $derived(dashboard.reports?.awaiting_my_action ?? []);

  const STATUS: Record<WorkStatus, { text: string; soft: string; dot: string; bar: string }> = {
    approved: {
      text: 'text-success',
      soft: 'bg-success-soft border-success/30',
      dot: 'bg-success',
      bar: 'bg-success'
    },
    in_review: {
      text: 'text-warning',
      soft: 'bg-warning-soft border-warning/30',
      dot: 'bg-warning',
      bar: 'bg-warning'
    },
    observed: {
      text: 'text-attention',
      soft: 'bg-attention-soft border-attention/30',
      dot: 'bg-attention',
      bar: 'bg-attention'
    },
    pending: {
      text: 'text-danger',
      soft: 'bg-danger-soft border-danger/30',
      dot: 'bg-danger',
      bar: 'bg-danger'
    }
  };
  const STATUS_ORDER: {
    key: WorkStatus;
    label: string;
    icon: IconName;
    tone: 'success' | 'warning' | 'attention' | 'neutral';
  }[] = [
    { key: 'approved', label: 'Aprobadas', icon: 'check-list', tone: 'success' },
    { key: 'in_review', label: 'En revisión', icon: 'clock', tone: 'warning' },
    { key: 'observed', label: 'Con observaciones', icon: 'alert', tone: 'attention' },
    { key: 'pending', label: 'Pendientes', icon: 'file', tone: 'neutral' }
  ];

  const num = (v: string | number) => Math.max(0, Math.min(100, Number(v) || 0));
  const pct = (v: string | number) =>
    `${num(v).toLocaleString('es-CO', { maximumFractionDigits: 1 })} %`;
  // Tono del avance: bajo (ámbar), medio (esmeralda), completo (verde).
  const progressTone = (v: string | number) =>
    num(v) >= 100 ? 'text-success' : num(v) >= 50 ? 'text-primary' : 'text-warning';
  const progressFill = (v: string | number) =>
    num(v) >= 100
      ? 'from-[#34d399] to-[#10b981]'
      : num(v) >= 50
        ? 'from-[#10b981] to-[#34d399]'
        : 'from-[#f59e0b] to-[#eab308]';

  function reportAlert(report: DashboardReport): { title: string; text: string; tone: string } {
    return report.status === 'observed'
      ? {
          title: 'Informe con observaciones',
          text: `El supervisor pidió correcciones al informe N.° ${report.number} del contrato ${report.contract_number}. Corríjalo y reenvíelo.`,
          tone: 'border-danger/40 bg-danger-soft text-danger'
        }
      : {
          title: 'Informe sin enviar',
          text: `El informe N.° ${report.number} del contrato ${report.contract_number} está en borrador. Envíelo al supervisor cuando esté completo.`,
          tone: 'border-warning/40 bg-warning-soft text-warning'
        };
  }

  // Donut: circunferencia de un círculo de radio 52.
  const CIRCUMFERENCE = 2 * Math.PI * 52;
</script>

{#snippet statusPill(status: WorkStatus, label: string)}
  <span
    class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-medium {STATUS[
      status
    ].soft} {STATUS[status].text}"
  >
    <span class="size-1.5 rounded-full {STATUS[status].dot}" aria-hidden="true"></span>
    {label}
  </span>
{/snippet}

{#snippet overview(item: ContractOverview)}
  {@const counts = item.counts.obligations}
  {@const items = item.counts.items}

  <!-- Progreso general -->
  <section
    class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"
    aria-labelledby="progreso-{item.contract.uuid}"
  >
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div class="min-w-0">
        <h2 id="progreso-{item.contract.uuid}" class="text-lg font-semibold text-ink">
          Progreso general del contrato
        </h2>
        <p class="mt-0.5 text-sm text-muted">
          <a
            href={resolve('/(app)/contracts/[uuid]', { uuid: item.contract.uuid })}
            class="text-primary hover:underline">{item.contract.contract_number}</a
          >
          · {item.contract.department}
          {#if item.contract.status !== 'active'}
            · <span class="text-warning">{item.contract.status_label}</span>
          {/if}
        </p>
      </div>
      <div class="text-right">
        <p class="text-4xl font-bold tabular-nums {progressTone(item.progress)}">
          {pct(item.progress)}
        </p>
        <p class="text-xs text-muted">completado</p>
      </div>
    </div>

    <div
      class="mt-4 h-3 overflow-hidden rounded-full bg-canvas ring-1 ring-border"
      role="progressbar"
      aria-label="Avance del contrato {item.contract.contract_number}"
      aria-valuemin={0}
      aria-valuemax={100}
      aria-valuenow={num(item.progress)}
      aria-valuetext={pct(item.progress)}
    >
      <div
        class="h-full rounded-full bg-linear-to-r {progressFill(item.progress)}"
        style:width="{num(item.progress)}%"
      ></div>
    </div>

    {#if item.payment_notice}
      {@const missing = Math.max(0, item.payment_notice.required - num(item.progress))}
      <div
        class="mt-5 flex items-center gap-4 rounded-xl border p-4 {item.payment_notice.met
          ? 'border-success/40 bg-success-soft'
          : 'border-danger/40 bg-danger-soft'}"
        role="status"
      >
        <span
          class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl {item
            .payment_notice.met
            ? 'bg-success/15 text-success'
            : 'bg-danger/15 text-danger'}"
          aria-hidden="true"
        >
          <Icon name={item.payment_notice.met ? 'shield' : 'lock'} />
        </span>
        <div class="min-w-0 flex-1">
          {#if item.payment_notice.met}
            <p class="font-semibold text-success">Avance suficiente para pago</p>
            <p class="text-sm text-ink">
              La Alcaldía exige <strong>{item.payment_notice.required} %</strong> de avance para habilitar
              el pago, y usted ya lo cumple. El pago además depende de las demás reglas y de un informe
              aprobado.
            </p>
          {:else}
            <p class="font-semibold text-danger">Pago bloqueado</p>
            <p class="text-sm text-ink">
              La Alcaldía exige <strong>{item.payment_notice.required} %</strong> de avance para
              habilitar el pago. Faltan <strong class="text-danger">{pct(missing)}</strong>.
            </p>
          {/if}
        </div>
      </div>
    {/if}
  </section>

  <!-- Indicadores -->
  <ul class="stagger grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-5">
    <li class="col-span-2 lg:col-span-1">
      <StatCard label="Obligaciones" value={counts.total} icon="clipboard" tone="primary" />
    </li>
    {#each STATUS_ORDER as s (s.key)}
      <li>
        <StatCard label={s.label} value={counts[s.key]} icon={s.icon} tone={s.tone} />
      </li>
    {/each}
  </ul>

  <!-- Avance por obligación -->
  <section
    class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"
    aria-labelledby="avance-{item.contract.uuid}"
  >
    <div class="flex flex-wrap items-baseline justify-between gap-2">
      <h2 id="avance-{item.contract.uuid}" class="text-lg font-semibold text-ink">
        Avance por obligación
      </h2>
      <p class="text-sm text-muted">
        {items.approved} de {items.total} actividades aprobadas
      </p>
    </div>

    {#if item.general_observations.length > 0}
      <div class="mt-4 rounded-xl border border-attention/30 bg-attention-soft px-4 py-3 text-sm">
        {#each item.general_observations as text, i (i)}
          <p>
            <span class="font-semibold text-attention">Observación general:</span>
            <span class="text-ink">{text}</span>
          </p>
        {/each}
      </div>
    {/if}

    <ul class="mt-4 space-y-6">
      {#each item.obligations as obligation (obligation.uuid)}
        <li>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <p class="flex min-w-0 flex-wrap items-center gap-2 font-medium text-ink">
              {obligation.title}
              {#if obligation.priority && obligation.priority_label}
                <PriorityBadge priority={obligation.priority} label={obligation.priority_label} />
              {/if}
            </p>
            <p class="flex items-center gap-2">
              {@render statusPill(obligation.status, obligation.status_label)}
              <span
                class="w-14 text-right text-sm font-semibold tabular-nums {STATUS[obligation.status]
                  .text}">{pct(obligation.progress)}</span
              >
            </p>
          </div>
          <div
            class="mt-2 h-2 overflow-hidden rounded-full bg-canvas"
            role="progressbar"
            aria-label="Avance de {obligation.title}"
            aria-valuemin={0}
            aria-valuemax={100}
            aria-valuenow={num(obligation.progress)}
            aria-valuetext={pct(obligation.progress)}
          >
            <div
              class="h-full rounded-full bg-linear-to-r {progressFill(obligation.progress)}"
              style:width="{num(obligation.progress)}%"
            ></div>
          </div>

          {#if obligation.items.length > 0}
            <ul class="mt-2 flex flex-wrap gap-2" aria-label="Actividades de {obligation.title}">
              {#each obligation.items as activity (activity.uuid)}
                <li
                  class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs {STATUS[
                    activity.status
                  ].soft} {STATUS[activity.status].text}"
                >
                  <span
                    class="size-1.5 rounded-full {STATUS[activity.status].dot}"
                    aria-hidden="true"
                  ></span>
                  {activity.title}
                  <span class="sr-only">: {activity.status_label}, {pct(activity.progress)}</span>
                  {#if activity.priority_label}
                    <span class="sr-only">, prioridad {activity.priority_label.toLowerCase()}</span>
                  {/if}
                </li>
              {/each}
            </ul>
          {/if}

          {#each obligation.observations as text, i (i)}
            <p
              class="mt-2 rounded-xl border border-attention/30 bg-attention-soft px-4 py-2.5 text-sm"
            >
              <span class="font-semibold text-attention">Observación del supervisor:</span>
              <span class="text-ink">{text}</span>
            </p>
          {/each}
        </li>
      {:else}
        <li class="text-sm text-muted">
          Este contrato aún no tiene obligaciones registradas. Solicite a la dependencia que las
          registre.
        </li>
      {/each}
    </ul>
  </section>

  <!-- Estadísticas y pagos -->
  <div class="grid gap-4 lg:grid-cols-3 lg:gap-6">
    <section
      class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6 lg:col-span-2"
      aria-labelledby="estadisticas-{item.contract.uuid}"
    >
      <h2 id="estadisticas-{item.contract.uuid}" class="text-lg font-semibold text-ink">
        Estadísticas detalladas
      </h2>
      <div class="mt-4 grid items-center gap-6 sm:grid-cols-[1fr_auto]">
        <div>
          <p class="mb-3 text-xs font-semibold tracking-wider text-muted uppercase">
            Estado de actividades
          </p>
          <dl class="space-y-3">
            {#each STATUS_ORDER as s (s.key)}
              <div class="flex items-center gap-3">
                <dt class="flex flex-1 items-center gap-2 text-sm text-ink">
                  <span class="size-2 rounded-full {STATUS[s.key].dot}" aria-hidden="true"></span>
                  {s.label}
                </dt>
                <dd class="flex items-center gap-3">
                  <span
                    class="h-1.5 w-24 overflow-hidden rounded-full bg-canvas sm:w-32"
                    aria-hidden="true"
                  >
                    <span
                      class="block h-full rounded-full {STATUS[s.key].bar}"
                      style:width="{items.total === 0 ? 0 : (items[s.key] / items.total) * 100}%"
                    ></span>
                  </span>
                  <span class="w-6 text-right font-semibold tabular-nums {STATUS[s.key].text}"
                    >{items[s.key]}</span
                  >
                </dd>
              </div>
            {/each}
          </dl>
        </div>

        <figure class="flex flex-col items-center">
          <svg
            viewBox="0 0 120 120"
            class="size-36"
            role="img"
            aria-label="Avance del contrato: {pct(item.progress)}"
          >
            <circle
              cx="60"
              cy="60"
              r="52"
              fill="none"
              stroke="var(--color-border)"
              stroke-width="12"
            />
            <circle
              cx="60"
              cy="60"
              r="52"
              fill="none"
              stroke="currentColor"
              stroke-width="12"
              stroke-linecap="round"
              stroke-dasharray={CIRCUMFERENCE}
              stroke-dashoffset={CIRCUMFERENCE * (1 - num(item.progress) / 100)}
              transform="rotate(-90 60 60)"
              class={progressTone(item.progress)}
            />
            <text
              x="60"
              y="60"
              text-anchor="middle"
              dominant-baseline="central"
              class="fill-current text-[22px] font-bold {progressTone(item.progress)}"
              >{Math.round(num(item.progress))}%</text
            >
          </svg>
          <figcaption class="mt-2 text-center text-sm text-muted">
            Progreso global<br />del contrato
          </figcaption>
        </figure>
      </div>
    </section>

    <section
      class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"
      aria-labelledby="pagos-{item.contract.uuid}"
    >
      <h2 id="pagos-{item.contract.uuid}" class="text-lg font-semibold text-ink">Pagos</h2>
      {#await getBudget(item.contract.uuid)}
        <p class="mt-4 text-sm text-muted" role="status">Consultando…</p>
      {:then budget}
        <dl class="mt-4 space-y-2 text-sm">
          <div class="flex justify-between gap-3">
            <dt class="text-muted">Valor del contrato</dt>
            <dd class="font-medium text-ink tabular-nums">{formatMoney(budget.total)}</dd>
          </div>
          <div class="flex justify-between gap-3">
            <dt class="text-muted">Pagado</dt>
            <dd class="font-medium text-success tabular-nums">{formatMoney(budget.paid)}</dd>
          </div>
          <div class="flex justify-between gap-3">
            <dt class="text-muted">En trámite</dt>
            <dd class="font-medium text-ink tabular-nums">
              {formatMoney(String(Number(budget.committed) - Number(budget.paid)))}
            </dd>
          </div>
          <div class="flex justify-between gap-3 border-t border-border pt-2">
            <dt class="text-muted">Saldo por pagar</dt>
            <dd class="font-semibold text-ink tabular-nums">{formatMoney(budget.available)}</dd>
          </div>
        </dl>
      {:catch}
        <p class="mt-4 text-sm text-muted">No fue posible consultar los pagos.</p>
      {/await}
      <p class="mt-4 text-xs text-muted">Termina el {formatDate(item.contract.end_date)}.</p>
      <a
        href={resolve('/(app)/contracts/[uuid]', { uuid: item.contract.uuid })}
        class="mt-3 inline-block text-sm font-medium text-primary hover:underline"
        >Ver pagos y detalle del contrato</a
      >
    </section>
  </div>
{/snippet}

<div class="space-y-6">
  {#if dashboard.deadlines}
    <DeadlineAlerts
      tasks={dashboard.deadlines.tasks}
      withinDays={dashboard.deadlines.within_days}
    />
  {/if}

  {#each pendingReports as report (report.uuid)}
    {@const alert = reportAlert(report)}
    <a
      href={resolve('/(app)/reports/[uuid]', { uuid: report.uuid })}
      class="flex items-start gap-3 rounded-2xl border px-5 py-4 transition hover:brightness-110 {alert.tone}"
    >
      <Icon name="alert" class="mt-0.5 size-5 shrink-0" />
      <span>
        <span class="block font-semibold">{alert.title}</span>
        <span class="block text-sm text-ink">{alert.text}</span>
      </span>
    </a>
  {/each}

  {#each contracts as item (item.contract.uuid)}
    <div class="space-y-4 sm:space-y-6">
      {@render overview(item)}
    </div>
  {:else}
    <div class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">
      No tiene contratos en ejecución. Cuando la Alcaldía active su contrato, aquí verá su avance.
    </div>
  {/each}
</div>
