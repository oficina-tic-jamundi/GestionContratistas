import { C as attr, T as escape_html, d as stringify, o as derived, s as ensure_array_like, t as attr_class } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { a as formatMoney, i as formatDateTime, r as formatDate } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { d as submitPayment, f as updatePayment, l as registerPaid, n as cancelPayment, r as evaluatePayment, t as approvePayment, u as returnPayment } from "../../../../../chunks/api3.js";
import { t as TextField } from "../../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { t as TextArea } from "../../../../../chunks/TextArea.js";
import { t as PaymentStatusBadge } from "../../../../../chunks/PaymentStatusBadge.js";
//#region src/lib/features/payments/EligibilityResults.svelte
function EligibilityResults($$renderer, $$props) {
	/** Resultado de las reglas tal como lo calculó el backend (la interfaz no las repite). */
	let { results } = $$props;
	$$renderer.push(`<ul class="space-y-2 text-sm"><!--[-->`);
	const each_array = ensure_array_like(results);
	for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
		let rule = each_array[$$index];
		$$renderer.push(`<li${attr_class(`flex gap-3 ${rule.enabled ? "" : "opacity-60"}`)}><span${attr_class(`mt-0.5 inline-flex size-5 shrink-0 items-center justify-center rounded-full text-xs font-bold ${!rule.enabled ? "bg-canvas text-muted" : rule.passed ? "bg-success-soft text-success" : "bg-danger-soft text-danger"}`)} aria-hidden="true">${escape_html(!rule.enabled ? "–" : rule.passed ? "✓" : "✕")}</span> <span><span class="font-medium text-ink">${escape_html(rule.label)}</span> <span class="sr-only">: ${escape_html(!rule.enabled ? "desactivada" : rule.passed ? "cumple" : "no cumple")}.</span> <span class="block text-xs text-muted">${escape_html(rule.reason)}</span></span></li>`);
	}
	$$renderer.push(`<!--]--></ul>`);
}
//#endregion
//#region src/routes/(app)/payments/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const payment = derived(() => data.payment);
		const refresh = () => invalidate("app:payment");
		let working = false;
		let actionError = null;
		/** Acciones directas (sin formulario): el mensaje del backend explica si no procede. */
		async function run(action, success) {
			working = true;
			actionError = null;
			try {
				await action();
				toasts.show(success);
			} catch (e) {
				actionError = e;
			} finally {
				working = false;
				await refresh();
			}
		}
		let dialog = null;
		let dialogOpen = false;
		let amount = "";
		let reason = "";
		let paymentDate = "";
		let reference = "";
		let dialogError = null;
		function open(next) {
			dialog = next;
			amount = payment().amount;
			reason = "";
			paymentDate = "";
			reference = "";
			dialogError = null;
			dialogOpen = true;
		}
		const TITLES = {
			edit: "Modificar valor",
			return: "Devolver para ajustes",
			paid: "Registrar pago efectuado",
			cancel: "Anular pago",
			approve: "Aprobar pago"
		};
		async function confirm() {
			working = true;
			dialogError = null;
			try {
				if (dialog === "edit") await updatePayment(payment().uuid, amount.trim(), payment().notes);
				else if (dialog === "return") await returnPayment(payment().uuid, reason);
				else if (dialog === "paid") await registerPaid(payment().uuid, paymentDate, reference.trim());
				else if (dialog === "cancel") await cancelPayment(payment().uuid, reason);
				else if (dialog === "approve") await approvePayment(payment().uuid);
				dialogOpen = false;
				toasts.show(dialog === "approve" ? "Pago aprobado." : dialog === "paid" ? "Pago registrado como pagado." : "Cambios guardados.");
			} catch (e) {
				dialogError = e;
			} finally {
				working = false;
				await refresh();
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					Button($$renderer, {
						href: resolve("/(app)/contracts/[uuid]", { uuid: payment().contract.uuid }),
						variant: "secondary",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Ver contrato`);
						},
						$$slots: { default: true }
					});
				}
				PageHeader($$renderer, {
					title: `Pago N.° ${stringify(payment().number)} · ${stringify(payment().contract.contract_number)}`,
					description: `${stringify(payment().contract.contractor)} · Informe N.° ${stringify(payment().report.number)} (${stringify(formatDate(payment().period_start))} – ${stringify(formatDate(payment().period_end))})`,
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-3"><div class="space-y-6 lg:col-span-2">`);
			Card($$renderer, {
				title: "Datos del pago",
				children: ($$renderer) => {
					$$renderer.push(`<dl class="grid gap-4 text-sm sm:grid-cols-2"><div><dt class="text-muted">Valor</dt> <dd class="text-xl font-semibold">${escape_html(formatMoney(payment().amount))}</dd></div> <div><dt class="text-muted">Informe que lo sustenta</dt> <dd><a class="text-primary hover:underline"${attr("href", resolve("/(app)/reports/[uuid]", { uuid: payment().report.uuid }))}>Informe N.° ${escape_html(payment().report.number)}</a></dd></div> <div><dt class="text-muted">Registrado por</dt> <dd>${escape_html(payment().created_by)} · ${escape_html(formatDateTime(payment().created_at))}</dd></div> `);
					if (payment().approved_by) $$renderer.push(`<!--[0--><div><dt class="text-muted">Aprobado por</dt> <dd>${escape_html(payment().approved_by)} · ${escape_html(formatDateTime(payment().approved_at))}</dd></div>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().payment_date) $$renderer.push(`<!--[0--><div><dt class="text-muted">Pagado</dt> <dd>${escape_html(formatDate(payment().payment_date))} · comprobante ${escape_html(payment().payment_reference)} <span class="block text-xs text-muted">Registró: ${escape_html(payment().paid_registered_by)}</span></dd></div>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().cancelled_reason) {
						$$renderer.push(`<!--[0--><div class="sm:col-span-2">`);
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->Anulado el ${escape_html(formatDateTime(payment().cancelled_at))}: ${escape_html(payment().cancelled_reason)}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></div>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().notes) $$renderer.push(`<!--[0--><div class="sm:col-span-2"><dt class="text-muted">Notas</dt> <dd class="whitespace-pre-line">${escape_html(payment().notes)}</dd></div>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></dl>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Card($$renderer, {
				title: "Elegibilidad",
				description: "La calcula el sistema con las reglas vigentes (ADR-007). Se evalúa de nuevo al enviar y al aprobar.",
				children: ($$renderer) => {
					if (payment().evaluation) {
						$$renderer.push(`<!--[0--><div class="space-y-4">`);
						Alert($$renderer, {
							variant: payment().evaluation.eligible ? "success" : "warning",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(payment().evaluation.eligible ? "Elegible" : "No elegible")} · evaluado por ${escape_html(payment().evaluation.evaluated_by)} el ${escape_html(formatDateTime(payment().evaluation.evaluated_at))}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----> `);
						EligibilityResults($$renderer, { results: payment().evaluation.results });
						$$renderer.push(`<!----></div>`);
					} else $$renderer.push(`<!--[-1--><p class="text-sm text-muted">Aún no se ha evaluado la elegibilidad de este pago.</p>`);
					$$renderer.push(`<!--]-->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Card($$renderer, {
				title: "Historial",
				description: "Del más reciente al más antiguo.",
				children: ($$renderer) => {
					$$renderer.push(`<ol class="relative space-y-5 border-l border-border pl-5"><!--[-->`);
					const each_array = ensure_array_like(data.history);
					for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
						let entry = each_array[$$index];
						$$renderer.push(`<li class="relative"><span class="absolute top-1.5 -left-[25px] size-2.5 rounded-full bg-primary" aria-hidden="true"></span> <p class="text-sm font-medium text-ink">${escape_html(entry.summary)}</p> <p class="text-xs text-muted">${escape_html(formatDateTime(entry.occurred_at))} · ${escape_html(entry.user ?? "Sistema")}</p> `);
						if (entry.comment) $$renderer.push(`<!--[0--><p class="mt-1 rounded-md bg-canvas px-3 py-2 text-sm whitespace-pre-line">${escape_html(entry.comment)}</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></li>`);
					}
					$$renderer.push(`<!--]--></ol>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> <div class="order-first space-y-6 lg:order-none">`);
			Card($$renderer, {
				title: "Estado",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-4 text-sm">`);
					PaymentStatusBadge($$renderer, {
						status: payment().status,
						label: payment().status_label
					});
					$$renderer.push(`<!----> `);
					if (formMessage(actionError)) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(actionError))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> <div class="flex flex-col gap-2">`);
					if (payment().can.submit) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							loading: working && !dialogOpen,
							onclick: () => run(() => submitPayment(payment().uuid), "Pago enviado a aprobación."),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Enviar a aprobación`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.approve) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							onclick: () => open("approve"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Aprobar`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.register_paid) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							onclick: () => open("paid"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Registrar pago efectuado`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.evaluate) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "secondary",
							disabled: working,
							onclick: () => run(() => evaluatePayment(payment().uuid), "Elegibilidad evaluada."),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Evaluar elegibilidad`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.edit) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "secondary",
							onclick: () => open("edit"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Modificar valor`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.return) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "secondary",
							onclick: () => open("return"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Devolver para ajustes`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (payment().can.cancel) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "ghost",
							onclick: () => open("cancel"),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Anular pago`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div> `);
					if (payment().status === "ready_for_approval" && !payment().can.approve) $$renderer.push(`<!--[0--><p class="text-xs text-muted">Lo aprueba una persona distinta de quien lo registró y envió (separación de funciones).</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Card($$renderer, {
				title: "Saldo del contrato",
				children: ($$renderer) => {
					$$renderer.push(`<dl class="space-y-2 text-sm"><div class="flex justify-between gap-2"><dt class="text-muted">Valor</dt> <dd>${escape_html(formatMoney(payment().budget.total))}</dd></div> <div class="flex justify-between gap-2"><dt class="text-muted">Comprometido</dt> <dd>${escape_html(formatMoney(payment().budget.committed))}</dd></div> <div class="flex justify-between gap-2"><dt class="text-muted">Pagado</dt> <dd>${escape_html(formatMoney(payment().budget.paid))}</dd></div> <div class="flex justify-between gap-2 border-t border-border pt-2 font-semibold"><dt>Disponible</dt> <dd>${escape_html(formatMoney(payment().budget.available))}</dd></div></dl>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></div> `);
			ConfirmDialog($$renderer, {
				title: dialog ? TITLES[dialog] : "",
				confirmLabel: dialog === "cancel" ? "Anular" : dialog === "approve" ? "Aprobar" : "Guardar",
				variant: dialog === "cancel" ? "danger" : "primary",
				loading: working,
				onconfirm: confirm,
				get open() {
					return dialogOpen;
				},
				set open($$value) {
					dialogOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3">`);
					if (formMessage(dialogError) && fieldErrors(dialogError, "amount").length + fieldErrors(dialogError, "reason").length + fieldErrors(dialogError, "payment_date").length + fieldErrors(dialogError, "payment_reference").length === 0) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(dialogError))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (dialog === "edit") {
						$$renderer.push("<!--[0-->");
						TextField($$renderer, {
							label: "Valor (pesos)",
							inputmode: "decimal",
							errors: fieldErrors(dialogError, "amount"),
							get value() {
								return amount;
							},
							set value($$value) {
								amount = $$value;
								$$settled = false;
							}
						});
					} else if (dialog === "approve") $$renderer.push(`<!--[1--><p>Se aprobará el pago por <strong>${escape_html(formatMoney(payment().amount))}</strong>. El sistema vuelve a
        evaluar la elegibilidad antes de aprobar.</p>`);
					else if (dialog === "paid") {
						$$renderer.push(`<!--[2--><p>Registre los datos del pago efectuado por tesorería. SIGCON no ejecuta pagos.</p> `);
						TextField($$renderer, {
							label: "Fecha de pago",
							type: "date",
							required: true,
							errors: fieldErrors(dialogError, "payment_date"),
							get value() {
								return paymentDate;
							},
							set value($$value) {
								paymentDate = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> `);
						TextField($$renderer, {
							label: "Comprobante de egreso",
							required: true,
							maxlength: 100,
							placeholder: "Ej. CE-2026-0145",
							errors: fieldErrors(dialogError, "payment_reference"),
							get value() {
								return reference;
							},
							set value($$value) {
								reference = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!---->`);
					} else {
						$$renderer.push(`<!--[-1--><p>${escape_html(dialog === "cancel" ? "El pago quedará anulado y liberará el saldo del contrato. No se elimina." : "El pago vuelve a \"En preparación\" para ajustes.")}</p> `);
						TextArea($$renderer, {
							label: "Motivo",
							required: true,
							rows: 3,
							maxlength: 1e3,
							errors: fieldErrors(dialogError, "reason"),
							get value() {
								return reason;
							},
							set value($$value) {
								reason = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!---->`);
					}
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!---->`);
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
