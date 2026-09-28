<script lang="ts">
  import type { Snippet } from 'svelte';
  import Icon, { type IconName } from './Icon.svelte';

  type Variant = 'info' | 'success' | 'warning' | 'danger';

  let {
    variant = 'info',
    title,
    children
  }: { variant?: Variant; title?: string; children: Snippet } = $props();

  const styles: Record<Variant, { box: string; icon: IconName; tint: string }> = {
    info: { box: 'border-info/40 bg-info-soft', icon: 'alert', tint: 'text-info' },
    success: { box: 'border-success/40 bg-success-soft', icon: 'check-list', tint: 'text-success' },
    warning: { box: 'border-warning/40 bg-warning-soft', icon: 'alert', tint: 'text-warning' },
    danger: { box: 'border-danger/40 bg-danger-soft', icon: 'alert', tint: 'text-danger' }
  };

  // Los errores y advertencias se anuncian de inmediato a lectores de pantalla.
  const role = $derived(variant === 'danger' || variant === 'warning' ? 'alert' : 'status');
</script>

<div class="flex gap-3 rounded-xl border px-4 py-3 text-sm {styles[variant].box}" {role}>
  <span class="mt-0.5 shrink-0 {styles[variant].tint}" aria-hidden="true">
    <Icon name={styles[variant].icon} class="size-5" />
  </span>
  <div class="min-w-0">
    {#if title}
      <p class="font-semibold {styles[variant].tint}">{title}</p>
    {/if}
    <div class="text-ink">{@render children()}</div>
  </div>
</div>
