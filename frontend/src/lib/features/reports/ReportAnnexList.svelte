<script lang="ts">
  import Button from '$lib/components/ui/Button.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import DocumentViewer, { isViewable } from '$lib/features/documents/DocumentViewer.svelte';
  import type { ContractDocument, DocumentList } from '$lib/types/documents';
  import { formatBytes, formatDateTime } from '$lib/utils/format';

  /**
   * Anexos vigentes de un informe, con previsualización dentro de SIGCON. Lo usan el
   * contratista (para comprobar qué adjuntó) y quien revisa (para leerlo antes de decidir).
   */
  let {
    documents,
    empty = 'Todavía no hay documentos anexos.'
  }: { documents: DocumentList | null; empty?: string } = $props();

  const items = $derived(documents?.items.filter((d) => d.status === 'active') ?? []);
  let viewing = $state<ContractDocument | null>(null);
</script>

{#if items.length === 0}
  <p class="mt-1 text-sm text-muted">{empty}</p>
{:else}
  <ul class="mt-2 divide-y divide-border">
    {#each items as doc (doc.uuid)}
      <li class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm">
        <span class="min-w-0">
          <span class="text-ink">{doc.original_name}</span>
          <span class="block text-xs text-muted">
            {doc.type.name} · {formatBytes(doc.size_bytes)} · {formatDateTime(doc.created_at)}
          </span>
        </span>
        {#if isViewable(doc.mime_type)}
          <Button size="sm" variant="secondary" onclick={() => (viewing = doc)}>
            <Icon name="file" class="size-4" /> Previsualizar
          </Button>
        {:else}
          <span class="text-xs text-muted">Se descarga desde el informe</span>
        {/if}
      </li>
    {/each}
  </ul>
{/if}

<DocumentViewer bind:document={viewing} />
