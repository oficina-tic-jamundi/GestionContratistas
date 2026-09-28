import { C as attr, T as escape_html, a as bind_props, l as props_id, o as derived, t as attr_class } from "./server.js";
import { a as formatMoney } from "./format.js";
import { t as Button } from "./Button.js";
import { t as TextField } from "./TextField.js";
import { t as fieldErrors } from "./forms.js";
import { t as TextArea } from "./TextArea.js";
import { t as SelectField } from "./SelectField.js";
import "./api8.js";
//#region src/lib/features/contractors/ContractorPicker.svelte
function ContractorPicker($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { value = "", initialLabel = "", errors = [] } = $$props;
		let query = initialLabel;
		$$renderer.push(`<div class="relative space-y-1"><label${attr("for", uid)} class="block text-sm font-medium text-ink">Contratista <span class="text-danger" aria-hidden="true">*</span></label> <input${attr("id", uid)} type="search" role="combobox" autocomplete="off"${attr("aria-expanded", false)}${attr("aria-controls", `${uid}-list`)}${attr("aria-invalid", errors.length > 0)} placeholder="Buscar por nombre o número de documento"${attr("value", query)}${attr_class(`block w-full rounded-md border bg-surface px-3 py-2 text-sm text-ink shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none ${errors.length > 0 ? "border-danger" : "border-border"}`)}/> <p class="text-xs text-muted" aria-live="polite">`);
		if (value) $$renderer.push(`<!--[2-->Contratista
      seleccionado.`);
		else $$renderer.push(`<!--[-1-->Escriba al menos 2 caracteres y seleccione de la lista (solo contratistas
      activos).`);
		$$renderer.push(`<!--]--></p> `);
		$$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (errors.length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(errors.join(" "))}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
		bind_props($$props, { value });
	});
}
//#endregion
//#region src/lib/features/contracts/ContractForm.svelte
function ContractForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Formulario de contrato en borrador (crear y editar). */
		let { initial, contractorLabel = "", departments, supervisors, submitting = false, error = null, submitLabel, onsubmit } = $$props;
		let form = {
			...initial,
			supervisor: initial.supervisor ?? "",
			signed_at: initial.signed_at ?? "",
			secop_url: initial.secop_url ?? "",
			total_value: initial.total_value.replace(/\.00$/, ""),
			payment_count: initial.payment_count ?? ""
		};
		const valuePreview = derived(() => /^\d+(\.\d{1,2})?$/.test(form.total_value) ? formatMoney(form.total_value) : "");
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-8" novalidate=""><fieldset class="space-y-4"><legend class="label-eyebrow mb-3">Identificación</legend> <div class="grid gap-4 sm:grid-cols-2">`);
			TextField($$renderer, {
				label: "Número de contrato",
				required: true,
				maxlength: 50,
				hint: "Tal como lo asigna la Oficina Jurídica.",
				errors: fieldErrors(error, "contract_number"),
				get value() {
					return form.contract_number;
				},
				set value($$value) {
					form.contract_number = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			SelectField($$renderer, {
				label: "Dependencia",
				placeholder: "Seleccione…",
				options: departments.map((d) => ({
					value: d.uuid,
					label: `${d.name} (${d.code})`
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
			$$renderer.push(`<!----> <div class="sm:col-span-2">`);
			ContractorPicker($$renderer, {
				initialLabel: contractorLabel,
				errors: fieldErrors(error, "contractor"),
				get value() {
					return form.contractor;
				},
				set value($$value) {
					form.contractor = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div> <div class="sm:col-span-2">`);
			TextArea($$renderer, {
				label: "Objeto del contrato",
				required: true,
				rows: 4,
				maxlength: 5e3,
				errors: fieldErrors(error, "object"),
				get value() {
					return form.object;
				},
				set value($$value) {
					form.object = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></div></fieldset> <fieldset class="space-y-4"><legend class="label-eyebrow mb-3">Plazo y valor</legend> <div class="grid gap-4 sm:grid-cols-3">`);
			TextField($$renderer, {
				label: "Fecha de suscripción",
				type: "date",
				errors: fieldErrors(error, "signed_at"),
				get value() {
					return form.signed_at;
				},
				set value($$value) {
					form.signed_at = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Fecha de inicio",
				type: "date",
				required: true,
				errors: fieldErrors(error, "start_date"),
				get value() {
					return form.start_date;
				},
				set value($$value) {
					form.start_date = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Fecha de terminación",
				type: "date",
				required: true,
				errors: fieldErrors(error, "end_date"),
				get value() {
					return form.end_date;
				},
				set value($$value) {
					form.end_date = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div> <div class="grid gap-4 sm:grid-cols-2">`);
			TextField($$renderer, {
				label: "Valor total (COP)",
				required: true,
				inputmode: "decimal",
				placeholder: "42000000",
				hint: valuePreview() ? `= ${valuePreview()}` : "Sin puntos de miles; decimales con punto.",
				errors: fieldErrors(error, "total_value"),
				get value() {
					return form.total_value;
				},
				set value($$value) {
					form.total_value = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Pagos pactados",
				inputmode: "numeric",
				placeholder: "11",
				hint: "Cuántos pagos contempla el contrato. Permite ver cuántos lleva y cuántos faltan.",
				errors: fieldErrors(error, "payment_count"),
				get value() {
					return form.payment_count;
				},
				set value($$value) {
					form.payment_count = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></fieldset> <fieldset class="space-y-4"><legend class="label-eyebrow mb-3">Supervisión y publicidad</legend> <div class="grid gap-4 sm:grid-cols-2">`);
			SelectField($$renderer, {
				label: "Supervisor",
				placeholder: "Sin asignar (obligatorio para activar)",
				options: supervisors.map((s) => ({
					value: s.uuid,
					label: s.department ? `${s.name} — ${s.department}` : s.name
				})),
				errors: fieldErrors(error, "supervisor"),
				get value() {
					return form.supervisor;
				},
				set value($$value) {
					form.supervisor = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <div class="sm:col-span-2">`);
			TextField($$renderer, {
				label: "Enlace al proceso en SECOP II",
				type: "url",
				maxlength: 500,
				placeholder: "https://community.secop.gov.co/…",
				hint: "Opcional.",
				errors: fieldErrors(error, "secop_url"),
				get value() {
					return form.secop_url;
				},
				set value($$value) {
					form.secop_url = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div></div></fieldset> <div class="flex flex-wrap gap-3 border-t border-border pt-6">`);
			Button($$renderer, {
				type: "submit",
				loading: submitting,
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(submitLabel)}`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></form>`);
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
export { ContractForm as t };
