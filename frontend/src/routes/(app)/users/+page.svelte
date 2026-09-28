<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import type { ResolvedPathname } from '$app/types';
  import { Permission } from '$lib/auth/permissions';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import Tabs from '$lib/components/ui/Tabs.svelte';
  import ScrollRegion from '$lib/components/ui/ScrollRegion.svelte';
  import Pagination from '$lib/components/ui/Pagination.svelte';
  import SelectField from '$lib/components/ui/SelectField.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import { buildQuery, formatDateTime } from '$lib/utils/format';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  // Valores del formulario de filtros; se sincronizan cuando cambia la URL.
  let search = $derived(data.query.search ?? '');
  let status = $derived(data.query.status ?? '');
  let role = $derived(data.query.role ?? '');

  function hrefFor(page: number): ResolvedPathname {
    return resolve(`/users?${buildQuery({ ...data.query, page: page === 1 ? null : page })}`);
  }

  function applyFilters(event: SubmitEvent): void {
    event.preventDefault();
    const qs = buildQuery({ ...data.query, search, status, role, page: null });
    void goto(resolve(`/users?${qs}`), { keepFocus: true });
  }
</script>

<PageHeader title="Usuarios" description="Cuentas de acceso a SIGCON y sus roles.">
  {#snippet actions()}
    {#if session.can(Permission.UsersCreate)}
      <Button href={resolve('/users/new')}>Nuevo usuario</Button>
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

<form
  class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-[1fr_auto_auto_auto] sm:items-end"
  onsubmit={applyFilters}
  role="search"
>
  <TextField label="Buscar" type="search" placeholder="Nombre o correo" bind:value={search} />
  <SelectField
    label="Estado"
    bind:value={status}
    placeholder="Todos"
    options={[
      { value: 'active', label: 'Activos' },
      { value: 'inactive', label: 'Inactivos' }
    ]}
  />
  {#if data.roles.length > 0}
    <SelectField
      label="Rol"
      bind:value={role}
      placeholder="Todos"
      options={data.roles.map((r) => ({ value: r.code, label: r.name }))}
    />
  {/if}
  <Button type="submit" variant="secondary">Filtrar</Button>
</form>

<div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">
  <ScrollRegion label="Tabla de usuarios">
    <table class="data-table">
      <thead>
        <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Correo</th>
          <th scope="col">Roles</th>
          <th scope="col">Estado</th>
          <th scope="col">Último acceso</th>
        </tr>
      </thead>
      <tbody>
        {#each data.result.items as user (user.uuid)}
          <tr>
            <td>
              <a
                href={resolve('/(app)/users/[uuid]', { uuid: user.uuid })}
                class="font-medium text-primary hover:underline">{user.full_name}</a
              >
            </td>
            <td class="quiet">{user.email}</td>
            <td>
              <div class="flex flex-wrap gap-1">
                {#each user.roles as r (r.code)}<Badge tone="info">{r.name}</Badge>{:else}<span
                    class="text-muted">—</span
                  >{/each}
              </div>
            </td>
            <td>
              <Badge tone={user.status === 'active' ? 'success' : 'neutral'}
                >{user.status_label}</Badge
              >
            </td>
            <td class="whitespace-nowrap quiet">
              {formatDateTime(user.last_login_at, 'Nunca')}
            </td>
          </tr>
        {:else}
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-muted">
              No hay usuarios que coincidan con los filtros.
            </td>
          </tr>
        {/each}
      </tbody>
    </table>
  </ScrollRegion>
</div>

<Pagination pagination={data.result.pagination} {hrefFor} />
