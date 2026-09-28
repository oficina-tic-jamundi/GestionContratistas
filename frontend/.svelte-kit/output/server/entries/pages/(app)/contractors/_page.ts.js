import { t as Permission } from "../../../../chunks/permissions.js";
import { t as requireAnyPermission } from "../../../../chunks/guards.js";
import { r as listContractors } from "../../../../chunks/api8.js";
//#region src/routes/(app)/contractors/+page.ts
var load = async ({ url, parent }) => {
	await parent();
	requireAnyPermission(Permission.ContractorsView, Permission.ContractsViewAssigned);
	const query = {
		page: Number(url.searchParams.get("page")) || 1,
		search: url.searchParams.get("search") ?? "",
		status: url.searchParams.get("status") ?? ""
	};
	return {
		result: await listContractors(query),
		query
	};
};
//#endregion
export { load };
