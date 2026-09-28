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
  import PaymentStatusBadge from '$lib/features/payments/PaymentStatusBadge.svelte';
  import { buildQuery, formatDate, formatMoney } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let status = $derived(data.query.status ?? '');

  const STATUS_OPTIONS = [
    { value: 'draft', label: 'En preparación' },
    { value: 'ready_for_approval', label: 'Listos para aprobación' },
    { value: 'approved', label: 'Aprobados' },
    { value: 'paid', label: 'Pagados' },
    { value: 'cancelled', label: 'Anulados' }
  ];

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/payments?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    void goto(resolve(`/payments?${buildQuery({ status, page: null })}`), { keepFocus: true });
  }
</script>

<PageHeader
  title="Pagos"
  description="Cuentas de cobro de los contratos. Se registran a partir de informes aprobados; SIGCON controla el trámite y registra el pago efectuado por tesorería."
>
  {#snippet actions()}
    {#if session.canAny(Permission.PaymentsConfigure, Permission.PaymentsManage, Permission.PaymentsApprove)}
      <Button href={resolve('/payments/rules')} variant="secondary">Reglas de elegibilidad</Button>
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
</form>

<ul class="space-y-3 md:hidden">
  {#each data.result.items as payment (payment.uuid)}
    <li>
      <a
        href={resolve('/(app)/payments/[uuid]', { uuid: payment.uuid })}
        class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"
      >
        <div class="flex items-start justify-between gap-2">
          <span class="font-medium text-primary"
            >{payment.contract.contract_number} · Pago N.° {payment.number}</span
          >
          <PaymentStatusBadge status={payment.status} label={payment.status_label} />
        </div>
        <p class="mt-1 text-sm text-ink">{payment.contract.contractor}</p>
        <p class="mt-1 text-sm font-semibold">{formatMoney(payment.amount)}</p>
      </a>
    </li>
  {:else}
    <li
      class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted"
    >
      No hay pagos para mostrar.
    </li>
  {/each}
</ul>

<div
  class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block"
>
  <ScrollRegion label="Tabla de pagos">
    <table class="data-table">
      <thead>
        <tr>
          <th scope="col">Pago</th>
          <th scope="col">Contratista</th>
          <th scope="col">Período</th>
          <th scope="col" class="num">Valor</th>
          <th scope="col">Estado</th>
        </tr>
      </thead>
      <tbody>
        {#each data.result.items as payment (payment.uuid)}
          <tr>
            <td class="whitespace-nowrap">
              <a
                href={resolve('/(app)/payments/[uuid]', { uuid: payment.uuid })}
                class="font-medium text-primary hover:underline"
                >{payment.contract.contract_number} · N.° {payment.number}</a
              >
            </td>
            <td>{payment.contract.contractor}</td>
            <td class="whitespace-nowrap quiet">
              {formatDate(payment.period_start)} – {formatDate(payment.period_end)}
            </td>
            <td class="num whitespace-nowrap">{formatMoney(payment.amount)}</td>
            <td>
              <PaymentStatusBadge status={payment.status} label={payment.status_label} />
            </td>
          </tr>
        {:else}
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-muted">No hay pagos para mostrar.</td
            >
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>

<Pagination pagination={data.result.pagination} {hrefFor} />
