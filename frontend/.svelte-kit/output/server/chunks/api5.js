import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/contracts/api.ts
var path = (uuid) => `/contracts/${encodeURIComponent(uuid)}`;
async function listContracts(query) {
	return toPaginated(await api.get("/contracts", { query: { ...query } }));
}
async function getContract(uuid) {
	return (await api.get(path(uuid))).data;
}
async function getContractHistory(uuid) {
	return (await api.get(`${path(uuid)}/history`)).data;
}
async function createContract(input) {
	return (await api.post("/contracts", input)).data;
}
async function updateContract(uuid, input) {
	return (await api.put(path(uuid), input)).data;
}
async function transitionContract(uuid, status, comment) {
	return (await api.post(`${path(uuid)}/transitions`, {
		status,
		comment
	})).data;
}
async function changeSupervisor(uuid, supervisor, comment) {
	return (await api.post(`${path(uuid)}/supervisor`, {
		supervisor,
		comment
	})).data;
}
async function deleteContract(uuid) {
	await api.delete(path(uuid));
}
async function listSupervisors() {
	return (await api.get("/supervisors")).data;
}
//#endregion
export { getContractHistory as a, transitionContract as c, getContract as i, updateContract as l, createContract as n, listContracts as o, deleteContract as r, listSupervisors as s, changeSupervisor as t };
