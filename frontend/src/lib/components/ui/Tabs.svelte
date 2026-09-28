<script lang="ts">
  import { page } from '$app/state';
  import type { ResolvedPathname } from '$app/types';

  /** Pestañas de navegación entre pantallas hermanas (por ejemplo, Roles y Usuarios). */
  let { label, items }: { label: string; items: { href: ResolvedPathname; label: string }[] } =
    $props();
</script>

<nav aria-label={label} class="mb-6">
  <ul class="inline-flex gap-1 rounded-xl border border-border bg-surface p-1">
    {#each items as item (item.href)}
      {@const current = page.url.pathname.startsWith(item.href)}
      <li>
        <a
          href={item.href}
          aria-current={current ? 'page' : undefined}
          class="block rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-150 {current
            ? 'bg-primary-soft text-primary shadow-card'
            : 'text-muted hover:bg-white/5 hover:text-ink'}"
        >
          {item.label}
        </a>
      </li>
    {/each}
  </ul>
</nav>
