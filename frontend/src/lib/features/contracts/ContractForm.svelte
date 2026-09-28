<script lang="ts">
  import Button from '$lib/components/ui/Button.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import ContractorPicker from '$lib/features/contractors/ContractorPicker.svelte';
  import type { ContractInput, Department, SupervisorOption } from '$lib/types/contracts';
  import { formatMoney } from '$lib/utils/format';
  import { fieldErrors } from '$lib/utils/forms';

  /** Formulario de contrato en borrador (crear y editar). */
  let {
    initial,
    contractorLabel = '',
    departments,
    supervisors,
    submitting = false,
    error = null,
    submitLabel,
    onsubmit
  }: {
    initial: ContractInput;
    contractorLabel?: string;
    departments: Department[];
    supervisors: SupervisorOption[];
    submitting?: boolean;
    error?: unknown;
    submitLabel: string;
    onsubmit: (input: ContractInput) => void;
  } = $props();

  // svelte-ignore state_referenced_locally
  let form = $state({
    ...initial,
    supervisor: initial.supervisor ?? '',
    signed_at: initial.signed_at ?? '',
    secop_url: initial.secop_url ?? '',
    total_value: initial.total_value.replace(/\.00$/, ''),
    payment_count: initial.payment_count ?? ''
  });

  const valuePreview = $derived(
    /^\d+(\.\d{1,2})?$/.test(form.total_value) ? formatMoney(form.total_value) : ''
  );
  const blankToNull = (v: string) => (v.trim() === '' ? null : v);

  function submit(event: SubmitEvent): void {
    event.preventDefault();
    onsubmit({
      ...form,
      supervisor: blankToNull(form.supervisor),
      signed_at: blankToNull(form.signed_at),
      secop_url: blankToNull(form.secop_url)
    });
  }
</script>

<form class="space-y-8" onsubmit={submit} novalidate>
  <fieldset class="space-y-4">
    <legend class="label-eyebrow mb-3">Identificación</legend>
    <div class="grid gap-4 sm:grid-cols-2">
      <TextField
        label="Número de contrato"
        required
        maxlength={50}
        hint="Tal como lo asigna la Oficina Jurídica."
        bind:value={form.contract_number}
        errors={fieldErrors(error, 'contract_number')}
      />
      <SelectField
        label="Dependencia"
        bind:value={form.department}
        placeholder="Seleccione…"
        options={departments.map((d) => ({ value: d.uuid, label: `${d.name} (${d.code})` }))}
        errors={fieldErrors(error, 'department')}
      />
      <div class="sm:col-span-2">
        <ContractorPicker
          bind:value={form.contractor}
          initialLabel={contractorLabel}
          errors={fieldErrors(error, 'contractor')}
        />
      </div>
      <div class="sm:col-span-2">
        <TextArea
          label="Objeto del contrato"
          required
          rows={4}
          maxlength={5000}
          bind:value={form.object}
          errors={fieldErrors(error, 'object')}
        />
      </div>
    </div>
  </fieldset>

  <fieldset class="space-y-4">
    <legend class="label-eyebrow mb-3">Plazo y valor</legend>
    <div class="grid gap-4 sm:grid-cols-3">
      <TextField
        label="Fecha de suscripción"
        type="date"
        bind:value={form.signed_at}
        errors={fieldErrors(error, 'signed_at')}
      />
      <TextField
        label="Fecha de inicio"
        type="date"
        required
        bind:value={form.start_date}
        errors={fieldErrors(error, 'start_date')}
      />
      <TextField
        label="Fecha de terminación"
        type="date"
        required
        bind:value={form.end_date}
        errors={fieldErrors(error, 'end_date')}
      />
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
      <TextField
        label="Valor total (COP)"
        required
        inputmode="decimal"
        placeholder="42000000"
        hint={valuePreview ? `= ${valuePreview}` : 'Sin puntos de miles; decimales con punto.'}
        bind:value={form.total_value}
        errors={fieldErrors(error, 'total_value')}
      />
      <TextField
        label="Pagos pactados"
        inputmode="numeric"
        placeholder="11"
        hint="Cuántos pagos contempla el contrato. Permite ver cuántos lleva y cuántos faltan."
        bind:value={form.payment_count}
        errors={fieldErrors(error, 'payment_count')}
      />
    </div>
  </fieldset>

  <fieldset class="space-y-4">
    <legend class="label-eyebrow mb-3">Supervisión y publicidad</legend>
    <div class="grid gap-4 sm:grid-cols-2">
      <SelectField
        label="Supervisor"
        bind:value={form.supervisor}
        placeholder="Sin asignar (obligatorio para activar)"
        options={supervisors.map((s) => ({
          value: s.uuid,
          label: s.department ? `${s.name} — ${s.department}` : s.name
        }))}
        errors={fieldErrors(error, 'supervisor')}
      />
      <div class="sm:col-span-2">
        <TextField
          label="Enlace al proceso en SECOP II"
          type="url"
          maxlength={500}
          placeholder="https://community.secop.gov.co/…"
          hint="Opcional."
          bind:value={form.secop_url}
          errors={fieldErrors(error, 'secop_url')}
        />
      </div>
    </div>
  </fieldset>

  <div class="flex flex-wrap gap-3 border-t border-border pt-6">
    <Button type="submit" loading={submitting}>{submitLabel}</Button>
  </div>
</form>
