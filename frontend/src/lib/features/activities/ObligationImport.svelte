<script lang="ts">
  import { onMount } from 'svelte';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { ObligationSuggestions } from '$lib/types/activities';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import { createObligations, suggestObligations } from './api';

  /**
   * Obligaciones tomadas del contrato firmado (ADR-022). SIGCON lee el PDF y PROPONE la lista;
   * la administración la revisa (corrige, quita o agrega) y solo al confirmar se registra.
   * Si el PDF no se puede leer, queda el registro a mano en la misma pantalla.
   */
  let {
    contractUuid,
    onimported,
    oncancel
  }: {
    contractUuid: string;
    onimported: (count: number) => void | Promise<void>;
    oncancel?: () => void;
  } = $props();

  interface Row {
    key: number;
    title: string;
    description: string;
    weight: string;
  }

  let loading = $state(true);
  let readError = $state<string | null>(null);
  let source = $state<ObligationSuggestions | null>(null);
  let rows = $state<Row[]>([]);
  let saving = $state(false);
  let error = $state<unknown>(null);
  let nextKey = 0;

  const row = (title = '', description = ''): Row => ({
    key: nextKey++,
    title,
    description,
    weight: '1'
  });
  const valid = $derived(rows.length > 0 && rows.every((r) => r.title.trim().length >= 3));

  onMount(async () => {
    try {
      source = await suggestObligations(contractUuid);
      rows = source.items.map((i) => row(i.title, i.description ?? ''));
    } catch (e) {
      readError = formMessage(e) ?? 'No fue posible leer el contrato.';
    } finally {
      loading = false;
      if (rows.length === 0) rows = [row()];
    }
  });

  function remove(key: number): void {
    rows = rows.filter((r) => r.key !== key);
  }

  async function save(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    saving = true;
    error = null;
    try {
      const created = await createObligations(
        contractUuid,
        rows.map((r) => ({
          title: r.title.trim(),
          description: r.description.trim() || null,
          weight: r.weight.trim() || '1',
          due_date: null
        }))
      );
      toasts.show(
        `${created.length} ${created.length === 1 ? 'obligación registrada' : 'obligaciones registradas'}.`
      );
      await onimported(created.length);
    } catch (e) {
      error = e;
    } finally {
      saving = false;
    }
  }
</script>

{#if loading}
  <p class="text-sm text-muted" role="status">Leyendo el contrato firmado…</p>
{:else}
  <form class="space-y-4" onsubmit={save}>
    {#if readError}
      <Alert variant="warning" title="No se pudieron tomar las obligaciones del contrato">
        {readError} Puede escribirlas aquí mismo.
      </Alert>
    {:else if source}
      <p class="text-sm text-muted">
        {#if source.items.length > 0}
          Se encontraron <strong class="text-ink">{source.items.length}</strong>
          {source.items.length === 1 ? 'obligación' : 'obligaciones'} en
          <span class="text-ink">{source.document.name}</span>. Revíselas antes de registrarlas:
          puede corregir el texto, quitar las que no correspondan o agregar otras.
        {:else}
          No se encontraron obligaciones en <span class="text-ink">{source.document.name}</span>.
        {/if}
      </p>
      {#each source.warnings as warning (warning.code)}
        <Alert variant="warning">{warning.message}</Alert>
      {/each}
      {#if source.existing_obligations > 0}
        <Alert variant="info">
          El contrato ya tiene {source.existing_obligations}
          {source.existing_obligations === 1
            ? 'obligación registrada'
            : 'obligaciones registradas'}. Las que confirme aquí se agregan a esas.
        </Alert>
      {/if}
    {/if}

    {#if formMessage(error) && fieldErrors(error, 'items').length === 0}
      <Alert variant="danger">{formMessage(error)}</Alert>
    {/if}

    <ol class="space-y-3">
      {#each rows as item, index (item.key)}
        <li class="rounded-xl border border-border bg-canvas p-4">
          <div class="flex items-start justify-between gap-3">
            <span class="pt-2 text-sm font-semibold text-muted tabular-nums">{index + 1}.</span>
            <div class="min-w-0 flex-1 space-y-3">
              <TextField
                label="Obligación {index + 1}"
                bind:value={item.title}
                maxlength={255}
                required
                errors={fieldErrors(error, `items.${index}.title`)}
              />
              <TextArea
                label="Texto completo (opcional)"
                rows={2}
                maxlength={5000}
                bind:value={item.description}
                errors={fieldErrors(error, `items.${index}.description`)}
              />
              <div class="w-32">
                <TextField
                  label="Peso"
                  inputmode="decimal"
                  bind:value={item.weight}
                  hint="1 = normal"
                  errors={fieldErrors(error, `items.${index}.weight`)}
                />
              </div>
            </div>
            <Button
              size="sm"
              variant="ghost"
              aria-label="Quitar la obligación {index + 1}"
              onclick={() => remove(item.key)}
            >
              Quitar
            </Button>
          </div>
        </li>
      {/each}
    </ol>

    {#if fieldErrors(error, 'items').length > 0}
      <p class="text-sm text-danger">{fieldErrors(error, 'items').join(' ')}</p>
    {/if}

    <div class="flex flex-wrap gap-3">
      <Button variant="secondary" onclick={() => (rows = [...rows, row()])}>
        + Agregar obligación
      </Button>
      <Button type="submit" loading={saving} disabled={!valid}>
        Registrar {rows.length}
        {rows.length === 1 ? 'obligación' : 'obligaciones'}
      </Button>
      {#if oncancel}
        <Button variant="ghost" onclick={oncancel}>Cancelar</Button>
      {/if}
    </div>
    <p class="text-xs text-muted">
      El peso indica cuánto cuenta cada obligación en el avance del contrato. Las tareas y subtareas
      las organizan después la administración y el supervisor.
    </p>
  </form>
{/if}
