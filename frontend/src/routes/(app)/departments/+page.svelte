<script lang="ts">
  import { invalidate } from '$app/navigation';
  import { Permission } from '$lib/auth/permissions';
  import Alert from '$lib/components/ui/Alert.svelte';
  import Badge from '$lib/components/ui/Badge.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Card from '$lib/components/ui/Card.svelte';
  import PageHeader from '$lib/components/ui/PageHeader.svelte';
  import ScrollRegion from '$lib/components/ui/ScrollRegion.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import {
    createDepartment,
    setDepartmentActive,
    updateDepartment
  } from '$lib/features/departments/api';
  import { toasts } from '$lib/stores/toasts.svelte';
  import type { Department } from '$lib/types/contracts';
  import { fieldErrors, formMessage } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  const canManage = $derived(session.can(Permission.DepartmentsManage));

  let editing = $state<Department | null>(null);
  let code = $state('');
  let name = $state('');
  let saving = $state(false);
  let error = $state<unknown>(null);

  function startEdit(department: Department): void {
    editing = department;
    code = department.code;
    name = department.name;
    error = null;
  }

  function reset(): void {
    editing = null;
    code = '';
    name = '';
    error = null;
  }

  async function save(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    saving = true;
    error = null;
    try {
      if (editing) {
        await updateDepartment(editing.uuid, { code, name });
        toasts.show('Dependencia actualizada.');
      } else {
        await createDepartment({ code, name });
        toasts.show('Dependencia creada.');
      }
      reset();
      await invalidate('app:departments');
    } catch (e) {
      error = e;
    } finally {
      saving = false;
    }
  }

  async function toggle(department: Department): Promise<void> {
    try {
      await setDepartmentActive(department.uuid, department.status !== 'active');
      toasts.show(
        department.status === 'active' ? 'Dependencia desactivada.' : 'Dependencia activada.'
      );
      await invalidate('app:departments');
    } catch (e) {
      toasts.show(formMessage(e) ?? 'No fue posible cambiar el estado.', 'error');
    }
  }
</script>

<PageHeader
  title="Dependencias"
  description="Secretarías y oficinas de la Alcaldía. No se eliminan: se desactivan para conservar el historial."
/>

<div class="grid gap-6 lg:grid-cols-3">
  <div
    class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card lg:col-span-2"
  >
    <ScrollRegion label="Tabla de dependencias">
      <table class="data-table">
        <thead>
          <tr>
            <th scope="col">Código</th>
            <th scope="col">Nombre</th>
            <th scope="col">Contratos en ejecución</th>
            <th scope="col">Estado</th>
            {#if canManage}<th scope="col"><span class="sr-only">Acciones</span></th>{/if}
          </tr>
        </thead>
        <tbody>
          {#each data.departments as department (department.uuid)}
            <tr>
              <td class="font-mono text-xs">{department.code}</td>
              <td>{department.name}</td>
              <td class="quiet">{department.active_contracts}</td>
              <td>
                <Badge tone={department.status === 'active' ? 'success' : 'neutral'}
                  >{department.status_label}</Badge
                >
              </td>
              {#if canManage}
                <td class="num whitespace-nowrap">
                  <Button variant="ghost" size="sm" onclick={() => startEdit(department)}
                    >Editar</Button
                  >
                  <Button variant="ghost" size="sm" onclick={() => toggle(department)}>
                    {department.status === 'active' ? 'Desactivar' : 'Activar'}
                  </Button>
                </td>
              {/if}
            </tr>
          {:else}
            <tr>
              <td colspan="5" class="px-4 py-10 text-center text-muted">
                Aún no hay dependencias registradas.
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
    </ScrollRegion>
  </div>

  {#if canManage}
    <Card title={editing ? `Editar ${editing.code}` : 'Nueva dependencia'}>
      <form class="space-y-4" onsubmit={save} novalidate>
        {#if formMessage(error)}<Alert variant="danger">{formMessage(error)}</Alert>{/if}
        <TextField
          label="Código"
          required
          maxlength={20}
          hint="Sigla en mayúsculas, ej. SPLAN."
          bind:value={code}
          errors={fieldErrors(error, 'code')}
        />
        <TextField
          label="Nombre"
          required
          maxlength={150}
          bind:value={name}
          errors={fieldErrors(error, 'name')}
        />
        <div class="flex gap-2">
          <Button type="submit" loading={saving}>{editing ? 'Guardar' : 'Crear'}</Button>
          {#if editing}<Button variant="secondary" onclick={reset}>Cancelar</Button>{/if}
        </div>
      </form>
    </Card>
  {/if}
</div>
