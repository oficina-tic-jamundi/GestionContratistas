import { t as api } from "./api.js";
//#region src/lib/features/activities/api.ts
var path = (uuid) => `/activities/${encodeURIComponent(uuid)}`;
async function getActivityTree(contractUuid) {
	return (await api.get(`/contracts/${encodeURIComponent(contractUuid)}/activities`)).data;
}
/** Una actividad con su contrato, su obligación y sus tareas (ADR-021). */
async function getActivity(uuid) {
	return (await api.get(path(uuid))).data;
}
/** Obligaciones propuestas desde el contrato firmado (no registra nada, ADR-022). */
async function suggestObligations(contractUuid) {
	return (await api.get(`/contracts/${encodeURIComponent(contractUuid)}/obligations/suggestions`)).data;
}
async function createObligation(contractUuid, input) {
	return (await api.post(`/contracts/${encodeURIComponent(contractUuid)}/obligations`, input)).data;
}
async function createChild(parentUuid, input) {
	return (await api.post(`${path(parentUuid)}/children`, input)).data;
}
async function updateActivity(uuid, input) {
	return (await api.put(path(uuid), input)).data;
}
/** Solo la Alcaldía (contracts.manage) asigna la prioridad. null la quita. */
async function setPriority(uuid, priority) {
	return (await api.put(`${path(uuid)}/priority`, { priority })).data;
}
async function deleteActivity(uuid) {
	await api.delete(path(uuid));
}
async function recordProgress(uuid, progress, note) {
	return (await api.post(`${path(uuid)}/progress`, {
		progress,
		note
	})).data;
}
async function getProgressHistory(uuid) {
	return (await api.get(`${path(uuid)}/progress`)).data;
}
//#endregion
export { getActivityTree as a, setPriority as c, getActivity as i, suggestObligations as l, createObligation as n, getProgressHistory as o, deleteActivity as r, recordProgress as s, createChild as t, updateActivity as u };
