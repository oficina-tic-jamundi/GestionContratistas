<script lang="ts">
  import type { Snippet } from 'svelte';
  import Icon, { type IconName } from './Icon.svelte';

  /**
   * Panel de contenido. Es la unidad visual de todas las pantallas: mismo radio, mismo borde
   * y misma sombra, para que la interfaz se lea como un solo sistema.
   */
  let {
    title,
    description,
    icon,
    children,
    actions,
    padded = true
  }: {
    title?: string;
    description?: string;
    /** Icono del encabezado; refuerza de qué trata el panel. */
    icon?: IconName;
    children: Snippet;
    actions?: Snippet;
    padded?: boolean;
  } = $props();
</script>

<section class="rounded-2xl border border-border bg-surface shadow-card">
  {#if title}
    <header class="flex items-start justify-between gap-3 border-b border-border px-5 py-4">
      <div class="flex min-w-0 items-start gap-3">
        {#if icon}
          <span
            class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary"
            aria-hidden="true"
          >
            <Icon name={icon} class="size-5" />
          </span>
        {/if}
        <div class="min-w-0">
          <h2 class="title-section">{title}</h2>
          {#if description}<p class="mt-0.5 text-xs text-muted">{description}</p>{/if}
        </div>
      </div>
      {#if actions}<div class="shrink-0">{@render actions()}</div>{/if}
    </header>
  {/if}
  <div class={padded ? 'p-5' : ''}>{@render children()}</div>
</section>
