<script lang="ts">
  import { resolve } from '$app/paths';
  import { Permission } from '$lib/auth/permissions';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Tabs from '$lib/components/ui/Tabs.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();
</script>

<PageHeader
  title="Roles y permisos"
  description="Cada rol agrupa permisos. Un usuario puede tener varios roles; sus permisos se suman."
>
  {#snippet actions()}
    {#if session.can(Permission.RolesManage)}
      <Button href={resolve('/roles/new')}>Nuevo rol</Button>
    {/if}
  {/snippet}
</PageHeader>

<Tabs
  label="Secciones de roles y usuarios"
  items={[
    { href: resolve('/roles'), label: 'Roles' },
    { href: resolve('/users'), label: 'Usuarios' }
  ]}
/>

<ul class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
  {#each data.roles as role (role.code)}
    <li class="flex flex-col rounded-2xl border border-border bg-surface p-5 shadow-card shadow-xs">
      <div class="flex items-start justify-between gap-2">
        <h2 class="font-semibold text-ink">
          <a
            href={resolve('/(app)/roles/[code]', { code: role.code })}
            class="hover:text-primary hover:underline">{role.name}</a
          >
        </h2>
        {#if role.is_system}<Badge>Sistema</Badge>{/if}
      </div>
      <p class="mt-1 font-mono text-xs text-muted">{role.code}</p>
      {#if role.description}<p class="mt-2 text-sm text-muted">{role.description}</p>{/if}
      <p class="mt-auto pt-4 text-sm text-muted">
        {role.permissions.length} permiso(s) · {role.user_count} usuario(s)
      </p>
    </li>
  {/each}
</ul>
