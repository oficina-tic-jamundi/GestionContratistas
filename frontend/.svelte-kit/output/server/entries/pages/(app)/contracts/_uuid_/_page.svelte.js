import { C as attr, T as escape_html, a as bind_props, d as stringify, l as props_id, o as derived, s as ensure_array_like, t as attr_class, w as clsx } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { n as goto, r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as Badge } from "../../../../../chunks/Badge.js";
import { n as ApiError } from "../../../../../chunks/api.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { a as formatMoney, i as formatDateTime, r as formatDate } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { t as PriorityBadge } from "../../../../../chunks/PriorityBadge.js";
import "../../../../../chunks/api3.js";
import "../../../../../chunks/DownloadLink.js";
import { t as TextField } from "../../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
import { t as ContractStatusBadge } from "../../../../../chunks/ContractStatusBadge.js";
import { c as setPriority, n as createObligation, o as getProgressHistory, r as deleteActivity, s as recordProgress, t as createChild, u as updateActivity } from "../../../../../chunks/api4.js";
import { c as transitionContract, r as deleteContract, t as changeSupervisor } from "../../../../../chunks/api5.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { r as Modal } from "../../../../../chunks/DocumentViewer.js";
import { t as TextArea } from "../../../../../chunks/TextArea.js";
import "../../../../../chunks/api7.js";
import { t as suggestPeriod } from "../../../../../chunks/period.js";
import { t as ReportStatusBadge } from "../../../../../chunks/ReportStatusBadge.js";
import { t as SelectField } from "../../../../../chunks/SelectField.js";
import { t as ObligationImport } from "../../../../../chunks/ObligationImport.js";
import { n as ProgressBar, t as DocumentsPanel } from "../../../../../chunks/DocumentsPanel.js";
import { n as evidenceImageUrl, t as captureEvidence } from "../../../../../chunks/api11.js";
import { t as PaymentStatusBadge } from "../../../../../chunks/PaymentStatusBadge.js";
//#region src/lib/components/ui/ExternalLink.svelte
function ExternalLink($$renderer, $$props) {
	/**
	* Enlace a un sitio externo (ej. SECOP II). Abre en otra pestaña sin dar acceso a
	* window.opener. Las rutas internas usan resolve() de $app/paths, no este componente.
	*/
	let { href, children } = $$props;
	$$renderer.push(`<a${attr("href", href)} target="_blank" rel="noopener noreferrer external"${attr_class(clsx("break-all text-primary hover:underline"))}>`);
	children($$renderer);
	$$renderer.push(`<!----></a>`);
}
//#endregion
//#region src/lib/types/activities.ts
var PRIORITY_OPTIONS = [
	{
		value: "high",
		label: "Alta"
	},
	{
		value: "medium",
		label: "Media"
	},
	{
		value: "low",
		label: "Baja"
	}
];
//#endregion
//#region src/lib/features/activities/ActivityNode.svelte
function ActivityNode_1($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const priorityId = props_id($$renderer);
		let { item, can, actions, numbering } = $$props;
		const isLeaf = derived(() => item.children.length === 0);
		const canEdit = derived(() => item.level === "obligation" ? can.manage_obligations : can.plan);
		const canAdd = derived(() => item.level !== "subtask" && can.plan && !(isLeaf() && item.has_progress_updates));
		const canDelete = derived(() => canEdit() && isLeaf() && !item.has_progress_updates);
		const meta = derived(() => [
			item.level_label,
			item.weight !== "1.00" ? `peso ${item.weight}` : null,
			item.due_date ? `fecha objetivo ${formatDate(item.due_date)}` : null,
			item.progress_is_computed ? "avance calculado" : null
		].filter(Boolean).join(" · "));
		const childLabel = derived(() => item.level === "obligation" ? "tarea" : "subtarea");
		const btn = "rounded px-2 py-1 text-xs text-primary hover:bg-primary-soft";
		function changePriority(event) {
			const value = event.currentTarget.value;
			actions.priority(item, value === "" ? null : value);
		}
		$$renderer.push(`<li class="py-3"><div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between"><div class="min-w-0 flex-1"><p class="text-sm text-ink"><span class="font-mono text-xs text-muted">${escape_html(numbering)}</span> <span${attr_class(clsx(item.level === "obligation" ? "font-semibold" : "font-medium"))}>${escape_html(item.title)}</span> `);
		if (item.priority && item.priority_label) {
			$$renderer.push("<!--[0-->");
			PriorityBadge($$renderer, {
				priority: item.priority,
				label: item.priority_label
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></p> `);
		if (item.description) $$renderer.push(`<!--[0--><p class="mt-0.5 text-xs whitespace-pre-line text-muted">${escape_html(item.description)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <p class="mt-0.5 text-xs text-muted">${escape_html(meta())}</p></div> <div class="w-full sm:w-56">`);
		ProgressBar($$renderer, {
			value: item.progress,
			label: `Avance de ${stringify(item.title)}`,
			size: "sm"
		});
		$$renderer.push(`<!----></div></div> <div class="mt-1 flex flex-wrap gap-1">`);
		if (can.record_progress && isLeaf()) $$renderer.push(`<!--[0--><button type="button" class="rounded px-2 py-1 text-xs text-primary hover:bg-primary-soft font-medium">Registrar avance</button>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (item.has_progress_updates) $$renderer.push(`<!--[0--><button type="button"${attr_class(clsx(btn))}>Ver avances</button>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (canAdd()) $$renderer.push(`<!--[0--><button type="button"${attr_class(clsx(btn))}>+ Agregar ${escape_html(childLabel())}</button>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (canEdit()) $$renderer.push(`<!--[0--><button type="button"${attr_class(clsx(btn))}>Editar</button>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (can.set_priority) {
			$$renderer.push(`<!--[0--><label${attr("for", priorityId)} class="sr-only">Prioridad de ${escape_html(item.title)}</label> `);
			$$renderer.select({
				id: priorityId,
				class: "rounded-lg border border-border bg-field px-2 py-1 text-xs text-ink",
				value: item.priority ?? "",
				onchange: changePriority
			}, ($$renderer) => {
				$$renderer.option({ value: "" }, ($$renderer) => {
					$$renderer.push(`Sin prioridad`);
				});
				$$renderer.push(`<!--[-->`);
				const each_array = ensure_array_like(PRIORITY_OPTIONS);
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let option = each_array[$$index];
					$$renderer.option({ value: option.value }, ($$renderer) => {
						$$renderer.push(`Prioridad ${escape_html(option.label.toLowerCase())}`);
					});
				}
				$$renderer.push(`<!--]-->`);
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (canDelete()) $$renderer.push(`<!--[0--><button type="button" class="rounded px-2 py-1 text-xs text-danger hover:bg-danger-soft">Eliminar</button>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div> `);
		if (item.children.length > 0) {
			$$renderer.push(`<!--[0--><ul class="mt-2 divide-y divide-border border-l-2 border-border pl-4"><!--[-->`);
			const each_array_1 = ensure_array_like(item.children);
			for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
				let child = each_array_1[i];
				ActivityNode_1($$renderer, {
					item: child,
					can,
					actions,
					numbering: `${stringify(numbering)}${stringify(i + 1)}.`
				});
			}
			$$renderer.push(`<!--]--></ul>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></li>`);
	});
}
//#endregion
//#region src/lib/features/activities/ActivitiesPanel.svelte
function ActivitiesPanel($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Obligaciones, tareas y subtareas de un contrato, con el avance calculado (ADR-013).
		* Tras cada cambio se invoca `onchange` para recargar los datos desde el servidor.
		*/
		let { contractUuid, tree, onchange } = $$props;
		let importOpen = false;
		let working = false;
		let error = null;
		let formOpen = false;
		let mode = { kind: "obligation" };
		let title = "";
		let description = "";
		let weight = "1";
		let dueDate = "";
		const formTitle = derived(() => mode.kind === "obligation" ? "Nueva obligación" : mode.kind === "child" ? `Nueva ${mode.parent.level === "obligation" ? "tarea" : "subtarea"}` : `Editar ${mode.item.level_label.toLowerCase()}`);
		function openForm(next) {
			mode = next;
			const item = next.kind === "edit" ? next.item : null;
			title = item?.title ?? "";
			description = item?.description ?? "";
			weight = item ? item.weight.replace(/\.00$/, "") : "1";
			dueDate = item?.due_date ?? "";
			error = null;
			formOpen = true;
		}
		async function saveForm() {
			working = true;
			error = null;
			const input = {
				title,
				description: description.trim() || null,
				weight,
				due_date: dueDate || null
			};
			try {
				if (mode.kind === "obligation") await createObligation(contractUuid, input);
				else if (mode.kind === "child") await createChild(mode.parent.uuid, input);
				else await updateActivity(mode.item.uuid, input);
				formOpen = false;
				await onchange();
				toasts.show("Cambios guardados.");
			} catch (e) {
				error = e;
			} finally {
				working = false;
			}
		}
		let progressOpen = false;
		let target = null;
		let progressValue = "0";
		let note = "";
		function openProgress(item) {
			target = item;
			progressValue = String(Math.round(Number(item.progress)));
			note = "";
			error = null;
			progressOpen = true;
		}
		async function saveProgress() {
			if (!target) return;
			working = true;
			error = null;
			try {
				await recordProgress(target.uuid, Number(progressValue), note);
				progressOpen = false;
				await onchange();
				toasts.show("Avance registrado.");
			} catch (e) {
				error = e;
			} finally {
				working = false;
			}
		}
		let deleteOpen = false;
		async function confirmDelete() {
			if (!target) return;
			working = true;
			try {
				await deleteActivity(target.uuid);
				deleteOpen = false;
				await onchange();
				toasts.show("Elemento eliminado.");
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible eliminar.", "error");
				deleteOpen = false;
			} finally {
				working = false;
			}
		}
		let historyOpen = false;
		let history = [];
		let historyError = null;
		async function openHistory(item) {
			target = item;
			history = [];
			historyError = null;
			historyOpen = true;
			try {
				history = (await getProgressHistory(item.uuid)).history;
			} catch (e) {
				historyError = e;
			}
		}
		const actions = {
			add: (parent) => openForm({
				kind: "child",
				parent
			}),
			edit: (item) => openForm({
				kind: "edit",
				item
			}),
			remove: (item) => {
				target = item;
				deleteOpen = true;
			},
			progress: openProgress,
			history: openHistory,
			priority: async (item, priority) => {
				try {
					await setPriority(item.uuid, priority);
					toasts.show("Prioridad guardada.");
					await onchange();
				} catch (e) {
					toasts.show(formMessage(e) ?? "No fue posible guardar la prioridad.", "error");
				}
			}
		};
		const pct = (v) => `${Number(v).toLocaleString("es-CO", { maximumFractionDigits: 2 })} %`;
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			Card($$renderer, {
				title: "Obligaciones y avance",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-4"><div><p class="mb-1 text-sm font-medium text-ink">Avance general del contrato</p> `);
					ProgressBar($$renderer, {
						value: tree.progress,
						label: "Avance general del contrato"
					});
					$$renderer.push(`<!----> <p class="mt-1 text-xs text-muted">Promedio ponderado de las obligaciones. El avance de cada tarea lo registra el supervisor al
        verificar lo ejecutado.</p></div> `);
					if (tree.items.length === 0) $$renderer.push(`<!--[0--><p class="rounded-md bg-canvas px-3 py-4 text-sm text-muted">${escape_html(tree.can.manage_obligations ? "Registre las obligaciones del contrato antes de activarlo." : "Este contrato aún no tiene obligaciones registradas.")}</p>`);
					else {
						$$renderer.push(`<!--[-1--><ul class="divide-y divide-border"><!--[-->`);
						const each_array = ensure_array_like(tree.items);
						for (let i = 0, $$length = each_array.length; i < $$length; i++) {
							let item = each_array[i];
							ActivityNode_1($$renderer, {
								item,
								can: tree.can,
								actions,
								numbering: `${stringify(i + 1)}.`
							});
						}
						$$renderer.push(`<!--]--></ul>`);
					}
					$$renderer.push(`<!--]--> `);
					if (tree.can.manage_obligations) {
						$$renderer.push(`<!--[0--><div class="flex flex-wrap gap-2">`);
						Button($$renderer, {
							size: "sm",
							onclick: () => importOpen = true,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Leer del contrato firmado`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							variant: "secondary",
							size: "sm",
							onclick: () => openForm({ kind: "obligation" }),
							children: ($$renderer) => {
								$$renderer.push(`<!---->+ Agregar obligación`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></div>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: formTitle(),
				confirmLabel: "Guardar",
				loading: working,
				onconfirm: saveForm,
				get open() {
					return formOpen;
				},
				set open($$value) {
					formOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3">`);
					if (formMessage(error) && fieldErrors(error, "title").length === 0) {
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
					TextArea($$renderer, {
						label: "Título",
						required: true,
						rows: 2,
						maxlength: 255,
						errors: fieldErrors(error, "title"),
						get value() {
							return title;
						},
						set value($$value) {
							title = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					TextArea($$renderer, {
						label: "Descripción",
						rows: 3,
						maxlength: 5e3,
						errors: fieldErrors(error, "description"),
						get value() {
							return description;
						},
						set value($$value) {
							description = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> <div class="grid grid-cols-2 gap-3">`);
					TextField($$renderer, {
						label: "Peso",
						inputmode: "decimal",
						hint: "Importancia frente a los demás del mismo nivel.",
						errors: fieldErrors(error, "weight"),
						get value() {
							return weight;
						},
						set value($$value) {
							weight = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					TextField($$renderer, {
						label: "Fecha objetivo",
						type: "date",
						errors: fieldErrors(error, "due_date"),
						get value() {
							return dueDate;
						},
						set value($$value) {
							dueDate = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Registrar avance",
				confirmLabel: "Registrar",
				loading: working,
				onconfirm: saveProgress,
				get open() {
					return progressOpen;
				},
				set open($$value) {
					progressOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3"><p class="font-medium text-ink">${escape_html(target?.title)}</p> `);
					if (formMessage(error) && fieldErrors(error, "progress").length === 0 && fieldErrors(error, "note").length === 0) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> <div><label for="progress-range" class="block text-sm font-medium text-ink">Avance acumulado: <span class="tabular-nums">${escape_html(progressValue)} %</span></label> <input id="progress-range" type="range" min="0" max="100" step="5"${attr("value", progressValue)} class="mt-2 w-full accent-primary"/> `);
					if (fieldErrors(error, "progress").length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(fieldErrors(error, "progress").join(" "))}</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div> `);
					TextArea($$renderer, {
						label: "¿Qué se realizó?",
						required: true,
						rows: 3,
						maxlength: 2e3,
						hint: "Mínimo 10 caracteres. Quedará en el historial de avances.",
						errors: fieldErrors(error, "note"),
						get value() {
							return note;
						},
						set value($$value) {
							note = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Eliminar elemento",
				confirmLabel: "Eliminar",
				variant: "danger",
				loading: working,
				onconfirm: confirmDelete,
				get open() {
					return deleteOpen;
				},
				set open($$value) {
					deleteOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<!---->Se eliminará "${escape_html(target?.title)}". Solo es posible porque aún no tiene avances registrados.`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Historial de avances",
				confirmLabel: "Cerrar",
				onconfirm: () => historyOpen = false,
				get open() {
					return historyOpen;
				},
				set open($$value) {
					historyOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3"><p class="font-medium text-ink">${escape_html(target?.title)}</p> `);
					if (historyError) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(historyError))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> <ol class="max-h-80 space-y-3 overflow-y-auto">`);
					const each_array_1 = ensure_array_like(history);
					if (each_array_1.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
							let entry = each_array_1[$$index_1];
							$$renderer.push(`<li class="rounded-md bg-canvas px-3 py-2"><p class="text-sm font-medium text-ink">${escape_html(pct(entry.previous_progress))} → ${escape_html(pct(entry.new_progress))}</p> <p class="text-xs text-muted">${escape_html(formatDateTime(entry.recorded_at))} · ${escape_html(entry.user)}</p> <p class="mt-1 text-sm whitespace-pre-line">${escape_html(entry.note)}</p></li>`);
						}
					} else {
						$$renderer.push("<!--[!-->");
						if (!historyError) $$renderer.push(`<!--[0--><li class="text-sm text-muted">Cargando…</li>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]-->`);
					}
					$$renderer.push(`<!--]--></ol></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Modal($$renderer, {
				title: "Obligaciones del contrato firmado",
				size: "lg",
				get open() {
					return importOpen;
				},
				set open($$value) {
					importOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					if (importOpen) {
						$$renderer.push("<!--[0-->");
						ObligationImport($$renderer, {
							contractUuid,
							onimported: async () => {
								importOpen = false;
								await onchange();
							},
							oncancel: () => importOpen = false
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
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
//#region src/lib/features/reports/ReportsPanel.svelte
function ReportsPanel($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Informes de un contrato; el contratista puede iniciar uno nuevo (el backend decide si procede). */
		let { contractUuid, contractStart, contractEnd, reports, canCreate } = $$props;
		const suggestion = derived(() => suggestPeriod(contractStart, contractEnd, reports));
		let formOpen = false;
		let start = "";
		let end = "";
		let creating = false;
		let error = null;
		function openForm() {
			start = suggestion()?.start ?? "";
			end = suggestion()?.end ?? "";
			error = null;
			formOpen = true;
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (canCreate && !formOpen) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							size: "sm",
							onclick: openForm,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Nuevo informe`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				Card($$renderer, {
					title: "Informes",
					padded: false,
					actions,
					children: ($$renderer) => {
						if (formOpen) {
							$$renderer.push(`<!--[0--><form class="space-y-3 border-b border-border bg-canvas px-5 py-4" novalidate=""><p class="text-sm font-medium text-ink">Período del informe</p> `);
							if (formMessage(error) && fieldErrors(error, "period_start").length === 0 && fieldErrors(error, "period_end").length === 0) {
								$$renderer.push("<!--[0-->");
								Alert($$renderer, {
									variant: "danger",
									children: ($$renderer) => {
										$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> <div class="grid gap-3 sm:grid-cols-2">`);
							TextField($$renderer, {
								label: "Desde",
								type: "date",
								required: true,
								min: contractStart,
								max: contractEnd,
								errors: fieldErrors(error, "period_start"),
								get value() {
									return start;
								},
								set value($$value) {
									start = $$value;
									$$settled = false;
								}
							});
							$$renderer.push(`<!----> `);
							TextField($$renderer, {
								label: "Hasta",
								type: "date",
								required: true,
								min: start || contractStart,
								max: contractEnd,
								errors: fieldErrors(error, "period_end"),
								get value() {
									return end;
								},
								set value($$value) {
									end = $$value;
									$$settled = false;
								}
							});
							$$renderer.push(`<!----></div> <p class="text-xs text-muted">El período debe estar dentro del plazo del contrato y no cruzarse con otro informe.</p> <div class="flex gap-2">`);
							Button($$renderer, {
								type: "submit",
								size: "sm",
								loading: creating,
								disabled: !start || !end,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Crear borrador`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----> `);
							Button($$renderer, {
								size: "sm",
								variant: "ghost",
								onclick: () => formOpen = false,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Cancelar`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----></div></form>`);
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> <ul class="divide-y divide-border">`);
						const each_array = ensure_array_like(reports);
						if (each_array.length !== 0) {
							$$renderer.push("<!--[-->");
							for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
								let report = each_array[$$index];
								$$renderer.push(`<li><a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: report.uuid }))} class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 text-sm hover:bg-canvas"><span><span class="font-medium text-ink">Informe N.° ${escape_html(report.number)}</span> <span class="block text-xs text-muted">${escape_html(formatDate(report.period_start))} – ${escape_html(formatDate(report.period_end))} `);
								if (report.current_version > 0) $$renderer.push(`<!--[0-->· versión ${escape_html(report.current_version)}`);
								else $$renderer.push("<!--[-1-->");
								$$renderer.push(`<!--]--></span></span> `);
								ReportStatusBadge($$renderer, {
									status: report.status,
									label: report.status_label
								});
								$$renderer.push(`<!----></a></li>`);
							}
						} else $$renderer.push(`<!--[!--><li class="px-5 py-6 text-sm text-muted">Aún no hay informes de este contrato.</li>`);
						$$renderer.push(`<!--]--></ul>`);
					},
					$$slots: {
						actions: true,
						default: true
					}
				});
			}
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
//#region src/lib/features/evidence/location.ts
var LOCATION_MESSAGES = {
	denied: "Permiso de ubicación denegado. Habilítelo en el navegador para georreferenciar la evidencia.",
	unavailable: "El dispositivo no pudo determinar la ubicación (active el GPS o intente al aire libre).",
	timeout: "La ubicación tardó demasiado. Puede reintentar antes de registrar la evidencia."
};
/** Traduce un error de la API de geolocalización a un estado de ubicación. */
function fromPositionError(code) {
	return { status: code === 1 ? "denied" : code === 3 ? "timeout" : "unavailable" };
}
function fromPosition(position) {
	const c = position.coords;
	return {
		status: "ok",
		latitude: c.latitude,
		longitude: c.longitude,
		accuracy: c.accuracy,
		altitude: c.altitude,
		heading: c.heading !== null && Number.isFinite(c.heading) ? c.heading : null,
		speed: c.speed,
		timestamp: position.timestamp
	};
}
function requestLocation(timeoutMs = 2e4) {
	if (typeof navigator === "undefined" || !("geolocation" in navigator)) return Promise.resolve({ status: "unavailable" });
	return new Promise((resolve) => {
		navigator.geolocation.getCurrentPosition((position) => resolve(fromPosition(position)), (error) => resolve(fromPositionError(error.code)), {
			enableHighAccuracy: true,
			timeout: timeoutMs,
			maximumAge: 0
		});
	});
}
/** Campos del formulario multipart que espera la API. */
function locationFields(location) {
	if (location.status !== "ok") return { location_status: location.status };
	const fields = {
		location_status: "ok",
		latitude: location.latitude.toFixed(6),
		longitude: location.longitude.toFixed(6),
		accuracy: location.accuracy.toFixed(2),
		location_captured_at: new Date(location.timestamp).toISOString()
	};
	if (location.altitude !== null) fields.altitude = location.altitude.toFixed(2);
	if (location.heading !== null) fields.heading = location.heading.toFixed(2);
	if (location.speed !== null) fields.speed = location.speed.toFixed(2);
	return fields;
}
/** Texto de la ubicación para la vista previa y la ficha de la evidencia. */
function describeCoordinates(latitude, longitude, accuracy) {
	return `Lat ${latitude.toFixed(6)}  Lon ${longitude.toFixed(6)}  ·  precisión ±${Math.round(accuracy)} m`;
}
//#endregion
//#region src/lib/features/evidence/EvidenceCapture.svelte
function EvidenceCapture($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Registro de una evidencia fotográfica (ADR-006):
		* 1. Se pide la ubicación al abrir (nunca se inventa: si falla, se informa el motivo).
		* 2. Foto con la cámara del navegador o, como alternativa, con el selector del teléfono.
		* 3. Vista previa con la marca de agua (orientativa; la oficial la genera el servidor).
		* 4. Se envía la foto ORIGINAL, sin marca, con la ubicación y la hora del dispositivo.
		*/
		let { open = false, activities, activityUuid = "", contractNumber, contractorName, maxMb, oncaptured } = $$props;
		let location = null;
		let locating = false;
		let stream = null;
		let cameraError = null;
		let photo = null;
		let description = "";
		let saving = false;
		let error = null;
		let inputKey = 0;
		const options = derived(() => flatten(activities));
		derived(() => options().find((o) => o.uuid === activityUuid));
		const tooBig = derived(() => photo !== null && photo.blob.size > maxMb * 1024 * 1024);
		/** La foto y la ubicación no son campos visibles: se muestran los mensajes del backend. */
		const errorMessages = derived(() => error instanceof ApiError && error.fieldErrors.length > 0 ? error.fieldErrors.filter((e) => e.field !== "description").map((e) => e.message) : error instanceof ApiError ? [error.message] : error instanceof Error ? [error.message] : error ? [formMessage(error) ?? "Ocurrió un error inesperado."] : []);
		function flatten(items, prefix = "") {
			return items.flatMap((item, i) => {
				const numbering = `${prefix}${i + 1}.`;
				return [{
					uuid: item.uuid,
					label: `${numbering} ${item.title}`,
					level: item.level_label
				}, ...flatten(item.children, numbering)];
			});
		}
		async function locate() {
			locating = true;
			location = await requestLocation();
			locating = false;
		}
		async function startCamera() {
			cameraError = null;
			if (!navigator.mediaDevices?.getUserMedia) {
				cameraError = "Este navegador no permite abrir la cámara aquí. Use \"Elegir o tomar foto\".";
				return;
			}
			try {
				stream = await navigator.mediaDevices.getUserMedia({
					video: {
						facingMode: { ideal: "environment" },
						width: { ideal: 1920 },
						height: { ideal: 1080 }
					},
					audio: false
				});
			} catch {
				cameraError = "No fue posible abrir la cámara (permiso denegado o sin cámara). Use \"Elegir o tomar foto\".";
			}
		}
		function stopCamera() {
			stream?.getTracks().forEach((t) => t.stop());
			stream = null;
		}
		async function shoot() {}
		async function save() {
			if (!photo || !location || !activityUuid) return;
			saving = true;
			error = null;
			try {
				await captureEvidence(activityUuid, photo.blob, {
					...locationFields(location),
					device_captured_at: photo.takenAt.toISOString(),
					...description.trim() ? { description: description.trim() } : {}
				});
				open = false;
				await oncaptured();
				toasts.show("Evidencia registrada.");
			} catch (e) {
				error = e;
			} finally {
				saving = false;
			}
		}
		function retake() {
			photo = null;
			inputKey++;
			startCamera();
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function footer($$renderer) {
					if (photo) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "ghost",
							onclick: retake,
							disabled: saving,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Repetir foto`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					Button($$renderer, {
						variant: "secondary",
						onclick: () => open = false,
						disabled: saving,
						children: ($$renderer) => {
							$$renderer.push(`<!---->Cancelar`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> `);
					Button($$renderer, {
						loading: saving,
						disabled: !photo || !activityUuid || locating || !location || tooBig(),
						onclick: save,
						children: ($$renderer) => {
							$$renderer.push(`<!---->Registrar evidencia`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!---->`);
				}
				Modal($$renderer, {
					title: "Registrar evidencia fotográfica",
					size: "lg",
					onclose: stopCamera,
					get open() {
						return open;
					},
					set open($$value) {
						open = $$value;
						$$settled = false;
					},
					footer,
					children: ($$renderer) => {
						$$renderer.push(`<div class="space-y-4">`);
						SelectField($$renderer, {
							label: "Obligación o tarea",
							placeholder: "Seleccione…",
							options: options().map((o) => ({
								value: o.uuid,
								label: o.label
							})),
							get value() {
								return activityUuid;
							},
							set value($$value) {
								activityUuid = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> <div aria-live="polite">`);
						if (locating) {
							$$renderer.push("<!--[0-->");
							Alert($$renderer, {
								variant: "info",
								children: ($$renderer) => {
									$$renderer.push(`<!---->Obteniendo la ubicación del dispositivo…`);
								},
								$$slots: { default: true }
							});
						} else if (location?.status === "ok") {
							$$renderer.push("<!--[1-->");
							Alert($$renderer, {
								variant: location.accuracy > 100 ? "warning" : "success",
								children: ($$renderer) => {
									$$renderer.push(`<!---->Ubicación: ${escape_html(describeCoordinates(location.latitude, location.longitude, location.accuracy))} `);
									if (location.accuracy > 100) $$renderer.push(`<!--[0-->— precisión baja; si puede, espere unos segundos y reintente.`);
									else $$renderer.push("<!--[-1-->");
									$$renderer.push(`<!--]-->`);
								},
								$$slots: { default: true }
							});
						} else if (location) {
							$$renderer.push("<!--[2-->");
							Alert($$renderer, {
								variant: "warning",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(LOCATION_MESSAGES[location.status])} La evidencia quedará registrada sin coordenadas.`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (!locating && location && (location.status !== "ok" || location.accuracy > 100)) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								size: "sm",
								variant: "ghost",
								onclick: locate,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Reintentar ubicación`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div> `);
						if (!photo) {
							$$renderer.push("<!--[0-->");
							if (stream) $$renderer.push(`<!--[0--><video autoplay="" playsinline="" muted="" class="max-h-[50dvh] w-full rounded-md bg-black object-contain"></video>`);
							else if (cameraError) {
								$$renderer.push("<!--[1-->");
								Alert($$renderer, {
									variant: "info",
									children: ($$renderer) => {
										$$renderer.push(`<!---->${escape_html(cameraError)}`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> <div class="flex flex-wrap gap-2">`);
							if (stream) {
								$$renderer.push("<!--[0-->");
								Button($$renderer, {
									onclick: shoot,
									children: ($$renderer) => {
										$$renderer.push(`<!---->Tomar foto`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> <label class="inline-flex cursor-pointer items-center rounded-md border border-border bg-surface px-4 py-2 text-sm font-medium text-ink hover:bg-canvas">Elegir o tomar foto <!---->`);
							$$renderer.push(`<input type="file" accept="image/jpeg,image/png,image/webp" capture="environment" class="sr-only"/>`);
							$$renderer.push(`<!----></label></div>`);
						} else {
							$$renderer.push(`<!--[-1--><figure class="space-y-2"><div role="img" aria-label="Vista previa de la evidencia con marca de agua"><canvas aria-hidden="true" class="max-h-[50dvh] w-full rounded-md object-contain"></canvas></div> <figcaption class="text-xs text-muted">Vista previa. La marca de agua oficial la genera el servidor con la hora oficial de
          Colombia; la foto original se conserva sin modificar.</figcaption></figure> `);
							if (tooBig()) {
								$$renderer.push("<!--[0-->");
								Alert($$renderer, {
									variant: "danger",
									children: ($$renderer) => {
										$$renderer.push(`<!---->La foto supera el máximo permitido (${escape_html(maxMb)} MB).`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> `);
							TextArea($$renderer, {
								label: "Descripción (opcional)",
								rows: 2,
								maxlength: 500,
								errors: fieldErrors(error, "description"),
								get value() {
									return description;
								},
								set value($$value) {
									description = $$value;
									$$settled = false;
								}
							});
							$$renderer.push(`<!---->`);
						}
						$$renderer.push(`<!--]--> `);
						if (errorMessages().length > 0) {
							$$renderer.push("<!--[0-->");
							Alert($$renderer, {
								variant: "danger",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(errorMessages().join(" "))}`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div>`);
					},
					$$slots: {
						footer: true,
						default: true
					}
				});
			}
		}
		do {
			$$settled = true;
			$$inner_renderer = $$renderer.copy();
			$$render_inner($$inner_renderer);
		} while (!$$settled);
		$$renderer.subsume($$inner_renderer);
		bind_props($$props, {
			open,
			activityUuid
		});
	});
}
//#endregion
//#region src/lib/features/evidence/EvidenceGallery.svelte
function EvidenceGallery($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Evidencias fotográficas del contrato: galería, ficha, registro y retiro (ADR-006/016). */
		let { list, activities, contractNumber, contractorName, onchange } = $$props;
		let captureOpen = false;
		let captureActivity = "";
		let detailOpen = false;
		const TONES = {
			ok: "success",
			low_accuracy: "warning",
			denied: "danger",
			unavailable: "danger",
			timeout: "danger"
		};
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (list.can_capture && activities.length > 0) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							size: "sm",
							onclick: () => captureOpen = true,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Registrar evidencia`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				Card($$renderer, {
					title: "Evidencias fotográficas",
					padded: false,
					actions,
					children: ($$renderer) => {
						if (list.items.length > 0) {
							$$renderer.push(`<!--[0--><ul class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 lg:grid-cols-4"><!--[-->`);
							const each_array = ensure_array_like(list.items);
							for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
								let evidence = each_array[$$index];
								$$renderer.push(`<li><button type="button" class="group block w-full overflow-hidden rounded-md border border-border bg-canvas text-left focus-visible:ring-2 focus-visible:ring-primary/40"><img${attr("src", evidenceImageUrl(evidence.uuid, "thumbnail"))}${attr("alt", `Evidencia de ${stringify(evidence.activity.title)}`)} loading="lazy"${attr_class(`aspect-[4/3] w-full object-cover ${evidence.status === "withdrawn" ? "opacity-40 grayscale" : ""}`)}/> <span class="block space-y-1 p-2 text-xs"><span class="line-clamp-2 font-medium text-ink">${escape_html(evidence.activity.title)}</span> <span class="block text-muted">${escape_html(formatDateTime(evidence.captured_at))}</span> `);
								if (evidence.status === "withdrawn") {
									$$renderer.push("<!--[0-->");
									Badge($$renderer, {
										children: ($$renderer) => {
											$$renderer.push(`<!---->Retirada`);
										},
										$$slots: { default: true }
									});
								} else if (evidence.location.status !== "ok") {
									$$renderer.push("<!--[1-->");
									Badge($$renderer, {
										tone: TONES[evidence.location.status],
										children: ($$renderer) => {
											$$renderer.push(`<!---->${escape_html(evidence.location.status === "low_accuracy" ? "Precisión baja" : "Sin ubicación")}`);
										},
										$$slots: { default: true }
									});
								} else $$renderer.push("<!--[-1-->");
								$$renderer.push(`<!--]--></span></button></li>`);
							}
							$$renderer.push(`<!--]--></ul>`);
						} else $$renderer.push(`<!--[-1--><p class="px-5 py-6 text-sm text-muted">${escape_html(list.can_capture ? "Aún no hay evidencias. Registre fotos de las actividades que ejecuta." : "Aún no hay evidencias registradas.")}</p>`);
						$$renderer.push(`<!--]-->`);
					},
					$$slots: {
						actions: true,
						default: true
					}
				});
			}
			$$renderer.push(`<!----> `);
			if (list.can_capture) {
				$$renderer.push("<!--[0-->");
				EvidenceCapture($$renderer, {
					activities,
					contractNumber,
					contractorName,
					maxMb: list.max_mb,
					oncaptured: onchange,
					get open() {
						return captureOpen;
					},
					set open($$value) {
						captureOpen = $$value;
						$$settled = false;
					},
					get activityUuid() {
						return captureActivity;
					},
					set activityUuid($$value) {
						captureActivity = $$value;
						$$settled = false;
					}
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			Modal($$renderer, {
				title: "Evidencia fotográfica",
				size: "lg",
				get open() {
					return detailOpen;
				},
				set open($$value) {
					detailOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
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
//#region src/lib/features/payments/PaymentsPanel.svelte
function PaymentsPanel($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Pagos del contrato con su saldo. Registrar un pago exige un informe aprobado. */
		let { budget, payments, reports, canCreate } = $$props;
		const available = derived(() => reports.filter((r) => r.status === "approved" && !payments.some((p) => p.report.uuid === r.uuid && p.status !== "cancelled")));
		let formOpen = false;
		let report = "";
		let amount = "";
		let saving = false;
		let error = null;
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (canCreate && !formOpen && available().length > 0) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							size: "sm",
							onclick: () => formOpen = true,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Registrar pago`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				}
				Card($$renderer, {
					title: "Pagos",
					padded: false,
					actions,
					children: ($$renderer) => {
						$$renderer.push(`<dl class="grid grid-cols-2 gap-3 border-b border-border px-5 py-4 text-sm sm:grid-cols-4"><div><dt class="text-xs text-muted">Valor del contrato</dt> <dd class="font-semibold">${escape_html(formatMoney(budget.total))}</dd></div> <div><dt class="text-xs text-muted">Comprometido</dt> <dd class="font-semibold">${escape_html(formatMoney(budget.committed))}</dd></div> <div><dt class="text-xs text-muted">Pagado</dt> <dd class="font-semibold text-success">${escape_html(formatMoney(budget.paid))}</dd></div> <div><dt class="text-xs text-muted">Saldo disponible</dt> <dd class="font-semibold">${escape_html(formatMoney(budget.available))}</dd></div></dl> `);
						if (formOpen) {
							$$renderer.push(`<!--[0--><form class="space-y-3 border-b border-border bg-canvas px-5 py-4" novalidate=""><p class="text-sm font-medium text-ink">Registrar pago</p> `);
							if (formMessage(error) && fieldErrors(error, "report").length === 0 && fieldErrors(error, "amount").length === 0) {
								$$renderer.push("<!--[0-->");
								Alert($$renderer, {
									variant: "danger",
									children: ($$renderer) => {
										$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> <div class="grid gap-3 sm:grid-cols-2">`);
							SelectField($$renderer, {
								label: "Informe aprobado",
								placeholder: "Seleccione…",
								options: available().map((r) => ({
									value: r.uuid,
									label: `N.° ${r.number} · ${formatDate(r.period_start)} – ${formatDate(r.period_end)}`
								})),
								errors: fieldErrors(error, "report"),
								get value() {
									return report;
								},
								set value($$value) {
									report = $$value;
									$$settled = false;
								}
							});
							$$renderer.push(`<!----> `);
							TextField($$renderer, {
								label: "Valor (pesos)",
								inputmode: "decimal",
								placeholder: "Ej. 2500000",
								hint: "Sin puntos de miles; decimales con punto.",
								errors: fieldErrors(error, "amount"),
								get value() {
									return amount;
								},
								set value($$value) {
									amount = $$value;
									$$settled = false;
								}
							});
							$$renderer.push(`<!----></div> <div class="flex gap-2">`);
							Button($$renderer, {
								type: "submit",
								size: "sm",
								loading: saving,
								disabled: !report || !amount.trim(),
								children: ($$renderer) => {
									$$renderer.push(`<!---->Registrar`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----> `);
							Button($$renderer, {
								size: "sm",
								variant: "ghost",
								onclick: () => formOpen = false,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Cancelar`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----></div></form>`);
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> <ul class="divide-y divide-border">`);
						const each_array = ensure_array_like(payments);
						if (each_array.length !== 0) {
							$$renderer.push("<!--[-->");
							for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
								let payment = each_array[$$index];
								$$renderer.push(`<li><a${attr("href", resolve("/(app)/payments/[uuid]", { uuid: payment.uuid }))} class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 text-sm hover:bg-canvas"><span><span class="font-medium text-ink">Pago N.° ${escape_html(payment.number)} · ${escape_html(formatMoney(payment.amount))}</span> <span class="block text-xs text-muted">Informe N.° ${escape_html(payment.report.number)} · ${escape_html(formatDate(payment.period_start))} – ${escape_html(formatDate(payment.period_end))}</span></span> `);
								PaymentStatusBadge($$renderer, {
									status: payment.status,
									label: payment.status_label
								});
								$$renderer.push(`<!----></a></li>`);
							}
						} else $$renderer.push(`<!--[!--><li class="px-5 py-6 text-sm text-muted">${escape_html(canCreate && available().length === 0 ? "Aún no hay pagos. Se registran a partir de informes aprobados." : "Aún no hay pagos registrados.")}</li>`);
						$$renderer.push(`<!--]--></ul>`);
					},
					$$slots: {
						actions: true,
						default: true
					}
				});
			}
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
//#region src/routes/(app)/contracts/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const contract = derived(() => data.contract);
		const actions = derived(() => contract().actions);
		let pending = null;
		let comment = "";
		let working = false;
		let actionError = null;
		let transitionOpen = false;
		const TRANSITION_VERBS = {
			active: "Activar",
			suspended: "Suspender",
			terminated: "Terminar",
			liquidated: "Liquidar",
			archived: "Archivar"
		};
		function verb(t) {
			return t.status === "active" && contract().status === "suspended" ? "Reanudar" : TRANSITION_VERBS[t.status] ?? t.label;
		}
		function askTransition(t) {
			pending = t;
			comment = "";
			actionError = null;
			transitionOpen = true;
		}
		async function confirmTransition() {
			if (!pending) return;
			working = true;
			actionError = null;
			try {
				await transitionContract(contract().uuid, pending.status, comment.trim() || null);
				toasts.show(`Contrato ${pending.label.toLowerCase()}.`);
				transitionOpen = false;
				await invalidate("app:contract");
			} catch (e) {
				actionError = e;
			} finally {
				working = false;
			}
		}
		let supervisorOpen = false;
		let newSupervisor = "";
		let supervisorComment = "";
		function askSupervisor() {
			newSupervisor = contract().supervisor?.uuid ?? "";
			supervisorComment = "";
			actionError = null;
			supervisorOpen = true;
		}
		async function confirmSupervisor() {
			working = true;
			actionError = null;
			try {
				await changeSupervisor(contract().uuid, newSupervisor, supervisorComment.trim() || null);
				toasts.show("Supervisor actualizado.");
				supervisorOpen = false;
				await invalidate("app:contract");
			} catch (e) {
				actionError = e;
			} finally {
				working = false;
			}
		}
		let deleteOpen = false;
		async function confirmDelete() {
			working = true;
			try {
				await deleteContract(contract().uuid);
				toasts.show("Borrador descartado.");
				await goto(resolve("/contracts"));
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible descartar el borrador.", "error");
				deleteOpen = false;
			} finally {
				working = false;
			}
		}
		const hasActions = derived(() => actions().edit || actions().delete || actions().change_supervisor || actions().transitions.length > 0);
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					Button($$renderer, {
						href: resolve("/contracts"),
						variant: "secondary",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Volver`);
						},
						$$slots: { default: true }
					});
				}
				PageHeader($$renderer, {
					title: `Contrato ${stringify(contract().contract_number)}`,
					description: contract().department.name,
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-3"><div class="space-y-6 lg:col-span-2">`);
			Card($$renderer, {
				title: "Datos del contrato",
				children: ($$renderer) => {
					$$renderer.push(`<dl class="grid gap-4 text-sm sm:grid-cols-2"><div class="sm:col-span-2"><dt class="text-muted">Objeto</dt> <dd class="mt-1 whitespace-pre-line">${escape_html(contract().object)}</dd></div> <div><dt class="text-muted">Contratista</dt> <dd class="mt-1">${escape_html(contract().contractor.name)} <span class="block text-xs text-muted">${escape_html(contract().contractor.document)}</span></dd></div> <div><dt class="text-muted">Supervisor</dt> <dd class="mt-1">${escape_html(contract().supervisor?.name ?? "Sin asignar")}</dd></div> <div><dt class="text-muted">Plazo</dt> <dd class="mt-1">${escape_html(formatDate(contract().start_date))} – ${escape_html(formatDate(contract().end_date))}</dd></div> <div><dt class="text-muted">Fecha de suscripción</dt> <dd class="mt-1">${escape_html(formatDate(contract().signed_at, "No registrada"))}</dd></div> <div><dt class="text-muted">Valor total</dt> <dd class="mt-1 text-base font-semibold">${escape_html(formatMoney(contract().total_value))}</dd></div> <div><dt class="text-muted">Dependencia</dt> <dd class="mt-1">${escape_html(contract().department.name)} (${escape_html(contract().department.code)})</dd></div> `);
					if (contract().secop_url) {
						$$renderer.push(`<!--[0--><div class="sm:col-span-2"><dt class="text-muted">SECOP II</dt> <dd class="mt-1">`);
						ExternalLink($$renderer, {
							href: contract().secop_url,
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(contract().secop_url)}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></dd></div>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></dl>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ActivitiesPanel($$renderer, {
				contractUuid: contract().uuid,
				tree: data.activities,
				onchange: () => invalidate("app:contract")
			});
			$$renderer.push(`<!----> `);
			EvidenceGallery($$renderer, {
				list: data.evidences,
				activities: data.activities.items,
				contractNumber: contract().contract_number,
				contractorName: contract().contractor.name,
				onchange: () => invalidate("app:contract")
			});
			$$renderer.push(`<!----> `);
			ReportsPanel($$renderer, {
				contractUuid: contract().uuid,
				contractStart: contract().start_date,
				contractEnd: contract().end_date,
				reports: data.reports,
				canCreate: session.can(Permission.ReportsCreate) && (contract().status === "active" || contract().status === "terminated")
			});
			$$renderer.push(`<!----> `);
			PaymentsPanel($$renderer, {
				budget: data.budget,
				payments: data.payments,
				reports: data.reports,
				canCreate: session.can(Permission.PaymentsManage)
			});
			$$renderer.push(`<!----> `);
			DocumentsPanel($$renderer, {
				owner: {
					kind: "contract",
					uuid: contract().uuid
				},
				list: data.documents,
				onchange: () => invalidate("app:contract")
			});
			$$renderer.push(`<!----> `);
			Card($$renderer, {
				title: "Historial",
				description: "Lo que ha pasado con este contrato, del más reciente al más antiguo.",
				children: ($$renderer) => {
					$$renderer.push(`<ol class="relative space-y-5 border-l border-border pl-5">`);
					const each_array = ensure_array_like(data.history);
					if (each_array.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
							let entry = each_array[$$index];
							$$renderer.push(`<li class="relative"><span class="absolute top-1.5 -left-[25px] size-2.5 rounded-full bg-primary" aria-hidden="true"></span> <p class="text-sm font-medium text-ink">${escape_html(entry.summary)}</p> <p class="text-xs text-muted">${escape_html(formatDateTime(entry.occurred_at))} · ${escape_html(entry.user ?? "Sistema")}</p> `);
							if (entry.comment) $$renderer.push(`<!--[0--><p class="mt-1 rounded-md bg-canvas px-3 py-2 text-sm whitespace-pre-line">${escape_html(entry.comment)}</p>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></li>`);
						}
					} else $$renderer.push(`<!--[!--><li class="text-sm text-muted">Sin eventos registrados.</li>`);
					$$renderer.push(`<!--]--></ol>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> <div class="space-y-6">`);
			Card($$renderer, {
				title: "Estado",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-4">`);
					ContractStatusBadge($$renderer, {
						status: contract().status,
						label: contract().status_label
					});
					$$renderer.push(`<!----> `);
					if (contract().status === "draft" && !contract().supervisor) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "info",
							children: ($$renderer) => {
								$$renderer.push(`<!---->Asigne un supervisor para poder activar el contrato.`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (hasActions()) {
						$$renderer.push(`<!--[0--><div class="flex flex-col gap-2"><!--[-->`);
						const each_array_1 = ensure_array_like(actions().transitions);
						for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
							let t = each_array_1[$$index_1];
							Button($$renderer, {
								variant: t.status === "suspended" || t.status === "terminated" ? "secondary" : "primary",
								onclick: () => askTransition(t),
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(verb(t))} contrato`);
								},
								$$slots: { default: true }
							});
						}
						$$renderer.push(`<!--]--> `);
						if (actions().edit) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: "secondary",
								href: resolve("/(app)/contracts/[uuid]/edit", { uuid: contract().uuid }),
								children: ($$renderer) => {
									$$renderer.push(`<!---->Editar borrador`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (actions().change_supervisor && data.supervisors.length > 0) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: "secondary",
								onclick: askSupervisor,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Cambiar supervisor`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (actions().delete) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: "ghost",
								onclick: () => deleteOpen = true,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Descartar borrador`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (contract().status !== "draft" && !actions().edit && hasActions()) $$renderer.push(`<!--[0--><p class="text-xs text-muted">Las modificaciones de un contrato en ejecución (otrosí, adición, prórroga) aún no están
            habilitadas en SIGCON.</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></div> `);
			ConfirmDialog($$renderer, {
				title: pending ? `${verb(pending)} contrato` : "",
				confirmLabel: pending ? verb(pending) : "Confirmar",
				variant: pending?.status === "terminated" || pending?.status === "suspended" ? "danger" : "primary",
				loading: working,
				onconfirm: confirmTransition,
				get open() {
					return transitionOpen;
				},
				set open($$value) {
					transitionOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3"><p>El contrato pasará de <strong>${escape_html(contract().status_label)}</strong> a <strong>${escape_html(pending?.label)}</strong>. El cambio queda en el historial y en la auditoría.</p> `);
					if (formMessage(actionError) && fieldErrors(actionError, "comment").length === 0) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(actionError))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					TextArea($$renderer, {
						label: pending?.requires_comment ? "Observación (obligatoria)" : "Observación (opcional)",
						required: pending?.requires_comment,
						rows: 3,
						maxlength: 2e3,
						hint: "Ej. número y fecha del acta o documento que soporta la decisión.",
						errors: fieldErrors(actionError, "comment"),
						get value() {
							return comment;
						},
						set value($$value) {
							comment = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Cambiar supervisor",
				confirmLabel: "Guardar",
				loading: working,
				onconfirm: confirmSupervisor,
				get open() {
					return supervisorOpen;
				},
				set open($$value) {
					supervisorOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3">`);
					if (formMessage(actionError) && fieldErrors(actionError, "supervisor").length === 0 && fieldErrors(actionError, "comment").length === 0) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(formMessage(actionError))}`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					SelectField($$renderer, {
						label: "Nuevo supervisor",
						placeholder: "Seleccione…",
						options: data.supervisors.map((s) => ({
							value: s.uuid,
							label: s.name
						})),
						errors: fieldErrors(actionError, "supervisor"),
						get value() {
							return newSupervisor;
						},
						set value($$value) {
							newSupervisor = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					TextArea($$renderer, {
						label: contract().status === "draft" ? "Motivo (opcional)" : "Motivo (obligatorio)",
						rows: 3,
						maxlength: 2e3,
						hint: "Ej. acto administrativo de designación.",
						errors: fieldErrors(actionError, "comment"),
						get value() {
							return supervisorComment;
						},
						set value($$value) {
							supervisorComment = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Descartar borrador",
				confirmLabel: "Descartar",
				variant: "danger",
				loading: working,
				onconfirm: confirmDelete,
				get open() {
					return deleteOpen;
				},
				set open($$value) {
					deleteOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<!---->El borrador dejará de aparecer en SIGCON. Su número no podrá reutilizarse. La acción queda en la
  auditoría.`);
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
