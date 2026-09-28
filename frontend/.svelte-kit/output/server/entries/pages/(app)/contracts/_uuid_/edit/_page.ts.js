import { t as resolve } from "../../../../../../chunks/paths.js";
import { t as Permission } from "../../../../../../chunks/permissions.js";
import { n as ApiError } from "../../../../../../chunks/api.js";
import { i as getContract, s as listSupervisors } from "../../../../../../chunks/api5.js";
import { n as requirePermission } from "../../../../../../chunks/guards.js";
import { t as listDepartments } from "../../../../../../chunks/api9.js";
import { error, redirect } from "@sveltejs/kit";
//#region src/routes/(app)/contracts/[uuid]/edit/+page.ts
var load = async ({ params, parent }) => {
	await parent();
	requirePermission(Permission.ContractsManage);
	try {
		const [contract, departments, supervisors] = await Promise.all([
			getContract(params.uuid),
			listDepartments("active"),
			listSupervisors()
		]);
		if (!contract.actions.edit) redirect(307, resolve("/(app)/contracts/[uuid]", { uuid: contract.uuid }));
		return {
			contract,
			departments,
			supervisors
		};
	} catch (e) {
		if (e instanceof ApiError && e.status === 404) error(404, "El contrato no existe.");
		throw e;
	}
};
//#endregion
export { load };
