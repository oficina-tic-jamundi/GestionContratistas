import { t as api } from "./api.js";
//#region src/lib/features/roles/api.ts
async function listRoles() {
	return (await api.get("/roles")).data;
}
async function listPermissions() {
	return (await api.get("/permissions")).data;
}
async function createRole(input) {
	return (await api.post("/roles", input)).data;
}
async function updateRole(code, input) {
	return (await api.put(`/roles/${encodeURIComponent(code)}`, input)).data;
}
async function deleteRole(code) {
	await api.delete(`/roles/${encodeURIComponent(code)}`);
}
//#endregion
export { updateRole as a, listRoles as i, deleteRole as n, listPermissions as r, createRole as t };
