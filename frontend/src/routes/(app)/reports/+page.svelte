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
  import { session } from '$lib/features/auth/session.svelte';
  import ReportStatusBadge from '$lib/features/reports/ReportStatusBadge.svelte';
  import { buildQuery, formatDate, formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let status = $derived(data.query.status ?? '');

  const isContractor = $derived(session.can(Permission.ReportsCreate));
  const isReviewer = $derived(session.can(Permission.ReportsReview));

  const STATUS_OPTIONS = [
    { value: 'pending_review', label: 'Pendientes de revisión' },
    { value: 'draft', label: 'Borrador' },
    { value: 'submitted', label: 'Enviado' },
    { value: 'in_review', label: 'En revisión' },
    { value: 'observed', label: 'Con observaciones' },
    { value: 'resubmitted', label: 'Corregido (reenviado)' },
    { value: 'approved', label: 'Aprobado' },
    { value: 'rejected', label: 'Rechazado' }
  ];

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/reports?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    void goto(resolve(`/reports?${buildQuery({ status, page: null })}`), { keepFocus: true });
  }
</script>

<PageHeader
  title={isContractor ? 'Mis informes' : 'Informes'}
  description={isContractor
    ? 'Para elaborar un informe nuevo, abra el contrato correspondiente.'
    : isReviewer
      ? 'Informes de los contratos que supervisa. Los borradores solo los ve el contratista.'
      : 'Informes de los contratos a los que tiene acceso.'}
>
  {#snippet actions()}
    {#if isContractor}
      <Button href={resolve('/contracts')} variant="secondary">Ir a mis contratos</Button>
    {/if}
  {/snippet}
</PageHeader>

<form
  class="mb-4 flex flex-wrap items-end gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card"
  onsubmit={apply}
  role="search"
>
  <div class="min-w-56">
    <SelectField label="Estado" bind:value={status} placeholder="Todos" options={STATUS_OPTIONS} />
  </div>
  <Button type="submit" variant="secondary">Filtrar</Button>
  {#if isReviewer}
    <Button variant="ghost" href={resolve(`/reports?${buildQuery({ status: 'pending_review' })}`)}
      >Pendientes de revisión</Button
    >
  {/if}
</form>

<ul class="space-y-3 md:hidden">
  {#each data.result.items as report (report.uuid)}
    <li>
      <a
        href={resolve('/(app)/reports/[uuid]', { uuid: report.uuid })}
        class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"
      >
        <div class="flex items-start justify-between gap-2">
          <span class="font-medium text-primary"
            >{report.contract.contract_number} · N.° {report.number}</span
          >
          <ReportStatusBadge status={report.status} label={report.status_label} />
        </div>
        <p class="mt-1 text-sm text-ink">{report.contract.contractor}</p>
        <p class="mt-1 text-xs text-muted">
          {formatDate(report.period_start)} – {formatDate(report.period_end)}
        </p>
      </a>
    </li>
  {:else}
    <li
      class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted"
    >
      No hay informes para mostrar.
    </li>
  {/each}
</ul>

<div
  class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block"
>
  <ScrollRegion label="Tabla de informes">
    <table class="data-table">
      <thead>
        <tr>
          <th scope="col">Informe</th>
          <th scope="col">Contratista</th>
          <th scope="col">Período</th>
          <th scope="col">Versión</th>
          <th scope="col">Actualizado</th>
          <th scope="col">Estado</th>
        </tr>
      </thead>
      <tbody>
        {#each data.result.items as report (report.uuid)}
          <tr>
            <td class="whitespace-nowrap">
              <a
                href={resolve('/(app)/reports/[uuid]', { uuid: report.uuid })}
                class="font-medium text-primary hover:underline"
                >{report.contract.contract_number} · N.° {report.number}</a
              >
            </td>
            <td>{report.contract.contractor}</td>
            <td class="whitespace-nowrap quiet">
              {formatDate(report.period_start)} – {formatDate(report.period_end)}
            </td>
            <td class="quiet">{report.current_version || '—'}</td>
            <td class="whitespace-nowrap quiet">{formatDateTime(report.updated_at)}</td>
            <td>
              <ReportStatusBadge status={report.status} label={report.status_label} />
            </td>
          </tr>
        {:else}
          <tr>
            <td colspan="6" class="px-4 py-10 text-center text-muted"
              >No hay informes para mostrar.</td
            >
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>

<Pagination pagination={data.result.pagination} {hrefFor} />
