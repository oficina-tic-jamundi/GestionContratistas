<script lang="ts" module>
  /** Tipos que el visor muestra dentro de la aplicación; el resto se descarga. */
  const VIEWABLE = ['application/pdf', 'image/jpeg', 'image/png'];

  export function isViewable(mimeType: string): boolean {
    return VIEWABLE.includes(mimeType);
  }
</script>

<script lang="ts">
  import Button from '$lib/components/ui/Button.svelte';
  import DownloadLink from '$lib/components/ui/DownloadLink.svelte';
  import Modal from '$lib/components/ui/Modal.svelte';
  import type { ContractDocument } from '$lib/types/documents';
  import { formatBytes, formatDateTime } from '$lib/utils/format';
  import { documentDownloadUrl } from './api';

  /**
   * Visor integrado de documentos: muestra PDF e imágenes sin salir de SIGCON. El archivo lo
   * sirve la API con la sesión del usuario y en un marco aislado (sin scripts).
   */
  let {
    document: doc = $bindable(null),
    onclose
  }: { document?: ContractDocument | null; onclose?: () => void } = $props();

  let open = $state(false);
  // El modal se abre cuando llega un documento y se limpia al cerrarse.
  $effect(() => {
    open = doc !== null;
  });

  function close(): void {
    doc = null;
    onclose?.();
  }
</script>

{#if doc}
  {@const file = doc}
  {@const url = documentDownloadUrl(file.uuid, true)}
  <Modal bind:open title={doc.original_name} size="lg" onclose={close}>
    <p class="mb-3 text-xs text-muted">
      {doc.type.name} · {formatBytes(doc.size_bytes)} · {doc.uploaded_by} · {formatDateTime(
        doc.created_at
      )}
      {#if doc.status === 'withdrawn'}· <span class="text-warning">Retirado</span>{/if}
    </p>

    {#if doc.mime_type === 'application/pdf'}
      <iframe
        src={url}
        title="Documento {doc.original_name}"
        class="h-[70dvh] w-full rounded-lg border border-border bg-white"
      ></iframe>
    {:else if doc.mime_type.startsWith('image/')}
      <img
        src={url}
        alt="Documento {doc.original_name}"
        class="max-h-[70dvh] w-full rounded-lg border border-border object-contain"
      />
    {:else}
      <p class="text-sm text-muted">
        Este tipo de archivo no se puede previsualizar. Descárguelo para abrirlo.
      </p>
    {/if}

    {#snippet footer()}
      <DownloadLink href={documentDownloadUrl(file.uuid)}>Descargar</DownloadLink>
      <Button variant="secondary" onclick={close}>Cerrar</Button>
    {/snippet}
  </Modal>
{/if}
