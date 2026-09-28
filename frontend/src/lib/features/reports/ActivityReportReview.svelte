<script lang="ts">
  import Alert from '$lib/components/ui/Alert.svelte';
  import type { Activity } from '$lib/types/activities';
  import type { DocumentList } from '$lib/types/documents';
  import type { ReportDetail } from '$lib/types/reports';
  import { formatDate, formatDateTime } from '$lib/utils/format';
  import ReportAnnexList from './ReportAnnexList.svelte';
  import ReportStatusBadge from './ReportStatusBadge.svelte';
  import ReviewActions from './ReviewActions.svelte';

  /**
   * Revisión del informe desde la actividad (ADR-021): el supervisor lee lo que el contratista
   * reportó en esta obligación, previsualiza los anexos sin salir de SIGCON y decide.
   * Las decisiones son las mismas del informe (`ReviewActions`): el backend las valida igual.
   */
  let {
    obligation,
    report,
    documents,
    onchange
  }: {
    obligation: Activity;
    report: ReportDetail | null;
    documents: DocumentList | null;
    onchange: () => Promise<void>;
  } = $props();

  const item = $derived(
    report?.content.items.find((i) => i.obligation_uuid === obligation.uuid) ?? null
  );
  const lastReview = $derived(report?.reviews[0] ?? null);
</script>

<section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="revision">
  <div class="flex flex-wrap items-start justify-between gap-3">
    <div>
      <h2 id="revision" class="text-lg font-semibold text-ink">Informe del período</h2>
      {#if report}
        <p class="mt-1 text-sm text-muted">
          N.º {report.number} · {formatDate(report.period_start)} a {formatDate(report.period_end)}
          · versión {report.current_version}
        </p>
      {/if}
    </div>
    {#if report}<ReportStatusBadge status={report.status} label={report.status_label} />{/if}
  </div>

  {#if !report}
    <p class="mt-4 text-sm text-muted">
      El contratista no ha enviado ningún informe de este contrato. Los borradores en elaboración no
      se ven hasta que él los envía; cuando lo haga, podrá leerlo y decidir aquí mismo.
    </p>
  {:else}
    <div class="mt-5 space-y-5">
      <div>
        <h3 class="text-sm font-semibold text-ink">Lo reportado en esta actividad</h3>
        <p
          class="mt-1 text-sm whitespace-pre-line {item?.description
            ? 'text-ink'
            : 'text-muted italic'}"
        >
          {item?.description ?? 'El contratista no describió esta obligación en el período.'}
        </p>
        {#if item}
          <p class="mt-2 text-xs text-muted">Avance reportado: {item.progress} %</p>
        {/if}
      </div>

      {#if report.content.summary}
        <div>
          <h3 class="text-sm font-semibold text-ink">Resumen del período</h3>
          <p class="mt-1 text-sm whitespace-pre-line text-ink">{report.content.summary}</p>
        </div>
      {/if}

      {#if item && item.evidences && item.evidences.length > 0}
        <p class="text-xs text-muted">
          {item.evidences.length}
          {item.evidences.length === 1 ? 'evidencia fotográfica' : 'evidencias fotográficas'} en el período.
          Véalas completas en el informe.
        </p>
      {/if}

      <div>
        <h3 class="text-sm font-semibold text-ink">Documentos anexos</h3>
        <ReportAnnexList {documents} empty="El informe no tiene anexos." />
      </div>

      {#if lastReview}
        <Alert variant={lastReview.decision === 'approved' ? 'success' : 'info'}>
          Última decisión: {lastReview.decision === 'approved'
            ? 'aprobado'
            : lastReview.decision === 'rejected'
              ? 'rechazado'
              : lastReview.decision === 'reopened'
                ? 'reabierto'
                : 'con observaciones'} por {lastReview.reviewer} el {formatDateTime(
            lastReview.created_at
          )}{lastReview.comment ? `: ${lastReview.comment}` : '.'}
        </Alert>
      {/if}

      <div class="border-t border-border pt-4">
        <h3 class="text-sm font-semibold text-ink">Su decisión</h3>
        <p class="mt-1 mb-3 text-xs text-muted">
          Aprobar, solicitar correcciones con las observaciones por obligación, o rechazar indicando
          el motivo. Todo queda registrado con su nombre y fecha.
        </p>
        <ReviewActions {report} {onchange} />
        {#if !report.can.start_review && !report.can.observe && !report.can.approve && !report.can.reject && !report.can.reopen}
          <p class="text-sm text-muted">
            No hay decisiones pendientes: el informe está en manos del contratista o ya quedó en
            firme.
          </p>
        {/if}
      </div>
    </div>
  {/if}
</section>
