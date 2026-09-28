<script lang="ts">
  import type { HTMLSelectAttributes } from 'svelte/elements';
  import Icon from './Icon.svelte';

  interface Option {
    value: string;
    label: string;
  }

  /** Lista desplegable con la misma altura, borde y foco que los campos de texto. */
  let {
    label,
    value = $bindable(''),
    options,
    placeholder,
    errors = [],
    hint,
    required = false,
    ...rest
  }: Omit<HTMLSelectAttributes, 'value'> & {
    label: string;
    value?: string;
    options: Option[];
    placeholder?: string;
    errors?: string[];
    hint?: string;
  } = $props();

  const uid = $props.id();
  const invalid = $derived(errors.length > 0);
  const describedBy = $derived(
    [invalid ? `${uid}-error` : null, hint ? `${uid}-hint` : null].filter(Boolean).join(' ') ||
      undefined
  );
</script>

<div class="space-y-1.5">
  <label for={uid} class="block text-sm font-medium text-ink">
    {label}
    {#if required}<span class="text-danger" aria-hidden="true">*</span>{/if}
  </label>
  <div class="relative">
    <select
      id={uid}
      bind:value
      {required}
      aria-invalid={invalid}
      aria-describedby={describedBy}
      class="block h-11 w-full appearance-none rounded-xl border bg-field bg-none px-3.5 pr-10 text-sm text-ink
        transition-[border-color,box-shadow] duration-150 ease-out
        focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none
        disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted
        {invalid ? 'border-danger' : 'border-border hover:border-border-strong'}"
      {...rest}
    >
      {#if placeholder !== undefined}<option value="">{placeholder}</option>{/if}
      {#each options as option (option.value)}
        <option value={option.value}>{option.label}</option>
      {/each}
    </select>
    <Icon
      name="chevron"
      class="pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2 text-muted"
    />
  </div>
  {#if invalid}
    <p id="{uid}-error" class="flex items-start gap-1.5 text-xs text-danger">
      <Icon name="alert" class="mt-px size-4 shrink-0" />
      <span>{errors.join(' ')}</span>
    </p>
  {:else if hint}
    <p id="{uid}-hint" class="text-xs text-muted">{hint}</p>
  {/if}
</div>

<style>
  /* El menú nativo se dibuja con los colores del sistema: se fijan para el tema oscuro. */
  option {
    background-color: #0f1d33;
    color: #f1f5f9;
  }
</style>
