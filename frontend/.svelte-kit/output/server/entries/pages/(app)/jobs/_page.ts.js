import { t as Permission } from "../../../../chunks/permissions.js";
import { n as requirePermission } from "../../../../chunks/guards.js";
import { n as listJobs, t as getQueueStatus } from "../../../../chunks/api12.js";
//#region src/routes/(app)/jobs/+page.ts
var load = async ({ url, parent, depends }) => {
	await parent();
	requirePermission(Permission.JobsManage);
	depends("app:jobs");
	const query = {
		page: Number(url.searchParams.get("page")) || 1,
		status: url.searchParams.get("status") ?? ""
	};
	const [result, status] = await Promise.all([listJobs(query), getQueueStatus()]);
	return {
		result,
		status,
		query
	};
};
//#endregion
export { load };
