<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import { updateContract } from '$lib/features/contracts/api';
  import ContractForm from '$lib/features/contracts/ContractForm.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ContractInput } from '$lib/types/contracts';
  import { formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  const contract = $derived(data.contract);
  let submitting = $state(false);
  let error = $state<unknown>(null);

  // La dependencia actual debe estar entre las opciones aunque se haya desactivado después.
  const departments = $derived(
    data.departments.some((d) => d.uuid === contract.department.uuid)
      ? data.departments
      : [
          ...data.departments,
          {
            ...contract.department,
            status: 'inactive' as const,
            status_label: 'Inactiva',
            active_contracts: 0
          }
        ]
  );

  async function save(input: ContractInput): Promise<void> {
    submitting = true;
    error = null;
    try {
      await updateContract(contract.uuid, input);
      toasts.show('Borrador actualizado.');
      await goto(resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid }));
    } catch (e) {
      error = e;
    } finally {
      submitting = false;
    }
  }
</script>

<PageHeader title="Editar borrador {contract.contract_number}">
  {#snippet actions()}
    <Button href={resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid })} variant="secondary"
      >Cancelar</Button
    >
  {/snippet}
</PageHeader>

<div class="max-w-4xl space-y-4">
  {#if formMessage(error)}<Alert variant="danger">{formMessage(error)}</Alert>{/if}
  <Card>
    <ContractForm
      initial={{
        contract_number: contract.contract_number,
        object: contract.object,
        contractor: contract.contractor.uuid,
        department: contract.department.uuid,
        supervisor: contract.supervisor?.uuid ?? null,
        signed_at: contract.signed_at,
        start_date: contract.start_date,
        end_date: contract.end_date,
        total_value: contract.total_value,
        payment_count: contract.payment_count === null ? '' : String(contract.payment_count),
        secop_url: contract.secop_url
      }}
      contractorLabel="{contract.contractor.name} — {contract.contractor.document}"
      {departments}
      supervisors={data.supervisors}
      {submitting}
      {error}
      submitLabel="Guardar borrador"
      onsubmit={save}
    />
  </Card>
</div>
