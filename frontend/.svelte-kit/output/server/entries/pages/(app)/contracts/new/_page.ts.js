import { t as Permission } from "../../../../../chunks/permissions.js";
import { s as listSupervisors } from "../../../../../chunks/api5.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../../chunks/api9.js";
//#region src/routes/(app)/contracts/new/+page.ts
var load = async ({ parent }) => {
	await parent();
	requirePermission(Permission.ContractsManage);
	const [departments, supervisors] = await Promise.all([listDepartments("active"), listSupervisors()]);
	return {
		departments,
		supervisors
	};
};
//#endregion
export { load };
