<script lang="ts">
  import type { ResolvedPathname } from '$app/types';
  import Icon, { type IconName } from './Icon.svelte';

  type Tone = 'primary' | 'info' | 'success' | 'warning' | 'attention' | 'danger' | 'neutral';

  /**
   * Tarjeta de estadística: un dato importante, grande y legible de un vistazo.
   * El color solo refuerza; el significado siempre está en el texto.
   */
  let {
    label,
    value,
    unit,
    hint,
    icon,
    tone = 'primary',
    href,
    /** Barra de proporción (0–100) bajo la cifra, para porcentajes de avance. */
    progress
  }: {
    label: string;
    value: string | number;
    unit?: string;
    hint?: string;
    icon?: IconName;
    tone?: Tone;
    href?: ResolvedPathname;
    progress?: number;
  } = $props();

  const tones: Record<Tone, { chip: string; bar: string }> = {
    primary: { chip: 'bg-primary-soft text-primary', bar: 'bg-primary' },
    info: { chip: 'bg-info-soft text-info', bar: 'bg-info' },
    success: { chip: 'bg-success-soft text-success', bar: 'bg-success' },
    warning: { chip: 'bg-warning-soft text-warning', bar: 'bg-warning' },
    attention: { chip: 'bg-attention-soft text-attention', bar: 'bg-attention' },
    danger: { chip: 'bg-danger-soft text-danger', bar: 'bg-danger' },
    neutral: { chip: 'bg-canvas text-muted', bar: 'bg-border-strong' }
  };

  const pct = $derived(Math.max(0, Math.min(100, progress ?? 0)));
</script>

{#snippet body()}
  <div class="flex items-start justify-between gap-3">
    <p class="label-eyebrow text-balance">{label}</p>
    {#if icon}
      <span
        class="grid size-9 shrink-0 place-items-center rounded-xl {tones[tone].chip}"
        aria-hidden="true"
      >
        <Icon name={icon} class="size-5" />
      </span>
    {/if}
  </div>
  <p class="mt-auto flex items-baseline gap-1.5 pt-3">
    <span class="value-kpi">{value}</span>
    {#if unit}<span class="text-sm font-medium text-muted">{unit}</span>{/if}
  </p>
  {#if progress !== undefined}
    <span
      class="mt-3 block h-1.5 overflow-hidden rounded-full bg-canvas"
      role="progressbar"
      aria-label={label}
      aria-valuemin={0}
      aria-valuemax={100}
      aria-valuenow={pct}
      aria-valuetext="{pct.toLocaleString('es-CO', { maximumFractionDigits: 0 })} %"
    >
      <span class="block h-full rounded-full {tones[tone].bar}" style:width="{pct}%"></span>
    </span>
  {/if}
  {#if hint}
    <p class="mt-2 text-xs text-muted">{hint}</p>
  {/if}
{/snippet}

{#if href}
  <!-- eslint-disable-next-line svelte/no-navigation-without-resolve -- href ya viene resuelto -->
  <a
    {href}
    class="card-interactive flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card"
  >
    {@render body()}
  </a>
{:else}
  <div class="flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card">
    {@render body()}
  </div>
{/if}
