<script lang="ts">
  import StatCard from '$lib/components/ui/StatCard.svelte';
  import type { Dashboard } from '$lib/types/notifications';

  /**
   * Cifras de cabecera del tablero: lo primero que se mira al entrar. Salen del mismo
   * tablero que calcula el backend según el alcance de cada persona (ADR-019).
   */
  let { dashboard }: { dashboard: Dashboard } = $props();

  const stats = $derived.by(() => {
    const contracts = Object.fromEntries(
      (dashboard.contracts?.by_status ?? []).map((s) => [s.status, s.count])
    );
    const reports = Object.fromEntries(
      (dashboard.reports?.by_status ?? []).map((s) => [s.status, s.count])
    );
    const pendingReview = (reports['submitted'] ?? 0) + (reports['resubmitted'] ?? 0);
    const endingSoon = dashboard.contracts?.ending_soon.length ?? 0;
    const payments = dashboard.payments;

    return [
      {
        label: 'Contratos activos',
        value: contracts['active'] ?? 0,
        icon: 'clipboard' as const,
        tone: 'primary' as const,
        hint:
          endingSoon > 0
            ? `${endingSoon} ${endingSoon === 1 ? 'termina' : 'terminan'} pronto`
            : 'Ninguno termina pronto'
      },
      {
        label: 'Informes por revisar',
        value: pendingReview,
        icon: 'file' as const,
        tone: pendingReview > 0 ? ('warning' as const) : ('success' as const),
        hint: pendingReview > 0 ? 'Esperan su decisión' : 'Sin cola de revisión'
      },
      {
        label: 'Pagos en trámite',
        value: (payments?.to_approve ?? 0) + (payments?.to_register ?? 0),
        icon: 'wallet' as const,
        tone: 'info' as const,
        hint: `${payments?.to_approve ?? 0} por aprobar · ${payments?.to_register ?? 0} por pagar`
      },
      {
        label: 'Con observaciones',
        value: reports['observed'] ?? 0,
        icon: 'alert' as const,
        tone: 'attention' as const,
        hint: 'Devueltos al contratista'
      }
    ];
  });
</script>

{#if dashboard.contracts || dashboard.reports || dashboard.payments}
  <ul class="stagger mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    {#each stats as stat (stat.label)}
      <li class="h-full">
        <StatCard
          label={stat.label}
          value={stat.value}
          icon={stat.icon}
          tone={stat.tone}
          hint={stat.hint}
        />
      </li>
    {/each}
  </ul>
{/if}
