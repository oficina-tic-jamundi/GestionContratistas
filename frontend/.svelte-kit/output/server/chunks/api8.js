import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/contractors/api.ts
var path = (uuid) => `/contractors/${encodeURIComponent(uuid)}`;
async function listContractors(query, signal) {
	return toPaginated(await api.get("/contractors", {
		query: { ...query },
		signal
	}));
}
async function getContractor(uuid) {
	return (await api.get(path(uuid))).data;
}
async function createContractor(input) {
	return (await api.post("/contractors", input)).data;
}
async function updateContractor(uuid, input) {
	return (await api.put(path(uuid), input)).data;
}
/** Devuelve la respuesta completa: el mensaje informa contratos en ejecución al desactivar. */
async function setContractorActive(uuid, active) {
	return api.post(`${path(uuid)}/${active ? "activate" : "deactivate"}`);
}
//#endregion
export { updateContractor as a, setContractorActive as i, getContractor as n, listContractors as r, createContractor as t };
