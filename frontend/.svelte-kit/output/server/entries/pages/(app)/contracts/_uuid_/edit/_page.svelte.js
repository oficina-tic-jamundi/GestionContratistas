import { T as escape_html, d as stringify, o as derived } from "../../../../../../chunks/server.js";
import { t as toasts } from "../../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../../chunks/paths.js";
import { n as goto } from "../../../../../../chunks/client.js";
import "../../../../../../chunks/navigation.js";
import { t as Button } from "../../../../../../chunks/Button.js";
import { t as Card } from "../../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../../chunks/forms.js";
import { l as updateContract } from "../../../../../../chunks/api5.js";
import { t as ContractForm } from "../../../../../../chunks/ContractForm.js";
//#region src/routes/(app)/contracts/[uuid]/edit/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const contract = derived(() => data.contract);
		let submitting = false;
		let error = null;
		const departments = derived(() => data.departments.some((d) => d.uuid === contract().department.uuid) ? data.departments : [...data.departments, {
			...contract().department,
			status: "inactive",
			status_label: "Inactiva",
			active_contracts: 0
		}]);
		async function save(input) {
			submitting = true;
			error = null;
			try {
				await updateContract(contract().uuid, input);
				toasts.show("Borrador actualizado.");
				await goto(resolve("/(app)/contracts/[uuid]", { uuid: contract().uuid }));
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		{
			function actions($$renderer) {
				Button($$renderer, {
					href: resolve("/(app)/contracts/[uuid]", { uuid: contract().uuid }),
					variant: "secondary",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Cancelar`);
					},
					$$slots: { default: true }
				});
			}
			PageHeader($$renderer, {
				title: `Editar borrador ${stringify(contract().contract_number)}`,
				actions,
				$$slots: { actions: true }
			});
		}
		$$renderer.push(`<!----> <div class="max-w-4xl space-y-4">`);
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
						contract_number: contract().contract_number,
						object: contract().object,
						contractor: contract().contractor.uuid,
						department: contract().department.uuid,
						supervisor: contract().supervisor?.uuid ?? null,
						signed_at: contract().signed_at,
						start_date: contract().start_date,
						end_date: contract().end_date,
						total_value: contract().total_value,
						payment_count: contract().payment_count === null ? "" : String(contract().payment_count),
						secop_url: contract().secop_url
					},
					contractorLabel: `${stringify(contract().contractor.name)} — ${stringify(contract().contractor.document)}`,
					departments: departments(),
					supervisors: data.supervisors,
					submitting,
					error,
					submitLabel: "Guardar borrador",
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
