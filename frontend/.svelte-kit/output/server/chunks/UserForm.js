import { C as attr, T as escape_html, s as ensure_array_like } from "./server.js";
import { t as Button } from "./Button.js";
import { t as Alert } from "./Alert.js";
import { t as TextField } from "./TextField.js";
import { t as fieldErrors } from "./forms.js";
import { t as SelectField } from "./SelectField.js";
import { t as Checkbox } from "./Checkbox.js";
//#region src/lib/components/ui/SecretReveal.svelte
function SecretReveal($$renderer, $$props) {
	/**
	* Muestra una contraseña temporal UNA sola vez. No se guarda en ningún almacenamiento
	* del navegador; al salir de la página desaparece.
	*/
	let { secret, email } = $$props;
	let copied = false;
	async function copy() {
		try {
			await navigator.clipboard.writeText(secret);
			copied = true;
			setTimeout(() => copied = false, 3e3);
		} catch {
			copied = false;
		}
	}
	Alert($$renderer, {
		variant: "warning",
		title: "Contraseña temporal — se muestra una sola vez",
		children: ($$renderer) => {
			$$renderer.push(`<p>Entregue esta contraseña a <strong>${escape_html(email)}</strong> por un canal seguro (en persona o por teléfono).
    El sistema no la envía por correo y no podrá volver a consultarla. El usuario deberá cambiarla al
    iniciar sesión.</p> <div class="mt-3 flex flex-wrap items-center gap-3"><code class="rounded-md border border-border bg-surface px-3 py-2 font-mono text-base tracking-wider text-ink select-all">${escape_html(secret)}</code> `);
			Button($$renderer, {
				variant: "secondary",
				size: "sm",
				onclick: copy,
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(copied ? "Copiada" : "Copiar")}`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div>`);
		},
		$$slots: { default: true }
	});
}
//#endregion
//#region src/lib/features/users/UserForm.svelte
function UserForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Formulario de datos de usuario (crear y editar).
		* Solo muestra la sección de roles si se reciben roles asignables.
		*/
		let { initial, roles = [], departments = [], editableFields = true, submitting = false, error = null, submitLabel, onsubmit } = $$props;
		let form = {
			...initial,
			phone: initial.phone ?? "",
			department: initial.department ?? "",
			roles: [...initial.roles ?? []]
		};
		function toggleRole(code, checked) {
			form.roles = checked ? [...form.roles, code] : form.roles.filter((r) => r !== code);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-6" novalidate=""><fieldset class="grid gap-4 sm:grid-cols-2"${attr("disabled", !editableFields, true)}><legend class="sr-only">Datos personales</legend> `);
			TextField($$renderer, {
				label: "Nombres",
				required: true,
				maxlength: 100,
				autocomplete: "off",
				errors: fieldErrors(error, "first_name"),
				get value() {
					return form.first_name;
				},
				set value($$value) {
					form.first_name = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Apellidos",
				required: true,
				maxlength: 100,
				autocomplete: "off",
				errors: fieldErrors(error, "last_name"),
				get value() {
					return form.last_name;
				},
				set value($$value) {
					form.last_name = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Correo electrónico",
				type: "email",
				required: true,
				maxlength: 191,
				autocomplete: "off",
				errors: fieldErrors(error, "email"),
				get value() {
					return form.email;
				},
				set value($$value) {
					form.email = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Teléfono",
				type: "tel",
				maxlength: 30,
				autocomplete: "off",
				hint: "Opcional.",
				errors: fieldErrors(error, "phone"),
				get value() {
					return form.phone;
				},
				set value($$value) {
					form.phone = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			if (departments.length > 0) {
				$$renderer.push("<!--[0-->");
				SelectField($$renderer, {
					label: "Dependencia",
					placeholder: "Sin dependencia",
					options: departments.map((d) => ({
						value: d.uuid,
						label: d.name
					})),
					errors: fieldErrors(error, "department"),
					get value() {
						return form.department;
					},
					set value($$value) {
						form.department = $$value;
						$$settled = false;
					}
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></fieldset> `);
			if (roles.length > 0) {
				$$renderer.push(`<!--[0--><fieldset class="space-y-3"><legend class="text-sm font-medium text-ink">Roles</legend> <div class="grid gap-3 sm:grid-cols-2"><!--[-->`);
				const each_array = ensure_array_like(roles);
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let role = each_array[$$index];
					Checkbox($$renderer, {
						label: role.name,
						description: role.description ?? void 0,
						checked: form.roles.includes(role.code),
						onchange: (e) => toggleRole(role.code, e.currentTarget.checked)
					});
				}
				$$renderer.push(`<!--]--></div> `);
				if (fieldErrors(error, "roles").length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(fieldErrors(error, "roles").join(" "))}</p>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></fieldset>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			Button($$renderer, {
				type: "submit",
				loading: submitting,
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(submitLabel)}`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></form>`);
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
export { SecretReveal as n, UserForm as t };
