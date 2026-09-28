import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as Badge } from "../../../../chunks/Badge.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { i as formatDateTime, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../chunks/TextField.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
import { t as Tabs } from "../../../../chunks/Tabs.js";
//#region src/routes/(app)/users/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let search = derived(() => data.query.search ?? "");
		let status = derived(() => data.query.status ?? "");
		let role = derived(() => data.query.role ?? "");
		function hrefFor(page) {
			return resolve(`/users?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (session.can(Permission.UsersCreate)) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/users/new"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Nuevo usuario`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				PageHeader($$renderer, {
					title: "Usuarios",
					description: "Cuentas de acceso a SIGCON y sus roles.",
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> `);
			Tabs($$renderer, {
				label: "Secciones de roles y usuarios",
				items: [{
					href: resolve("/roles"),
					label: "Roles"
				}, {
					href: resolve("/users"),
					label: "Usuarios"
				}]
			});
			$$renderer.push(`<!----> <form class="mb-4 grid gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card sm:grid-cols-[1fr_auto_auto_auto] sm:items-end" role="search">`);
			TextField($$renderer, {
				label: "Buscar",
				type: "search",
				placeholder: "Nombre o correo",
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
			if (data.roles.length > 0) {
				$$renderer.push("<!--[0-->");
				SelectField($$renderer, {
					label: "Rol",
					placeholder: "Todos",
					options: data.roles.map((r) => ({
						value: r.code,
						label: r.name
					})),
					get value() {
						return role();
					},
					set value($$value) {
						role($$value);
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
			$$renderer.push(`<!----></form> <div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card">`);
			ScrollRegion($$renderer, {
				label: "Tabla de usuarios",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Nombre</th><th scope="col">Correo</th><th scope="col">Roles</th><th scope="col">Estado</th><th scope="col">Último acceso</th></tr></thead><tbody>`);
					const each_array = ensure_array_like(data.result.items);
					if (each_array.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
							let user = each_array[$$index_1];
							$$renderer.push(`<tr><td><a${attr("href", resolve("/(app)/users/[uuid]", { uuid: user.uuid }))} class="font-medium text-primary hover:underline">${escape_html(user.full_name)}</a></td><td class="quiet">${escape_html(user.email)}</td><td><div class="flex flex-wrap gap-1">`);
							const each_array_1 = ensure_array_like(user.roles);
							if (each_array_1.length !== 0) {
								$$renderer.push("<!--[-->");
								for (let $$index = 0, $$length = each_array_1.length; $$index < $$length; $$index++) {
									let r = each_array_1[$$index];
									Badge($$renderer, {
										tone: "info",
										children: ($$renderer) => {
											$$renderer.push(`<!---->${escape_html(r.name)}`);
										},
										$$slots: { default: true }
									});
								}
							} else $$renderer.push(`<!--[!--><span class="text-muted">—</span>`);
							$$renderer.push(`<!--]--></div></td><td>`);
							Badge($$renderer, {
								tone: user.status === "active" ? "success" : "neutral",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(user.status_label)}`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----></td><td class="whitespace-nowrap quiet">${escape_html(formatDateTime(user.last_login_at, "Nunca"))}</td></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="5" class="px-4 py-10 text-center text-muted">No hay usuarios que coincidan con los filtros.</td></tr>`);
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
