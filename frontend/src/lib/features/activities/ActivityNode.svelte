<script lang="ts">
  import ProgressBar from '$lib/components/ui/ProgressBar.svelte';
  import {
    PRIORITY_OPTIONS,
    type Activity,
    type ActivityActions,
    type ActivityPriority,
    type ActivityTree
  } from '$lib/types/activities';
  import { formatDate } from '$lib/utils/format';
  import ActivityNode from './ActivityNode.svelte';
  import PriorityBadge from './PriorityBadge.svelte';

  /**
   * Un elemento del árbol (obligación, tarea o subtarea) con sus hijos.
   * Los botones reflejan las capacidades que informa el backend; el backend vuelve a validar.
   */
  let {
    item,
    can,
    actions,
    numbering
  }: { item: Activity; can: ActivityTree['can']; actions: ActivityActions; numbering: string } =
    $props();

  const isLeaf = $derived(item.children.length === 0);
  const canEdit = $derived(item.level === 'obligation' ? can.manage_obligations : can.plan);
  // No se divide un elemento que ya tiene avances registrados (ADR-013).
  const canAdd = $derived(
    item.level !== 'subtask' && can.plan && !(isLeaf && item.has_progress_updates)
  );
  const canDelete = $derived(canEdit && isLeaf && !item.has_progress_updates);
  const meta = $derived(
    [
      item.level_label,
      item.weight !== '1.00' ? `peso ${item.weight}` : null,
      item.due_date ? `fecha objetivo ${formatDate(item.due_date)}` : null,
      item.progress_is_computed ? 'avance calculado' : null
    ]
      .filter(Boolean)
      .join(' · ')
  );
  const childLabel = $derived(item.level === 'obligation' ? 'tarea' : 'subtarea');

  const btn = 'rounded px-2 py-1 text-xs text-primary hover:bg-primary-soft';
  const priorityId = $props.id();

  function changePriority(event: Event): void {
    const value = (event.currentTarget as HTMLSelectElement).value;
    actions.priority(item, value === '' ? null : (value as ActivityPriority));
  }
</script>

<li class="py-3">
  <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0 flex-1">
      <p class="text-sm text-ink">
        <span class="font-mono text-xs text-muted">{numbering}</span>
        <span class={item.level === 'obligation' ? 'font-semibold' : 'font-medium'}
          >{item.title}</span
        >
        {#if item.priority && item.priority_label}
          <PriorityBadge priority={item.priority} label={item.priority_label} />
        {/if}
      </p>
      {#if item.description}
        <p class="mt-0.5 text-xs whitespace-pre-line text-muted">{item.description}</p>
      {/if}
      <p class="mt-0.5 text-xs text-muted">{meta}</p>
    </div>
    <div class="w-full sm:w-56">
      <ProgressBar value={item.progress} label="Avance de {item.title}" size="sm" />
    </div>
  </div>

  <div class="mt-1 flex flex-wrap gap-1">
    {#if can.record_progress && isLeaf}
      <button type="button" class="{btn} font-medium" onclick={() => actions.progress(item)}
        >Registrar avance</button
      >
    {/if}
    {#if item.has_progress_updates}
      <button type="button" class={btn} onclick={() => actions.history(item)}>Ver avances</button>
    {/if}
    {#if canAdd}
      <button type="button" class={btn} onclick={() => actions.add(item)}
        >+ Agregar {childLabel}</button
      >
    {/if}
    {#if canEdit}
      <button type="button" class={btn} onclick={() => actions.edit(item)}>Editar</button>
    {/if}
    {#if can.set_priority}
      <label for={priorityId} class="sr-only">Prioridad de {item.title}</label>
      <select
        id={priorityId}
        class="rounded-lg border border-border bg-field px-2 py-1 text-xs text-ink"
        value={item.priority ?? ''}
        onchange={changePriority}
      >
        <option value="">Sin prioridad</option>
        {#each PRIORITY_OPTIONS as option (option.value)}
          <option value={option.value}>Prioridad {option.label.toLowerCase()}</option>
        {/each}
      </select>
    {/if}
    {#if canDelete}
      <button
        type="button"
        class="rounded px-2 py-1 text-xs text-danger hover:bg-danger-soft"
        onclick={() => actions.remove(item)}>Eliminar</button
      >
    {/if}
  </div>

  {#if item.children.length > 0}
    <ul class="mt-2 divide-y divide-border border-l-2 border-border pl-4">
      {#each item.children as child, i (child.uuid)}
        <ActivityNode item={child} {can} {actions} numbering="{numbering}{i + 1}." />
      {/each}
    </ul>
  {/if}
</li>
