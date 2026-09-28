import { t as Permission } from "../../../../../chunks/permissions.js";
import { n as ApiError } from "../../../../../chunks/api.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { c as listPayments, i as getBudget } from "../../../../../chunks/api3.js";
import { a as getActivityTree } from "../../../../../chunks/api4.js";
import { a as getContractHistory, i as getContract, s as listSupervisors } from "../../../../../chunks/api5.js";
import { n as listContractDocuments } from "../../../../../chunks/api6.js";
import { a as listReports } from "../../../../../chunks/api7.js";
import { r as listEvidences } from "../../../../../chunks/api11.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/contracts/[uuid]/+page.ts
var load = async ({ params, parent, depends }) => {
	await parent();
	depends("app:contract");
	try {
		const [contract, history, supervisors, activities, documents, reports, evidences, budget, payments] = await Promise.all([
			getContract(params.uuid),
			getContractHistory(params.uuid),
			session.can(Permission.ContractsManage) ? listSupervisors() : Promise.resolve([]),
			getActivityTree(params.uuid),
			listContractDocuments(params.uuid),
			listReports({
				contract: params.uuid,
				per_page: 100,
				sort: "period",
				direction: "desc"
			}),
			listEvidences(params.uuid),
			getBudget(params.uuid),
			listPayments({
				contract: params.uuid,
				per_page: 100
			})
		]);
		return {
			contract,
			history,
			supervisors,
			activities,
			documents,
			reports: reports.items,
			evidences,
			budget,
			payments: payments.items
		};
	} catch (e) {
		if (e instanceof ApiError && (e.status === 404 || e.status === 403)) error(404, "El contrato no existe o no tiene acceso a él.");
		throw e;
	}
};
//#endregion
export { load };
