import { t as Permission } from "../../../../chunks/permissions.js";
import { t as api } from "../../../../chunks/api.js";
import { t as toPaginated } from "../../../../chunks/pagination.js";
import { n as requirePermission } from "../../../../chunks/guards.js";
//#region src/lib/features/audit/api.ts
async function listAuditLogs(query) {
	return toPaginated(await api.get("/audit-logs", { query: { ...query } }));
}
async function listAuditActions() {
	return (await api.get("/audit-logs/actions")).data;
}
//#endregion
//#region src/routes/(app)/audit/+page.ts
var FILTERS = [
	"action",
	"user",
	"entity_type",
	"entity_id",
	"from",
	"to"
];
var load = async ({ url, parent }) => {
	await parent();
	requirePermission(Permission.AuditView);
	const query = { page: Number(url.searchParams.get("page")) || 1 };
	for (const key of FILTERS) {
		const value = url.searchParams.get(key);
		if (value) query[key] = value;
	}
	const [result, actions] = await Promise.all([listAuditLogs(query), listAuditActions()]);
	return {
		result,
		actions,
		query
	};
};
//#endregion
export { load };
