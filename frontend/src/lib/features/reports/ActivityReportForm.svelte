<script lang="ts">
  import { resolve } from '$app/paths';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import ConfirmDialog from '$lib/components/ui/ConfirmDialog.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import DocumentViewer, { isViewable } from '$lib/features/documents/DocumentViewer.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ActivityDetail } from '$lib/types/activities';
  import type { ContractDocument, DocumentList } from '$lib/types/documents';
  import type { ReportDetail, ReportSummary } from '$lib/types/reports';
  import { formatDate } from '$lib/utils/format';
  import { formMessage } from '$lib/utils/forms';
  import ReportAnnexList from './ReportAnnexList.svelte';
  import ReportComposer from './ReportComposer.svelte';
  import ReportUploadForm from './ReportUploadForm.svelte';
  import { periodFor } from './activityReport';
  import { submitReport } from './api';

  /**
   * Informes desde la actividad del contratista (ADR-021, ADR-023). Dos caminos:
   * - **Subir informe**: anexar el acta o el informe que ya tiene hecho.
   * - **Crear informe**: elegir acta o informe, escribir o dictar, tomar fotos y generar el PDF.
   * Lo creado o subido queda en el borrador del informe del período; al final se envía.
   */
  let {
    detail,
    reports,
    report,
    documents,
    onchange
  }: {
    detail: ActivityDetail;
    reports: ReportSummary[];
    report: ReportDetail | null;
    documents: DocumentList | null;
    onchange: () => Promise<void>;
  } = $props();

  let mode = $state<'upload' | 'create' | null>(null);
  let viewing = $state<ContractDocument | null>(null);
  let created = $state<ContractDocument | null>(null);
  let confirmOpen = $state(false);
  let sending = $state(false);
  let error = $state<unknown>(null);

  const period = $derived(periodFor(detail, reports, report));
  // Hasta dónde llegan los informes presentados (los rechazados no cuentan: ese período se repite).
  const coveredUntil = $derived(
    reports
      .filter((r) => r.status !== 'rejected')
      .map((r) => r.period_end)
      .sort()
      .at(-1) ?? null
  );
  const underReview = $derived(
    reports.filter((r) => ['submitted', 'in_review', 'resubmitted'].includes(r.status))
  );
  const hasDocuments = $derived((documents?.items ?? []).some((d) => d.status === 'active'));

  async function done(document: ContractDocument): Promise<void> {
    mode = null;
    created = document;
    await onchange();
    toasts.show(`${document.type.name}: listo.`);
    if (isViewable(document.mime_type)) viewing = document;
  }

  async function send(): Promise<void> {
    if (!report) return;
    sending = true;
    error = null;
    try {
      await submitReport(report.uuid);
      confirmOpen = false;
      created = null;
      await onchange();
      toasts.show('Informe enviado al supervisor.');
    } catch (e) {
      error = e;
      confirmOpen = false;
    } finally {
      sending = false;
    }
  }
</script>

<section
  class="rounded-2xl border border-border bg-surface p-6"
  aria-labelledby="informes-actividad"
>
  <h2 id="informes-actividad" class="text-lg font-semibold text-ink">Subir o crear informe</h2>
  {#if period}
    <p class="mt-1 text-sm text-muted">
      {report ? 'Informe abierto del período' : 'Nuevo informe del período'}
      {formatDate(period.start)} a {formatDate(period.end)}
      {#if detail.contract.supervisor}· revisa {detail.contract.supervisor}{/if}
    </p>
  {:else}
    <div class="mt-3">
      <Alert variant="info" title="No hay un período pendiente de informe">
        Ya hay un informe por cada período del contrato{coveredUntil
          ? ` (el último cubre hasta el ${formatDate(coveredUntil)}, fin del contrato)`
          : ''}.
        {#if underReview.length > 0}
          {underReview.length === 1 ? 'Uno está' : `${underReview.length} están`} en revisión con el supervisor:
          si le hace observaciones, aquí se habilitan de nuevo estos botones para corregirlo.
        {:else}
          Para corregir uno, el supervisor debe devolverlo con observaciones.
        {/if}
      </Alert>
      <!-- La pantalla no se queda sin salida: desde aquí se llega a lo que sí puede consultar. -->
      <div class="mt-3 flex flex-wrap gap-2">
        {#if underReview[0]}
          <Button
            size="sm"
            variant="secondary"
            href={resolve('/(app)/reports/[uuid]', { uuid: underReview[0].uuid })}
          >
            Ver el informe en revisión
          </Button>
        {/if}
        <Button size="sm" variant="ghost" href={resolve('/(app)/reports')}>
          Ver todos mis informes
        </Button>
      </div>
    </div>
  {/if}

  {#if formMessage(error)}
    <div class="mt-4"><Alert variant="danger">{formMessage(error)}</Alert></div>
  {/if}

  <div class="mt-5">
    {#if mode === 'upload'}
      <ReportUploadForm {detail} {reports} {report} ondone={done} oncancel={() => (mode = null)} />
    {:else if mode === 'create'}
      <ReportComposer {detail} {reports} {report} ondone={done} oncancel={() => (mode = null)} />
    {:else}
      <div class="grid gap-3 sm:grid-cols-2">
        <button
          type="button"
          class="flex items-start gap-4 rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft disabled:cursor-not-allowed disabled:opacity-60"
          disabled={!period}
          onclick={() => (mode = 'upload')}
        >
          <span
            class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary"
          >
            <Icon name="upload" class="size-6" />
          </span>
          <span>
            <span class="block text-base font-semibold text-ink">Subir informe</span>
            <span class="mt-1 block text-sm text-muted">
              Ya tengo el acta o el informe hecho y lo quiero anexar.
            </span>
          </span>
        </button>
        <button
          type="button"
          class="flex items-start gap-4 rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft disabled:cursor-not-allowed disabled:opacity-60"
          disabled={!period}
          onclick={() => (mode = 'create')}
        >
          <span
            class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary"
          >
            <Icon name="mic" class="size-6" />
          </span>
          <span>
            <span class="block text-base font-semibold text-ink">Crear informe</span>
            <span class="mt-1 block text-sm text-muted">
              Escribo o dicto, tomo fotos y SIGCON arma el acta o el informe.
            </span>
          </span>
        </button>
      </div>

      {#if created}
        <div class="mt-4">
          <Alert variant="success">
            {created.type.name} listo: {created.original_name}. Revíselo en la lista y, cuando
            termine, pulse "Enviar al supervisor".
          </Alert>
        </div>
      {/if}

      {#if report}
        <div class="mt-6 border-t border-border pt-4">
          <h3 class="text-sm font-semibold text-ink">Lo que lleva en este período</h3>
          <ReportAnnexList {documents} empty="Todavía no ha subido ni creado documentos." />

          <div class="mt-4 flex flex-wrap items-center gap-3">
            <Button disabled={!hasDocuments} onclick={() => (confirmOpen = true)}>
              Enviar al supervisor
            </Button>
            <p class="text-xs text-warning">
              Es un borrador: el supervisor no lo ve hasta que pulse "Enviar al supervisor".
            </p>
          </div>
        </div>
      {/if}
    {/if}
  </div>
</section>

<DocumentViewer bind:document={viewing} />

<ConfirmDialog
  bind:open={confirmOpen}
  title="Enviar al supervisor"
  confirmLabel="Enviar"
  loading={sending}
  onconfirm={send}
>
  El informe del período, con todos sus documentos, quedará en revisión y no podrá editarlo hasta
  que el supervisor responda.
</ConfirmDialog>
