import { T as escape_html, d as stringify, o as derived, s as ensure_array_like } from "./server.js";
import { t as toasts } from "./toasts.svelte.js";
import { t as Button } from "./Button.js";
import { t as Alert } from "./Alert.js";
import { n as formMessage, t as fieldErrors } from "./forms.js";
import { t as ConfirmDialog } from "./ConfirmDialog.js";
import { t as TextArea } from "./TextArea.js";
import { c as reopenReport, d as startReview, o as observeReport, s as rejectReport, t as approveReport } from "./api7.js";
//#region src/lib/features/reports/ReviewActions.svelte
function ReviewActions($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Decisiones sobre el informe. Solo se muestran las que el backend declara posibles
		* (`report.can`); el backend vuelve a validar todo con el informe bloqueado.
		*/
		let { report, onchange } = $$props;
		let open = false;
		let action = "approve";
		let comment = "";
		let observations = {};
		let general = "";
		let working = false;
		let error = null;
		const TITLES = {
			observe: "Solicitar correcciones",
			approve: "Aprobar informe",
			reject: "Rechazar informe",
			reopen: "Reabrir informe aprobado"
		};
		function ask(next) {
			action = next;
			comment = "";
			general = "";
			observations = Object.fromEntries(report.content.items.map((i) => [i.obligation_uuid, ""]));
			error = null;
			open = true;
		}
		async function begin() {
			working = true;
			try {
				await startReview(report.uuid);
				await onchange();
				toasts.show("Revisión iniciada.");
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible iniciar la revisión.", "error");
			} finally {
				working = false;
			}
		}
		async function confirm() {
			working = true;
			error = null;
			try {
				if (action === "observe") {
					const list = [...general.trim() ? [{
						obligation: null,
						text: general.trim()
					}] : [], ...Object.entries(observations).filter(([, text]) => text.trim() !== "").map(([obligation, text]) => ({
						obligation,
						text: text.trim()
					}))];
					await observeReport(report.uuid, comment.trim() || null, list);
					toasts.show("Observaciones enviadas al contratista.");
				} else if (action === "approve") {
					await approveReport(report.uuid, comment.trim() || null);
					toasts.show("Informe aprobado.");
				} else if (action === "reject") {
					await rejectReport(report.uuid, comment.trim());
					toasts.show("Informe rechazado.");
				} else {
					await reopenReport(report.uuid, comment.trim());
					toasts.show("Informe reabierto.");
				}
				open = false;
				await onchange();
			} catch (e) {
				error = e;
			} finally {
				working = false;
			}
		}
		const any = derived(() => report.can.start_review || report.can.observe || report.can.approve || report.can.reject || report.can.reopen);
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			if (any()) {
				$$renderer.push(`<!--[0--><div class="flex flex-col gap-2">`);
				if (report.can.start_review) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						loading: working && !open,
						onclick: begin,
						children: ($$renderer) => {
							$$renderer.push(`<!---->Iniciar revisión`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> <p class="text-xs text-muted">Al iniciar la revisión el informe queda "En revisión" y podrá aprobarlo, observarlo o
        rechazarlo.</p>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (report.can.approve) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						onclick: () => ask("approve"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Aprobar`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (report.can.observe) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						variant: "secondary",
						onclick: () => ask("observe"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Solicitar correcciones`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (report.can.reject) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						variant: "ghost",
						onclick: () => ask("reject"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Rechazar`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (report.can.reopen) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						variant: "secondary",
						onclick: () => ask("reopen"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Reabrir informe`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			ConfirmDialog($$renderer, {
				title: TITLES[action],
				confirmLabel: action === "observe" ? "Enviar observaciones" : TITLES[action].split(" ")[0],
				variant: action === "reject" ? "danger" : "primary",
				loading: working,
				onconfirm: confirm,
				get open() {
					return open;
				},
				set open($$value) {
					open = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="max-h-[60vh] space-y-3 overflow-y-auto">`);
					if (formMessage(error) && fieldErrors(error, "comment").length === 0 && fieldErrors(error, "reason").length === 0) {
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
					if (action === "approve") {
						$$renderer.push(`<!--[0--><p>Se aprobará la versión ${escape_html(report.current_version)}. La aprobación queda registrada con su
        nombre y fecha.</p> `);
						TextArea($$renderer, {
							label: "Comentario (opcional)",
							rows: 2,
							maxlength: 5e3,
							get value() {
								return comment;
							},
							set value($$value) {
								comment = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!---->`);
					} else if (action === "reject") {
						$$renderer.push(`<!--[1--><p>El informe quedará rechazado y no podrá corregirse; el contratista deberá presentar uno
        nuevo para el mismo período.</p> `);
						TextArea($$renderer, {
							label: "Motivo del rechazo",
							required: true,
							rows: 3,
							maxlength: 5e3,
							errors: fieldErrors(error, "comment"),
							get value() {
								return comment;
							},
							set value($$value) {
								comment = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!---->`);
					} else if (action === "reopen") {
						$$renderer.push(`<!--[2--><p>El informe dejará de estar aprobado y volverá al contratista con observaciones. La
        aprobación anterior se conserva en el historial.</p> `);
						TextArea($$renderer, {
							label: "Motivo de la reapertura",
							required: true,
							rows: 3,
							maxlength: 2e3,
							errors: fieldErrors(error, "reason"),
							get value() {
								return comment;
							},
							set value($$value) {
								comment = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!---->`);
					} else {
						$$renderer.push(`<!--[-1--><p>Indique qué debe corregir el contratista. Puede observar obligaciones específicas.</p> `);
						if (fieldErrors(error, "observations").length > 0) $$renderer.push(`<!--[0--><p class="text-sm text-danger">${escape_html(fieldErrors(error, "observations").join(" "))}</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						TextArea($$renderer, {
							label: "Observación general",
							rows: 2,
							maxlength: 5e3,
							get value() {
								return general;
							},
							set value($$value) {
								general = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> <!--[-->`);
						const each_array = ensure_array_like(report.content.items);
						for (let index = 0, $$length = each_array.length; index < $$length; index++) {
							let item = each_array[index];
							TextArea($$renderer, {
								label: `${stringify(index + 1)}. ${stringify(item.title)}`,
								rows: 2,
								maxlength: 5e3,
								get value() {
									return observations[item.obligation_uuid];
								},
								set value($$value) {
									observations[item.obligation_uuid] = $$value;
									$$settled = false;
								}
							});
						}
						$$renderer.push(`<!--]-->`);
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
export { ReviewActions as t };
