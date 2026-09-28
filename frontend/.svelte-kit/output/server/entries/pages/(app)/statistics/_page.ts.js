import { t as Permission } from "../../../../chunks/permissions.js";
import { t as api } from "../../../../chunks/api.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { error } from "@sveltejs/kit";
//#region src/lib/features/statistics/api.ts
/** Esquema de pagos por contrato, dentro del alcance del usuario (ADR-021). */
async function getPaymentStatistics() {
	return (await api.get("/statistics/payments")).data;
}
//#endregion
//#region src/routes/(app)/statistics/+page.ts
var load = async ({ parent }) => {
	await parent();
	if (!session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)) error(403, "No tiene permiso para acceder a esta sección.");
	return { statistics: await getPaymentStatistics() };
};
//#endregion
export { load };
