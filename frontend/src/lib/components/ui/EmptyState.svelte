<script lang="ts">
  import type { Snippet } from 'svelte';
  import Icon, { type IconName } from './Icon.svelte';

  /**
   * Estado vacío: explica por qué no hay datos y, cuando corresponde, ofrece la acción que
   * los crea. Nunca se deja una tabla o una lista en blanco.
   */
  let {
    title,
    description,
    icon = 'inbox',
    action
  }: {
    title: string;
    description?: string;
    icon?: IconName;
    action?: Snippet;
  } = $props();
</script>

<div class="flex flex-col items-center px-6 py-12 text-center">
  <span class="grid size-14 place-items-center rounded-2xl bg-canvas text-muted" aria-hidden="true">
    <Icon name={icon} class="size-7" />
  </span>
  <p class="mt-4 font-semibold text-ink">{title}</p>
  {#if description}
    <p class="mt-1 max-w-md text-sm text-muted">{description}</p>
  {/if}
  {#if action}
    <div class="mt-5">{@render action()}</div>
  {/if}
</div>
