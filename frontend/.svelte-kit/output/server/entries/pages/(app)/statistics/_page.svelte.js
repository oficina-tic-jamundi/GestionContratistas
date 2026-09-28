import { C as attr, T as escape_html, o as derived, s as ensure_array_like, t as attr_class, w as clsx } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { a as formatMoney, r as formatDate } from "../../../../chunks/format.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as ContractStatusBadge } from "../../../../chunks/ContractStatusBadge.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
//#region src/routes/(app)/statistics/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Estadísticas de pagos (ADR-021): cuántos pagos lleva cada contrato y cuántos le faltan.
		* "Faltan" solo se muestra si el contrato declara cuántos pagos se pactaron.
		*/
		let { data } = $$props;
		const totals = derived(() => data.statistics.totals);
		const pct = (v) => `${Number(v).toLocaleString("es-CO", { maximumFractionDigits: 1 })} %`;
		PageHeader($$renderer, {
			title: "Estadísticas",
			description: "Esquema de pagos por contrato: plazo, pagos pactados, pagados y pendientes."
		});
		$$renderer.push(`<!----> <ul class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4"><li class="rounded-2xl border border-border bg-surface p-4"><p class="text-3xl font-bold text-ink tabular-nums">${escape_html(totals().contracts)}</p> <p class="mt-1 text-sm text-muted">Contratos en seguimiento</p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-3xl font-bold text-success tabular-nums">${escape_html(totals().paid)}</p> <p class="mt-1 text-sm text-muted">Pagos realizados</p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-3xl font-bold text-warning tabular-nums">${escape_html(totals().in_process)}</p> <p class="mt-1 text-sm text-muted">Pagos en trámite</p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-2xl font-bold text-ink tabular-nums">${escape_html(formatMoney(totals().paid_amount))}</p> <p class="mt-1 text-sm text-muted">Pagado de ${escape_html(formatMoney(totals().total_value))}</p></li></ul> `);
		if (totals().without_agreed > 0) $$renderer.push(`<!--[0--><p class="mb-4 rounded-xl border border-border bg-canvas px-4 py-3 text-sm text-muted">${escape_html(totals().without_agreed)}
    ${escape_html(totals().without_agreed === 1 ? "contrato no tiene" : "contratos no tienen")} registrados los pagos
    pactados, así que no se puede calcular cuántos faltan. Se indican al crear o editar el contrato, mientras
    está en borrador.</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">`);
		ScrollRegion($$renderer, {
			label: "Esquema de pagos por contrato",
			children: ($$renderer) => {
				$$renderer.push(`<table class="data-table min-w-3xl"><thead><tr><th scope="col">Contrato</th><th scope="col">Plazo</th><th scope="col" class="num">Avance</th><th scope="col" class="num">Pactados</th><th scope="col" class="num">Pagados</th><th scope="col" class="num">En trámite</th><th scope="col" class="num">Faltan</th><th scope="col" class="num">Pagado</th></tr></thead><tbody>`);
				const each_array = ensure_array_like(data.statistics.contracts);
				if (each_array.length !== 0) {
					$$renderer.push("<!--[-->");
					for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
						let row = each_array[$$index];
						$$renderer.push(`<tr><td><a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: row.uuid }))} class="font-medium text-primary hover:underline">${escape_html(row.contract_number)}</a> <span class="block text-xs text-muted">${escape_html(row.contractor)} · ${escape_html(row.department)}</span> <span class="mt-1 inline-block">`);
						ContractStatusBadge($$renderer, {
							status: row.status,
							label: row.status_label
						});
						$$renderer.push(`<!----></span></td><td class="quiet">${escape_html(row.months)}
              ${escape_html(row.months === 1 ? "mes" : "meses")} <span class="block text-xs">${escape_html(formatDate(row.start_date))} – ${escape_html(formatDate(row.end_date))}</span></td><td class="num">${escape_html(pct(row.progress))}</td><td class="num">${escape_html(row.payments.agreed ?? "—")}</td><td class="num font-semibold text-success">${escape_html(row.payments.paid)}</td><td class="num">${escape_html(row.payments.in_process)}</td><td class="num">`);
						if (row.payments.pending === null) $$renderer.push(`<!--[0--><span class="text-muted">—</span>`);
						else $$renderer.push(`<!--[-1--><span${attr_class(clsx(row.payments.pending === 0 ? "text-success" : "text-ink"))}>${escape_html(row.payments.pending)}</span>`);
						$$renderer.push(`<!--]--></td><td class="num">${escape_html(formatMoney(row.payments.paid_amount))}</td></tr>`);
					}
				} else $$renderer.push(`<!--[!--><tr><td class="px-4 py-6 text-muted" colspan="8">No hay contratos en su alcance.</td></tr>`);
				$$renderer.push(`<!--]--></tbody></table>`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----></div>`);
	});
}
//#endregion
export { _page as default };
