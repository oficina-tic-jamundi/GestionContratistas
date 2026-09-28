import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { n as requirePermission } from "../../../../chunks/guards.js";
import { r as listUsers } from "../../../../chunks/api10.js";
import { i as listRoles } from "../../../../chunks/api13.js";
//#region src/routes/(app)/users/+page.ts
var load = async ({ url, parent }) => {
	await parent();
	requirePermission(Permission.UsersView);
	const params = url.searchParams;
	const query = {
		page: Number(params.get("page")) || 1,
		search: params.get("search") ?? "",
		status: params.get("status") ?? "",
		role: params.get("role") ?? "",
		sort: params.get("sort") ?? "name",
		direction: params.get("direction") === "desc" ? "desc" : "asc"
	};
	const [result, roles] = await Promise.all([listUsers(query), session.can(Permission.RolesView) ? listRoles() : Promise.resolve([])]);
	return {
		result,
		roles,
		query
	};
};
//#endregion
export { load };
