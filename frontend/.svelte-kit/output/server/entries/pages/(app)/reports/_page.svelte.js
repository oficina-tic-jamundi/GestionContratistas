import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { i as formatDateTime, r as formatDate, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as ReportStatusBadge } from "../../../../chunks/ReportStatusBadge.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
//#region src/routes/(app)/reports/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let status = derived(() => data.query.status ?? "");
		const isContractor = derived(() => session.can(Permission.ReportsCreate));
		const isReviewer = derived(() => session.can(Permission.ReportsReview));
		const STATUS_OPTIONS = [
			{
				value: "pending_review",
				label: "Pendientes de revisión"
			},
			{
				value: "draft",
				label: "Borrador"
			},
			{
				value: "submitted",
				label: "Enviado"
			},
			{
				value: "in_review",
				label: "En revisión"
			},
			{
				value: "observed",
				label: "Con observaciones"
			},
			{
				value: "resubmitted",
				label: "Corregido (reenviado)"
			},
			{
				value: "approved",
				label: "Aprobado"
			},
			{
				value: "rejected",
				label: "Rechazado"
			}
		];
		function hrefFor(page) {
			return resolve(`/reports?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (isContractor()) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/contracts"),
							variant: "secondary",
							children: ($$renderer) => {
								$$renderer.push(`<!---->Ir a mis contratos`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				PageHeader($$renderer, {
					title: isContractor() ? "Mis informes" : "Informes",
					description: isContractor() ? "Para elaborar un informe nuevo, abra el contrato correspondiente." : isReviewer() ? "Informes de los contratos que supervisa. Los borradores solo los ve el contratista." : "Informes de los contratos a los que tiene acceso.",
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
			$$renderer.push(`<!----> `);
			if (isReviewer()) {
				$$renderer.push("<!--[0-->");
				Button($$renderer, {
					variant: "ghost",
					href: resolve(`/reports?${buildQuery({ status: "pending_review" })}`),
					children: ($$renderer) => {
						$$renderer.push(`<!---->Pendientes de revisión`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></form> <ul class="space-y-3 md:hidden">`);
			const each_array = ensure_array_like(data.result.items);
			if (each_array.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let report = each_array[$$index];
					$$renderer.push(`<li><a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: report.uuid }))} class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"><div class="flex items-start justify-between gap-2"><span class="font-medium text-primary">${escape_html(report.contract.contract_number)} · N.° ${escape_html(report.number)}</span> `);
					ReportStatusBadge($$renderer, {
						status: report.status,
						label: report.status_label
					});
					$$renderer.push(`<!----></div> <p class="mt-1 text-sm text-ink">${escape_html(report.contract.contractor)}</p> <p class="mt-1 text-xs text-muted">${escape_html(formatDate(report.period_start))} – ${escape_html(formatDate(report.period_end))}</p></a></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted">No hay informes para mostrar.</li>`);
			$$renderer.push(`<!--]--></ul> <div class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block">`);
			ScrollRegion($$renderer, {
				label: "Tabla de informes",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Informe</th><th scope="col">Contratista</th><th scope="col">Período</th><th scope="col">Versión</th><th scope="col">Actualizado</th><th scope="col">Estado</th></tr></thead><tbody>`);
					const each_array_1 = ensure_array_like(data.result.items);
					if (each_array_1.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
							let report = each_array_1[$$index_1];
							$$renderer.push(`<tr><td class="whitespace-nowrap"><a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: report.uuid }))} class="font-medium text-primary hover:underline">${escape_html(report.contract.contract_number)} · N.° ${escape_html(report.number)}</a></td><td>${escape_html(report.contract.contractor)}</td><td class="whitespace-nowrap quiet">${escape_html(formatDate(report.period_start))} – ${escape_html(formatDate(report.period_end))}</td><td class="quiet">${escape_html(report.current_version || "—")}</td><td class="whitespace-nowrap quiet">${escape_html(formatDateTime(report.updated_at))}</td><td>`);
							ReportStatusBadge($$renderer, {
								status: report.status,
								label: report.status_label
							});
							$$renderer.push(`<!----></td></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="6" class="px-4 py-10 text-center text-muted">No hay informes para mostrar.</td></tr>`);
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
