<script lang="ts">
  import Alert from '$lib/components/ui/Alert.svelte';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import ConfirmDialog from '$lib/components/ui/ConfirmDialog.svelte';
  import DownloadLink from '$lib/components/ui/DownloadLink.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ContractDocument, DocumentList, DocumentOwnerRef } from '$lib/types/documents';
  import { formatBytes, formatDateTime } from '$lib/utils/format';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import DocumentViewer, { isViewable } from './DocumentViewer.svelte';
  import { documentDownloadUrl, uploadDocument, withdrawDocument } from './api';

  /**
   * Documentos de un contrato o anexos de un informe. La validación real (tipo, contenido, tamaño, duplicados) la hace
   * el backend; aquí solo se ayuda al usuario a elegir un archivo aceptable.
   */
  let {
    owner,
    list,
    onchange,
    title = 'Documentos',
    empty = 'No hay documentos cargados.'
  }: {
    owner: DocumentOwnerRef;
    list: DocumentList;
    onchange: () => Promise<void>;
    title?: string;
    empty?: string;
  } = $props();

  const ACCEPT = '.pdf,.jpg,.jpeg,.png,.docx,.xlsx';

  let file = $state<File | null>(null);
  let type = $state('');
  let description = $state('');
  let uploading = $state(false);
  let error = $state<unknown>(null);
  let inputKey = $state(0); // para limpiar el <input type="file"> tras cargar

  const tooBig = $derived(file !== null && file.size > list.max_mb * 1024 * 1024);

  async function upload(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    if (!file) return;
    uploading = true;
    error = null;
    try {
      await uploadDocument(owner, file, type, description.trim() || null);
      file = null;
      description = '';
      inputKey++;
      await onchange();
      toasts.show('Documento cargado.');
    } catch (e) {
      error = e;
    } finally {
      uploading = false;
    }
  }

  // --- Retiro ---
  let withdrawOpen = $state(false);
  let target = $state<ContractDocument | null>(null);
  let reason = $state('');
  let withdrawError = $state<unknown>(null);
  let working = $state(false);

  function askWithdraw(doc: ContractDocument): void {
    target = doc;
    reason = '';
    withdrawError = null;
    withdrawOpen = true;
  }

  async function confirmWithdraw(): Promise<void> {
    if (!target) return;
    working = true;
    withdrawError = null;
    try {
      await withdrawDocument(target.uuid, reason);
      withdrawOpen = false;
      await onchange();
      toasts.show('Documento retirado.');
    } catch (e) {
      withdrawError = e;
    } finally {
      working = false;
    }
  }

  // Visor integrado: previsualiza sin salir de SIGCON (ADR-021).
  let viewing = $state<ContractDocument | null>(null);
</script>

<Card {title} padded={false}>
  <ul class="divide-y divide-border">
    {#each list.items as doc (doc.uuid)}
      <li class="flex flex-wrap items-start justify-between gap-3 px-5 py-3 text-sm">
        <div class="min-w-0 flex-1">
          <p
            class="font-medium break-all text-ink {doc.status === 'withdrawn'
              ? 'line-through'
              : ''}"
          >
            {doc.original_name}
          </p>
          <p class="text-xs text-muted">
            {doc.type.name} · {formatBytes(doc.size_bytes)} · {doc.uploaded_by} · {formatDateTime(
              doc.created_at
            )}
          </p>
          {#if doc.description}<p class="mt-0.5 text-xs text-muted">{doc.description}</p>{/if}
          {#if doc.withdrawn}
            <p class="mt-1 text-xs text-warning">
              Retirado por {doc.withdrawn.by ?? '—'} ({formatDateTime(doc.withdrawn.at)}): {doc
                .withdrawn.reason}
            </p>
          {/if}
        </div>
        <div class="flex items-center gap-2">
          {#if doc.status === 'withdrawn'}<Badge>Retirado</Badge>{/if}
          {#if isViewable(doc.mime_type)}
            <button
              type="button"
              class="rounded px-2 py-1 text-xs font-medium text-primary hover:bg-primary-soft"
              onclick={() => (viewing = doc)}>Ver</button
            >
          {/if}
          <DownloadLink href={documentDownloadUrl(doc.uuid)}>Descargar</DownloadLink>
          {#if doc.can_withdraw}
            <button
              type="button"
              class="rounded px-2 py-1 text-xs text-danger hover:bg-danger-soft"
              onclick={() => askWithdraw(doc)}>Retirar</button
            >
          {/if}
        </div>
      </li>
    {:else}
      <li class="px-5 py-6 text-sm text-muted">{empty}</li>
    {/each}
  </ul>

  {#if list.can_upload}
    <form class="space-y-3 border-t border-border bg-canvas px-5 py-4" onsubmit={upload} novalidate>
      <p class="text-sm font-medium text-ink">Cargar documento</p>
      {#if formMessage(error) && fieldErrors(error, 'file').length === 0 && fieldErrors(error, 'type').length === 0}
        <Alert variant="danger">{formMessage(error)}</Alert>
      {/if}
      <div class="grid gap-3 sm:grid-cols-2">
        <SelectField
          label="Tipo de documento"
          bind:value={type}
          placeholder="Seleccione…"
          options={list.types.map((t) => ({ value: t.code, label: t.name }))}
          errors={fieldErrors(error, 'type')}
        />
        <div class="space-y-1">
          <label for="document-file-{owner.kind}" class="block text-sm font-medium text-ink"
            >Archivo</label
          >
          {#key inputKey}
            <input
              id="document-file-{owner.kind}"
              type="file"
              accept={ACCEPT}
              onchange={(e) => (file = e.currentTarget.files?.[0] ?? null)}
              class="block w-full text-sm file:mr-3 file:rounded-md file:border file:border-border file:bg-surface file:px-3 file:py-1.5 file:text-sm"
            />
          {/key}
          <p class="text-xs {tooBig ? 'text-danger' : 'text-muted'}">
            PDF, JPG, PNG, DOCX o XLSX. Máximo {list.max_mb} MB.
          </p>
          {#if fieldErrors(error, 'file').length > 0}
            <p class="text-xs text-danger">{fieldErrors(error, 'file').join(' ')}</p>
          {/if}
        </div>
      </div>
      <TextField label="Descripción (opcional)" maxlength={255} bind:value={description} />
      <Button type="submit" size="sm" loading={uploading} disabled={!file || !type || tooBig}
        >Cargar</Button
      >
    </form>
  {/if}
</Card>

<ConfirmDialog
  bind:open={withdrawOpen}
  title="Retirar documento"
  confirmLabel="Retirar"
  variant="danger"
  loading={working}
  onconfirm={confirmWithdraw}
>
  <div class="space-y-3">
    <p>
      "{target?.original_name}" quedará marcado como retirado. No se elimina: sigue disponible para
      consulta con el motivo registrado.
    </p>
    <TextArea
      label="Motivo"
      required
      rows={2}
      maxlength={500}
      bind:value={reason}
      errors={fieldErrors(withdrawError, 'reason')}
    />
  </div>
</ConfirmDialog>

<DocumentViewer bind:document={viewing} />
