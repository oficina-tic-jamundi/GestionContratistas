<script lang="ts">
  import { onDestroy } from 'svelte';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import TextArea from '$lib/components/ui/TextArea.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import {
    composeReportDocument,
    listDocuments,
    uploadDocument
  } from '$lib/features/documents/api';
  import type { ActivityDetail } from '$lib/types/activities';
  import type { ContractDocument } from '$lib/types/documents';
  import type { ReportDetail, ReportSummary } from '$lib/types/reports';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import DictationButton from './DictationButton.svelte';
  import {
    TEMPLATES,
    ensureReport,
    saveObligationText,
    type TemplateCode,
    type TemplateField
  } from './activityReport';

  /**
   * "Crear informe" (ADR-023): el contratista elige el formato (acta o informe, cada uno con
   * sus campos), escribe o dicta, toma fotos y las va anexando, y al final SIGCON genera el
   * documento en PDF dentro del informe del período.
   *
   * Las fotos se quedan en el navegador hasta pulsar "Crear": así no quedan anexos sueltos si
   * el contratista cambia de idea.
   */
  let {
    detail,
    reports,
    report,
    ondone,
    oncancel
  }: {
    detail: ActivityDetail;
    reports: ReportSummary[];
    report: ReportDetail | null;
    ondone: (document: ContractDocument) => Promise<void>;
    oncancel: () => void;
  } = $props();

  const MAX_PHOTOS = 20;
  const today = new Date().toLocaleDateString('en-CA', { timeZone: 'America/Bogota' });

  interface Photo {
    key: number;
    file: File;
    url: string;
    caption: string;
    /** SHA-256 del archivo: evita agregar dos veces la misma foto. */
    sha256: string;
    /** Anexo ya creado en el informe (si se reintenta, no se vuelve a subir). */
    uuid?: string;
  }

  let template = $state<TemplateCode | null>(null);
  let values = $state<Record<string, string>>({});
  let photos = $state<Photo[]>([]);
  let busy = $state(false);
  let step = $state('');
  let error = $state<unknown>(null);
  let notice = $state<string | null>(null);
  let nextKey = 0;
  // Informe del período usado en un intento anterior: un reintento no crea otro.
  let current: ReportDetail | null = null;

  const spec = $derived(template ? TEMPLATES[template] : null);
  const missing = $derived(
    spec
      ? spec.fields.filter(
          (f) => f.required && (values[f.code] ?? '').trim().length < Math.max(1, f.min ?? 1)
        )
      : []
  );

  function choose(code: TemplateCode): void {
    template = code;
    // Todos los campos con valor inicial: un campo enlazado no puede empezar indefinido.
    values = Object.fromEntries(
      TEMPLATES[code].fields.map((f) => [f.code, f.kind === 'date' ? today : ''])
    );
    error = null;
  }

  async function sha256(file: File): Promise<string> {
    // Sin HTTPS (p. ej. acceso por IP en la red local) el navegador no ofrece crypto.subtle.
    if (!globalThis.crypto?.subtle) return `${file.name}:${file.size}:${file.lastModified}`;
    const digest = await crypto.subtle.digest('SHA-256', await file.arrayBuffer());
    return [...new Uint8Array(digest)].map((b) => b.toString(16).padStart(2, '0')).join('');
  }

  async function addPhotos(event: Event): Promise<void> {
    const input = event.currentTarget as HTMLInputElement;
    const files = [...(input.files ?? [])].filter((f) => /^image\/(jpeg|png)$/.test(f.type));
    input.value = ''; // permite tomar otra foto con el mismo control
    notice = null;
    for (const file of files) {
      if (photos.length >= MAX_PHOTOS) {
        notice = `Puede agregar hasta ${MAX_PHOTOS} fotos.`;
        break;
      }
      const hash = await sha256(file);
      if (photos.some((p) => p.sha256 === hash)) {
        notice = 'Esa foto ya está agregada.';
        continue;
      }
      photos = [
        ...photos,
        { key: nextKey++, file, url: URL.createObjectURL(file), caption: '', sha256: hash }
      ];
    }
  }

  /** Sube la foto como anexo; si ya estaba en el informe (reintento), reutiliza ese anexo. */
  async function uploadPhoto(target: ReportDetail, photo: Photo, index: number): Promise<string> {
    if (photo.uuid) return photo.uuid;
    const owner = { kind: 'report', uuid: target.uuid } as const;
    try {
      const doc = await uploadDocument(
        owner,
        photo.file,
        'report_annex',
        photo.caption.trim() || `Foto ${index + 1}: ${detail.activity.title}`
      );
      return doc.uuid;
    } catch (e) {
      const existing = (await listDocuments(owner)).items.find(
        (d) => d.status === 'active' && d.sha256 === photo.sha256
      );
      if (existing) return existing.uuid;
      throw e;
    }
  }

  function removePhoto(key: number): void {
    const photo = photos.find((p) => p.key === key);
    if (photo) URL.revokeObjectURL(photo.url);
    photos = photos.filter((p) => p.key !== key);
  }

  function dictate(field: TemplateField, text: string): void {
    const current = values[field.code] ?? '';
    values[field.code] = current ? `${current} ${text}` : text;
  }

  onDestroy(() => photos.forEach((p) => URL.revokeObjectURL(p.url)));

  async function create(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    if (!template || !spec) return;
    busy = true;
    error = null;
    try {
      step = 'Preparando el informe del período…';
      const target = current ?? (await ensureReport(detail, reports, report));
      current = target;
      await saveObligationText(target, detail.obligation.uuid, values[spec.main] ?? '');

      const uploaded: { uuid: string; caption: string }[] = [];
      for (const [index, photo] of photos.entries()) {
        step = `Subiendo foto ${index + 1} de ${photos.length}…`;
        photo.uuid = await uploadPhoto(target, photo, index);
        uploaded.push({ uuid: photo.uuid, caption: photo.caption.trim() });
      }

      step = `Creando ${spec.label.toLowerCase()}…`;
      const fields = Object.fromEntries(
        Object.entries(values)
          .map(([code, value]) => [code, value.trim()])
          .filter(([, value]) => value !== '')
      );
      const document = await composeReportDocument(target.uuid, {
        template,
        activity: detail.activity.uuid,
        fields,
        photos: uploaded
      });
      await ondone(document);
    } catch (e) {
      error = e;
    } finally {
      busy = false;
      step = '';
    }
  }
</script>

{#if !spec}
  <div class="space-y-4">
    <div>
      <h3 class="text-base font-semibold text-ink">¿Qué va a crear?</h3>
      <p class="mt-1 text-sm text-muted">Cada formato tiene su propia planilla.</p>
    </div>
    <div class="grid gap-3 sm:grid-cols-2">
      {#each Object.entries(TEMPLATES) as [code, option] (code)}
        <button
          type="button"
          class="rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft"
          onclick={() => choose(code as TemplateCode)}
        >
          <span class="flex items-center gap-2 text-base font-semibold text-ink">
            <Icon name={code === 'acta' ? 'users' : 'file'} class="size-5 text-primary" />
            {option.label}
          </span>
          <span class="mt-1 block text-sm text-muted">{option.description}</span>
        </button>
      {/each}
    </div>
    <Button variant="ghost" onclick={oncancel}>Volver</Button>
  </div>
{:else}
  <form class="space-y-5" onsubmit={create}>
    <div class="flex flex-wrap items-center justify-between gap-2">
      <h3 class="text-base font-semibold text-ink">Crear {spec.label.toLowerCase()}</h3>
      <Button size="sm" variant="ghost" onclick={() => (template = null)}>Cambiar formato</Button>
    </div>

    {#if formMessage(error)}<Alert variant="danger">{formMessage(error)}</Alert>{/if}

    <div class="grid gap-4 sm:grid-cols-2">
      {#each spec.fields.filter((f) => f.kind !== 'text') as field (field.code)}
        <TextField
          label={field.label}
          type={field.kind === 'date' ? 'date' : field.kind === 'time' ? 'time' : 'text'}
          bind:value={values[field.code]}
          required={field.required}
          maxlength={field.kind === 'line' ? field.max : undefined}
          errors={fieldErrors(error, `fields.${field.code}`)}
        />
      {/each}
    </div>

    {#each spec.fields.filter((f) => f.kind === 'text') as field, index (field.code)}
      <div class="space-y-2">
        <TextArea
          label={field.label}
          bind:value={values[field.code]}
          rows={field.code === spec.main ? 6 : 3}
          required={field.required}
          maxlength={field.max}
          hint={field.hint ??
            (field.required
              ? `Escríbalo o díctelo. Mínimo ${field.min ?? 1} caracteres.`
              : undefined)}
          errors={fieldErrors(error, `fields.${field.code}`)}
        />
        <DictationButton
          label="Dictar {field.label.toLowerCase()}"
          notice={index === 0}
          ontext={(text) => dictate(field, text)}
        />
      </div>
    {/each}

    <section class="space-y-3" aria-labelledby="fotos-titulo">
      <div class="flex flex-wrap items-center justify-between gap-2">
        <h4 id="fotos-titulo" class="text-sm font-semibold text-ink">
          Fotos ({photos.length} de {MAX_PHOTOS})
        </h4>
        <div class="flex flex-wrap gap-2">
          <label
            class="btn-primary inline-flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold"
          >
            <Icon name="camera" class="size-4" /> Tomar foto
            <input
              type="file"
              accept="image/jpeg,image/png"
              capture="environment"
              class="sr-only"
              disabled={photos.length >= MAX_PHOTOS}
              onchange={addPhotos}
            />
          </label>
          <label
            class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink hover:border-primary/50"
          >
            <Icon name="upload" class="size-4" /> Elegir de la galería
            <input
              type="file"
              accept="image/jpeg,image/png"
              multiple
              class="sr-only"
              disabled={photos.length >= MAX_PHOTOS}
              onchange={addPhotos}
            />
          </label>
        </div>
      </div>

      {#if notice}<p class="text-sm text-warning" role="status">{notice}</p>{/if}

      {#if photos.length === 0}
        <p class="rounded-xl border border-dashed border-border p-4 text-sm text-muted">
          Tome fotos de la actividad y se irán agregando aquí. Aparecerán en el documento, con su
          descripción.
        </p>
      {:else}
        <ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          {#each photos as photo, index (photo.key)}
            <li class="overflow-hidden rounded-xl border border-border bg-canvas">
              <img
                src={photo.url}
                alt="Foto {index + 1} de la actividad"
                class="aspect-[4/3] w-full object-cover"
              />
              <div class="space-y-2 p-3">
                <TextField
                  label="Descripción de la foto {index + 1}"
                  bind:value={photo.caption}
                  maxlength={200}
                />
                <Button
                  size="sm"
                  variant="ghost"
                  aria-label="Quitar la foto {index + 1}"
                  onclick={() => removePhoto(photo.key)}
                >
                  Quitar
                </Button>
              </div>
            </li>
          {/each}
        </ul>
      {/if}
    </section>

    {#if busy && step}
      <p class="text-sm text-muted" role="status">{step}</p>
    {/if}

    <div class="flex flex-wrap gap-3">
      <Button type="submit" loading={busy} disabled={missing.length > 0}>
        Crear {spec.label.toLowerCase()}
      </Button>
      <Button variant="ghost" onclick={oncancel} disabled={busy}>Volver</Button>
    </div>
    {#if missing.length > 0}
      <p class="text-xs text-muted">
        Falta completar: {missing.map((f) => f.label.toLowerCase()).join(', ')}.
      </p>
    {/if}
    <p class="text-xs text-muted">
      Formato provisional de SIGCON mientras la Alcaldía adopta sus planillas oficiales.
    </p>
  </form>
{/if}
