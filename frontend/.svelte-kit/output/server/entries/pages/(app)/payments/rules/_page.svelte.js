import { T as escape_html, o as derived, s as ensure_array_like } from "../../../../../chunks/server.js";
import "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as Badge } from "../../../../../chunks/Badge.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { i as formatDateTime } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import "../../../../../chunks/api3.js";
import { t as TextField } from "../../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
import { t as Checkbox } from "../../../../../chunks/Checkbox.js";
//#region src/lib/features/payments/RuleEditor.svelte
function RuleEditor($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Una regla de elegibilidad: activación y parámetros (el backend los valida). */
		let { rule, editable, onsaved } = $$props;
		let enabled = rule.enabled;
		let numbers = Object.fromEntries(rule.parameters.filter((p) => p.type === "integer").map((p) => [p.name, String(rule.params[p.name] ?? p.min ?? "")]));
		let choices = Object.fromEntries(rule.parameters.filter((p) => p.type === "choices").map((p) => [p.name, [...rule.params[p.name] ?? []]]));
		let saving = false;
		let error = null;
		function toggle(name, value, checked) {
			const current = choices[name] ?? [];
			choices[name] = checked ? [...current, value] : current.filter((v) => v !== value);
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-3" novalidate=""><div class="flex flex-wrap items-start justify-between gap-2"><div><p class="font-medium text-ink">${escape_html(rule.label)}</p> <p class="text-xs text-muted">${escape_html(rule.description)}</p></div> `);
			Badge($$renderer, {
				tone: rule.enabled ? "success" : "neutral",
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(rule.enabled ? "Activa" : "Desactivada")}`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> `);
			if (formMessage(error) && !Object.keys(numbers).concat(Object.keys(choices)).some((n) => fieldErrors(error, `params.${n}`).length > 0)) {
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
			Checkbox($$renderer, {
				label: "Regla activa",
				disabled: !editable,
				get checked() {
					return enabled;
				},
				set checked($$value) {
					enabled = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <!--[-->`);
			const each_array = ensure_array_like(rule.parameters);
			for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
				let param = each_array[$$index_1];
				if (param.type === "integer") {
					$$renderer.push(`<!--[0--><div class="max-w-48">`);
					TextField($$renderer, {
						label: param.label,
						type: "number",
						min: param.min,
						max: param.max,
						disabled: !editable,
						errors: fieldErrors(error, `params.${param.name}`),
						get value() {
							return numbers[param.name];
						},
						set value($$value) {
							numbers[param.name] = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div>`);
				} else {
					$$renderer.push(`<!--[-1--><fieldset class="space-y-2"><legend class="text-sm font-medium text-ink">${escape_html(param.label)}</legend> <!--[-->`);
					const each_array_1 = ensure_array_like(param.options ?? []);
					for (let $$index = 0, $$length = each_array_1.length; $$index < $$length; $$index++) {
						let option = each_array_1[$$index];
						Checkbox($$renderer, {
							label: option.label,
							checked: (choices[param.name] ?? []).includes(option.value),
							disabled: !editable,
							onchange: (e) => toggle(param.name, option.value, e.currentTarget.checked)
						});
					}
					$$renderer.push(`<!--]--> `);
					if (fieldErrors(error, `params.${param.name}`).length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(fieldErrors(error, `params.${param.name}`).join(" "))}</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></fieldset>`);
				}
				$$renderer.push(`<!--]-->`);
			}
			$$renderer.push(`<!--]--> <div class="flex flex-wrap items-center gap-3">`);
			if (editable) {
				$$renderer.push("<!--[0-->");
				Button($$renderer, {
					type: "submit",
					size: "sm",
					loading: saving,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Guardar`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (rule.updated_by) $$renderer.push(`<!--[0--><span class="text-xs text-muted">Última modificación: ${escape_html(rule.updated_by)}, ${escape_html(formatDateTime(rule.updated_at))}</span>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div></form>`);
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
//#region src/routes/(app)/payments/rules/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const editable = derived(() => session.can(Permission.PaymentsConfigure));
		{
			function actions($$renderer) {
				Button($$renderer, {
					href: resolve("/payments"),
					variant: "secondary",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Volver a pagos`);
					},
					$$slots: { default: true }
				});
			}
			PageHeader($$renderer, {
				title: "Reglas de elegibilidad de pagos",
				description: "Condiciones que el sistema verifica antes de enviar un pago a aprobación y antes de aprobarlo (ADR-007).",
				actions,
				$$slots: { actions: true }
			});
		}
		$$renderer.push(`<!----> <div class="mb-4">`);
		Alert($$renderer, {
			variant: "info",
			children: ($$renderer) => {
				$$renderer.push(`<!---->Las reglas desactivadas dependen de definiciones institucionales pendientes (documentos
    obligatorios, evidencias mínimas, avance requerido). Cada cambio queda en la auditoría. `);
				if (!editable()) $$renderer.push(`<!--[0-->Solo quien tiene el permiso de configuración puede modificarlas.`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]-->`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----></div> <div class="grid gap-4 lg:grid-cols-2"><!--[-->`);
		const each_array = ensure_array_like(data.rules);
		for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
			let rule = each_array[$$index];
			Card($$renderer, {
				children: ($$renderer) => {
					RuleEditor($$renderer, {
						rule,
						editable: editable(),
						onsaved: () => invalidate("app:payment-rules")
					});
				},
				$$slots: { default: true }
			});
		}
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
export { _page as default };
