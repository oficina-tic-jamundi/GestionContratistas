<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import { Permission } from '$lib/auth/permissions';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import { buildQuery } from '$lib/utils/format';
  import type { PageProps } from './$types';

  /**
   * Contratistas: punto de entrada de la administración y de la supervisión. Cada tarjeta
   * abre la ficha del contratista con sus actividades (ADR-021).
   */
  let { data }: PageProps = $props();

  let search = $derived(data.query.search ?? '');
  let status = $derived(data.query.status ?? '');
  const canManage = $derived(session.can(Permission.ContractorsManage));

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/contractors?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    const qs = buildQuery({ ...data.query, search, status, page: null });
    void goto(resolve(`/contractors?${qs}`), { keepFocus: true });
  }

  const initial = (name: string) => name.trim().charAt(0).toUpperCase();
</script>

<PageHeader
  title="Contratistas"
  description={canManage
    ? 'Abra un contratista para ver sus actividades y su información.'
    : 'Contratistas de los contratos que usted supervisa. Ábralos para ver sus actividades.'}
>
  {#snippet actions()}
    {#if canManage}
      <Button href={resolve('/contractors/new')}>Nuevo contratista</Button>
    {/if}
  {/snippet}
</PageHeader>

<form
  class="mb-6 grid gap-3 rounded-2xl border border-border bg-surface p-4 sm:grid-cols-[1fr_12rem_auto] sm:items-end"
  onsubmit={apply}
  role="search"
>
  <TextField label="Buscar" placeholder="Nombre o número de documento" bind:value={search} />
  <SelectField
    label="Estado"
    bind:value={status}
    placeholder="Todos"
    options={[
      { value: 'active', label: 'Activos' },
      { value: 'inactive', label: 'Inactivos' }
    ]}
  />
  <Button type="submit" variant="secondary">Filtrar</Button>
</form>

<ul class="stagger grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
  {#each data.result.items as contractor (contractor.uuid)}
    <li>
      <a
        href={resolve('/(app)/contractors/[uuid]', { uuid: contractor.uuid })}
        class="card-interactive flex h-full items-start gap-4 rounded-2xl border border-border bg-surface p-5 shadow-card hover:bg-primary-soft"
      >
        <span
          class="inline-flex size-12 shrink-0 items-center justify-center rounded-full bg-[#2563eb] text-lg font-semibold text-white"
          aria-hidden="true">{initial(contractor.name)}</span
        >
        <span class="min-w-0 flex-1">
          <span class="block truncate text-base font-semibold text-ink">{contractor.name}</span>
          <span class="mt-0.5 block font-mono text-xs text-muted">{contractor.document}</span>
          <span class="mt-1 block text-xs text-muted">{contractor.person_type_label}</span>
          <span class="mt-3 flex flex-wrap items-center gap-2">
            <Badge tone={contractor.status === 'active' ? 'success' : 'neutral'}>
              {contractor.status_label}
            </Badge>
            {#if !contractor.user}
              <Badge tone="warning">Sin cuenta vinculada</Badge>
            {/if}
          </span>
        </span>
        <Icon name="chevron" class="size-5 -rotate-90 text-muted" />
      </a>
    </li>
  {:else}
    <li
      class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted sm:col-span-2 xl:col-span-3"
    >
      {data.query.search || data.query.status
        ? 'No hay contratistas que coincidan con el filtro.'
        : 'Aún no hay contratistas registrados.'}
    </li>
  {/each}
</ul>

<div class="mt-6">
  <Pagination pagination={data.result.pagination} {hrefFor} />
</div>
