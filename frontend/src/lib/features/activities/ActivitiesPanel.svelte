<script lang="ts">
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import ConfirmDialog from '$lib/components/ui/ConfirmDialog.svelte';
  import Modal from '$lib/components/ui/Modal.svelte';
  import ProgressBar from '$lib/components/ui/ProgressBar.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type {
    Activity,
    ActivityActions,
    ActivityTree,
    ProgressEntry
  } from '$lib/types/activities';
  import { formatDateTime } from '$lib/utils/format';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import ActivityNode from './ActivityNode.svelte';
  import ObligationImport from './ObligationImport.svelte';
  import {
    createChild,
    createObligation,
    deleteActivity,
    getProgressHistory,
    recordProgress,
    setPriority,
    updateActivity
  } from './api';

  /**
   * Obligaciones, tareas y subtareas de un contrato, con el avance calculado (ADR-013).
   * Tras cada cambio se invoca `onchange` para recargar los datos desde el servidor.
   */
  let {
    contractUuid,
    tree,
    onchange
  }: { contractUuid: string; tree: ActivityTree; onchange: () => Promise<void> } = $props();

  let importOpen = $state(false);
  let working = $state(false);
  let error = $state<unknown>(null);

  // --- Crear / editar ---
  type FormMode =
    { kind: 'obligation' } | { kind: 'child'; parent: Activity } | { kind: 'edit'; item: Activity };
  let formOpen = $state(false);
  let mode = $state<FormMode>({ kind: 'obligation' });
  let title = $state('');
  let description = $state('');
  let weight = $state('1');
  let dueDate = $state('');

  const formTitle = $derived(
    mode.kind === 'obligation'
      ? 'Nueva obligación'
      : mode.kind === 'child'
        ? `Nueva ${mode.parent.level === 'obligation' ? 'tarea' : 'subtarea'}`
        : `Editar ${mode.item.level_label.toLowerCase()}`
  );

  function openForm(next: FormMode): void {
    mode = next;
    const item = next.kind === 'edit' ? next.item : null;
    title = item?.title ?? '';
    description = item?.description ?? '';
    weight = item ? item.weight.replace(/\.00$/, '') : '1';
    dueDate = item?.due_date ?? '';
    error = null;
    formOpen = true;
  }

  async function saveForm(): Promise<void> {
    working = true;
    error = null;
    const input = {
      title,
      description: description.trim() || null,
      weight,
      due_date: dueDate || null
    };
    try {
      if (mode.kind === 'obligation') await createObligation(contractUuid, input);
      else if (mode.kind === 'child') await createChild(mode.parent.uuid, input);
      else await updateActivity(mode.item.uuid, input);
      formOpen = false;
      await onchange();
      toasts.show('Cambios guardados.');
    } catch (e) {
      error = e;
    } finally {
      working = false;
    }
  }

  // --- Avance ---
  let progressOpen = $state(false);
  let target = $state<Activity | null>(null);
  let progressValue = $state('0');
  let note = $state('');

  function openProgress(item: Activity): void {
    target = item;
    progressValue = String(Math.round(Number(item.progress)));
    note = '';
    error = null;
    progressOpen = true;
  }

  async function saveProgress(): Promise<void> {
    if (!target) return;
    working = true;
    error = null;
    try {
      await recordProgress(target.uuid, Number(progressValue), note);
      progressOpen = false;
      await onchange();
      toasts.show('Avance registrado.');
    } catch (e) {
      error = e;
    } finally {
      working = false;
    }
  }

  // --- Eliminar ---
  let deleteOpen = $state(false);

  async function confirmDelete(): Promise<void> {
    if (!target) return;
    working = true;
    try {
      await deleteActivity(target.uuid);
      deleteOpen = false;
      await onchange();
      toasts.show('Elemento eliminado.');
    } catch (e) {
      toasts.show(formMessage(e) ?? 'No fue posible eliminar.', 'error');
      deleteOpen = false;
    } finally {
      working = false;
    }
  }

  // --- Historial de avances ---
  let historyOpen = $state(false);
  let history = $state<ProgressEntry[]>([]);
  let historyError = $state<unknown>(null);

  async function openHistory(item: Activity): Promise<void> {
    target = item;
    history = [];
    historyError = null;
    historyOpen = true;
    try {
      history = (await getProgressHistory(item.uuid)).history;
    } catch (e) {
      historyError = e;
    }
  }

  const actions: ActivityActions = {
    add: (parent) => openForm({ kind: 'child', parent }),
    edit: (item) => openForm({ kind: 'edit', item }),
    remove: (item) => {
      target = item;
      deleteOpen = true;
    },
    progress: openProgress,
    history: openHistory,
    priority: async (item, priority) => {
      try {
        await setPriority(item.uuid, priority);
        toasts.show('Prioridad guardada.');
        await onchange();
      } catch (e) {
        toasts.show(formMessage(e) ?? 'No fue posible guardar la prioridad.', 'error');
      }
    }
  };

  const pct = (v: string) => `${Number(v).toLocaleString('es-CO', { maximumFractionDigits: 2 })} %`;
</script>

<Card title="Obligaciones y avance">
  <div class="space-y-4">
    <div>
      <p class="mb-1 text-sm font-medium text-ink">Avance general del contrato</p>
      <ProgressBar value={tree.progress} label="Avance general del contrato" />
      <p class="mt-1 text-xs text-muted">
        Promedio ponderado de las obligaciones. El avance de cada tarea lo registra el supervisor al
        verificar lo ejecutado.
      </p>
    </div>

    {#if tree.items.length === 0}
      <p class="rounded-md bg-canvas px-3 py-4 text-sm text-muted">
        {tree.can.manage_obligations
          ? 'Registre las obligaciones del contrato antes de activarlo.'
          : 'Este contrato aún no tiene obligaciones registradas.'}
      </p>
    {:else}
      <ul class="divide-y divide-border">
        {#each tree.items as item, i (item.uuid)}
          <ActivityNode {item} can={tree.can} {actions} numbering="{i + 1}." />
        {/each}
      </ul>
    {/if}

    {#if tree.can.manage_obligations}
      <div class="flex flex-wrap gap-2">
        <Button size="sm" onclick={() => (importOpen = true)}>Leer del contrato firmado</Button>
        <Button variant="secondary" size="sm" onclick={() => openForm({ kind: 'obligation' })}
          >+ Agregar obligación</Button
        >
      </div>
    {/if}
  </div>
</Card>

<ConfirmDialog
  bind:open={formOpen}
  title={formTitle}
  confirmLabel="Guardar"
  loading={working}
  onconfirm={saveForm}
>
  <div class="space-y-3">
    {#if formMessage(error) && fieldErrors(error, 'title').length === 0}
      <Alert variant="danger">{formMessage(error)}</Alert>
    {/if}
    <TextArea
      label="Título"
      required
      rows={2}
      maxlength={255}
      bind:value={title}
      errors={fieldErrors(error, 'title')}
    />
    <TextArea
      label="Descripción"
      rows={3}
      maxlength={5000}
      bind:value={description}
      errors={fieldErrors(error, 'description')}
    />
    <div class="grid grid-cols-2 gap-3">
      <TextField
        label="Peso"
        inputmode="decimal"
        hint="Importancia frente a los demás del mismo nivel."
        bind:value={weight}
        errors={fieldErrors(error, 'weight')}
      />
      <TextField
        label="Fecha objetivo"
        type="date"
        bind:value={dueDate}
        errors={fieldErrors(error, 'due_date')}
      />
    </div>
  </div>
</ConfirmDialog>

<ConfirmDialog
  bind:open={progressOpen}
  title="Registrar avance"
  confirmLabel="Registrar"
  loading={working}
  onconfirm={saveProgress}
>
  <div class="space-y-3">
    <p class="font-medium text-ink">{target?.title}</p>
    {#if formMessage(error) && fieldErrors(error, 'progress').length === 0 && fieldErrors(error, 'note').length === 0}
      <Alert variant="danger">{formMessage(error)}</Alert>
    {/if}
    <div>
      <label for="progress-range" class="block text-sm font-medium text-ink">
        Avance acumulado: <span class="tabular-nums">{progressValue} %</span>
      </label>
      <input
        id="progress-range"
        type="range"
        min="0"
        max="100"
        step="5"
        bind:value={progressValue}
        class="mt-2 w-full accent-primary"
      />
      {#if fieldErrors(error, 'progress').length > 0}
        <p class="text-xs text-danger">{fieldErrors(error, 'progress').join(' ')}</p>
      {/if}
    </div>
    <TextArea
      label="¿Qué se realizó?"
      required
      rows={3}
      maxlength={2000}
      hint="Mínimo 10 caracteres. Quedará en el historial de avances."
      bind:value={note}
      errors={fieldErrors(error, 'note')}
    />
  </div>
</ConfirmDialog>

<ConfirmDialog
  bind:open={deleteOpen}
  title="Eliminar elemento"
  confirmLabel="Eliminar"
  variant="danger"
  loading={working}
  onconfirm={confirmDelete}
>
  Se eliminará "{target?.title}". Solo es posible porque aún no tiene avances registrados.
</ConfirmDialog>

<ConfirmDialog
  bind:open={historyOpen}
  title="Historial de avances"
  confirmLabel="Cerrar"
  onconfirm={() => (historyOpen = false)}
>
  <div class="space-y-3">
    <p class="font-medium text-ink">{target?.title}</p>
    {#if historyError}
      <Alert variant="danger">{formMessage(historyError)}</Alert>
    {/if}
    <ol class="max-h-80 space-y-3 overflow-y-auto">
      {#each history as entry (entry.uuid)}
        <li class="rounded-md bg-canvas px-3 py-2">
          <p class="text-sm font-medium text-ink">
            {pct(entry.previous_progress)} → {pct(entry.new_progress)}
          </p>
          <p class="text-xs text-muted">{formatDateTime(entry.recorded_at)} · {entry.user}</p>
          <p class="mt-1 text-sm whitespace-pre-line">{entry.note}</p>
        </li>
      {:else}
        {#if !historyError}<li class="text-sm text-muted">Cargando…</li>{/if}
      {/each}
    </ol>
  </div>
</ConfirmDialog>

<Modal bind:open={importOpen} title="Obligaciones del contrato firmado" size="lg">
  {#if importOpen}
    <ObligationImport
      {contractUuid}
      onimported={async () => {
        importOpen = false;
        await onchange();
      }}
      oncancel={() => (importOpen = false)}
    />
  {/if}
</Modal>
