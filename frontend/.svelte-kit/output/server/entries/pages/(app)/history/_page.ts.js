import { r as listHistory } from "../../../../chunks/api2.js";
//#region src/routes/(app)/history/+page.ts
var KINDS = [
	"progress",
	"evidence",
	"report",
	"payment",
	"contract"
];
var load = async ({ url, parent }) => {
	await parent();
	const page = Number(url.searchParams.get("page")) || 1;
	const raw = url.searchParams.get("type");
	const type = KINDS.find((k) => k === raw);
	return {
		result: await listHistory({
			page,
			type
		}),
		type: type ?? null
	};
};
//#endregion
export { load };
