<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import { Permission } from '$lib/auth/permissions';
  import Button from '$lib/components/ui/Button.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import ScrollRegion from '$lib/components/ui/ScrollRegion.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import ContractStatusBadge from '$lib/features/contracts/ContractStatusBadge.svelte';
  import { buildQuery, formatDate, formatMoney } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let search = $derived(data.query.search ?? '');
  let status = $derived(data.query.status ?? '');
  let department = $derived(data.query.department ?? '');

  const title = $derived(
    session.can(Permission.ContractsViewAll)
      ? 'Contratos'
      : session.can(Permission.ContractsViewAssigned)
        ? 'Contratos que supervisa'
        : 'Mis contratos'
  );

  const STATUS_OPTIONS = [
    { value: 'draft', label: 'Borrador' },
    { value: 'active', label: 'Activo' },
    { value: 'suspended', label: 'Suspendido' },
    { value: 'terminated', label: 'Terminado' },
    { value: 'liquidated', label: 'Liquidado' },
    { value: 'archived', label: 'Archivado' }
  ];

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/contracts?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    const qs = buildQuery({ ...data.query, search, status, department, page: null });
    void goto(resolve(`/contracts?${qs}`), { keepFocus: true });
  }
</script>

<PageHeader
  {title}
  description="Valores en pesos colombianos. Fechas de inicio y terminación del contrato."
>
  {#snippet actions()}
    {#if session.can(Permission.ContractsManage)}
      <Button href={resolve('/contracts/new')}>Nuevo contrato</Button>
    {/if}
  {/snippet}
</PageHeader>

<form
  class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto] lg:items-end"
  onsubmit={apply}
  role="search"
>
  <TextField
    label="Buscar"
    type="search"
    placeholder="Número, objeto, contratista o documento"
    bind:value={search}
  />
  <SelectField label="Estado" bind:value={status} placeholder="Todos" options={STATUS_OPTIONS} />
  {#if data.departments.length > 0}
    <SelectField
      label="Dependencia"
      bind:value={department}
      placeholder="Todas"
      options={data.departments.map((d) => ({ value: d.uuid, label: d.name }))}
    />
  {/if}
  <Button type="submit" variant="secondary">Filtrar</Button>
</form>

<!-- Celular: tarjetas (el contratista consulta desde el lugar de trabajo; §56). -->
<ul class="space-y-3 md:hidden">
  {#each data.result.items as contract (contract.uuid)}
    <li>
      <a
        href={resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid })}
        class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"
      >
        <div class="flex items-start justify-between gap-2">
          <span class="font-medium text-primary">{contract.contract_number}</span>
          <ContractStatusBadge status={contract.status} label={contract.status_label} />
        </div>
        <p class="mt-1 line-clamp-2 text-sm text-ink">{contract.object}</p>
        <dl class="mt-3 grid grid-cols-2 gap-2 text-xs">
          <div>
            <dt class="text-muted">Plazo</dt>
            <dd>{formatDate(contract.start_date)} – {formatDate(contract.end_date)}</dd>
          </div>
          <div class="text-right">
            <dt class="text-muted">Valor</dt>
            <dd class="font-medium">{formatMoney(contract.total_value)}</dd>
          </div>
          <div class="col-span-2">
            <dt class="text-muted">Dependencia</dt>
            <dd>{contract.department.name}</dd>
          </div>
        </dl>
      </a>
    </li>
  {:else}
    <li
      class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted"
    >
      No hay contratos para mostrar.
    </li>
  {/each}
</ul>

<div
  class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block"
>
  <ScrollRegion label="Tabla de contratos">
    <table class="data-table">
      <thead>
        <tr>
          <th scope="col">Número</th>
          <th scope="col">Contratista</th>
          <th scope="col">Dependencia</th>
          <th scope="col">Plazo</th>
          <th scope="col" class="num">Valor</th>
          <th scope="col">Estado</th>
        </tr>
      </thead>
      <tbody>
        {#each data.result.items as contract (contract.uuid)}
          <tr>
            <td class="whitespace-nowrap">
              <a
                href={resolve('/(app)/contracts/[uuid]', { uuid: contract.uuid })}
                class="font-medium text-primary hover:underline">{contract.contract_number}</a
              >
            </td>
            <td>
              {contract.contractor.name}
              <span class="block text-xs text-muted">{contract.contractor.document}</span>
            </td>
            <td class="quiet">{contract.department.code}</td>
            <td class="whitespace-nowrap quiet">
              {formatDate(contract.start_date)} – {formatDate(contract.end_date)}
            </td>
            <td class="num whitespace-nowrap">{formatMoney(contract.total_value)}</td>
            <td>
              <ContractStatusBadge status={contract.status} label={contract.status_label} />
            </td>
          </tr>
        {:else}
          <tr>
            <td colspan="6" class="px-4 py-10 text-center text-muted"
              >No hay contratos para mostrar.</td
            >
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>

<Pagination pagination={data.result.pagination} {hrefFor} />
