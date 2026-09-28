<script lang="ts">
  import type { Snippet } from 'svelte';
  import Button from './Button.svelte';

  let {
    open = $bindable(false),
    title,
    confirmLabel = 'Confirmar',
    variant = 'primary',
    loading = false,
    onconfirm,
    children
  }: {
    open?: boolean;
    title: string;
    confirmLabel?: string;
    variant?: 'primary' | 'danger';
    loading?: boolean;
    onconfirm: () => void;
    children: Snippet;
  } = $props();

  const uid = $props.id();

  /**
   * Sincroniza `open` con el <dialog> nativo, que aporta foco atrapado, cierre con Escape
   * y fondo inerte de forma accesible. Se vuelve a ejecutar cuando cambia `open`.
   */
  function syncDialog(dialog: HTMLDialogElement): void {
    if (open && !dialog.open) dialog.showModal();
    if (!open && dialog.open) dialog.close();
  }
</script>

<dialog
  {@attach syncDialog}
  aria-labelledby="{uid}-title"
  onclose={() => (open = false)}
  class="modal m-auto w-[calc(100%-1.5rem)] max-w-md rounded-2xl border border-border bg-deep/90 p-0 text-ink shadow-pop backdrop-blur-xl backdrop:bg-backdrop backdrop:backdrop-blur-sm"
>
  <div class="space-y-3 p-5">
    <h2 id="{uid}-title" class="text-lg font-semibold text-ink">{title}</h2>
    <div class="text-sm text-muted">{@render children()}</div>
  </div>
  <div class="flex justify-end gap-2 border-t border-border bg-canvas px-5 py-3">
    <Button variant="secondary" onclick={() => (open = false)} disabled={loading}>Cancelar</Button>
    <Button {variant} {loading} onclick={onconfirm}>{confirmLabel}</Button>
  </div>
</dialog>
