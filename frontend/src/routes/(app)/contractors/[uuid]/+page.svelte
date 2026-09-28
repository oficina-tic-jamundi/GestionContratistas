<script lang="ts">
  import { invalidate } from '$app/navigation';
  import { resolve } from '$app/paths';
  import { Permission } from '$lib/auth/permissions';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import ConfirmDialog from '$lib/components/ui/ConfirmDialog.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import { setContractorActive, updateContractor } from '$lib/features/contractors/api';
  import ContractorForm from '$lib/features/contractors/ContractorForm.svelte';
  import ContractStatusBadge from '$lib/features/contracts/ContractStatusBadge.svelte';
  import ContractActivities from '$lib/features/contractors/ContractActivities.svelte';
  import ReportStatusBadge from '$lib/features/reports/ReportStatusBadge.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ContractorInput } from '$lib/types/contracts';
  import { formatDate, formatMoney } from '$lib/utils/format';
  import { formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  /**
   * Ficha del contratista (ADR-021): primero sus actividades y su avance, después su
   * información. La administración puede editarlo y activarlo o desactivarlo; el supervisor
   * consulta y abre el informe para revisarlo.
   */
  let { data }: PageProps = $props();

  const contractor = $derived(data.contractor);
  const canManage = $derived(session.can(Permission.ContractorsManage));
  // La cuenta actualmente vinculada debe aparecer en la lista aunque no esté en la primera página.
  const accounts = $derived.by(() => {
    const linked = data.contractor.user;
    if (!linked || data.accounts.some((a) => a.uuid === linked.uuid)) return data.accounts;
    return [...data.accounts, { uuid: linked.uuid, email: linked.email, full_name: linked.email }];
  });

  let saving = $state(false);
  let error = $state<unknown>(null);
  let confirmToggle = $state(false);
  let working = $state(false);
  let editing = $state(false);

  async function save(input: ContractorInput): Promise<void> {
    saving = true;
    error = null;
    try {
      await updateContractor(contractor.uuid, input);
      toasts.show('Contratista actualizado.');
      editing = false;
      await invalidate('app:contractor');
    } catch (e) {
      error = e;
    } finally {
      saving = false;
    }
  }

  async function toggle(): Promise<void> {
    working = true;
    try {
      const response = await setContractorActive(contractor.uuid, contractor.status !== 'active');
      toasts.show(response.message ?? 'Estado actualizado.', 'info', 8000);
      await invalidate('app:contractor');
    } catch (e) {
      toasts.show(formMessage(e) ?? 'No fue posible cambiar el estado.', 'error');
    } finally {
      working = false;
      confirmToggle = false;
    }
  }

  /** Informe más reciente presentado: es el que el supervisor revisa. */
  const lastReport = (reports: PageProps['data']['files'][number]['reports']) =>
    reports.find((r) => r.status !== 'draft') ?? null;
</script>

<PageHeader title={contractor.name} description="Documento {contractor.document}">
  {#snippet actions()}
    {#if canManage}
      <Button variant="secondary" onclick={() => (editing = !editing)}>
        {editing ? 'Cerrar edición' : 'Editar información'}
      </Button>
      <Button
        variant={contractor.status === 'active' ? 'danger' : 'secondary'}
        onclick={() => (confirmToggle = true)}
      >
        {contractor.status === 'active' ? 'Desactivar' : 'Activar'}
      </Button>
    {/if}
    <Button href={resolve('/contractors')} variant="secondary">Volver</Button>
  {/snippet}
</PageHeader>

{#if formMessage(error)}<div class="mb-4">
    <Alert variant="danger">{formMessage(error)}</Alert>
  </div>{/if}

<!-- Resumen: lo esencial de un vistazo -->
<ul class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-xs text-muted">Estado</p>
    <p class="mt-1">
      <Badge tone={contractor.status === 'active' ? 'success' : 'neutral'}>
        {contractor.status_label}
      </Badge>
    </p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-xs text-muted">Tipo</p>
    <p class="mt-1 text-sm text-ink">{contractor.person_type_label}</p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-xs text-muted">Contratos</p>
    <p class="mt-1 text-sm text-ink">{data.files.length}</p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-xs text-muted">Cuenta SIGCON</p>
    <p class="mt-1 text-sm break-all text-ink">{contractor.user?.email ?? 'Sin vincular'}</p>
  </li>
</ul>

{#if editing && canManage}
  <div class="mb-6">
    <Card title="Editar información del contratista">
      {#key contractor.updated_at}
        <ContractorForm
          initial={{
            person_type: contractor.person_type,
            document_type: contractor.document_type,
            document_number: contractor.document_number,
            verification_digit: contractor.verification_digit?.toString() ?? null,
            name: contractor.name,
            email: contractor.email,
            phone: contractor.phone,
            address: contractor.address,
            user: contractor.user?.uuid ?? null
          }}
          {accounts}
          submitting={saving}
          {error}
          submitLabel="Guardar cambios"
          onsubmit={save}
        />
      {/key}
    </Card>
  </div>
{/if}

<!-- Actividades por contrato: lo que hace el contratista y cómo va -->
<div class="space-y-6">
  {#each data.files as file (file.contract.uuid)}
    {@const report = lastReport(file.reports)}
    <section
      class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"
      aria-labelledby="contrato-{file.contract.uuid}"
    >
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
          <h2 id="contrato-{file.contract.uuid}" class="text-lg font-semibold text-ink">
            Contrato {file.contract.contract_number}
          </h2>
          <p class="mt-0.5 text-sm text-muted">
            {file.contract.department.name} · {formatDate(file.contract.start_date)} – {formatDate(
              file.contract.end_date
            )} · {formatMoney(file.contract.total_value)}
          </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <ContractStatusBadge status={file.contract.status} label={file.contract.status_label} />
          <Button
            variant="secondary"
            size="sm"
            href={resolve('/(app)/contracts/[uuid]', { uuid: file.contract.uuid })}
          >
            Ver contrato
          </Button>
        </div>
      </div>

      <ContractActivities tree={file.tree} />

      <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-border pt-4">
        {#if report}
          <span class="text-sm text-muted">Último informe presentado:</span>
          <span class="text-sm font-medium text-ink">N.° {report.number}</span>
          <ReportStatusBadge status={report.status} label={report.status_label} />
          <Button size="sm" href={resolve('/(app)/reports/[uuid]', { uuid: report.uuid })}>
            Ver informe
          </Button>
        {:else}
          <p class="text-sm text-muted">Este contrato aún no tiene informes presentados.</p>
        {/if}
      </div>
    </section>
  {:else}
    <p class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">
      Este contratista no tiene contratos registrados.
    </p>
  {/each}
</div>

<ConfirmDialog
  bind:open={confirmToggle}
  title={contractor.status === 'active' ? 'Desactivar contratista' : 'Activar contratista'}
  confirmLabel={contractor.status === 'active' ? 'Desactivar' : 'Activar'}
  variant={contractor.status === 'active' ? 'danger' : 'primary'}
  loading={working}
  onconfirm={toggle}
>
  {#if contractor.status === 'active'}
    No podrá asignársele contratos nuevos. Los contratos en ejecución no se modifican.
  {:else}
    Podrá volver a asignársele contratos.
  {/if}
</ConfirmDialog>
