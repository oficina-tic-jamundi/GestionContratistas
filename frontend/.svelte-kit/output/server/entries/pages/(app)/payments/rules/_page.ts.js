import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { s as listPaymentRules } from "../../../../../chunks/api3.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/payments/rules/+page.ts
var load = async ({ parent, depends }) => {
	await parent();
	if (!session.canAny(Permission.PaymentsConfigure, Permission.PaymentsManage, Permission.PaymentsApprove)) error(403, "No tiene permiso para acceder a esta sección.");
	depends("app:payment-rules");
	return { rules: await listPaymentRules() };
};
//#endregion
export { load };
