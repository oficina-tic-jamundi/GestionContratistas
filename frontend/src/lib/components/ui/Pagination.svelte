<script lang="ts">
  import type { ResolvedPathname } from '$app/types';
  import type { PaginationMeta } from '$lib/types/api';

  let {
    pagination,
    hrefFor
  }: { pagination: PaginationMeta; hrefFor: (page: number) => ResolvedPathname } = $props();

  const first = $derived(
    pagination.total === 0 ? 0 : (pagination.page - 1) * pagination.per_page + 1
  );
  const last = $derived(Math.min(pagination.page * pagination.per_page, pagination.total));
  const linkClass =
    'inline-flex h-9 items-center rounded-lg border border-border px-3 text-ink transition-colors hover:border-primary/50 hover:bg-primary-soft';
</script>

<nav
  class="flex flex-wrap items-center justify-between gap-3 px-1 py-3 text-sm"
  aria-label="Paginación"
>
  <p class="text-muted">
    {#if pagination.total === 0}
      Sin resultados
    {:else}
      Mostrando {first}–{last} de {pagination.total}
    {/if}
  </p>
  {#if pagination.total_pages > 1}
    <div class="flex items-center gap-2">
      {#if pagination.page > 1}
        <!-- eslint-disable-next-line svelte/no-navigation-without-resolve -- hrefFor devuelve rutas resueltas -->
        <a href={hrefFor(pagination.page - 1)} class={linkClass}>Anterior</a>
      {/if}
      <span class="text-muted">Página {pagination.page} de {pagination.total_pages}</span>
      {#if pagination.page < pagination.total_pages}
        <!-- eslint-disable-next-line svelte/no-navigation-without-resolve -- hrefFor devuelve rutas resueltas -->
        <a href={hrefFor(pagination.page + 1)} class={linkClass}>Siguiente</a>
      {/if}
    </div>
  {/if}
</nav>
