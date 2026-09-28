import { T as escape_html } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { n as goto } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { t as createRole } from "../../../../../chunks/api13.js";
import { t as RoleForm } from "../../../../../chunks/RoleForm.js";
//#region src/routes/(app)/roles/new/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let submitting = false;
		let error = null;
		async function save(input) {
			submitting = true;
			error = null;
			try {
				const role = await createRole(input);
				toasts.show(`Rol "${role.name}" creado.`);
				await goto(resolve("/(app)/roles/[code]", { code: role.code }));
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		PageHeader($$renderer, { title: "Nuevo rol" });
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
				RoleForm($$renderer, {
					initial: {
						code: "",
						name: "",
						description: null,
						permissions: []
					},
					permissions: data.permissions,
					isNew: true,
					submitting,
					error,
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
