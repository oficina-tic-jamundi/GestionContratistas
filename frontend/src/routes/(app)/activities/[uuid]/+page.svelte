<script lang="ts">
  import { invalidate } from '$app/navigation';
  import { resolve } from '$app/paths';
  import { Permission } from '$lib/auth/permissions';
  import Icon from '$lib/components/ui/Icon.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import PriorityBadge from '$lib/features/activities/PriorityBadge.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import ActivityReportForm from '$lib/features/reports/ActivityReportForm.svelte';
  import ActivityReportReview from '$lib/features/reports/ActivityReportReview.svelte';
  import ReportAnnexList from '$lib/features/reports/ReportAnnexList.svelte';
  import ReportStatusBadge from '$lib/features/reports/ReportStatusBadge.svelte';
  import type { Activity } from '$lib/types/activities';
  import { formatDate, formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  /**
   * Una actividad con su contexto (ADR-021). El contratista ve qué debe hacer y envía su
   * informe; quien revisa ve lo mismo y, además, el informe del período con sus decisiones.
   * La actividad no se edita aquí: eso se hace desde el contrato.
   */
  let { data }: PageProps = $props();

  const activity = $derived(data.detail.activity);
  const contract = $derived(data.detail.contract);
  const reviewer = $derived(session.can(Permission.ReportsReview));

  const num = (v: string) => Math.max(0, Math.min(100, Number(v) || 0));
  const pct = (v: string) => `${num(v).toLocaleString('es-CO', { maximumFractionDigits: 0 })} %`;
  const state = (item: Activity) =>
    num(item.progress) >= 100 ? 'Terminada' : num(item.progress) > 0 ? 'En curso' : 'Pendiente';

  // Observaciones del supervisor sobre esta obligación en la última revisión.
  const observations = $derived(
    (data.report?.reviews[0]?.observations ?? []).filter(
      (o) => o.activity_uuid === null || o.activity_uuid === data.detail.obligation.uuid
    )
  );

  const refresh = () => invalidate('app:activities');
</script>

<a
  href={resolve('/(app)/activities')}
  class="mb-4 inline-flex items-center gap-1 text-sm text-muted hover:text-ink"
>
  <Icon name="chevron" class="size-4 rotate-90" /> Mis actividades
</a>

<PageHeader
  title={activity.title}
  description="Contrato {contract.contract_number} · {contract.department}"
/>

<div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
  <div class="space-y-6">
    {#if reviewer}
      <section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="que-hacer">
        <h2 id="que-hacer" class="text-lg font-semibold text-ink">Qué debe hacer</h2>
        <p class="mt-2 text-sm whitespace-pre-line text-ink">
          {activity.description ?? 'La Alcaldía no registró una descripción para esta actividad.'}
        </p>

        {#if data.detail.parent}
          <p class="mt-4 text-sm text-muted">
            Hace parte de: <span class="text-ink">{data.detail.parent.title}</span>
          </p>
        {/if}

        {#if data.detail.children.length > 0}
          <h3 class="mt-6 text-sm font-semibold text-ink">Tareas de esta actividad</h3>
          <ul class="mt-2 divide-y divide-border">
            {#each data.detail.children as child (child.uuid)}
              <li class="flex items-center justify-between gap-3 py-2 text-sm">
                <span class="text-ink">{child.title}</span>
                <span class="shrink-0 text-muted tabular-nums"
                  >{state(child)} · {pct(child.progress)}</span
                >
              </li>
            {/each}
          </ul>
        {/if}
      </section>

      {#if observations.length > 0}
        <section
          class="rounded-2xl border border-warning/40 bg-warning-soft p-6"
          aria-labelledby="observaciones"
        >
          <h2 id="observaciones" class="text-lg font-semibold text-ink">
            Observaciones del supervisor
          </h2>
          <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-ink">
            {#each observations as observation, index (index)}
              <li>{observation.text}</li>
            {/each}
          </ul>
          <p class="mt-3 text-xs text-muted">
            {reviewer
              ? 'Son las observaciones de la última revisión de este informe.'
              : 'Corrija lo señalado y vuelva a enviar el informe del período.'}
          </p>
        </section>
      {/if}

      <ActivityReportReview
        obligation={data.detail.obligation}
        report={data.report}
        documents={data.documents}
        onchange={refresh}
      />
    {:else}
      <!-- Para el contratista lo primero es lo que debe hacer ahora: corregir lo observado y
           subir o crear su informe. Los botones siempre están; si no se pueden usar, dicen por qué. -->
      {#if observations.length > 0}
        <section
          class="rounded-2xl border border-warning/40 bg-warning-soft p-6"
          aria-labelledby="observaciones"
        >
          <h2 id="observaciones" class="text-lg font-semibold text-ink">
            Observaciones del supervisor
          </h2>
          <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-ink">
            {#each observations as observation, index (index)}
              <li>{observation.text}</li>
            {/each}
          </ul>
          <p class="mt-3 text-xs text-muted">
            {reviewer
              ? 'Son las observaciones de la última revisión de este informe.'
              : 'Corrija lo señalado y vuelva a enviar el informe del período.'}
          </p>
        </section>
      {/if}

      <ActivityReportForm
        detail={data.detail}
        reports={data.reports}
        report={data.report?.can.edit ? data.report : null}
        documents={data.report?.can.edit ? data.documents : null}
        onchange={refresh}
      />

      {#if data.report && !data.report.can.edit}
        {@const sent = data.report}
        <section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="enviado">
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <h2 id="enviado" class="text-lg font-semibold text-ink">
                {sent.status === 'approved'
                  ? 'Informe aprobado'
                  : sent.status === 'rejected'
                    ? 'Informe rechazado'
                    : 'Informe enviado'}
              </h2>
              <p class="mt-1 text-sm text-muted">
                N.º {sent.number} · {formatDate(sent.period_start)} a {formatDate(sent.period_end)}
              </p>
            </div>
            <ReportStatusBadge status={sent.status} label={sent.status_label} />
          </div>
          <p class="mt-3 text-sm text-muted">
            {#if sent.status === 'approved'}
              Lo aprobó {sent.approved_by ??
                contract.supervisor ??
                'su supervisor'}{sent.approved_at ? ` el ${formatDateTime(sent.approved_at)}` : ''}.
              Con esto la Alcaldía puede tramitar el pago del período.
            {:else if sent.status === 'rejected'}
              Debe presentar un informe nuevo para este período. El motivo está en las observaciones
              del supervisor.
            {:else}
              Está con {contract.supervisor ?? 'su supervisor'}. Podrá editarlo si le hace
              observaciones.
            {/if}
          </p>

          <h3 class="mt-5 text-sm font-semibold text-ink">Lo que reportó en esta actividad</h3>
          <p class="mt-1 text-sm whitespace-pre-line text-ink">
            {sent.content.items.find((i) => i.obligation_uuid === data.detail.obligation.uuid)
              ?.description ?? 'Sin descripción para esta obligación.'}
          </p>

          <h3 class="mt-5 text-sm font-semibold text-ink">Documentos enviados</h3>
          <ReportAnnexList documents={data.documents} empty="El informe se envió sin anexos." />
        </section>
      {/if}

      <section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="que-hacer">
        <h2 id="que-hacer" class="text-lg font-semibold text-ink">Qué debe hacer</h2>
        <p class="mt-2 text-sm whitespace-pre-line text-ink">
          {activity.description ?? 'La Alcaldía no registró una descripción para esta actividad.'}
        </p>

        {#if data.detail.parent}
          <p class="mt-4 text-sm text-muted">
            Hace parte de: <span class="text-ink">{data.detail.parent.title}</span>
          </p>
        {/if}

        {#if data.detail.children.length > 0}
          <h3 class="mt-6 text-sm font-semibold text-ink">Tareas de esta actividad</h3>
          <ul class="mt-2 divide-y divide-border">
            {#each data.detail.children as child (child.uuid)}
              <li class="flex items-center justify-between gap-3 py-2 text-sm">
                <span class="text-ink">{child.title}</span>
                <span class="shrink-0 text-muted tabular-nums"
                  >{state(child)} · {pct(child.progress)}</span
                >
              </li>
            {/each}
          </ul>
        {/if}
      </section>
    {/if}
  </div>

  <aside class="space-y-6">
    <section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="resumen">
      <h2 id="resumen" class="text-lg font-semibold text-ink">Resumen</h2>
      <dl class="mt-3 space-y-3 text-sm">
        <div class="flex items-center justify-between gap-3">
          <dt class="text-muted">Avance</dt>
          <dd class="font-semibold text-ink tabular-nums">{pct(activity.progress)}</dd>
        </div>
        <div class="flex items-center justify-between gap-3">
          <dt class="text-muted">Estado</dt>
          <dd class="text-ink">{state(activity)}</dd>
        </div>
        {#if activity.priority && activity.priority_label}
          <div class="flex items-center justify-between gap-3">
            <dt class="text-muted">Prioridad</dt>
            <dd><PriorityBadge priority={activity.priority} label={activity.priority_label} /></dd>
          </div>
        {/if}
        {#if activity.due_date}
          <div class="flex items-center justify-between gap-3">
            <dt class="text-muted">Fecha de entrega</dt>
            <dd class="text-ink">{formatDate(activity.due_date)}</dd>
          </div>
        {/if}
        <div class="flex items-center justify-between gap-3">
          <dt class="text-muted">Supervisor</dt>
          <dd class="text-ink">{contract.supervisor ?? 'Sin asignar'}</dd>
        </div>
        <div class="flex items-center justify-between gap-3">
          <dt class="text-muted">Actualizada</dt>
          <dd class="text-ink">{formatDateTime(activity.updated_at)}</dd>
        </div>
      </dl>
      <p class="mt-4 text-xs text-muted">
        El avance lo registra su supervisor con base en los informes aprobados.
      </p>
    </section>

    {#if data.reports.length > 0}
      <section
        class="rounded-2xl border border-border bg-surface p-6"
        aria-labelledby="mis-informes"
      >
        <h2 id="mis-informes" class="text-lg font-semibold text-ink">Informes del contrato</h2>
        <ul class="mt-3 space-y-2 text-sm">
          {#each data.reports as item (item.uuid)}
            <li class="flex items-center justify-between gap-3">
              <a
                class="text-ink hover:text-primary"
                href={resolve('/(app)/reports/[uuid]', { uuid: item.uuid })}
              >
                N.º {item.number} · {formatDate(item.period_start)}
              </a>
              <ReportStatusBadge status={item.status} label={item.status_label} />
            </li>
          {/each}
        </ul>
      </section>
    {/if}
  </aside>
</div>
