<script lang="ts">
  import Badge from '$lib/components/ui/Badge.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import ErrorState from '$lib/components/ui/ErrorState.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import { Permission } from '$lib/auth/permissions';
  import { session } from '$lib/features/auth/session.svelte';
  import ContractorDashboard from '$lib/features/dashboard/ContractorDashboard.svelte';
  import DashboardStats from '$lib/features/dashboard/DashboardStats.svelte';
  import Skeleton from '$lib/components/ui/Skeleton.svelte';
  import DashboardPanels from '$lib/features/notifications/DashboardPanels.svelte';
  import { formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  // El contratista (sin perfil de supervisión ni de administración) ve su panel propio (ADR-021).
  const contractorView = $derived(
    session.can(Permission.ActivitiesExecute) &&
      !session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)
  );
</script>

<PageHeader
  title="Panel de control"
  description={session.user ? `Bienvenido(a), ${session.user.first_name}.` : undefined}
/>

{#if contractorView}
  {#await data.dashboard}
    <Skeleton lines={5} label="Cargando el panel…" />
  {:then dashboard}
    <ContractorDashboard {dashboard} />
  {:catch err}
    <ErrorState error={err} title="No fue posible cargar el panel" />
  {/await}
{:else}
  {#await data.dashboard then dashboard}
    <DashboardStats {dashboard} />
  {/await}

  <div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
      {#await data.dashboard}
        <Skeleton lines={4} label="Cargando el tablero…" />
      {:then dashboard}
        <DashboardPanels {dashboard} />
      {:catch err}
        <ErrorState error={err} title="No fue posible cargar el tablero" />
      {/await}
    </div>

    <div class="space-y-6">
      <Card title="Estado del sistema">
        {#await data.health}
          <Skeleton lines={3} card={false} label="Consultando el estado…" />
        {:then health}
          <dl class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <dt class="text-muted">API</dt>
              <dd>
                <Badge tone={health.status === 'ok' ? 'success' : 'warning'}
                  >{health.status === 'ok' ? 'Operativa' : 'Degradada'}</Badge
                >
              </dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-muted">Base de datos</dt>
              <dd>
                <Badge tone={health.checks.database === 'ok' ? 'success' : 'danger'}
                  >{health.checks.database === 'ok' ? 'Conectada' : 'No disponible'}</Badge
                >
              </dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-muted">Hora del servidor</dt>
              <dd>{formatDateTime(health.server_time)}</dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-muted">Versión</dt>
              <dd class="font-mono">{health.version}</dd>
            </div>
          </dl>
        {:catch err}
          <ErrorState error={err} title="No fue posible consultar el estado" />
        {/await}
      </Card>
    </div>
  </div>
{/if}
