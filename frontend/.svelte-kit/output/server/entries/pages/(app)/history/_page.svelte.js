import { C as attr, T as escape_html, d as stringify, o as derived, s as ensure_array_like, t as attr_class } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { n as goto } from "../../../../chunks/client.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as Icon } from "../../../../chunks/Icon.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { i as formatDateTime, t as buildQuery } from "../../../../chunks/format.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
//#region src/routes/(app)/history/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Historial del contratista (ADR-021): lo que ocurrió en sus contratos, del más reciente al más antiguo. */
		let { data } = $$props;
		const FILTERS = [
			{
				value: "progress",
				label: "Avances"
			},
			{
				value: "evidence",
				label: "Evidencias"
			},
			{
				value: "report",
				label: "Informes"
			},
			{
				value: "payment",
				label: "Pagos"
			},
			{
				value: "contract",
				label: "Contrato"
			}
		];
		const ICONS = {
			progress: {
				icon: "chart",
				tone: "bg-primary-soft text-primary"
			},
			evidence: {
				icon: "camera",
				tone: "bg-info-soft text-info"
			},
			report: {
				icon: "clipboard",
				tone: "bg-warning-soft text-warning"
			},
			payment: {
				icon: "wallet",
				tone: "bg-success-soft text-success"
			},
			contract: {
				icon: "shield",
				tone: "bg-canvas text-muted"
			}
		};
		function hrefFor(page) {
			return resolve(`/history?${buildQuery({
				page: page === 1 ? null : page,
				type: data.type
			})}`);
		}
		async function filter(value) {
			await goto(resolve(`/history?${buildQuery({ type: value || null })}`));
		}
		const scopeDescription = derived(() => session.can(Permission.ContractsViewAll) ? "Todo lo que ocurre en los contratos: avances y evidencias de los contratistas, revisiones de los supervisores y pagos." : session.can(Permission.ContractsViewAssigned) ? "Lo que ocurre en los contratos que usted supervisa: avances y evidencias de sus contratistas y sus propias revisiones." : "Sus avances, evidencias e informes, y lo que el supervisor decide sobre ellos.");
		const pct = (v) => `${Number(v).toLocaleString("es-CO", { maximumFractionDigits: 2 })} %`;
		function detail(event) {
			if (event.kind === "progress" && event.from_progress !== null && event.to_progress !== null) return `${event.title}: ${pct(event.from_progress)} → ${pct(event.to_progress)}`;
			if (event.kind === "report" && event.ref_number !== null) return `Informe N.° ${event.ref_number}`;
			if (event.kind === "payment" && event.ref_number !== null) return `Pago N.° ${event.ref_number}`;
			return event.title;
		}
		PageHeader($$renderer, {
			title: "Historial",
			description: scopeDescription()
		});
		$$renderer.push(`<!----> <div class="mb-6 max-w-xs">`);
		SelectField($$renderer, {
			label: "Mostrar",
			value: data.type ?? "",
			placeholder: "Todos los eventos",
			options: FILTERS,
			onchange: (e) => filter(e.currentTarget.value)
		});
		$$renderer.push(`<!----></div> <ol class="space-y-3">`);
		const each_array = ensure_array_like(data.result.items);
		if (each_array.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let i = 0, $$length = each_array.length; i < $$length; i++) {
				let event = each_array[i];
				$$renderer.push(`<li class="flex gap-4 rounded-2xl border border-border bg-surface p-4"><span${attr_class(`inline-flex size-10 shrink-0 items-center justify-center rounded-xl ${stringify(ICONS[event.kind].tone)}`)} aria-hidden="true">`);
				Icon($$renderer, { name: ICONS[event.kind].icon });
				$$renderer.push(`<!----></span> <div class="min-w-0 flex-1"><div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1"><p class="font-semibold text-ink">${escape_html(event.summary)}</p> <time class="text-xs text-muted"${attr("datetime", event.occurred_at)}>${escape_html(formatDateTime(event.occurred_at))}</time></div> `);
				if (detail(event)) {
					$$renderer.push(`<!--[0--><p class="mt-0.5 text-sm text-ink">`);
					if (event.target?.type === "report") $$renderer.push(`<!--[0--><a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: event.target.uuid }))} class="text-primary hover:underline">${escape_html(detail(event))}</a>`);
					else if (event.target?.type === "payment") $$renderer.push(`<!--[1--><a${attr("href", resolve("/(app)/payments/[uuid]", { uuid: event.target.uuid }))} class="text-primary hover:underline">${escape_html(detail(event))}</a>`);
					else $$renderer.push(`<!--[-1-->${escape_html(detail(event))}`);
					$$renderer.push(`<!--]--></p>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (event.comment) $$renderer.push(`<!--[0--><p class="mt-1 text-sm whitespace-pre-line text-muted">${escape_html(event.comment)}</p>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <p class="mt-1 text-xs text-muted">${escape_html(event.kind_label)} · <a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: event.contract.uuid }))} class="text-primary hover:underline">Contrato ${escape_html(event.contract.contract_number)}</a> · ${escape_html(event.contract.contractor)} `);
				if (event.actor) $$renderer.push(`<!--[0-->· <span class="text-ink">${escape_html(event.actor)}</span>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></p></div></li>`);
			}
		} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">${escape_html(data.type ? "No hay eventos de este tipo." : "Aún no hay eventos en sus contratos.")}</li>`);
		$$renderer.push(`<!--]--></ol> <div class="mt-6">`);
		Pagination($$renderer, {
			pagination: data.result.pagination,
			hrefFor
		});
		$$renderer.push(`<!----></div>`);
	});
}
//#endregion
export { _page as default };
