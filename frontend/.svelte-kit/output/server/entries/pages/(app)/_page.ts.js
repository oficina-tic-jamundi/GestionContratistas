import { t as api } from "../../../chunks/api.js";
import { n as getDashboard } from "../../../chunks/api2.js";
//#region src/lib/features/system/health.ts
async function fetchHealth(signal) {
	return (await api.get("/health", { signal })).data;
}
//#endregion
//#region src/routes/(app)/+page.ts
var load = async ({ parent }) => {
	await parent();
	return {
		dashboard: getDashboard(),
		health: fetchHealth()
	};
};
//#endregion
export { load };
