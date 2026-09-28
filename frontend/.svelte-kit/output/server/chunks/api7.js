import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/reports/api.ts
var path = (uuid) => `/reports/${encodeURIComponent(uuid)}`;
async function listReports(query) {
	return toPaginated(await api.get("/reports", { query: { ...query } }));
}
async function getReport(uuid) {
	return (await api.get(path(uuid))).data;
}
async function getReportHistory(uuid) {
	return (await api.get(`${path(uuid)}/history`)).data;
}
async function getReportVersion(uuid, version) {
	return (await api.get(`${path(uuid)}/versions/${version}`)).data;
}
async function saveReport(uuid, input) {
	return (await api.put(path(uuid), input)).data;
}
async function submitReport(uuid) {
	return (await api.post(`${path(uuid)}/submit`)).data;
}
async function startReview(uuid) {
	return (await api.post(`${path(uuid)}/start-review`)).data;
}
async function observeReport(uuid, comment, observations) {
	return (await api.post(`${path(uuid)}/observe`, {
		comment,
		observations
	})).data;
}
async function approveReport(uuid, comment) {
	return (await api.post(`${path(uuid)}/approve`, { comment })).data;
}
async function rejectReport(uuid, comment) {
	return (await api.post(`${path(uuid)}/reject`, { comment })).data;
}
var base = "/api/v1";
/** Descarga del PDF de una versión (archivo de la API; no pasa por resolve()). */
function reportPdfUrl(uuid, version) {
	return `${base}${path(uuid)}/versions/${version}/pdf`;
}
async function reopenReport(uuid, reason) {
	return (await api.post(`${path(uuid)}/reopen`, { reason })).data;
}
//#endregion
export { listReports as a, reopenReport as c, startReview as d, submitReport as f, getReportVersion as i, reportPdfUrl as l, getReport as n, observeReport as o, getReportHistory as r, rejectReport as s, approveReport as t, saveReport as u };
