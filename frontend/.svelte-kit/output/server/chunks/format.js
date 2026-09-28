//#region src/lib/utils/format.ts
var dateTime = new Intl.DateTimeFormat("es-CO", {
	dateStyle: "medium",
	timeStyle: "short",
	timeZone: "America/Bogota"
});
function formatDateTime(iso, empty = "—") {
	if (!iso) return empty;
	const date = new Date(iso);
	return Number.isNaN(date.getTime()) ? empty : dateTime.format(date);
}
var money = new Intl.NumberFormat("es-CO", {
	style: "currency",
	currency: "COP",
	minimumFractionDigits: 0,
	maximumFractionDigits: 2
});
/**
* Valor en pesos colombianos. Recibe el texto decimal de la API ("42000000.00").
* Solo para mostrar: los cálculos con dinero se hacen en el backend.
*/
function formatMoney(value) {
	if (value === null || value === void 0 || value === "") return "—";
	const amount = Number(value);
	return Number.isFinite(amount) ? money.format(amount) : value;
}
var dateOnly = new Intl.DateTimeFormat("es-CO", {
	dateStyle: "medium",
	timeZone: "UTC"
});
/**
* Fecha sin hora ("2026-01-15"). Se formatea en UTC para que la fecha no se corra un día
* por la zona horaria del navegador.
*/
function formatDate(value, empty = "—") {
	if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) return empty;
	return dateOnly.format(/* @__PURE__ */ new Date(`${value}T00:00:00Z`));
}
/** Tamaño de archivo legible (KB/MB). */
function formatBytes(bytes) {
	if (bytes < 1024) return `${bytes} B`;
	if (bytes < 1048576) return `${(bytes / 1024).toLocaleString("es-CO", { maximumFractionDigits: 1 })} KB`;
	return `${(bytes / 1024 / 1024).toLocaleString("es-CO", { maximumFractionDigits: 1 })} MB`;
}
/** Construye un query string (sin "?") conservando solo valores no vacíos. */
function buildQuery(params) {
	const search = new URLSearchParams();
	for (const [key, value] of Object.entries(params)) if (value !== null && value !== void 0 && value !== "") search.set(key, String(value));
	return search.toString();
}
//#endregion
export { formatMoney as a, formatDateTime as i, formatBytes as n, formatDate as r, buildQuery as t };
