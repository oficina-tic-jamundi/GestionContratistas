import { t as api } from "./api.js";
import { t as toPaginated } from "./pagination.js";
//#region src/lib/features/payments/api.ts
var path = (uuid) => `/payments/${encodeURIComponent(uuid)}`;
async function listPayments(query) {
	return toPaginated(await api.get("/payments", { query: { ...query } }));
}
async function getBudget(contractUuid) {
	return (await api.get(`/contracts/${encodeURIComponent(contractUuid)}/budget`)).data;
}
async function getPayment(uuid) {
	return (await api.get(path(uuid))).data;
}
async function getPaymentHistory(uuid) {
	return (await api.get(`${path(uuid)}/history`)).data;
}
async function updatePayment(uuid, amount, notes) {
	return (await api.put(path(uuid), {
		amount,
		notes
	})).data;
}
async function evaluatePayment(uuid) {
	return (await api.post(`${path(uuid)}/evaluate`)).data;
}
async function submitPayment(uuid) {
	return (await api.post(`${path(uuid)}/submit`)).data;
}
async function approvePayment(uuid) {
	return (await api.post(`${path(uuid)}/approve`)).data;
}
async function returnPayment(uuid, reason) {
	return (await api.post(`${path(uuid)}/return`, { reason })).data;
}
async function registerPaid(uuid, paymentDate, reference) {
	return (await api.post(`${path(uuid)}/paid`, {
		payment_date: paymentDate,
		payment_reference: reference
	})).data;
}
async function cancelPayment(uuid, reason) {
	return (await api.post(`${path(uuid)}/cancel`, { reason })).data;
}
async function listPaymentRules() {
	return (await api.get("/payment-rules")).data;
}
//#endregion
export { getPayment as a, listPayments as c, submitPayment as d, updatePayment as f, getBudget as i, registerPaid as l, cancelPayment as n, getPaymentHistory as o, evaluatePayment as r, listPaymentRules as s, approvePayment as t, returnPayment as u };
