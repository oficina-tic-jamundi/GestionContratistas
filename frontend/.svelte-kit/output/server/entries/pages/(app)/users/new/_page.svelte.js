import { T as escape_html } from "../../../../../chunks/server.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { t as createUser } from "../../../../../chunks/api10.js";
import { n as SecretReveal, t as UserForm } from "../../../../../chunks/UserForm.js";
//#region src/routes/(app)/users/new/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let submitting = false;
		let error = null;
		let created = null;
		async function save(input) {
			submitting = true;
			error = null;
			try {
				created = await createUser(data.roles.length > 0 ? input : {
					...input,
					roles: void 0
				});
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		PageHeader($$renderer, {
			title: "Nuevo usuario",
			description: "El sistema genera una contraseña temporal segura."
		});
		$$renderer.push(`<!----> <div class="max-w-3xl space-y-4">`);
		if (created) {
			$$renderer.push("<!--[0-->");
			Alert($$renderer, {
				variant: "success",
				title: "Usuario creado",
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(created.user.full_name)} ya puede iniciar sesión con la contraseña temporal.`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			SecretReveal($$renderer, {
				secret: created.temporary_password,
				email: created.user.email
			});
			$$renderer.push(`<!----> <div class="flex gap-2">`);
			Button($$renderer, {
				href: resolve("/(app)/users/[uuid]", { uuid: created.user.uuid }),
				children: ($$renderer) => {
					$$renderer.push(`<!---->Ver usuario`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Button($$renderer, {
				href: resolve("/users"),
				variant: "secondary",
				children: ($$renderer) => {
					$$renderer.push(`<!---->Volver al listado`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div>`);
		} else {
			$$renderer.push("<!--[-1-->");
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
					UserForm($$renderer, {
						initial: {
							first_name: "",
							last_name: "",
							email: "",
							phone: null,
							roles: [],
							department: null
						},
						roles: data.roles,
						departments: data.departments,
						submitting,
						error,
						submitLabel: "Crear usuario",
						onsubmit: save
					});
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!---->`);
		}
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
export { _page as default };
