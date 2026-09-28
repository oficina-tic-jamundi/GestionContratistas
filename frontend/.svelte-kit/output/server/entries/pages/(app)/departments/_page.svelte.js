import { T as escape_html, o as derived, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as toasts } from "../../../../chunks/toasts.svelte.js";
import { r as invalidate } from "../../../../chunks/client.js";
import "../../../../chunks/navigation.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as Badge } from "../../../../chunks/Badge.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as Card } from "../../../../chunks/Card.js";
import { t as Alert } from "../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../chunks/forms.js";
import { t as ScrollRegion } from "../../../../chunks/ScrollRegion.js";
import { n as setDepartmentActive } from "../../../../chunks/api9.js";
//#region src/routes/(app)/departments/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const canManage = derived(() => session.can(Permission.DepartmentsManage));
		let editing = null;
		let code = "";
		let name = "";
		let saving = false;
		let error = null;
		function startEdit(department) {
			editing = department;
			code = department.code;
			name = department.name;
			error = null;
		}
		function reset() {
			editing = null;
			code = "";
			name = "";
			error = null;
		}
		async function toggle(department) {
			try {
				await setDepartmentActive(department.uuid, department.status !== "active");
				toasts.show(department.status === "active" ? "Dependencia desactivada." : "Dependencia activada.");
				await invalidate("app:departments");
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible cambiar el estado.", "error");
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			PageHeader($$renderer, {
				title: "Dependencias",
				description: "Secretarías y oficinas de la Alcaldía. No se eliminan: se desactivan para conservar el historial."
			});
			$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-3"><div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-card lg:col-span-2">`);
			ScrollRegion($$renderer, {
				label: "Tabla de dependencias",
				children: ($$renderer) => {
					$$renderer.push(`<table class="data-table"><thead><tr><th scope="col">Código</th><th scope="col">Nombre</th><th scope="col">Contratos en ejecución</th><th scope="col">Estado</th>`);
					if (canManage()) $$renderer.push(`<!--[0--><th scope="col"><span class="sr-only">Acciones</span></th>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></tr></thead><tbody>`);
					const each_array = ensure_array_like(data.departments);
					if (each_array.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
							let department = each_array[$$index];
							$$renderer.push(`<tr><td class="font-mono text-xs">${escape_html(department.code)}</td><td>${escape_html(department.name)}</td><td class="quiet">${escape_html(department.active_contracts)}</td><td>`);
							Badge($$renderer, {
								tone: department.status === "active" ? "success" : "neutral",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(department.status_label)}`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----></td>`);
							if (canManage()) {
								$$renderer.push(`<!--[0--><td class="num whitespace-nowrap">`);
								Button($$renderer, {
									variant: "ghost",
									size: "sm",
									onclick: () => startEdit(department),
									children: ($$renderer) => {
										$$renderer.push(`<!---->Editar`);
									},
									$$slots: { default: true }
								});
								$$renderer.push(`<!----> `);
								Button($$renderer, {
									variant: "ghost",
									size: "sm",
									onclick: () => toggle(department),
									children: ($$renderer) => {
										$$renderer.push(`<!---->${escape_html(department.status === "active" ? "Desactivar" : "Activar")}`);
									},
									$$slots: { default: true }
								});
								$$renderer.push(`<!----></td>`);
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></tr>`);
						}
					} else $$renderer.push(`<!--[!--><tr><td colspan="5" class="px-4 py-10 text-center text-muted">Aún no hay dependencias registradas.</td></tr>`);
					$$renderer.push(`<!--]--></tbody></table>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> `);
			if (canManage()) {
				$$renderer.push("<!--[0-->");
				Card($$renderer, {
					title: editing ? `Editar ${editing.code}` : "Nueva dependencia",
					children: ($$renderer) => {
						$$renderer.push(`<form class="space-y-4" novalidate="">`);
						if (formMessage(error)) {
							$$renderer.push("<!--[0-->");
							Alert($$renderer, {
								variant: "danger",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						TextField($$renderer, {
							label: "Código",
							required: true,
							maxlength: 20,
							hint: "Sigla en mayúsculas, ej. SPLAN.",
							errors: fieldErrors(error, "code"),
							get value() {
								return code;
							},
							set value($$value) {
								code = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> `);
						TextField($$renderer, {
							label: "Nombre",
							required: true,
							maxlength: 150,
							errors: fieldErrors(error, "name"),
							get value() {
								return name;
							},
							set value($$value) {
								name = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> <div class="flex gap-2">`);
						Button($$renderer, {
							type: "submit",
							loading: saving,
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(editing ? "Guardar" : "Crear")}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----> `);
						if (editing) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: "secondary",
								onclick: reset,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Cancelar`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div></form>`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div>`);
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
