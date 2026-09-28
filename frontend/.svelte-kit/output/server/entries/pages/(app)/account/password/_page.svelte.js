import { T as escape_html, o as derived } from "../../../../../chunks/server.js";
import "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import "../../../../../chunks/navigation.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { t as TextField } from "../../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
//#region src/routes/(app)/account/password/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let current = "";
		let next = "";
		let confirmation = "";
		let submitting = false;
		let error = null;
		const mismatch = derived(() => confirmation.length > 0 && next !== confirmation);
		const forced = derived(() => session.mustChangePassword);
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			PageHeader($$renderer, {
				title: "Cambiar contraseña",
				description: "Use una frase larga que sea fácil de recordar para usted y difícil de adivinar."
			});
			$$renderer.push(`<!----> <div class="max-w-xl space-y-4">`);
			if (forced()) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "warning",
					title: "Debe cambiar su contraseña temporal",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Por seguridad, antes de usar el sistema debe definir una contraseña personal.`);
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
					$$renderer.push(`<form class="space-y-4" novalidate="">`);
					TextField($$renderer, {
						label: "Contraseña actual",
						type: "password",
						autocomplete: "current-password",
						required: true,
						errors: fieldErrors(error, "current_password"),
						get value() {
							return current;
						},
						set value($$value) {
							current = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					TextField($$renderer, {
						label: "Nueva contraseña",
						type: "password",
						autocomplete: "new-password",
						required: true,
						hint: "Mínimo 12 caracteres. No use su correo ni contraseñas comunes.",
						errors: fieldErrors(error, "new_password"),
						get value() {
							return next;
						},
						set value($$value) {
							next = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					TextField($$renderer, {
						label: "Confirmar nueva contraseña",
						type: "password",
						autocomplete: "new-password",
						required: true,
						errors: mismatch() ? ["Las contraseñas no coinciden."] : [],
						get value() {
							return confirmation;
						},
						set value($$value) {
							confirmation = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> <div class="flex gap-2">`);
					Button($$renderer, {
						type: "submit",
						loading: submitting,
						disabled: mismatch() || !current || !next,
						children: ($$renderer) => {
							$$renderer.push(`<!---->Guardar contraseña`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> `);
					if (!forced()) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							href: resolve("/"),
							variant: "secondary",
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
			$$renderer.push(`<!----></div>`);
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
