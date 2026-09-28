<script lang="ts">
  import type { Snippet } from 'svelte';
  import Icon from './Icon.svelte';

  /**
   * Diálogo modal genérico sobre el <dialog> nativo (foco atrapado, Escape y fondo inerte).
   * `onclose` se invoca al cerrarse por cualquier vía (Escape, botón o cambio de `open`).
   */
  let {
    open = $bindable(false),
    title,
    size = 'md',
    onclose,
    children,
    footer
  }: {
    open?: boolean;
    title: string;
    size?: 'md' | 'lg';
    onclose?: () => void;
    children: Snippet;
    footer?: Snippet;
  } = $props();

  const uid = $props.id();

  function syncDialog(dialog: HTMLDialogElement): void {
    if (open && !dialog.open) dialog.showModal();
    if (!open && dialog.open) dialog.close();
  }
</script>

<dialog
  {@attach syncDialog}
  aria-labelledby="{uid}-title"
  onclose={() => {
    open = false;
    onclose?.();
  }}
  class="modal m-auto max-h-[92dvh] w-[calc(100%-1.5rem)] {size === 'lg'
    ? 'max-w-3xl'
    : 'max-w-lg'} rounded-2xl border border-border bg-deep/90 p-0 text-ink shadow-pop backdrop-blur-xl backdrop:bg-backdrop backdrop:backdrop-blur-sm"
>
  <div class="flex items-center justify-between gap-3 border-b border-border px-5 py-3">
    <h2 id="{uid}-title" class="text-lg font-semibold text-ink">{title}</h2>
    <button
      type="button"
      class="inline-flex size-9 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink"
      aria-label="Cerrar"
      onclick={() => (open = false)}><Icon name="close" class="size-5" /></button
    >
  </div>
  <div class="p-5 text-sm">{@render children()}</div>
  {#if footer}
    <div class="flex flex-wrap justify-end gap-2 border-t border-border bg-canvas px-5 py-3">
      {@render footer()}
    </div>
  {/if}
</dialog>
