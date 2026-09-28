import { n as ApiError } from "../../../../../chunks/api.js";
import { r as listDocuments } from "../../../../../chunks/api6.js";
import { i as getReportVersion, n as getReport, r as getReportHistory } from "../../../../../chunks/api7.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/reports/[uuid]/+page.ts
var load = async ({ params, parent, depends }) => {
	await parent();
	depends("app:report");
	try {
		const [report, history, documents] = await Promise.all([
			getReport(params.uuid),
			getReportHistory(params.uuid),
			listDocuments({
				kind: "report",
				uuid: params.uuid
			})
		]);
		return {
			report,
			history,
			documents,
			frozen: !report.can.edit && report.current_version > 0 ? await getReportVersion(params.uuid, report.current_version) : null
		};
	} catch (e) {
		if (e instanceof ApiError && (e.status === 404 || e.status === 403)) error(404, "El informe no existe o no tiene acceso a él.");
		throw e;
	}
};
//#endregion
export { load };
