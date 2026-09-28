<script lang="ts">
  import { toasts } from '$lib/stores/toasts.svelte';

  const styles = {
    success: 'border-success bg-success-soft text-success',
    error: 'border-danger bg-danger-soft text-danger',
    info: 'border-info bg-info-soft text-info'
  };
</script>

<div
  class="pointer-events-none fixed inset-x-4 bottom-4 z-50 flex flex-col items-end gap-2 sm:inset-x-auto sm:right-4"
  aria-live="polite"
  role="status"
>
  {#each toasts.items as toast (toast.id)}
    <div
      class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-lg border-l-4 bg-deep/90 px-4 py-3 text-sm shadow-lg shadow-black/40 backdrop-blur-xl {styles[
        toast.kind
      ]}"
    >
      <p class="flex-1 text-ink">{toast.message}</p>
      <button
        type="button"
        class="text-muted hover:text-ink"
        aria-label="Cerrar notificación"
        onclick={() => toasts.dismiss(toast.id)}>×</button
      >
    </div>
  {/each}
</div>
