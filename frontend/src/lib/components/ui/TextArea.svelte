<script lang="ts">
  import type { HTMLTextareaAttributes } from 'svelte/elements';

  let {
    label,
    value = $bindable(''),
    errors = [],
    hint,
    required = false,
    rows = 4,
    ...rest
  }: Omit<HTMLTextareaAttributes, 'value'> & {
    label: string;
    value?: string | null;
    errors?: string[];
    hint?: string;
  } = $props();

  const uid = $props.id();
  const describedBy = $derived(
    [errors.length > 0 ? `${uid}-error` : null, hint ? `${uid}-hint` : null]
      .filter(Boolean)
      .join(' ') || undefined
  );
</script>

<div class="space-y-1.5">
  <label for={uid} class="block text-sm font-medium text-ink">
    {label}
    {#if required}<span class="text-danger" aria-hidden="true">*</span>{/if}
  </label>
  <textarea
    id={uid}
    bind:value
    {required}
    {rows}
    aria-invalid={errors.length > 0}
    aria-describedby={describedBy}
    class="block w-full rounded-xl border bg-field px-3.5 py-2.5 text-sm text-ink
      transition-[border-color,box-shadow] duration-150 ease-out
      placeholder:text-muted/80 focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none
      disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted
      {errors.length > 0 ? 'border-danger' : 'border-border hover:border-border-strong'}"
    {...rest}></textarea>
  {#if hint}<p id="{uid}-hint" class="text-xs text-muted">{hint}</p>{/if}
  {#if errors.length > 0}
    <ul id="{uid}-error" class="space-y-0.5 text-xs text-danger">
      {#each errors as error (error)}<li>{error}</li>{/each}
    </ul>
  {/if}
</div>
