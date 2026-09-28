<script lang="ts">
  import Badge from '$lib/components/ui/Badge.svelte';
  import type { ActivityPriority } from '$lib/types/activities';

  /** Prioridad asignada por la Alcaldía (ADR-021). El texto siempre acompaña al color. */
  let { priority, label }: { priority: ActivityPriority; label: string } = $props();

  const tones = { high: 'danger', medium: 'warning', low: 'info' } as const;
</script>

<Badge tone={tones[priority]}>Prioridad {label.toLowerCase()}</Badge>
