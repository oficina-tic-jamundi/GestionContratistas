<script lang="ts">
  import type { HTMLInputAttributes } from 'svelte/elements';
  import Icon from './Icon.svelte';

  /**
   * Campo de texto de la aplicación. Etiqueta siempre visible, ayuda y error asociados por
   * `aria-describedby`, y una sola forma de marcar el error (borde, icono y texto).
   * En los campos de contraseña aparece el botón para mostrar u ocultar lo escrito.
   */
  let {
    label,
    value = $bindable(''),
    errors = [],
    hint,
    required = false,
    type = 'text',
    class: className = '',
    ...rest
  }: Omit<HTMLInputAttributes, 'value'> & {
    label: string;
    value?: string | null;
    errors?: string[];
    hint?: string;
  } = $props();

  const uid = $props.id();
  const invalid = $derived(errors.length > 0);
  const describedBy = $derived(
    [invalid ? `${uid}-error` : null, hint ? `${uid}-hint` : null].filter(Boolean).join(' ') ||
      undefined
  );

  let revealed = $state(false);
  const isPassword = $derived(type === 'password');
  const inputType = $derived(isPassword && revealed ? 'text' : type);
</script>

<div class="space-y-1.5">
  <label for={uid} class="block text-sm font-medium text-ink">
    {label}
    {#if required}<span class="text-danger" aria-hidden="true">*</span>{/if}
  </label>
  <div class="relative">
    <input
      id={uid}
      type={inputType}
      bind:value
      {required}
      aria-invalid={invalid}
      aria-describedby={describedBy}
      class="block h-11 w-full rounded-xl border bg-field px-3.5 text-sm text-ink
        transition-[border-color,box-shadow] duration-150 ease-out
        placeholder:text-muted/80 focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none
        disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted
        {invalid ? 'border-danger' : 'border-border hover:border-border-strong'}
        {isPassword ? 'pr-12' : ''} {className}"
      {...rest}
    />
    {#if isPassword}
      <button
        type="button"
        class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center rounded-r-xl text-muted transition-colors hover:text-ink"
        aria-label={revealed ? 'Ocultar la contraseña' : 'Mostrar la contraseña'}
        aria-pressed={revealed}
        onclick={() => (revealed = !revealed)}
      >
        <Icon name={revealed ? 'eye-off' : 'eye'} class="size-5" />
      </button>
    {/if}
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
