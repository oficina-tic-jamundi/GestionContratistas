import { C as attr, T as escape_html, d as stringify, n as attr_style, o as derived, s as ensure_array_like, t as attr_class } from "./server.js";
import "./toasts.svelte.js";
import { t as Badge } from "./Badge.js";
import { i as formatDateTime, n as formatBytes } from "./format.js";
import { t as Button } from "./Button.js";
import { t as Card } from "./Card.js";
import { t as Alert } from "./Alert.js";
import { t as DownloadLink } from "./DownloadLink.js";
import { t as TextField } from "./TextField.js";
import { n as formMessage, t as fieldErrors } from "./forms.js";
import { t as ConfirmDialog } from "./ConfirmDialog.js";
import { n as isViewable, t as DocumentViewer } from "./DocumentViewer.js";
import { t as documentDownloadUrl } from "./api6.js";
import { t as TextArea } from "./TextArea.js";
import { t as SelectField } from "./SelectField.js";
//#region src/lib/components/ui/ProgressBar.svelte
function ProgressBar($$renderer, $$props) {
	/** Barra de avance accesible: expone el valor a lectores de pantalla y lo muestra como texto. */
	let { value, label, size = "md" } = $$props;
	const numeric = derived(() => Math.max(0, Math.min(100, Number(value) || 0)));
	const text = derived(() => `${numeric().toLocaleString("es-CO", { maximumFractionDigits: 2 })} %`);
	const tone = derived(() => numeric() >= 100 ? "bg-success" : numeric() > 0 ? "bg-primary" : "bg-border");
	$$renderer.push(`<div class="flex items-center gap-2"><div${attr_class(`w-full overflow-hidden rounded-full bg-canvas ring-1 ring-border ${size === "sm" ? "h-1.5" : "h-2.5"}`)} role="progressbar"${attr("aria-label", label)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", numeric())}${attr("aria-valuetext", text())}><div${attr_class(`h-full rounded-full ${tone()}`)}${attr_style("", { width: `${stringify(numeric())}%` })}></div></div> <span class="w-16 shrink-0 text-right text-xs font-medium text-ink tabular-nums">${escape_html(text())}</span></div>`);
}
//#endregion
//#region src/lib/features/documents/DocumentsPanel.svelte
function DocumentsPanel($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Documentos de un contrato o anexos de un informe. La validación real (tipo, contenido, tamaño, duplicados) la hace
		* el backend; aquí solo se ayuda al usuario a elegir un archivo aceptable.
		*/
		let { owner, list, onchange, title = "Documentos", empty = "No hay documentos cargados." } = $$props;
		const ACCEPT = ".pdf,.jpg,.jpeg,.png,.docx,.xlsx";
		let type = "";
		let description = "";
		let uploading = false;
		let error = null;
		const tooBig = derived(() => false);
		let withdrawOpen = false;
		let reason = "";
		let withdrawError = null;
		let working = false;
		async function confirmWithdraw() {}
		let viewing = null;
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			Card($$renderer, {
				title,
				padded: false,
				children: ($$renderer) => {
					$$renderer.push(`<ul class="divide-y divide-border">`);
					const each_array = ensure_array_like(list.items);
					if (each_array.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
							let doc = each_array[$$index];
							$$renderer.push(`<li class="flex flex-wrap items-start justify-between gap-3 px-5 py-3 text-sm"><div class="min-w-0 flex-1"><p${attr_class(`font-medium break-all text-ink ${doc.status === "withdrawn" ? "line-through" : ""}`)}>${escape_html(doc.original_name)}</p> <p class="text-xs text-muted">${escape_html(doc.type.name)} · ${escape_html(formatBytes(doc.size_bytes))} · ${escape_html(doc.uploaded_by)} · ${escape_html(formatDateTime(doc.created_at))}</p> `);
							if (doc.description) $$renderer.push(`<!--[0--><p class="mt-0.5 text-xs text-muted">${escape_html(doc.description)}</p>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> `);
							if (doc.withdrawn) $$renderer.push(`<!--[0--><p class="mt-1 text-xs text-warning">Retirado por ${escape_html(doc.withdrawn.by ?? "—")} (${escape_html(formatDateTime(doc.withdrawn.at))}): ${escape_html(doc.withdrawn.reason)}</p>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></div> <div class="flex items-center gap-2">`);
							if (doc.status === "withdrawn") {
								$$renderer.push("<!--[0-->");
								Badge($$renderer, {
									children: ($$renderer) => {
										$$renderer.push(`<!---->Retirado`);
									},
									$$slots: { default: true }
								});
							} else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> `);
							if (isViewable(doc.mime_type)) $$renderer.push(`<!--[0--><button type="button" class="rounded px-2 py-1 text-xs font-medium text-primary hover:bg-primary-soft">Ver</button>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--> `);
							DownloadLink($$renderer, {
								href: documentDownloadUrl(doc.uuid),
								children: ($$renderer) => {
									$$renderer.push(`<!---->Descargar`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----> `);
							if (doc.can_withdraw) $$renderer.push(`<!--[0--><button type="button" class="rounded px-2 py-1 text-xs text-danger hover:bg-danger-soft">Retirar</button>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></div></li>`);
						}
					} else $$renderer.push(`<!--[!--><li class="px-5 py-6 text-sm text-muted">${escape_html(empty)}</li>`);
					$$renderer.push(`<!--]--></ul> `);
					if (list.can_upload) {
						$$renderer.push(`<!--[0--><form class="space-y-3 border-t border-border bg-canvas px-5 py-4" novalidate=""><p class="text-sm font-medium text-ink">Cargar documento</p> `);
						if (formMessage(error) && fieldErrors(error, "file").length === 0 && fieldErrors(error, "type").length === 0) {
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
							label: "Tipo de documento",
							placeholder: "Seleccione…",
							options: list.types.map((t) => ({
								value: t.code,
								label: t.name
							})),
							errors: fieldErrors(error, "type"),
							get value() {
								return type;
							},
							set value($$value) {
								type = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> <div class="space-y-1"><label${attr("for", `document-file-${stringify(owner.kind)}`)} class="block text-sm font-medium text-ink">Archivo</label> <!---->`);
						$$renderer.push(`<input${attr("id", `document-file-${stringify(owner.kind)}`)} type="file"${attr("accept", ACCEPT)} class="block w-full text-sm file:mr-3 file:rounded-md file:border file:border-border file:bg-surface file:px-3 file:py-1.5 file:text-sm"/>`);
						$$renderer.push(`<!----> <p${attr_class(`text-xs ${tooBig() ? "text-danger" : "text-muted"}`)}>PDF, JPG, PNG, DOCX o XLSX. Máximo ${escape_html(list.max_mb)} MB.</p> `);
						if (fieldErrors(error, "file").length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-danger">${escape_html(fieldErrors(error, "file").join(" "))}</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div></div> `);
						TextField($$renderer, {
							label: "Descripción (opcional)",
							maxlength: 255,
							get value() {
								return description;
							},
							set value($$value) {
								description = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							type: "submit",
							size: "sm",
							loading: uploading,
							disabled: true,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Cargar`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></form>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]-->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Retirar documento",
				confirmLabel: "Retirar",
				variant: "danger",
				loading: working,
				onconfirm: confirmWithdraw,
				get open() {
					return withdrawOpen;
				},
				set open($$value) {
					withdrawOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3"><p>"${escape_html(void 0)}" quedará marcado como retirado. No se elimina: sigue disponible para
      consulta con el motivo registrado.</p> `);
					TextArea($$renderer, {
						label: "Motivo",
						required: true,
						rows: 2,
						maxlength: 500,
						errors: fieldErrors(withdrawError, "reason"),
						get value() {
							return reason;
						},
						set value($$value) {
							reason = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			DocumentViewer($$renderer, {
				get document() {
					return viewing;
				},
				set document($$value) {
					viewing = $$value;
					$$settled = false;
				}
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
export { ProgressBar as n, DocumentsPanel as t };
