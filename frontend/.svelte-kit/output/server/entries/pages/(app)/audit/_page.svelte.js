import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { i as formatDateTime, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../chunks/TextField.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
//#region src/routes/(app)/audit/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let action = derived(() => data.query.action ?? "");
		let from = derived(() => data.query.from ?? "");
		let to = derived(() => data.query.to ?? "");
		const actionOptions = derived(() => [
			{
				value: "auth.",
				label: "Autenticación (todos)"
			},
			{
				value: "user.",
				label: "Usuarios (todos)"
			},
			{
				value: "role.",
				label: "Roles (todos)"
			},
			...Object.entries(data.actions).map(([value, label]) => ({
				value,
				label
			}))
		]);
		function hrefFor(page) {
			return resolve(`/audit?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		function describe(metadata) {
			if (!metadata) return "";
			return Object.entries(metadata).map(([key, value]) => `${key}: ${typeof value === "string" ? value : JSON.stringify(value)}`).join(" · ");
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			PageHeader($$renderer, {
				title: "Auditoría",
				description: "Registro de solo lectura de las acciones realizadas en el sistema. Horas en America/Bogota."
			});
			$$renderer.push(`<!----> <form class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-[2fr_1fr_1fr_auto] sm:items-end" role="search">`);
			SelectField($$renderer, {
				label: "Acción",
				placeholder: "Todas",
				options: actionOptions(),
				get value() {
					return action();
				},
				set value($$value) {
					action($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Desde",
				type: "date",
				get value() {
					return from();
				},
				set value($$value) {
					from($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Hasta",
				type: "date",
				get value() {
					return to();
				},
				set value($$value) {
					to($$value);
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
			$$renderer.push(`<!----></form> `);
			if (data.query.entity_type || data.query.user) $$renderer.push(`<!--[0--><p class="mb-3 text-sm text-muted">Filtrado por ${escape_html(data.query.entity_type ? `${data.query.entity_type} ${data.query.entity_id ?? ""}` : "usuario")}. <a${attr("href", resolve("/audit"))} class="text-primary hover:underline">Quitar filtro</a></p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">`);
			ScrollRegion($$renderer, {
				label: "Registros de auditoría",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Fecha y hora</th><th scope="col">Acción</th><th scope="col">Usuario</th><th scope="col">Detalle</th><th scope="col">IP</th></tr></thead><tbody class="divide-y divide-border align-top">`);
					const each_array = ensure_array_like(data.result.items);
					if (each_array.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
							let entry = each_array[$$index];
							$$renderer.push(`<tr><td class="whitespace-nowrap">${escape_html(formatDateTime(entry.occurred_at))}</td><td><span class="font-medium">${escape_html(entry.action_label)}</span> <span class="block font-mono text-xs text-muted">${escape_html(entry.action)}</span></td><td>`);
							if (entry.user) $$renderer.push(`<!--[0-->${escape_html(entry.user.name)}<span class="block text-xs text-muted">${escape_html(entry.user.email)}</span>`);
							else $$renderer.push(`<!--[-1--><span class="text-muted">Sistema / anónimo</span>`);
							$$renderer.push(`<!--]--></td><td class="max-w-md text-xs break-words quiet">`);
							if (entry.entity_type) $$renderer.push(`<!--[0--><span class="block">${escape_html(entry.entity_type)}: ${escape_html(entry.entity_id)}</span>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> ${escape_html(describe(entry.metadata))}</td><td class="font-mono text-xs whitespace-nowrap quiet">${escape_html(entry.ip_address ?? "—")}</td></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="5" class="px-4 py-10 text-center text-muted">No hay registros para los filtros seleccionados.</td></tr>`);
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
