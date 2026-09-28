import { T as escape_html } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { n as goto } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { n as createContract } from "../../../../../chunks/api5.js";
import { t as ContractForm } from "../../../../../chunks/ContractForm.js";
//#region src/routes/(app)/contracts/new/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let submitting = false;
		let error = null;
		async function save(input) {
			submitting = true;
			error = null;
			try {
				const contract = await createContract(input);
				toasts.show("Contrato registrado en borrador.");
				await goto(resolve("/(app)/contracts/[uuid]", { uuid: contract.uuid }));
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		PageHeader($$renderer, {
			title: "Nuevo contrato",
			description: "Se registra en borrador. Podrá revisarlo y activarlo cuando tenga supervisor asignado."
		});
		$$renderer.push(`<!----> <div class="max-w-4xl space-y-4">`);
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
		Card($$renderer, {
			children: ($$renderer) => {
				ContractForm($$renderer, {
					initial: {
						contract_number: "",
						object: "",
						contractor: "",
						department: "",
						supervisor: null,
						signed_at: null,
						start_date: "",
						end_date: "",
						total_value: "",
						payment_count: "",
						secop_url: null
					},
					departments: data.departments,
					supervisors: data.supervisors,
					submitting,
					error,
					submitLabel: "Registrar borrador",
					onsubmit: save
				});
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----></div>`);
	});
}
//#endregion
export { _page as default };
