<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import ObligationImport from '$lib/features/activities/ObligationImport.svelte';
  import { createContractor } from '$lib/features/contractors/api';
  import ContractorForm from '$lib/features/contractors/ContractorForm.svelte';
  import { createContract } from '$lib/features/contracts/api';
  import ContractForm from '$lib/features/contracts/ContractForm.svelte';
  import { uploadDocument } from '$lib/features/documents/api';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { Contractor, ContractInput, ContractorInput } from '$lib/types/contracts';
  import { formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  /**
   * Alta de un contratista en tres pasos (ADR-022): sus datos, su contrato con el PDF firmado y
   * las obligaciones que SIGCON lee de ese PDF, revisadas antes de registrarlas.
   *
   * Cada paso guarda lo suyo: si algo falla, lo ya registrado se conserva y se reintenta solo
   * lo pendiente (no se duplica el contratista ni el contrato).
   */
  let { data }: PageProps = $props();

  const STEPS = ['Datos del contratista', 'Contrato firmado', 'Obligaciones'];

  let step = $state(0);
  let contractor = $state<Contractor | null>(null);
  let contractUuid = $state<string | null>(null);
  let pdf = $state<File | null>(null);
  let submitting = $state(false);
  let error = $state<unknown>(null);
  let pdfError = $state<string | null>(null);

  async function saveContractor(input: ContractorInput): Promise<void> {
    submitting = true;
    error = null;
    try {
      contractor = await createContractor(input);
      toasts.show('Contratista registrado.');
      if (data.withContract) {
        step = 1;
      } else {
        await finish();
      }
    } catch (e) {
      error = e;
    } finally {
      submitting = false;
    }
  }

  function pickPdf(event: Event): void {
    const file = (event.currentTarget as HTMLInputElement).files?.[0] ?? null;
    pdfError =
      file && file.type !== 'application/pdf' ? 'El contrato debe ser un archivo PDF.' : null;
    pdf = pdfError ? null : file;
  }

  async function saveContract(input: ContractInput): Promise<void> {
    if (!pdf) {
      pdfError = 'Adjunte el contrato firmado en PDF: de él se toman las obligaciones.';
      return;
    }
    submitting = true;
    error = null;
    try {
      // El contrato se crea una sola vez; si falló la carga del PDF, solo se reintenta la carga.
      contractUuid ??= (await createContract(input)).uuid;
      await uploadDocument({ kind: 'contract', uuid: contractUuid }, pdf, 'signed_contract', null);
      toasts.show('Contrato registrado en borrador con su PDF.');
      step = 2;
    } catch (e) {
      error = e;
    } finally {
      submitting = false;
    }
  }

  async function finish(): Promise<void> {
    if (!contractor) return;
    await goto(resolve('/(app)/contractors/[uuid]', { uuid: contractor.uuid }));
  }
</script>

<PageHeader
  title="Nuevo contratista"
  description={data.withContract
    ? 'Registre al contratista, su contrato firmado y las obligaciones que se leen de él.'
    : 'Solo se registran los datos necesarios para identificar y contactar al contratista.'}
/>

<div class="max-w-4xl space-y-4">
  {#if data.withContract}
    <ol class="grid gap-2 sm:grid-cols-3" aria-label="Pasos del registro">
      {#each STEPS as label, index (label)}
        <li
          class="flex items-center gap-3 rounded-xl border px-4 py-3 text-sm {index === step
            ? 'border-primary/50 bg-primary-soft text-ink'
            : index < step
              ? 'border-border bg-surface text-ink'
              : 'border-border bg-surface text-muted'}"
          aria-current={index === step ? 'step' : undefined}
        >
          <span
            class="grid size-7 shrink-0 place-items-center rounded-full text-xs font-semibold {index <
            step
              ? 'bg-success text-deep'
              : index === step
                ? 'btn-primary'
                : 'bg-canvas text-muted'}"
          >
            {#if index < step}<Icon name="check-list" class="size-4" />{:else}{index + 1}{/if}
          </span>
          <span>{label}{index < step ? ' (listo)' : ''}</span>
        </li>
      {/each}
    </ol>
  {/if}

  {#if formMessage(error)}<Alert variant="danger">{formMessage(error)}</Alert>{/if}

  {#if step === 0}
    <Card>
      <ContractorForm
        initial={{
          person_type: 'natural',
          document_type: 'CC',
          document_number: '',
          verification_digit: null,
          name: '',
          email: null,
          phone: null,
          address: null,
          user: null
        }}
        accounts={data.accounts}
        {submitting}
        {error}
        submitLabel={data.withContract ? 'Guardar y continuar' : 'Registrar contratista'}
        onsubmit={saveContractor}
      />
    </Card>
  {:else if step === 1 && contractor}
    {#if data.departments.length === 0}
      <Alert variant="warning" title="No hay dependencias activas">
        Registre al menos una dependencia antes de crear contratos.
      </Alert>
    {/if}
    <Card title="Contrato de {contractor.name}">
      <div class="mb-6 space-y-2 rounded-xl border border-primary/30 bg-primary-soft p-4">
        <label for="contrato-pdf" class="block text-sm font-medium text-ink">
          Contrato firmado (PDF) <span class="text-danger" aria-hidden="true">*</span>
        </label>
        <input
          id="contrato-pdf"
          type="file"
          accept="application/pdf,.pdf"
          required
          aria-describedby="contrato-pdf-ayuda"
          onchange={pickPdf}
          class="w-full rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1 file:text-on-primary"
        />
        <p id="contrato-pdf-ayuda" class="text-xs text-muted">
          SIGCON leerá la sección de obligaciones específicas para proponerle las actividades. Debe
          ser un PDF con texto (no escaneado).
        </p>
        {#if pdfError}<p class="text-sm text-danger" role="alert">{pdfError}</p>{/if}
        {#if contractUuid}
          <p class="text-xs text-warning">
            El contrato ya quedó registrado; falta cargar el PDF. Selecciónelo y vuelva a guardar.
          </p>
        {/if}
      </div>

      <ContractForm
        initial={{
          contract_number: '',
          object: '',
          contractor: contractor.uuid,
          department: '',
          supervisor: null,
          signed_at: null,
          start_date: '',
          end_date: '',
          total_value: '',
          payment_count: '',
          secop_url: null
        }}
        contractorLabel={contractor.name}
        departments={data.departments}
        supervisors={data.supervisors}
        {submitting}
        {error}
        submitLabel="Guardar contrato y leer obligaciones"
        onsubmit={saveContract}
      />
    </Card>
    <p class="text-sm text-muted">
      ¿Aún no tiene el contrato?
      <button type="button" class="text-primary underline" onclick={finish}>
        Terminar ahora
      </button>
      y regístrelo después desde Contratos.
    </p>
  {:else if step === 2 && contractUuid}
    <Card title="Obligaciones del contrato">
      <ObligationImport {contractUuid} onimported={finish} />
    </Card>
    <Button variant="ghost" onclick={finish}>Omitir y registrar las obligaciones después</Button>
  {/if}
</div>
