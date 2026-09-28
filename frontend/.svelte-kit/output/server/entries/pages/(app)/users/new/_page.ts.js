import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../../chunks/api9.js";
import { i as listRoles } from "../../../../../chunks/api13.js";
//#region src/routes/(app)/users/new/+page.ts
var load = async ({ parent }) => {
	await parent();
	requirePermission(Permission.UsersCreate);
	const canAssign = session.can(Permission.UsersAssignRoles, Permission.RolesView);
	const [roles, departments] = await Promise.all([canAssign ? listRoles() : Promise.resolve([]), listDepartments("active")]);
	return {
		roles,
		departments
	};
};
//#endregion
export { load };
