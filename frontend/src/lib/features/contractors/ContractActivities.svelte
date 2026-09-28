<script lang="ts">
  import { resolve } from '$app/paths';
  import Icon from '$lib/components/ui/Icon.svelte';
  import PriorityBadge from '$lib/features/activities/PriorityBadge.svelte';
  import type { Activity, ActivityTree } from '$lib/types/activities';
  import { formatDate } from '$lib/utils/format';

  /**
   * Actividades del contrato en modo consulta: avance del contrato arriba y, debajo, cada
   * obligación con sus tareas. Sin botones de edición: quien planea lo hace desde el contrato.
   * Al abrir una obligación se ve qué debe hacerse y, para quien revisa, el informe del período.
   */
  let { tree }: { tree: ActivityTree } = $props();

  const num = (v: string) => Math.max(0, Math.min(100, Number(v) || 0));
  const pct = (v: string) => `${num(v).toLocaleString('es-CO', { maximumFractionDigits: 1 })} %`;
  const fill = (v: string) =>
    num(v) >= 100
      ? 'bg-success'
      : num(v) >= 50
        ? 'bg-primary'
        : num(v) > 0
          ? 'bg-warning'
          : 'bg-border';
  const leaves = (item: Activity): Activity[] =>
    item.children.length === 0 ? [] : item.children.flatMap((c) => [c, ...leaves(c)]);
</script>

<div class="mt-5">
  <div class="flex items-center justify-between gap-3">
    <p class="text-sm font-medium text-ink">Avance del contrato</p>
    <p class="text-lg font-bold tabular-nums text-ink">{pct(tree.progress)}</p>
  </div>
  <div
    class="mt-2 h-3 overflow-hidden rounded-full bg-canvas ring-1 ring-border"
    role="progressbar"
    aria-label="Avance del contrato"
    aria-valuemin={0}
    aria-valuemax={100}
    aria-valuenow={num(tree.progress)}
    aria-valuetext={pct(tree.progress)}
  >
    <div
      class="h-full rounded-full {fill(tree.progress)}"
      style:width="{num(tree.progress)}%"
    ></div>
  </div>
  <p class="mt-2 text-xs text-muted">
    El avance de cada tarea lo registra el supervisor al verificar lo ejecutado.
  </p>

  <ul class="mt-4 space-y-3">
    {#each tree.items as obligation (obligation.uuid)}
      <li class="rounded-xl border border-border bg-canvas p-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
          <p class="flex min-w-0 flex-wrap items-center gap-2 text-sm font-medium text-ink">
            <a
              class="inline-flex items-center gap-1 hover:text-primary"
              href={resolve('/(app)/activities/[uuid]', { uuid: obligation.uuid })}
            >
              {obligation.title}
              <Icon name="chevron" class="size-4 -rotate-90 text-muted" />
            </a>
            {#if obligation.priority && obligation.priority_label}
              <PriorityBadge priority={obligation.priority} label={obligation.priority_label} />
            {/if}
          </p>
          <p class="text-sm font-semibold tabular-nums text-ink">{pct(obligation.progress)}</p>
        </div>
        <div
          class="mt-2 h-2 overflow-hidden rounded-full bg-surface"
          role="progressbar"
          aria-label="Avance de {obligation.title}"
          aria-valuemin={0}
          aria-valuemax={100}
          aria-valuenow={num(obligation.progress)}
          aria-valuetext={pct(obligation.progress)}
        >
          <div
            class="h-full rounded-full {fill(obligation.progress)}"
            style:width="{num(obligation.progress)}%"
          ></div>
        </div>

        {#if leaves(obligation).length > 0}
          <ul class="mt-3 space-y-1.5">
            {#each leaves(obligation) as task (task.uuid)}
              <li class="flex flex-wrap items-baseline justify-between gap-x-3 text-sm">
                <span class="text-ink">
                  {task.title}
                  <span class="text-xs text-muted">({task.level_label.toLowerCase()})</span>
                  {#if task.due_date}
                    <span class="text-xs text-muted">· entrega {formatDate(task.due_date)}</span>
                  {/if}
                </span>
                <span class="tabular-nums text-muted">{pct(task.progress)}</span>
              </li>
            {/each}
          </ul>
        {/if}
      </li>
    {:else}
      <li class="rounded-xl border border-border bg-canvas p-4 text-sm text-muted">
        Este contrato aún no tiene obligaciones registradas.
      </li>
    {/each}
  </ul>
</div>
