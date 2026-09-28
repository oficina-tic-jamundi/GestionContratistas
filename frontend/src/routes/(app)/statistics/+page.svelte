<script lang="ts">
  import { resolve } from '$app/paths';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import ScrollRegion from '$lib/components/ui/ScrollRegion.svelte';
  import ContractStatusBadge from '$lib/features/contracts/ContractStatusBadge.svelte';
  import type { ContractStatus } from '$lib/types/contracts';
  import { formatDate, formatMoney } from '$lib/utils/format';
  import type { PageProps } from './$types';

  /**
   * Estadísticas de pagos (ADR-021): cuántos pagos lleva cada contrato y cuántos le faltan.
   * "Faltan" solo se muestra si el contrato declara cuántos pagos se pactaron.
   */
  let { data }: PageProps = $props();

  const totals = $derived(data.statistics.totals);
  const pct = (v: string) => `${Number(v).toLocaleString('es-CO', { maximumFractionDigits: 1 })} %`;
</script>

<PageHeader
  title="Estadísticas"
  description="Esquema de pagos por contrato: plazo, pagos pactados, pagados y pendientes."
/>

<ul class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-3xl font-bold text-ink tabular-nums">{totals.contracts}</p>
    <p class="mt-1 text-sm text-muted">Contratos en seguimiento</p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-3xl font-bold text-success tabular-nums">{totals.paid}</p>
    <p class="mt-1 text-sm text-muted">Pagos realizados</p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-3xl font-bold text-warning tabular-nums">{totals.in_process}</p>
    <p class="mt-1 text-sm text-muted">Pagos en trámite</p>
  </li>
  <li class="rounded-2xl border border-border bg-surface p-4">
    <p class="text-2xl font-bold text-ink tabular-nums">{formatMoney(totals.paid_amount)}</p>
    <p class="mt-1 text-sm text-muted">Pagado de {formatMoney(totals.total_value)}</p>
  </li>
</ul>

{#if totals.without_agreed > 0}
  <p class="mb-4 rounded-xl border border-border bg-canvas px-4 py-3 text-sm text-muted">
    {totals.without_agreed}
    {totals.without_agreed === 1 ? 'contrato no tiene' : 'contratos no tienen'} registrados los pagos
    pactados, así que no se puede calcular cuántos faltan. Se indican al crear o editar el contrato, mientras
    está en borrador.
  </p>
{/if}

<div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">
  <ScrollRegion label="Esquema de pagos por contrato">
    <table class="data-table min-w-3xl">
      <thead>
        <tr>
          <th scope="col">Contrato</th>
          <th scope="col">Plazo</th>
          <th scope="col" class="num">Avance</th>
          <th scope="col" class="num">Pactados</th>
          <th scope="col" class="num">Pagados</th>
          <th scope="col" class="num">En trámite</th>
          <th scope="col" class="num">Faltan</th>
          <th scope="col" class="num">Pagado</th>
        </tr>
      </thead>
      <tbody>
        {#each data.statistics.contracts as row (row.uuid)}
          <tr>
            <td>
              <a
                href={resolve('/(app)/contracts/[uuid]', { uuid: row.uuid })}
                class="font-medium text-primary hover:underline">{row.contract_number}</a
              >
              <span class="block text-xs text-muted">{row.contractor} · {row.department}</span>
              <span class="mt-1 inline-block">
                <ContractStatusBadge
                  status={row.status as ContractStatus}
                  label={row.status_label}
                />
              </span>
            </td>
            <td class="quiet">
              {row.months}
              {row.months === 1 ? 'mes' : 'meses'}
              <span class="block text-xs">
                {formatDate(row.start_date)} – {formatDate(row.end_date)}
              </span>
            </td>
            <td class="num">{pct(row.progress)}</td>
            <td class="num">{row.payments.agreed ?? '—'}</td>
            <td class="num font-semibold text-success">{row.payments.paid}</td>
            <td class="num">{row.payments.in_process}</td>
            <td class="num">
              {#if row.payments.pending === null}
                <span class="text-muted">—</span>
              {:else}
                <span class={row.payments.pending === 0 ? 'text-success' : 'text-ink'}
                  >{row.payments.pending}</span
                >
              {/if}
            </td>
            <td class="num">{formatMoney(row.payments.paid_amount)}</td>
          </tr>
        {:else}
          <tr>
            <td class="px-4 py-6 text-muted" colspan="8">No hay contratos en su alcance.</td>
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>
