<script lang="ts">
  /**
   * Marcador de posición mientras llegan los datos: ocupa el mismo sitio que el contenido
   * real, así la pantalla no salta al cargar. Se anuncia una sola vez a lectores de pantalla.
   */
  let {
    lines = 3,
    label = 'Cargando…',
    card = true
  }: { lines?: number; label?: string; card?: boolean } = $props();

  const widths = ['w-2/3', 'w-full', 'w-5/6', 'w-3/4', 'w-1/2'];
</script>

<div
  class={card ? 'rounded-2xl border border-border bg-surface p-5 shadow-card' : ''}
  role="status"
  aria-live="polite"
>
  <span class="sr-only">{label}</span>
  <div class="space-y-3" aria-hidden="true">
    {#each Array.from({ length: lines }, (_, i) => i) as line (line)}
      <div class="skeleton h-4 {widths[line % widths.length]}"></div>
    {/each}
  </div>
</div>
