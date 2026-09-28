import { T as escape_html, o as derived } from "./server.js";
import { t as Button } from "./Button.js";
import { t as TextField } from "./TextField.js";
import { t as fieldErrors } from "./forms.js";
import { t as SelectField } from "./SelectField.js";
//#region src/lib/features/contractors/ContractorForm.svelte
function ContractorForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { initial, accounts = [], submitting = false, error = null, submitLabel, onsubmit } = $$props;
		let form = {
			...initial,
			verification_digit: initial.verification_digit ?? "",
			email: initial.email ?? "",
			phone: initial.phone ?? "",
			address: initial.address ?? "",
			user: initial.user ?? ""
		};
		const DOCUMENT_TYPES = {
			natural: [
				{
					value: "CC",
					label: "Cédula de ciudadanía"
				},
				{
					value: "CE",
					label: "Cédula de extranjería"
				},
				{
					value: "PPT",
					label: "Permiso por protección temporal"
				},
				{
					value: "PA",
					label: "Pasaporte"
				},
				{
					value: "NIT",
					label: "NIT"
				}
			],
			juridica: [{
				value: "NIT",
				label: "NIT"
			}]
		};
		const documentOptions = derived(() => DOCUMENT_TYPES[form.person_type]);
		function changePersonType() {
			if (!documentOptions().some((o) => o.value === form.document_type)) form.document_type = documentOptions()[0]?.value ?? "NIT";
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-6" novalidate=""><fieldset class="grid gap-4 sm:grid-cols-2"><legend class="sr-only">Identificación</legend> `);
			SelectField($$renderer, {
				label: "Tipo de persona",
				onchange: changePersonType,
				options: [{
					value: "natural",
					label: "Persona natural"
				}, {
					value: "juridica",
					label: "Persona jurídica"
				}],
				errors: fieldErrors(error, "person_type"),
				get value() {
					return form.person_type;
				},
				set value($$value) {
					form.person_type = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			SelectField($$renderer, {
				label: "Tipo de documento",
				options: documentOptions(),
				errors: fieldErrors(error, "document_type"),
				get value() {
					return form.document_type;
				},
				set value($$value) {
					form.document_type = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Número de documento",
				required: true,
				maxlength: 30,
				inputmode: form.document_type === "CC" || form.document_type === "NIT" ? "numeric" : "text",
				hint: "Puede escribirlo con o sin puntos.",
				errors: fieldErrors(error, "document_number"),
				get value() {
					return form.document_number;
				},
				set value($$value) {
					form.document_number = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			if (form.document_type === "NIT") {
				$$renderer.push("<!--[0-->");
				TextField($$renderer, {
					label: "Dígito de verificación",
					required: true,
					maxlength: 1,
					inputmode: "numeric",
					hint: "Tal como aparece en el RUT. El sistema lo valida.",
					errors: fieldErrors(error, "verification_digit"),
					get value() {
						return form.verification_digit;
					},
					set value($$value) {
						form.verification_digit = $$value;
						$$settled = false;
					}
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div class="sm:col-span-2">`);
			TextField($$renderer, {
				label: form.person_type === "juridica" ? "Razón social" : "Nombre completo",
				required: true,
				maxlength: 200,
				errors: fieldErrors(error, "name"),
				get value() {
					return form.name;
				},
				set value($$value) {
					form.name = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></fieldset> <fieldset class="grid gap-4 sm:grid-cols-2"><legend class="mb-2 text-sm font-medium text-ink">Contacto</legend> `);
			TextField($$renderer, {
				label: "Correo electrónico",
				type: "email",
				maxlength: 191,
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
				errors: fieldErrors(error, "phone"),
				get value() {
					return form.phone;
				},
				set value($$value) {
					form.phone = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <div class="sm:col-span-2">`);
			TextField($$renderer, {
				label: "Dirección",
				maxlength: 200,
				errors: fieldErrors(error, "address"),
				get value() {
					return form.address;
				},
				set value($$value) {
					form.address = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></fieldset> `);
			if (accounts.length > 0) {
				$$renderer.push("<!--[0-->");
				SelectField($$renderer, {
					label: "Cuenta de acceso a SIGCON",
					placeholder: "Sin cuenta vinculada",
					options: accounts.map((a) => ({
						value: a.uuid,
						label: `${a.full_name} (${a.email})`
					})),
					errors: fieldErrors(error, "user"),
					get value() {
						return form.user;
					},
					set value($$value) {
						form.user = $$value;
						$$settled = false;
					}
				});
				$$renderer.push(`<!----> <p class="-mt-4 text-xs text-muted">Vincule la cuenta con rol Contratista para que la persona consulte sus contratos.</p>`);
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
export { ContractorForm as t };
