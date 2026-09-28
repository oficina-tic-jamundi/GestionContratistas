import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { o as listContracts } from "../../../../chunks/api5.js";
import { t as listDepartments } from "../../../../chunks/api9.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/contracts/+page.ts
var load = async ({ url, parent }) => {
	await parent();
	if (!session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned, Permission.ContractsViewOwn)) error(403, "No tiene permiso para acceder a esta sección.");
	const params = url.searchParams;
	const query = {
		page: Number(params.get("page")) || 1,
		search: params.get("search") ?? "",
		status: params.get("status") ?? "",
		department: params.get("department") ?? "",
		ending_before: params.get("ending_before") ?? ""
	};
	const canFilterDepartments = session.canAny(Permission.DepartmentsView, Permission.ContractsManage, Permission.UsersView);
	const [result, departments] = await Promise.all([listContracts(query), canFilterDepartments ? listDepartments() : Promise.resolve([])]);
	return {
		result,
		departments,
		query
	};
};
//#endregion
export { load };
