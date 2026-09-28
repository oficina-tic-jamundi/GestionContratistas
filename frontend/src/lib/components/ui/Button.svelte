<script lang="ts">
  import type { Snippet } from 'svelte';
  import type { HTMLButtonAttributes } from 'svelte/elements';
  import type { ResolvedPathname } from '$app/types';

  type Variant = 'primary' | 'secondary' | 'danger' | 'ghost';

  /**
   * Botón de la aplicación. Alturas fijas por tamaño (mínimo 44 px en táctil), un solo estilo
   * de foco y una respuesta breve al pulsar, para que todos los botones se sientan iguales.
   */
  let {
    variant = 'primary',
    size = 'md',
    loading = false,
    href,
    disabled = false,
    type = 'button',
    class: className = '',
    children,
    ...rest
  }: HTMLButtonAttributes & {
    variant?: Variant;
    size?: 'sm' | 'md' | 'lg';
    loading?: boolean;
    /** Ruta ya resuelta con resolve() de $app/paths. */
    href?: ResolvedPathname;
    children: Snippet;
  } = $props();

  const variants: Record<Variant, string> = {
    primary: 'btn-primary border-transparent font-semibold hover:brightness-110',
    secondary:
      'bg-field text-ink border-border hover:border-primary/50 hover:bg-primary-soft hover:text-ink',
    danger: 'bg-danger-strong text-white hover:brightness-110 border-transparent',
    ghost: 'bg-transparent text-primary border-transparent hover:bg-primary-soft'
  };
  const sizes = {
    sm: 'h-9 px-3 text-xs',
    md: 'h-11 px-4 text-sm',
    lg: 'h-13 px-6 text-base'
  };

  const classes = $derived(
    `inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border font-medium
     transition-[background-color,border-color,color,box-shadow,transform,filter] duration-150 ease-out
     active:scale-[0.98] disabled:pointer-events-none disabled:opacity-55 disabled:active:scale-100
     ${variants[variant]} ${sizes[size]} ${className}`
  );
</script>

{#if href}
  <!-- eslint-disable-next-line svelte/no-navigation-without-resolve -- href ya viene resuelto (tipo ResolvedPathname) -->
  <a {href} class={classes}>{@render children()}</a>
{:else}
  <button {type} class={classes} disabled={disabled || loading} aria-busy={loading} {...rest}>
    {#if loading}
      <span
        class="size-4 animate-spin rounded-full border-2 border-current border-t-transparent"
        aria-hidden="true"
      ></span>
    {/if}
    {@render children()}
  </button>
{/if}
