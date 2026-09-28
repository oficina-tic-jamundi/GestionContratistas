<script lang="ts">
  import { resolve } from '$app/paths';
  import Icon from '$lib/components/ui/Icon.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import PriorityBadge from '$lib/features/activities/PriorityBadge.svelte';
  import ContractStatusBadge from '$lib/features/contracts/ContractStatusBadge.svelte';
  import type { Activity } from '$lib/types/activities';
  import { formatDate } from '$lib/utils/format';
  import type { PageProps } from './$types';

  /**
   * Mis actividades (ADR-021): una tarjeta por obligación del contrato. Al abrirla se ve qué
   * hay que hacer y desde allí se envía el informe.
   */
  let { data }: PageProps = $props();

  const num = (v: string) => Math.max(0, Math.min(100, Number(v) || 0));
  const pct = (v: string) => `${num(v).toLocaleString('es-CO', { maximumFractionDigits: 0 })} %`;
  const fill = (v: string) =>
    num(v) >= 100 ? 'bg-success' : num(v) > 0 ? 'bg-warning' : 'bg-border';
  const countTasks = (item: Activity): number =>
    item.children.length === 0
      ? 0
      : item.children.length + item.children.reduce((total, c) => total + countTasks(c), 0);
</script>

<PageHeader
  title="Mis actividades"
  description="Abra una actividad para ver qué debe hacer y enviar su informe."
/>

<div class="space-y-8">
  {#each data.sections as section (section.contract.uuid)}
    <section aria-labelledby="contrato-{section.contract.uuid}">
      <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-0">
          <h2 id="contrato-{section.contract.uuid}" class="text-lg font-semibold text-ink">
            Contrato {section.contract.contract_number}
          </h2>
          <p class="text-sm text-muted">
            {section.contract.department.name} · hasta {formatDate(section.contract.end_date)}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <ContractStatusBadge
            status={section.contract.status}
            label={section.contract.status_label}
          />
          <span class="text-sm font-semibold text-ink tabular-nums"
            >{pct(section.tree.progress)} de avance</span
          >
        </div>
      </div>

      <ul class="stagger grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        {#each section.tree.items as activity (activity.uuid)}
          {@const tasks = countTasks(activity)}
          <li>
            <a
              href={resolve('/(app)/activities/[uuid]', { uuid: activity.uuid })}
              class="card-interactive flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card hover:bg-primary-soft"
            >
              <span class="flex items-start justify-between gap-3">
                <span class="text-base font-semibold text-ink">{activity.title}</span>
                <Icon name="chevron" class="size-5 shrink-0 -rotate-90 text-muted" />
              </span>

              {#if activity.description}
                <span class="mt-1 line-clamp-2 text-sm text-muted">{activity.description}</span>
              {/if}

              <span class="mt-3 flex flex-wrap items-center gap-2">
                {#if activity.priority && activity.priority_label}
                  <PriorityBadge priority={activity.priority} label={activity.priority_label} />
                {/if}
                {#if tasks > 0}
                  <span class="text-xs text-muted">{tasks} {tasks === 1 ? 'tarea' : 'tareas'}</span>
                {/if}
                {#if activity.due_date}
                  <span class="text-xs text-muted">· entrega {formatDate(activity.due_date)}</span>
                {/if}
              </span>

              <span class="mt-auto pt-4">
                <span class="flex items-center justify-between text-xs text-muted">
                  <span>Avance</span>
                  <span class="font-semibold text-ink tabular-nums">{pct(activity.progress)}</span>
                </span>
                <span
                  class="mt-1 block h-2 overflow-hidden rounded-full bg-canvas"
                  role="progressbar"
                  aria-label="Avance de {activity.title}"
                  aria-valuemin={0}
                  aria-valuemax={100}
                  aria-valuenow={num(activity.progress)}
                  aria-valuetext={pct(activity.progress)}
                >
                  <span
                    class="block h-full rounded-full {fill(activity.progress)}"
                    style:width="{num(activity.progress)}%"
                  ></span>
                </span>
              </span>
            </a>
          </li>
        {:else}
          <li
            class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted md:col-span-2 xl:col-span-3"
          >
            Este contrato aún no tiene actividades registradas. La Alcaldía las registra al
            formalizar el contrato.
          </li>
        {/each}
      </ul>
    </section>
  {:else}
    <p class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">
      No tiene contratos en ejecución. Cuando la Alcaldía active su contrato, aquí verá sus
      actividades.
    </p>
  {/each}
</div>
