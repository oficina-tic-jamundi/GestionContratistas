//#region src/lib/features/reports/period.ts
/**
* Período sugerido para un informe nuevo: desde el día siguiente al último informe vigente
* (o desde el inicio del contrato) hasta el fin de ese mes, sin pasar del fin del contrato.
*
* Es solo una sugerencia editable: la periodicidad real la define la Alcaldía (ADR-015)
* y el backend valida que el período esté dentro del contrato y no se cruce con otro.
* Fechas en formato AAAA-MM-DD (calendario, sin zona horaria).
*/
function suggestPeriod(contractStart, contractEnd, reports) {
	const lastEnd = reports.filter((r) => r.status !== "rejected").map((r) => r.period_end).sort().at(-1);
	const start = lastEnd ? addDays(lastEnd, 1) : contractStart;
	if (start > contractEnd) return null;
	const monthEnd = endOfMonth(start);
	return {
		start,
		end: monthEnd < contractEnd ? monthEnd : contractEnd
	};
}
function parse(date) {
	const [y = 0, m = 1, d = 1] = date.split("-").map(Number);
	return new Date(Date.UTC(y, m - 1, d));
}
function format(date) {
	return date.toISOString().slice(0, 10);
}
function addDays(date, days) {
	const d = parse(date);
	d.setUTCDate(d.getUTCDate() + days);
	return format(d);
}
function endOfMonth(date) {
	const d = parse(date);
	return format(new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth() + 1, 0)));
}
//#endregion
export { suggestPeriod as t };
