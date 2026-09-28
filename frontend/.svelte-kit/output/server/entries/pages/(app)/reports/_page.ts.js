import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { a as listReports } from "../../../../chunks/api7.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/reports/+page.ts
var load = async ({ url, parent }) => {
	await parent();
	if (!session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned, Permission.ContractsViewOwn)) error(403, "No tiene permiso para acceder a esta sección.");
	const query = {
		page: Number(url.searchParams.get("page")) || 1,
		status: url.searchParams.get("status") ?? ""
	};
	return {
		result: await listReports(query),
		query
	};
};
//#endregion
export { load };
