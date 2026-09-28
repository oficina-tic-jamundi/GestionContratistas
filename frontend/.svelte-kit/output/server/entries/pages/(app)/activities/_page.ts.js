import { a as getActivityTree } from "../../../../chunks/api4.js";
import { o as listContracts } from "../../../../chunks/api5.js";
//#region src/routes/(app)/activities/+page.ts
var ORDER = [
	"active",
	"suspended",
	"terminated",
	"liquidated",
	"archived",
	"draft"
];
var load = async ({ parent, depends }) => {
	await parent();
	depends("app:activities");
	const contracts = (await listContracts({
		per_page: 50,
		sort: "end_date",
		direction: "desc"
	})).items;
	contracts.sort((a, b) => ORDER.indexOf(a.status) - ORDER.indexOf(b.status));
	return { sections: await Promise.all(contracts.map(async (contract) => ({
		contract,
		tree: await getActivityTree(contract.uuid)
	}))) };
};
//#endregion
export { load };
