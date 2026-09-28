import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/users/api.ts
async function listUsers(query, signal) {
	return toPaginated(await api.get("/users", {
		query: { ...query },
		signal
	}));
}
async function getUser(uuid) {
	return (await api.get(`/users/${encodeURIComponent(uuid)}`)).data;
}
async function createUser(input) {
	return (await api.post("/users", input)).data;
}
async function updateUser(uuid, changes) {
	return (await api.patch(`/users/${encodeURIComponent(uuid)}`, changes)).data;
}
async function setUserActive(uuid, active) {
	const action = active ? "activate" : "deactivate";
	return (await api.post(`/users/${encodeURIComponent(uuid)}/${action}`)).data;
}
async function resetUserPassword(uuid) {
	return (await api.post(`/users/${encodeURIComponent(uuid)}/reset-password`)).data;
}
//#endregion
export { setUserActive as a, resetUserPassword as i, getUser as n, updateUser as o, listUsers as r, createUser as t };
