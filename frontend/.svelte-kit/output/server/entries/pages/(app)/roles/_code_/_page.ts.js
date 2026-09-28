import { t as Permission } from "../../../../../chunks/permissions.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { i as listRoles, r as listPermissions } from "../../../../../chunks/api13.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/roles/[code]/+page.ts
var load = async ({ params, parent }) => {
	await parent();
	requirePermission(Permission.RolesView);
	const [roles, permissions] = await Promise.all([listRoles(), listPermissions()]);
	const role = roles.find((r) => r.code === params.code);
	if (!role) error(404, "El rol no existe.");
	return {
		role,
		permissions
	};
};
//#endregion
export { load };
