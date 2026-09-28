<script lang="ts">
  import { resolve } from '$app/paths';
  import Icon from '$lib/components/ui/Icon.svelte';
  import type { TaskDeadline } from '$lib/types/notifications';
  import { formatDate } from '$lib/utils/format';

  /**
   * Alertas preventivas de entrega (ADR-021): tareas con fecha objetivo próxima o vencida.
   * La fecha la registra la Alcaldía al planear la tarea; SIGCON no inventa plazos.
   */
  let {
    tasks,
    withinDays,
    showContractor = false
  }: { tasks: TaskDeadline[]; withinDays: number; showContractor?: boolean } = $props();

  const overdue = $derived(tasks.filter((t) => t.days_left < 0));
  const soon = $derived(tasks.filter((t) => t.days_left >= 0));

  function whenText(task: TaskDeadline): string {
    if (task.days_left < 0) {
      const days = Math.abs(task.days_left);
      return `venció hace ${days} ${days === 1 ? 'día' : 'días'}`;
    }
    if (task.days_left === 0) return 'vence hoy';
    return `quedan ${task.days_left} ${task.days_left === 1 ? 'día' : 'días'}`;
  }
</script>

{#if tasks.length > 0}
  <section
    class="rounded-2xl border px-5 py-4 {overdue.length > 0
      ? 'border-danger/40 bg-danger-soft'
      : 'border-warning/40 bg-warning-soft'}"
    aria-labelledby="alertas-entrega"
    role="status"
  >
    <p
      class="flex items-center gap-2 font-semibold {overdue.length > 0
        ? 'text-danger'
        : 'text-warning'}"
    >
      <Icon name="alert" class="size-5 shrink-0" />
      <span id="alertas-entrega">
        {#if overdue.length > 0}
          Entregas vencidas
        {:else}
          Entregas próximas
        {/if}
      </span>
    </p>
    <p class="mt-1 text-sm text-muted">
      Tareas con fecha objetivo vencida o dentro de los próximos {withinDays} días.
    </p>
    <ul class="mt-3 space-y-1.5 text-sm">
      {#each [...overdue, ...soon] as task (task.activity_uuid)}
        <li class="flex flex-wrap items-baseline gap-x-2">
          <span class="font-medium text-ink">{task.title}</span>
          <span
            class={task.days_left < 0 ? 'font-semibold text-danger' : 'font-semibold text-warning'}
            >— {whenText(task)}</span
          >
          <span class="text-muted">({formatDate(task.due_date)})</span>
          <a
            href={resolve('/(app)/contracts/[uuid]', { uuid: task.contract_uuid })}
            class="text-primary hover:underline">{task.contract_number}</a
          >
          {#if showContractor}<span class="text-muted">· {task.contractor}</span>{/if}
        </li>
      {/each}
    </ul>
  </section>
{/if}
