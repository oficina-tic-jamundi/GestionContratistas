<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import { createContract } from '$lib/features/contracts/api';
  import ContractForm from '$lib/features/contracts/ContractForm.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ContractInput } from '$lib/types/contracts';
  import { formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let submitting = $state(false);
  let error = $state<unknown>(null);

  async function save(input: ContractInput): Promise<void> {
    submitting = true;
    error = null;
    try {
      const contract = await createContract(input);
      toasts.show('Contrato registrado en borrador.');
      await goto(resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid }));
    } catch (e) {
      error = e;
    } finally {
      submitting = false;
    }
  }
</script>

<PageHeader
  title="Nuevo contrato"
  description="Se registra en borrador. Podrá revisarlo y activarlo cuando tenga supervisor asignado."
/>

<div class="max-w-4xl space-y-4">
  {#if data.departments.length === 0}
    <Alert variant="warning" title="No hay dependencias activas">
      Registre al menos una dependencia antes de crear contratos.
    </Alert>
  {/if}
  {#if formMessage(error)}<Alert variant="danger">{formMessage(error)}</Alert>{/if}
  <Card>
    <ContractForm
      initial={{
        contract_number: '',
        object: '',
        contractor: '',
        department: '',
        supervisor: null,
        signed_at: null,
        start_date: '',
        end_date: '',
        total_value: '',
        payment_count: '',
        secop_url: null
      }}
      departments={data.departments}
      supervisors={data.supervisors}
      {submitting}
      {error}
      submitLabel="Registrar borrador"
      onsubmit={save}
    />
  </Card>
</div>
