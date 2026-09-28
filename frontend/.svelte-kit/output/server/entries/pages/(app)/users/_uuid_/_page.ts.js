import { t as Permission } from "../../../../../chunks/permissions.js";
import { n as ApiError } from "../../../../../chunks/api.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../../chunks/api9.js";
import { n as getUser } from "../../../../../chunks/api10.js";
import { i as listRoles } from "../../../../../chunks/api13.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/users/[uuid]/+page.ts
var load = async ({ params, parent }) => {
	await parent();
	requirePermission(Permission.UsersView);
	try {
		const canAssign = session.can(Permission.UsersAssignRoles, Permission.RolesView);
		const [user, roles, departments] = await Promise.all([
			getUser(params.uuid),
			canAssign ? listRoles() : Promise.resolve([]),
			session.can(Permission.UsersUpdate) ? listDepartments("active") : Promise.resolve([])
		]);
		return {
			user,
			roles,
			departments
		};
	} catch (e) {
		if (e instanceof ApiError && e.status === 404) error(404, "El usuario no existe.");
		throw e;
	}
};
//#endregion
export { load };
