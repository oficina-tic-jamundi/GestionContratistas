import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { a as formatMoney, r as formatDate, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../chunks/TextField.js";
import { t as ContractStatusBadge } from "../../../../chunks/ContractStatusBadge.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
//#region src/routes/(app)/contracts/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let search = derived(() => data.query.search ?? "");
		let status = derived(() => data.query.status ?? "");
		let department = derived(() => data.query.department ?? "");
		const title = derived(() => session.can(Permission.ContractsViewAll) ? "Contratos" : session.can(Permission.ContractsViewAssigned) ? "Contratos que supervisa" : "Mis contratos");
		const STATUS_OPTIONS = [
			{
				value: "draft",
				label: "Borrador"
			},
			{
				value: "active",
				label: "Activo"
			},
			{
				value: "suspended",
				label: "Suspendido"
			},
			{
				value: "terminated",
				label: "Terminado"
			},
			{
				value: "liquidated",
				label: "Liquidado"
			},
			{
				value: "archived",
				label: "Archivado"
			}
		];
		function hrefFor(page) {
			return resolve(`/contracts?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (session.can(Permission.ContractsManage)) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/contracts/new"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Nuevo contrato`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				PageHeader($$renderer, {
					title: title(),
					description: "Valores en pesos colombianos. Fechas de inicio y terminación del contrato.",
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <form class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto] lg:items-end" role="search">`);
			TextField($$renderer, {
				label: "Buscar",
				type: "search",
				placeholder: "Número, objeto, contratista o documento",
				get value() {
					return search();
				},
				set value($$value) {
					search($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
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
			$$renderer.push(`<!----> `);
			if (data.departments.length > 0) {
				$$renderer.push("<!--[0-->");
				SelectField($$renderer, {
					label: "Dependencia",
					placeholder: "Todas",
					options: data.departments.map((d) => ({
						value: d.uuid,
						label: d.name
					})),
					get value() {
						return department();
					},
					set value($$value) {
						department($$value);
						$$settled = false;
					}
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
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
					let contract = each_array[$$index];
					$$renderer.push(`<li><a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: contract.uuid }))} class="block rounded-2xl border border-border bg-surface p-4 shadow-card card-interactive active:bg-canvas"><div class="flex items-start justify-between gap-2"><span class="font-medium text-primary">${escape_html(contract.contract_number)}</span> `);
					ContractStatusBadge($$renderer, {
						status: contract.status,
						label: contract.status_label
					});
					$$renderer.push(`<!----></div> <p class="mt-1 line-clamp-2 text-sm text-ink">${escape_html(contract.object)}</p> <dl class="mt-3 grid grid-cols-2 gap-2 text-xs"><div><dt class="text-muted">Plazo</dt> <dd>${escape_html(formatDate(contract.start_date))} – ${escape_html(formatDate(contract.end_date))}</dd></div> <div class="text-right"><dt class="text-muted">Valor</dt> <dd class="font-medium">${escape_html(formatMoney(contract.total_value))}</dd></div> <div class="col-span-2"><dt class="text-muted">Dependencia</dt> <dd>${escape_html(contract.department.name)}</dd></div></dl></a></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted">No hay contratos para mostrar.</li>`);
			$$renderer.push(`<!--]--></ul> <div class="hidden overflow-hidden rounded-2xl border border-border bg-surface shadow-card md:block">`);
			ScrollRegion($$renderer, {
				label: "Tabla de contratos",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Número</th><th scope="col">Contratista</th><th scope="col">Dependencia</th><th scope="col">Plazo</th><th scope="col" class="num">Valor</th><th scope="col">Estado</th></tr></thead><tbody>`);
					const each_array_1 = ensure_array_like(data.result.items);
					if (each_array_1.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
							let contract = each_array_1[$$index_1];
							$$renderer.push(`<tr><td class="whitespace-nowrap"><a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: contract.uuid }))} class="font-medium text-primary hover:underline">${escape_html(contract.contract_number)}</a></td><td>${escape_html(contract.contractor.name)} <span class="block text-xs text-muted">${escape_html(contract.contractor.document)}</span></td><td class="quiet">${escape_html(contract.department.code)}</td><td class="whitespace-nowrap quiet">${escape_html(formatDate(contract.start_date))} – ${escape_html(formatDate(contract.end_date))}</td><td class="num whitespace-nowrap">${escape_html(formatMoney(contract.total_value))}</td><td>`);
							ContractStatusBadge($$renderer, {
								status: contract.status,
								label: contract.status_label
							});
							$$renderer.push(`<!----></td></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="6" class="px-4 py-10 text-center text-muted">No hay contratos para mostrar.</td></tr>`);
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
