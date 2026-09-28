import { t as Permission } from "../../../../chunks/permissions.js";
import { n as requirePermission } from "../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../chunks/api9.js";
//#region src/routes/(app)/departments/+page.ts
var load = async ({ parent, depends }) => {
	await parent();
	requirePermission(Permission.DepartmentsView);
	depends("app:departments");
	return { departments: await listDepartments() };
};
//#endregion
export { load };
