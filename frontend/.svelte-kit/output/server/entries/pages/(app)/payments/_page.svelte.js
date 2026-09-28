import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { a as formatMoney, r as formatDate, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
import { t as PaymentStatusBadge } from "../../../../chunks/PaymentStatusBadge.js";
//#region src/routes/(app)/payments/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let status = derived(() => data.query.status ?? "");
		const STATUS_OPTIONS = [
			{
				value: "draft",
				label: "En preparación"
			},
			{
				value: "ready_for_approval",
				label: "Listos para aprobación"
			},
			{
				value: "approved",
				label: "Aprobados"
			},
			{
				value: "paid",
				label: "Pagados"
			},
			{
				value: "cancelled",
				label: "Anulados"
			}
		];
		function hrefFor(page) {
			return resolve(`/payments?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (session.canAny(Permission.PaymentsConfigure, Permission.PaymentsManage, Permission.PaymentsApprove)) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/payments/rules"),
							variant: "secondary",
							children: ($$renderer) => {
								$$renderer.push(`<!---->Reglas de elegibilidad`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				PageHeader($$renderer, {
					title: "Pagos",
					description: "Cuentas de cobro de los contratos. Se registran a partir de informes aprobados; SIGCON controla el trámite y registra el pago efectuado por tesorería.",
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <form class="mb-4 flex flex-wrap items-end gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card" role="search"><div class="min-w-56">`);
			SelectField($$renderer, {
				label: "Estado",
				placeholder: "Todos",
				options: STATUS_OPTIONS,
				get value() {
					return status();
				},
				set value($$value) {
					status($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div> `);
			Button($$renderer, {
				type: "submit",
				variant: "secondary",
				children: ($$renderer) => {
					$$renderer.push(`<!---->Filtrar`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></form> <ul class="space-y-3 md:hidden">`);
			const each_array = ensure_array_like(data.result.items);
			if (each_array.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let payment = each_array[$$index];
					$$renderer.push(`<li><a${attr("href", resolve("/(app)/payments/[uuid]", { uuid: payment.uuid }))} class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"><div class="flex items-start justify-between gap-2"><span class="font-medium text-primary">${escape_html(payment.contract.contract_number)} · Pago N.° ${escape_html(payment.number)}</span> `);
					PaymentStatusBadge($$renderer, {
						status: payment.status,
						label: payment.status_label
					});
					$$renderer.push(`<!----></div> <p class="mt-1 text-sm text-ink">${escape_html(payment.contract.contractor)}</p> <p class="mt-1 text-sm font-semibold">${escape_html(formatMoney(payment.amount))}</p></a></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted">No hay pagos para mostrar.</li>`);
			$$renderer.push(`<!--]--></ul> <div class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block">`);
			ScrollRegion($$renderer, {
				label: "Tabla de pagos",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Pago</th><th scope="col">Contratista</th><th scope="col">Período</th><th scope="col" class="num">Valor</th><th scope="col">Estado</th></tr></thead><tbody>`);
					const each_array_1 = ensure_array_like(data.result.items);
					if (each_array_1.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
							let payment = each_array_1[$$index_1];
							$$renderer.push(`<tr><td class="whitespace-nowrap"><a${attr("href", resolve("/(app)/payments/[uuid]", { uuid: payment.uuid }))} class="font-medium text-primary hover:underline">${escape_html(payment.contract.contract_number)} · N.° ${escape_html(payment.number)}</a></td><td>${escape_html(payment.contract.contractor)}</td><td class="whitespace-nowrap quiet">${escape_html(formatDate(payment.period_start))} – ${escape_html(formatDate(payment.period_end))}</td><td class="num whitespace-nowrap">${escape_html(formatMoney(payment.amount))}</td><td>`);
							PaymentStatusBadge($$renderer, {
								status: payment.status,
								label: payment.status_label
							});
							$$renderer.push(`<!----></td></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="5" class="px-4 py-10 text-center text-muted">No hay pagos para mostrar.</td></tr>`);
					$$renderer.push(`<!--]--></tbody></table>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> `);
			Pagination($$renderer, {
				pagination: data.result.pagination,
				hrefFor
			});
			$$renderer.push(`<!---->`);
		}
		do {
			$$settled = true;
			$$inner_renderer = $$renderer.copy();
			$$render_inner($$inner_renderer);
		} while (!$$settled);
		$$renderer.subsume($$inner_renderer);
	});
}
//#endregion
export { _page as default };
