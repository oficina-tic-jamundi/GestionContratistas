<script lang="ts">
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import { uploadDocument } from '$lib/features/documents/api';
  import type { ActivityDetail } from '$lib/types/activities';
  import type { ContractDocument } from '$lib/types/documents';
  import type { ReportDetail, ReportSummary } from '$lib/types/reports';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import DictationButton from './DictationButton.svelte';
  import { TEMPLATES, ensureReport, saveObligationText, type TemplateCode } from './activityReport';

  /**
   * "Subir informe": el contratista ya tiene el acta o el informe hecho (PDF, Word, imagen) y
   * lo anexa. Se pide una descripción breve de lo realizado para el informe del período.
   */
  let {
    detail,
    reports,
    report,
    ondone,
    oncancel
  }: {
    detail: ActivityDetail;
    reports: ReportSummary[];
    report: ReportDetail | null;
    ondone: (document: ContractDocument) => Promise<void>;
    oncancel: () => void;
  } = $props();

  const ACCEPT = '.pdf,.docx,.xlsx,.jpg,.jpeg,.png';

  let kind = $state<TemplateCode>('informe');
  let file = $state<File | null>(null);
  let summary = $state('');
  let busy = $state(false);
  let error = $state<unknown>(null);

  const ready = $derived(file !== null && summary.trim().length >= 20);

  async function submit(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    if (!file) return;
    busy = true;
    error = null;
    try {
      const target = await ensureReport(detail, reports, report);
      await saveObligationText(target, detail.obligation.uuid, summary);
      const document = await uploadDocument(
        { kind: 'report', uuid: target.uuid },
        file,
        kind,
        `Actividad: ${detail.activity.title}`
      );
      await ondone(document);
    } catch (e) {
      error = e;
    } finally {
      busy = false;
    }
  }
</script>

<form class="space-y-5" onsubmit={submit}>
  <div>
    <h3 class="text-base font-semibold text-ink">Subir un informe ya elaborado</h3>
    <p class="mt-1 text-sm text-muted">Anexe el archivo del acta o del informe que ya tiene.</p>
  </div>

  {#if formMessage(error) && fieldErrors(error, 'file').length === 0}
    <Alert variant="danger">{formMessage(error)}</Alert>
  {/if}

  <fieldset class="space-y-2">
    <legend class="text-sm font-medium text-ink">¿Qué documento es?</legend>
    <div class="flex flex-wrap gap-2">
      {#each Object.entries(TEMPLATES) as [code, template] (code)}
        <label
          class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-sm transition {kind ===
          code
            ? 'border-primary/50 bg-primary-soft text-ink'
            : 'border-border bg-field text-muted hover:border-primary/40'}"
        >
          <input type="radio" name="tipo-subir" value={code} bind:group={kind} class="size-4" />
          {template.label}
        </label>
      {/each}
    </div>
  </fieldset>

  <div class="space-y-2">
    <label class="block text-sm font-medium text-ink" for="archivo-informe">
      <span class="inline-flex items-center gap-2">
        <Icon name="upload" class="size-4" /> Archivo
        <span class="text-danger" aria-hidden="true">*</span>
      </span>
    </label>
    <input
      id="archivo-informe"
      type="file"
      accept={ACCEPT}
      required
      aria-describedby="archivo-informe-ayuda"
      onchange={(e) => (file = (e.currentTarget as HTMLInputElement).files?.[0] ?? null)}
      class="w-full rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1 file:text-on-primary"
    />
    <p id="archivo-informe-ayuda" class="text-xs text-muted">PDF, Word, Excel o imagen.</p>
    {#if fieldErrors(error, 'file').length > 0}
      <p class="text-sm text-danger" role="alert">{fieldErrors(error, 'file').join(' ')}</p>
    {/if}
  </div>

  <div class="space-y-2">
    <TextArea
      label="Resumen de lo realizado"
      bind:value={summary}
      rows={3}
      required
      maxlength={5000}
      hint="Dos o tres frases para el informe del período. Mínimo 20 caracteres."
    />
    <DictationButton ontext={(t) => (summary = summary ? `${summary} ${t}` : t)} />
  </div>

  <div class="flex flex-wrap gap-3">
    <Button type="submit" loading={busy} disabled={!ready}>
      <Icon name="upload" class="size-4" /> Anexar informe
    </Button>
    <Button variant="ghost" onclick={oncancel}>Volver</Button>
  </div>
</form>
