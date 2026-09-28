import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { s as listSupervisors } from "../../../../../chunks/api5.js";
import { n as requirePermission } from "../../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../../chunks/api9.js";
import { r as listUsers } from "../../../../../chunks/api10.js";
//#region src/routes/(app)/contractors/new/+page.ts
var load = async ({ parent }) => {
	await parent();
	requirePermission(Permission.ContractorsManage);
	const accounts = session.can(Permission.UsersView) ? (await listUsers({
		role: "contractor",
		status: "active",
		per_page: 100
	})).items : [];
	const withContract = session.can(Permission.ContractsManage);
	const [departments, supervisors] = withContract ? await Promise.all([listDepartments("active"), listSupervisors()]) : [[], []];
	return {
		accounts,
		withContract,
		departments,
		supervisors
	};
};
//#endregion
export { load };
