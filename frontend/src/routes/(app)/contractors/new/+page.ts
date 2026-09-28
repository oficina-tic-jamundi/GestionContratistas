import { requirePermission } from '$lib/auth/guards';
import { Permission } from '$lib/auth/permissions';
import { session } from '$lib/features/auth/session.svelte';
import { listSupervisors } from '$lib/features/contracts/api';
import { listDepartments } from '$lib/features/departments/api';
import { listUsers } from '$lib/features/users/api';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ parent }) => {
  await parent();
  requirePermission(Permission.ContractorsManage);

  // Cuentas con rol Contratista para vincular (solo si puede consultar usuarios).
  const accounts = session.can(Permission.UsersView)
    ? (await listUsers({ role: 'contractor', status: 'active', per_page: 100 })).items
    : [];

  // El contrato y sus obligaciones se registran en el mismo asistente (ADR-022), si el usuario
  // también gestiona contratos. Si no, el asistente termina con los datos del contratista.
  const withContract = session.can(Permission.ContractsManage);
  const [departments, supervisors] = withContract
    ? await Promise.all([listDepartments('active'), listSupervisors()])
    : [[], []];

  return { accounts, withContract, departments, supervisors };
};
