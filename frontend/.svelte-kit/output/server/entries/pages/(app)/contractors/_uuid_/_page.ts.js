import { t as Permission } from "../../../../../chunks/permissions.js";
import { n as ApiError } from "../../../../../chunks/api.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { a as getActivityTree } from "../../../../../chunks/api4.js";
import { o as listContracts } from "../../../../../chunks/api5.js";
import { a as listReports } from "../../../../../chunks/api7.js";
import { t as requireAnyPermission } from "../../../../../chunks/guards.js";
import { n as getContractor } from "../../../../../chunks/api8.js";
import { r as listUsers } from "../../../../../chunks/api10.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/contractors/[uuid]/+page.ts
var load = async ({ params, parent, depends }) => {
	await parent();
	requireAnyPermission(Permission.ContractorsView, Permission.ContractsViewAssigned);
	depends("app:contractor");
	try {
		const [contractor, contracts, accounts] = await Promise.all([
			getContractor(params.uuid),
			listContracts({
				contractor: params.uuid,
				per_page: 50
			}),
			session.can(Permission.ContractorsManage, Permission.UsersView) ? listUsers({
				role: "contractor",
				status: "active",
				per_page: 100
			}).then((r) => r.items) : Promise.resolve([])
		]);
		return {
			contractor,
			contracts,
			accounts,
			files: await Promise.all(contracts.items.map(async (contract) => ({
				contract,
				tree: await getActivityTree(contract.uuid),
				reports: (await listReports({
					contract: contract.uuid,
					per_page: 50,
					sort: "period",
					direction: "desc"
				})).items
			})))
		};
	} catch (e) {
		if (e instanceof ApiError && e.status === 404) error(404, "El contratista no existe.");
		throw e;
	}
};
//#endregion
export { load };
