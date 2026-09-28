import { t as Permission } from "../../../../../chunks/permissions.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { r as listPermissions } from "../../../../../chunks/api13.js";
//#region src/routes/(app)/roles/new/+page.ts
var load = async ({ parent }) => {
	await parent();
	requirePermission(Permission.RolesManage);
	return { permissions: await listPermissions() };
};
//#endregion
export { load };
