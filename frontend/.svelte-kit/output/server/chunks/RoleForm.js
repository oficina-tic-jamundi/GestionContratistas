import { C as attr, T as escape_html, o as derived, s as ensure_array_like } from "./server.js";
import { t as Button } from "./Button.js";
import { t as Alert } from "./Alert.js";
import { t as TextField } from "./TextField.js";
import { t as fieldErrors } from "./forms.js";
import { t as Checkbox } from "./Checkbox.js";
//#region src/lib/features/roles/RoleForm.svelte
function RoleForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { initial, permissions, isNew, permissionsEditable = true, readonly = false, submitting = false, error = null, onsubmit } = $$props;
		let form = {
			code: initial.code ?? "",
			name: initial.name,
			description: initial.description ?? "",
			permissions: [...initial.permissions]
		};
		const MODULE_LABELS = {
			users: "Usuarios",
			roles: "Roles y permisos",
			audit: "Auditoría"
		};
		const modules = derived(() => {
			const groups = {};
			for (const permission of permissions) (groups[permission.module] ??= []).push(permission);
			return Object.entries(groups);
		});
		function toggle(code, checked) {
			form.permissions = checked ? [...form.permissions, code] : form.permissions.filter((p) => p !== code);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-6" novalidate=""><fieldset class="grid gap-4 sm:grid-cols-2"${attr("disabled", readonly, true)}><legend class="sr-only">Datos del rol</legend> `);
			if (isNew) {
				$$renderer.push("<!--[0-->");
				TextField($$renderer, {
					label: "Código",
					required: true,
					maxlength: 50,
					hint: "Identificador interno permanente: minúsculas, números y guion bajo (ej. jefe_oficina).",
					errors: fieldErrors(error, "code"),
					get value() {
						return form.code;
					},
					set value($$value) {
						form.code = $$value;
						$$settled = false;
					}
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			TextField($$renderer, {
				label: "Nombre",
				required: true,
				maxlength: 100,
				errors: fieldErrors(error, "name"),
				get value() {
					return form.name;
				},
				set value($$value) {
					form.name = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <div class="sm:col-span-2">`);
			TextField($$renderer, {
				label: "Descripción",
				maxlength: 255,
				errors: fieldErrors(error, "description"),
				get value() {
					return form.description;
				},
				set value($$value) {
					form.description = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></fieldset> <fieldset class="space-y-4"${attr("disabled", readonly || !permissionsEditable, true)}><legend class="text-sm font-medium text-ink">Permisos</legend> `);
			if (!permissionsEditable) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "info",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Los permisos del rol Administrador no se modifican desde la aplicación, para evitar que el
        sistema quede sin administración. Los permisos nuevos se le asignan al actualizar el
        sistema.`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <!--[-->`);
			const each_array = ensure_array_like(modules());
			for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
				let [module, items] = each_array[$$index_1];
				$$renderer.push(`<div class="rounded-md border border-border p-4"><h3 class="mb-3 text-sm font-semibold text-ink">${escape_html(MODULE_LABELS[module] ?? module)}</h3> <div class="grid gap-3 sm:grid-cols-2"><!--[-->`);
				const each_array_1 = ensure_array_like(items);
				for (let $$index = 0, $$length = each_array_1.length; $$index < $$length; $$index++) {
					let permission = each_array_1[$$index];
					Checkbox($$renderer, {
						label: permission.description,
						description: permission.code,
						checked: form.permissions.includes(permission.code),
						onchange: (e) => toggle(permission.code, e.currentTarget.checked)
					});
				}
				$$renderer.push(`<!--]--></div></div>`);
			}
			$$renderer.push(`<!--]--> `);
			if (fieldErrors(error, "permissions").length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(fieldErrors(error, "permissions").join(" "))}</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></fieldset> `);
			if (!readonly) {
				$$renderer.push("<!--[0-->");
				Button($$renderer, {
					type: "submit",
					loading: submitting,
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(isNew ? "Crear rol" : "Guardar cambios")}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></form>`);
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
export { RoleForm as t };
