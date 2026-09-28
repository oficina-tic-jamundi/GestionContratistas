<script lang="ts">
  import type { Snippet } from 'svelte';

  /**
   * Encabezado de pantalla: el único <h1> de la página, con su descripción y las acciones
   * principales a la derecha (debajo en celular).
   */
  let { title, description, actions }: { title: string; description?: string; actions?: Snippet } =
    $props();
</script>

<svelte:head>
  <title>{title} · SIGCON</title>
</svelte:head>

<div class="mb-6 flex flex-wrap items-end justify-between gap-x-4 gap-y-3">
  <div class="min-w-0">
    <h1 class="title-page">{title}</h1>
    {#if description}<p class="mt-1 max-w-2xl text-sm text-muted">{description}</p>{/if}
  </div>
  {#if actions}<div class="flex flex-wrap items-center gap-2">{@render actions()}</div>{/if}
</div>
