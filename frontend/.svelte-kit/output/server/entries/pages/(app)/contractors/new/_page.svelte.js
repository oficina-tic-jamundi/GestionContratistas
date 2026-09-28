import { C as attr, T as escape_html, d as stringify, s as ensure_array_like, t as attr_class } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { n as goto } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Icon } from "../../../../../chunks/Icon.js";
import "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import "../../../../../chunks/api5.js";
import "../../../../../chunks/api6.js";
import { t as createContractor } from "../../../../../chunks/api8.js";
import "../../../../../chunks/ObligationImport.js";
import { t as ContractorForm } from "../../../../../chunks/ContractorForm.js";
import { t as ContractForm } from "../../../../../chunks/ContractForm.js";
//#region src/routes/(app)/contractors/new/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Alta de un contratista en tres pasos (ADR-022): sus datos, su contrato con el PDF firmado y
		* las obligaciones que SIGCON lee de ese PDF, revisadas antes de registrarlas.
		*
		* Cada paso guarda lo suyo: si algo falla, lo ya registrado se conserva y se reintenta solo
		* lo pendiente (no se duplica el contratista ni el contrato).
		*/
		let { data } = $$props;
		const STEPS = [
			"Datos del contratista",
			"Contrato firmado",
			"Obligaciones"
		];
		let step = 0;
		let contractor = null;
		let submitting = false;
		let error = null;
		let pdfError = null;
		async function saveContractor(input) {
			submitting = true;
			error = null;
			try {
				contractor = await createContractor(input);
				toasts.show("Contratista registrado.");
				if (data.withContract) step = 1;
				else await finish();
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		async function saveContract(input) {
			pdfError = "Adjunte el contrato firmado en PDF: de él se toman las obligaciones.";
		}
		async function finish() {
			if (!contractor) return;
			await goto(resolve("/(app)/contractors/[uuid]", { uuid: contractor.uuid }));
		}
		PageHeader($$renderer, {
			title: "Nuevo contratista",
			description: data.withContract ? "Registre al contratista, su contrato firmado y las obligaciones que se leen de él." : "Solo se registran los datos necesarios para identificar y contactar al contratista."
		});
		$$renderer.push(`<!----> <div class="max-w-4xl space-y-4">`);
		if (data.withContract) {
			$$renderer.push(`<!--[0--><ol class="grid gap-2 sm:grid-cols-3" aria-label="Pasos del registro"><!--[-->`);
			const each_array = ensure_array_like(STEPS);
			for (let index = 0, $$length = each_array.length; index < $$length; index++) {
				let label = each_array[index];
				$$renderer.push(`<li${attr_class(`flex items-center gap-3 rounded-xl border px-4 py-3 text-sm ${index === step ? "border-primary/50 bg-primary-soft text-ink" : index < step ? "border-border bg-surface text-ink" : "border-border bg-surface text-muted"}`)}${attr("aria-current", index === step ? "step" : void 0)}><span${attr_class(`grid size-7 shrink-0 place-items-center rounded-full text-xs font-semibold ${index < step ? "bg-success text-deep" : index === step ? "btn-primary" : "bg-canvas text-muted"}`)}>`);
				if (index < step) {
					$$renderer.push("<!--[0-->");
					Icon($$renderer, {
						name: "check-list",
						class: "size-4"
					});
				} else $$renderer.push(`<!--[-1-->${escape_html(index + 1)}`);
				$$renderer.push(`<!--]--></span> <span>${escape_html(label)}${escape_html(index < step ? " (listo)" : "")}</span></li>`);
			}
			$$renderer.push(`<!--]--></ol>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
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
		if (step === 0) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				children: ($$renderer) => {
					ContractorForm($$renderer, {
						initial: {
							person_type: "natural",
							document_type: "CC",
							document_number: "",
							verification_digit: null,
							name: "",
							email: null,
							phone: null,
							address: null,
							user: null
						},
						accounts: data.accounts,
						submitting,
						error,
						submitLabel: data.withContract ? "Guardar y continuar" : "Registrar contratista",
						onsubmit: saveContractor
					});
				},
				$$slots: { default: true }
			});
		} else if (step === 1 && contractor) {
			$$renderer.push("<!--[1-->");
			if (data.departments.length === 0) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "warning",
					title: "No hay dependencias activas",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Registre al menos una dependencia antes de crear contratos.`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			Card($$renderer, {
				title: `Contrato de ${stringify(contractor.name)}`,
				children: ($$renderer) => {
					$$renderer.push(`<div class="mb-6 space-y-2 rounded-xl border border-primary/30 bg-primary-soft p-4"><label for="contrato-pdf" class="block text-sm font-medium text-ink">Contrato firmado (PDF) <span class="text-danger" aria-hidden="true">*</span></label> <input id="contrato-pdf" type="file" accept="application/pdf,.pdf" required="" aria-describedby="contrato-pdf-ayuda" class="w-full rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1 file:text-on-primary"/> <p id="contrato-pdf-ayuda" class="text-xs text-muted">SIGCON leerá la sección de obligaciones específicas para proponerle las actividades. Debe
          ser un PDF con texto (no escaneado).</p> `);
					if (pdfError) $$renderer.push(`<!--[0--><p class="text-sm text-danger" role="alert">${escape_html(pdfError)}</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					$$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div> `);
					ContractForm($$renderer, {
						initial: {
							contract_number: "",
							object: "",
							contractor: contractor.uuid,
							department: "",
							supervisor: null,
							signed_at: null,
							start_date: "",
							end_date: "",
							total_value: "",
							payment_count: "",
							secop_url: null
						},
						contractorLabel: contractor.name,
						departments: data.departments,
						supervisors: data.supervisors,
						submitting,
						error,
						submitLabel: "Guardar contrato y leer obligaciones",
						onsubmit: saveContract
					});
					$$renderer.push(`<!---->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> <p class="text-sm text-muted">¿Aún no tiene el contrato? <button type="button" class="text-primary underline">Terminar ahora</button> y regístrelo después desde Contratos.</p>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
export { _page as default };
