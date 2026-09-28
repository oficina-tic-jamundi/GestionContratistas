<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import Button from '$lib/components/ui/Button.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import ScrollRegion from '$lib/components/ui/ScrollRegion.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { buildQuery, formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let action = $derived(data.query.action ?? '');
  let from = $derived(data.query.from ?? '');
  let to = $derived(data.query.to ?? '');

  // Filtros por prefijo (todos los eventos de un módulo) + eventos individuales.
  const actionOptions = $derived([
    { value: 'auth.', label: 'Autenticación (todos)' },
    { value: 'user.', label: 'Usuarios (todos)' },
    { value: 'role.', label: 'Roles (todos)' },
    ...Object.entries(data.actions).map(([value, label]) => ({ value, label }))
  ]);

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/audit?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    const qs = buildQuery({ ...data.query, action, from, to, page: null });
    void goto(resolve(`/audit?${qs}`), { keepFocus: true });
  }

  function describe(metadata: Record<string, unknown> | null): string {
    if (!metadata) return '';
    return Object.entries(metadata)
      .map(([key, value]) => `${key}: ${typeof value === 'string' ? value : JSON.stringify(value)}`)
      .join(' · ');
  }
</script>

<PageHeader
  title="Auditoría"
  description="Registro de solo lectura de las acciones realizadas en el sistema. Horas en America/Bogota."
/>

<form
  class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-[2fr_1fr_1fr_auto] sm:items-end"
  onsubmit={apply}
  role="search"
>
  <SelectField label="Acción" bind:value={action} placeholder="Todas" options={actionOptions} />
  <TextField label="Desde" type="date" bind:value={from} />
  <TextField label="Hasta" type="date" bind:value={to} />
  <Button type="submit" variant="secondary">Filtrar</Button>
</form>

{#if data.query.entity_type || data.query.user}
  <p class="mb-3 text-sm text-muted">
    Filtrado por {data.query.entity_type
      ? `${data.query.entity_type} ${data.query.entity_id ?? ''}`
      : 'usuario'}.
    <a href={resolve('/audit')} class="text-primary hover:underline">Quitar filtro</a>
  </p>
{/if}

<div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">
  <ScrollRegion label="Registros de auditoría">
    <table class="data-table">
      <thead>
        <tr>
          <th scope="col">Fecha y hora</th>
          <th scope="col">Acción</th>
          <th scope="col">Usuario</th>
          <th scope="col">Detalle</th>
          <th scope="col">IP</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border align-top">
        {#each data.result.items as entry (entry.id)}
          <tr>
            <td class="whitespace-nowrap">{formatDateTime(entry.occurred_at)}</td>
            <td>
              <span class="font-medium">{entry.action_label}</span>
              <span class="block font-mono text-xs text-muted">{entry.action}</span>
            </td>
            <td>
              {#if entry.user}
                {entry.user.name}<span class="block text-xs text-muted">{entry.user.email}</span>
              {:else}
                <span class="text-muted">Sistema / anónimo</span>
              {/if}
            </td>
            <td class="max-w-md text-xs break-words quiet">
              {#if entry.entity_type}<span class="block"
                  >{entry.entity_type}: {entry.entity_id}</span
                >{/if}
              {describe(entry.metadata)}
            </td>
            <td class="font-mono text-xs whitespace-nowrap quiet">{entry.ip_address ?? '—'}</td>
          </tr>
        {:else}
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-muted">
              No hay registros para los filtros seleccionados.
            </td>
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>

<Pagination pagination={data.result.pagination} {hrefFor} />
