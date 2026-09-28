import { t as Permission } from "../../../../chunks/permissions.js";
import { n as requirePermission } from "../../../../chunks/guards.js";
import { i as listRoles } from "../../../../chunks/api13.js";
//#region src/routes/(app)/roles/+page.ts
var load = async ({ parent }) => {
	await parent();
	requirePermission(Permission.RolesView);
	return { roles: await listRoles() };
};
//#endregion
export { load };
