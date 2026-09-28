import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { i as getActivity } from "../../../../../chunks/api4.js";
import { r as listDocuments } from "../../../../../chunks/api6.js";
import { a as listReports, n as getReport } from "../../../../../chunks/api7.js";
//#region src/routes/(app)/activities/[uuid]/+page.ts
/** Estados en los que el contratista todavía puede escribir en el informe (ADR-015). */
var EDITABLE = ["draft", "observed"];
/** Estados en los que el informe está en manos del supervisor. */
var REVIEW = [
	"submitted",
	"in_review",
	"resubmitted"
];
var load = async ({ params, parent, depends }) => {
	await parent();
	depends("app:activities");
	const detail = await getActivity(params.uuid);
	const reports = (await listReports({
		contract: detail.contract.uuid,
		per_page: 50,
		sort: "period"
	})).items;
	const reviewer = session.can(Permission.ReportsReview);
	const pending = reports.find((r) => REVIEW.includes(r.status));
	const open = reports.find((r) => EDITABLE.includes(r.status));
	const chosen = (reviewer ? pending ?? open : open ?? pending) ?? reports.at(-1);
	const report = chosen ? await getReport(chosen.uuid) : null;
	return {
		detail,
		reports,
		report,
		documents: report ? await listDocuments({
			kind: "report",
			uuid: report.uuid
		}) : null
	};
};
//#endregion
export { load };
