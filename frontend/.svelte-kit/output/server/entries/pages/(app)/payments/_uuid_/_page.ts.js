import { n as ApiError } from "../../../../../chunks/api.js";
import { a as getPayment, o as getPaymentHistory } from "../../../../../chunks/api3.js";
import { error } from "@sveltejs/kit";
//#region src/routes/(app)/payments/[uuid]/+page.ts
var load = async ({ params, parent, depends }) => {
	await parent();
	depends("app:payment");
	try {
		const [payment, history] = await Promise.all([getPayment(params.uuid), getPaymentHistory(params.uuid)]);
		return {
			payment,
			history
		};
	} catch (e) {
		if (e instanceof ApiError && (e.status === 404 || e.status === 403)) error(404, "El pago no existe o no tiene acceso a él.");
		throw e;
	}
};
//#endregion
export { load };
