<script lang="ts">
  import { goto, invalidate } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import { retryJob } from '$lib/features/jobs/api';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { Job } from '$lib/types/jobs';
  import { buildQuery, formatDateTime } from '$lib/utils/format';
  import { formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let status = $derived(data.query.status ?? '');
  let retrying = $state<string | null>(null);

  const TONES = {
    pending: 'info',
    processing: 'warning',
    completed: 'success',
    failed: 'danger'
  } as const;

  const STATUS_OPTIONS = [
    { value: 'failed', label: 'Fallidos' },
    { value: 'pending', label: 'Pendientes' },
    { value: 'processing', label: 'En proceso' },
    { value: 'completed', label: 'Completados' }
  ];

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/jobs?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function apply(event: SubmitEvent): void {
    event.preventDefault();
    void goto(resolve(`/jobs?${buildQuery({ status, page: null })}`), { keepFocus: true });
  }

  async function retry(job: Job): Promise<void> {
    retrying = job.uuid;
    try {
      await retryJob(job.uuid);
      await invalidate('app:jobs');
      toasts.show('La tarea se volverá a ejecutar en la próxima pasada del procesador.');
    } catch (e) {
      toasts.show(formMessage(e) ?? 'No fue posible reintentar la tarea.', 'error');
    } finally {
      retrying = null;
    }
  }
</script>

<PageHeader
  title="Tareas programadas"
  description="Trabajos en segundo plano (PDF de informes, etc.). Los procesa el Cron del servidor cada 5 minutos."
>
  {#snippet actions()}
    <Button variant="secondary" onclick={() => invalidate('app:jobs')}>Actualizar</Button>
  {/snippet}
</PageHeader>

<div class="mb-6 grid gap-4 lg:grid-cols-3">
  <Card title="Procesador">
    <div class="space-y-3 text-sm">
      {#if data.status.stale}
        <Alert variant="danger">
          {data.status.last_run
            ? `El procesador no se ejecuta desde hace más de ${data.status.stale_after_minutes} minutos.`
            : 'El procesador nunca se ha ejecutado.'}
          Verifique el Cron de cPanel (<code>bin/console jobs:run</code> cada 5 minutos).
        </Alert>
      {:else}
        <Alert variant="success">El procesador se está ejecutando con normalidad.</Alert>
      {/if}
      {#if data.status.last_run}
        <dl class="space-y-1">
          <div class="flex justify-between gap-2">
            <dt class="text-muted">Última ejecución</dt>
            <dd>{formatDateTime(data.status.last_run.started_at)}</dd>
          </div>
          <div class="flex justify-between gap-2">
            <dt class="text-muted">Procesados / con error</dt>
            <dd>{data.status.last_run.processed} / {data.status.last_run.failed}</dd>
          </div>
        </dl>
      {/if}
    </div>
  </Card>

  <div class="lg:col-span-2">
    <Card title="Cola">
      <dl class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
        {#each STATUS_OPTIONS as option (option.value)}
          <div class="rounded-md border border-border p-3">
            <dt class="text-muted">{option.label}</dt>
            <dd
              class="text-2xl font-semibold {option.value === 'failed' &&
              data.status.counts.failed > 0
                ? 'text-danger'
                : 'text-ink'}"
            >
              {data.status.counts[option.value as keyof typeof data.status.counts]}
            </dd>
          </div>
        {/each}
      </dl>
    </Card>
  </div>
</div>

<form
  class="mb-4 flex flex-wrap items-end gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card"
  onsubmit={apply}
  role="search"
>
  <div class="min-w-48">
    <SelectField label="Estado" bind:value={status} placeholder="Todos" options={STATUS_OPTIONS} />
  </div>
  <Button type="submit" variant="secondary">Filtrar</Button>
</form>

<ul class="space-y-3">
  {#each data.result.items as job (job.uuid)}
    <li class="rounded-2xl border border-border bg-surface p-4 shadow-card text-sm shadow-xs">
      <div class="flex flex-wrap items-start justify-between gap-2">
        <div>
          <p class="font-medium text-ink">{job.type_label}</p>
          <p class="text-xs text-muted">
            Creada {formatDateTime(job.created_at)} · intentos {job.attempts} de {job.max_attempts}
            {#if job.status === 'pending' && job.attempts > 0}
              · próximo intento {formatDateTime(job.available_at)}{/if}
            {#if job.completed_at}· completada {formatDateTime(job.completed_at)}{/if}
          </p>
        </div>
        <div class="flex items-center gap-2">
          <Badge tone={TONES[job.status]}>{job.status_label}</Badge>
          {#if job.status === 'failed'}
            <Button
              size="sm"
              variant="secondary"
              loading={retrying === job.uuid}
              onclick={() => retry(job)}>Reintentar</Button
            >
          {/if}
        </div>
      </div>
      {#if job.last_error}
        <p class="mt-2 rounded bg-canvas px-3 py-2 text-xs break-words text-danger">
          {job.last_error}
        </p>
      {/if}
    </li>
  {:else}
    <li
      class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted"
    >
      No hay tareas para mostrar.
    </li>
  {/each}
</ul>

<Pagination pagination={data.result.pagination} {hrefFor} />
