import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/jobs/api.ts
async function listJobs(query) {
	return toPaginated(await api.get("/jobs", { query: { ...query } }));
}
async function getQueueStatus() {
	return (await api.get("/jobs/status")).data;
}
async function retryJob(uuid) {
	return (await api.post(`/jobs/${encodeURIComponent(uuid)}/retry`)).data;
}
//#endregion
export { listJobs as n, retryJob as r, getQueueStatus as t };
