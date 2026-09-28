import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as Badge } from "../../../../chunks/Badge.js";
import { t as Icon } from "../../../../chunks/Icon.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../chunks/TextField.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
//#region src/routes/(app)/contractors/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Contratistas: punto de entrada de la administración y de la supervisión. Cada tarjeta
		* abre la ficha del contratista con sus actividades (ADR-021).
		*/
		let { data } = $$props;
		let search = derived(() => data.query.search ?? "");
		let status = derived(() => data.query.status ?? "");
		const canManage = derived(() => session.can(Permission.ContractorsManage));
		function hrefFor(page) {
			return resolve(`/contractors?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		const initial = (name) => name.trim().charAt(0).toUpperCase();
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (canManage()) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/contractors/new"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Nuevo contratista`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				PageHeader($$renderer, {
					title: "Contratistas",
					description: canManage() ? "Abra un contratista para ver sus actividades y su información." : "Contratistas de los contratos que usted supervisa. Ábralos para ver sus actividades.",
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <form class="mb-6 grid gap-3 rounded-2xl border border-border bg-surface p-4 sm:grid-cols-[1fr_12rem_auto] sm:items-end" role="search">`);
			TextField($$renderer, {
				label: "Buscar",
				placeholder: "Nombre o número de documento",
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
				options: [{
					value: "active",
					label: "Activos"
				}, {
					value: "inactive",
					label: "Inactivos"
				}],
				get value() {
					return status();
				},
				set value($$value) {
					status($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			Button($$renderer, {
				type: "submit",
				variant: "secondary",
				children: ($$renderer) => {
					$$renderer.push(`<!---->Filtrar`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></form> <ul class="stagger grid gap-4 sm:grid-cols-2 xl:grid-cols-3">`);
			const each_array = ensure_array_like(data.result.items);
			if (each_array.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let contractor = each_array[$$index];
					$$renderer.push(`<li><a${attr("href", resolve("/(app)/contractors/[uuid]", { uuid: contractor.uuid }))} class="card-interactive flex h-full items-start gap-4 rounded-2xl border border-border bg-surface p-5 shadow-card hover:bg-primary-soft"><span class="inline-flex size-12 shrink-0 items-center justify-center rounded-full bg-[#2563eb] text-lg font-semibold text-white" aria-hidden="true">${escape_html(initial(contractor.name))}</span> <span class="min-w-0 flex-1"><span class="block truncate text-base font-semibold text-ink">${escape_html(contractor.name)}</span> <span class="mt-0.5 block font-mono text-xs text-muted">${escape_html(contractor.document)}</span> <span class="mt-1 block text-xs text-muted">${escape_html(contractor.person_type_label)}</span> <span class="mt-3 flex flex-wrap items-center gap-2">`);
					Badge($$renderer, {
						tone: contractor.status === "active" ? "success" : "neutral",
						children: ($$renderer) => {
							$$renderer.push(`<!---->${escape_html(contractor.status_label)}`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> `);
					if (!contractor.user) {
						$$renderer.push("<!--[0-->");
						Badge($$renderer, {
							tone: "warning",
							children: ($$renderer) => {
								$$renderer.push(`<!---->Sin cuenta vinculada`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></span></span> `);
					Icon($$renderer, {
						name: "chevron",
						class: "size-5 -rotate-90 text-muted"
					});
					$$renderer.push(`<!----></a></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted sm:col-span-2 xl:col-span-3">${escape_html(data.query.search || data.query.status ? "No hay contratistas que coincidan con el filtro." : "Aún no hay contratistas registrados.")}</li>`);
			$$renderer.push(`<!--]--></ul> <div class="mt-6">`);
			Pagination($$renderer, {
				pagination: data.result.pagination,
				hrefFor
			});
			$$renderer.push(`<!----></div>`);
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
