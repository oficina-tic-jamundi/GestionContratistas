import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/notifications/api.ts
async function listNotifications(query) {
	return toPaginated(await api.get("/notifications", { query: {
		page: query.page,
		unread: query.unread ? 1 : void 0
	} }));
}
async function unreadCount() {
	return (await api.get("/notifications/unread-count")).data.unread;
}
async function markAllRead() {
	await api.post("/notifications/read-all");
}
async function getDashboard() {
	return (await api.get("/dashboard")).data;
}
/** Historial cronológico con el alcance del usuario: propio, supervisado o todo (ADR-021). */
async function listHistory(query) {
	return toPaginated(await api.get("/history", { query: {
		page: query.page,
		type: query.type
	} }));
}
var base = "/api/v1";
/** Descargas CSV (archivo de la API; no pasan por resolve()). */
function exportUrl(kind, status) {
	return `${base}/exports/${kind}${status ? `?status=${encodeURIComponent(status)}` : ""}`;
}
//#endregion
export { markAllRead as a, listNotifications as i, getDashboard as n, unreadCount as o, listHistory as r, exportUrl as t };
