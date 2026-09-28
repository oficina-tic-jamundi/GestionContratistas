import { t as api } from "./api.js";
//#region src/lib/features/departments/api.ts
async function listDepartments(status) {
	return (await api.get("/departments", { query: { status } })).data;
}
async function setDepartmentActive(uuid, active) {
	const action = active ? "activate" : "deactivate";
	return (await api.post(`/departments/${encodeURIComponent(uuid)}/${action}`)).data;
}
//#endregion
export { setDepartmentActive as n, listDepartments as t };
