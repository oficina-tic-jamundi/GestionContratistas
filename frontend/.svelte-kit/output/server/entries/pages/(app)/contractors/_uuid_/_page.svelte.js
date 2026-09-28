import { C as attr, T as escape_html, d as stringify, n as attr_style, o as derived, s as ensure_array_like, t as attr_class } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as Badge } from "../../../../../chunks/Badge.js";
import { t as Icon } from "../../../../../chunks/Icon.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { a as formatMoney, r as formatDate } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { t as PriorityBadge } from "../../../../../chunks/PriorityBadge.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { t as ContractStatusBadge } from "../../../../../chunks/ContractStatusBadge.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { t as ReportStatusBadge } from "../../../../../chunks/ReportStatusBadge.js";
import { a as updateContractor, i as setContractorActive } from "../../../../../chunks/api8.js";
import { t as ContractorForm } from "../../../../../chunks/ContractorForm.js";
//#region src/lib/features/contractors/ContractActivities.svelte
function ContractActivities($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Actividades del contrato en modo consulta: avance del contrato arriba y, debajo, cada
		* obligación con sus tareas. Sin botones de edición: quien planea lo hace desde el contrato.
		* Al abrir una obligación se ve qué debe hacerse y, para quien revisa, el informe del período.
		*/
		let { tree } = $$props;
		const num = (v) => Math.max(0, Math.min(100, Number(v) || 0));
		const pct = (v) => `${num(v).toLocaleString("es-CO", { maximumFractionDigits: 1 })} %`;
		const fill = (v) => num(v) >= 100 ? "bg-success" : num(v) >= 50 ? "bg-primary" : num(v) > 0 ? "bg-warning" : "bg-border";
		const leaves = (item) => item.children.length === 0 ? [] : item.children.flatMap((c) => [c, ...leaves(c)]);
		$$renderer.push(`<div class="mt-5"><div class="flex items-center justify-between gap-3"><p class="text-sm font-medium text-ink">Avance del contrato</p> <p class="text-lg font-bold tabular-nums text-ink">${escape_html(pct(tree.progress))}</p></div> <div class="mt-2 h-3 overflow-hidden rounded-full bg-canvas ring-1 ring-border" role="progressbar" aria-label="Avance del contrato"${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", num(tree.progress))}${attr("aria-valuetext", pct(tree.progress))}><div${attr_class(`h-full rounded-full ${stringify(fill(tree.progress))}`)}${attr_style("", { width: `${stringify(num(tree.progress))}%` })}></div></div> <p class="mt-2 text-xs text-muted">El avance de cada tarea lo registra el supervisor al verificar lo ejecutado.</p> <ul class="mt-4 space-y-3">`);
		const each_array = ensure_array_like(tree.items);
		if (each_array.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
				let obligation = each_array[$$index_1];
				$$renderer.push(`<li class="rounded-xl border border-border bg-canvas p-4"><div class="flex flex-wrap items-center justify-between gap-2"><p class="flex min-w-0 flex-wrap items-center gap-2 text-sm font-medium text-ink"><a class="inline-flex items-center gap-1 hover:text-primary"${attr("href", resolve("/(app)/activities/[uuid]", { uuid: obligation.uuid }))}>${escape_html(obligation.title)} `);
				Icon($$renderer, {
					name: "chevron",
					class: "size-4 -rotate-90 text-muted"
				});
				$$renderer.push(`<!----></a> `);
				if (obligation.priority && obligation.priority_label) {
					$$renderer.push("<!--[0-->");
					PriorityBadge($$renderer, {
						priority: obligation.priority,
						label: obligation.priority_label
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></p> <p class="text-sm font-semibold tabular-nums text-ink">${escape_html(pct(obligation.progress))}</p></div> <div class="mt-2 h-2 overflow-hidden rounded-full bg-surface" role="progressbar"${attr("aria-label", `Avance de ${stringify(obligation.title)}`)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", num(obligation.progress))}${attr("aria-valuetext", pct(obligation.progress))}><div${attr_class(`h-full rounded-full ${stringify(fill(obligation.progress))}`)}${attr_style("", { width: `${stringify(num(obligation.progress))}%` })}></div></div> `);
				if (leaves(obligation).length > 0) {
					$$renderer.push(`<!--[0--><ul class="mt-3 space-y-1.5"><!--[-->`);
					const each_array_1 = ensure_array_like(leaves(obligation));
					for (let $$index = 0, $$length = each_array_1.length; $$index < $$length; $$index++) {
						let task = each_array_1[$$index];
						$$renderer.push(`<li class="flex flex-wrap items-baseline justify-between gap-x-3 text-sm"><span class="text-ink">${escape_html(task.title)} <span class="text-xs text-muted">(${escape_html(task.level_label.toLowerCase())})</span> `);
						if (task.due_date) $$renderer.push(`<!--[0--><span class="text-xs text-muted">· entrega ${escape_html(formatDate(task.due_date))}</span>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></span> <span class="tabular-nums text-muted">${escape_html(pct(task.progress))}</span></li>`);
					}
					$$renderer.push(`<!--]--></ul>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></li>`);
			}
		} else $$renderer.push(`<!--[!--><li class="rounded-xl border border-border bg-canvas p-4 text-sm text-muted">Este contrato aún no tiene obligaciones registradas.</li>`);
		$$renderer.push(`<!--]--></ul></div>`);
	});
}
//#endregion
//#region src/routes/(app)/contractors/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Ficha del contratista (ADR-021): primero sus actividades y su avance, después su
		* información. La administración puede editarlo y activarlo o desactivarlo; el supervisor
		* consulta y abre el informe para revisarlo.
		*/
		let { data } = $$props;
		const contractor = derived(() => data.contractor);
		const canManage = derived(() => session.can(Permission.ContractorsManage));
		const accounts = derived(() => {
			const linked = data.contractor.user;
			if (!linked || data.accounts.some((a) => a.uuid === linked.uuid)) return data.accounts;
			return [...data.accounts, {
				uuid: linked.uuid,
				email: linked.email,
				full_name: linked.email
			}];
		});
		let saving = false;
		let error = null;
		let confirmToggle = false;
		let working = false;
		let editing = false;
		async function save(input) {
			saving = true;
			error = null;
			try {
				await updateContractor(contractor().uuid, input);
				toasts.show("Contratista actualizado.");
				editing = false;
				await invalidate("app:contractor");
			} catch (e) {
				error = e;
			} finally {
				saving = false;
			}
		}
		async function toggle() {
			working = true;
			try {
				const response = await setContractorActive(contractor().uuid, contractor().status !== "active");
				toasts.show(response.message ?? "Estado actualizado.", "info", 8e3);
				await invalidate("app:contractor");
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible cambiar el estado.", "error");
			} finally {
				working = false;
				confirmToggle = false;
			}
		}
		/** Informe más reciente presentado: es el que el supervisor revisa. */
		const lastReport = (reports) => reports.find((r) => r.status !== "draft") ?? null;
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (canManage()) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "secondary",
							onclick: () => editing = !editing,
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(editing ? "Cerrar edición" : "Editar información")}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							variant: contractor().status === "active" ? "danger" : "secondary",
							onclick: () => confirmToggle = true,
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(contractor().status === "active" ? "Desactivar" : "Activar")}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!---->`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					Button($$renderer, {
						href: resolve("/contractors"),
						variant: "secondary",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Volver`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!---->`);
				}
				PageHeader($$renderer, {
					title: contractor().name,
					description: `Documento ${stringify(contractor().document)}`,
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> `);
			if (formMessage(error)) {
				$$renderer.push(`<!--[0--><div class="mb-4">`);
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <ul class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4"><li class="rounded-2xl border border-border bg-surface p-4"><p class="text-xs text-muted">Estado</p> <p class="mt-1">`);
			Badge($$renderer, {
				tone: contractor().status === "active" ? "success" : "neutral",
				children: ($$renderer) => {
					$$renderer.push(`<!---->${escape_html(contractor().status_label)}`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-xs text-muted">Tipo</p> <p class="mt-1 text-sm text-ink">${escape_html(contractor().person_type_label)}</p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-xs text-muted">Contratos</p> <p class="mt-1 text-sm text-ink">${escape_html(data.files.length)}</p></li> <li class="rounded-2xl border border-border bg-surface p-4"><p class="text-xs text-muted">Cuenta SIGCON</p> <p class="mt-1 text-sm break-all text-ink">${escape_html(contractor().user?.email ?? "Sin vincular")}</p></li></ul> `);
			if (editing && canManage()) {
				$$renderer.push(`<!--[0--><div class="mb-6">`);
				Card($$renderer, {
					title: "Editar información del contratista",
					children: ($$renderer) => {
						$$renderer.push(`<!---->`);
						ContractorForm($$renderer, {
							initial: {
								person_type: contractor().person_type,
								document_type: contractor().document_type,
								document_number: contractor().document_number,
								verification_digit: contractor().verification_digit?.toString() ?? null,
								name: contractor().name,
								email: contractor().email,
								phone: contractor().phone,
								address: contractor().address,
								user: contractor().user?.uuid ?? null
							},
							accounts: accounts(),
							submitting: saving,
							error,
							submitLabel: "Guardar cambios",
							onsubmit: save
						});
						$$renderer.push(`<!---->`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div class="space-y-6">`);
			const each_array = ensure_array_like(data.files);
			if (each_array.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let file = each_array[$$index];
					const report = lastReport(file.reports);
					$$renderer.push(`<section class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"${attr("aria-labelledby", `contrato-${stringify(file.contract.uuid)}`)}><div class="flex flex-wrap items-start justify-between gap-3"><div class="min-w-0"><h2${attr("id", `contrato-${stringify(file.contract.uuid)}`)} class="text-lg font-semibold text-ink">Contrato ${escape_html(file.contract.contract_number)}</h2> <p class="mt-0.5 text-sm text-muted">${escape_html(file.contract.department.name)} · ${escape_html(formatDate(file.contract.start_date))} – ${escape_html(formatDate(file.contract.end_date))} · ${escape_html(formatMoney(file.contract.total_value))}</p></div> <div class="flex flex-wrap items-center gap-2">`);
					ContractStatusBadge($$renderer, {
						status: file.contract.status,
						label: file.contract.status_label
					});
					$$renderer.push(`<!----> `);
					Button($$renderer, {
						variant: "secondary",
						size: "sm",
						href: resolve("/(app)/contracts/[uuid]", { uuid: file.contract.uuid }),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Ver contrato`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----></div></div> `);
					ContractActivities($$renderer, { tree: file.tree });
					$$renderer.push(`<!----> <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-border pt-4">`);
					if (report) {
						$$renderer.push(`<!--[0--><span class="text-sm text-muted">Último informe presentado:</span> <span class="text-sm font-medium text-ink">N.° ${escape_html(report.number)}</span> `);
						ReportStatusBadge($$renderer, {
							status: report.status,
							label: report.status_label
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							size: "sm",
							href: resolve("/(app)/reports/[uuid]", { uuid: report.uuid }),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Ver informe`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!---->`);
					} else $$renderer.push(`<!--[-1--><p class="text-sm text-muted">Este contrato aún no tiene informes presentados.</p>`);
					$$renderer.push(`<!--]--></div></section>`);
				}
			} else $$renderer.push(`<!--[!--><p class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">Este contratista no tiene contratos registrados.</p>`);
			$$renderer.push(`<!--]--></div> `);
			ConfirmDialog($$renderer, {
				title: contractor().status === "active" ? "Desactivar contratista" : "Activar contratista",
				confirmLabel: contractor().status === "active" ? "Desactivar" : "Activar",
				variant: contractor().status === "active" ? "danger" : "primary",
				loading: working,
				onconfirm: toggle,
				get open() {
					return confirmToggle;
				},
				set open($$value) {
					confirmToggle = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					if (contractor().status === "active") $$renderer.push(`<!--[0-->No podrá asignársele contratos nuevos. Los contratos en ejecución no se modifican.`);
					else $$renderer.push(`<!--[-1-->Podrá volver a asignársele contratos.`);
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
export { _page as default };
