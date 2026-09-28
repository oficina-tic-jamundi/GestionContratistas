import { C as attr, T as escape_html, d as stringify, o as derived, s as ensure_array_like, t as attr_class } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Badge } from "../../../../../chunks/Badge.js";
import { i as formatDateTime, n as formatBytes, r as formatDate } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { t as DownloadLink } from "../../../../../chunks/DownloadLink.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { t as TextArea } from "../../../../../chunks/TextArea.js";
import { f as submitReport, l as reportPdfUrl, u as saveReport } from "../../../../../chunks/api7.js";
import { t as ReportStatusBadge } from "../../../../../chunks/ReportStatusBadge.js";
import { t as ReviewActions } from "../../../../../chunks/ReviewActions.js";
import { n as ProgressBar, t as DocumentsPanel } from "../../../../../chunks/DocumentsPanel.js";
import { n as evidenceImageUrl } from "../../../../../chunks/api11.js";
//#region src/lib/features/reports/ReportContentView.svelte
function ReportContentView($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Contenido de un informe tal como quedó congelado en una versión (solo lectura). */
		let { content } = $$props;
		$$renderer.push(`<div class="space-y-6 text-sm"><section><h3 class="font-semibold text-ink">Resumen del período</h3> <p class="mt-1 whitespace-pre-line text-ink">${escape_html(content.summary ?? "—")}</p></section> <section class="space-y-4"><h3 class="font-semibold text-ink">Actividades por obligación</h3> `);
		const each_array = ensure_array_like(content.items);
		if (each_array.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let index = 0, $$length = each_array.length; index < $$length; index++) {
				let item = each_array[index];
				$$renderer.push(`<article class="rounded-md border border-border p-4"><div class="flex flex-wrap items-start justify-between gap-2"><p class="font-medium text-ink">${escape_html(index + 1)}. ${escape_html(item.title)}</p> <div class="w-40">`);
				ProgressBar($$renderer, {
					value: item.progress,
					label: `Avance de ${stringify(item.title)}`,
					size: "sm"
				});
				$$renderer.push(`<!----></div></div> <p${attr_class(`mt-2 whitespace-pre-line ${item.description ? "text-ink" : "text-muted italic"}`)}>${escape_html(item.description ?? "Sin descripción en este período.")}</p> `);
				if (item.progress_updates.length > 0) {
					$$renderer.push(`<!--[0--><details class="mt-3"><summary class="cursor-pointer text-xs text-primary">${escape_html(item.progress_updates.length)}
              ${escape_html(item.progress_updates.length === 1 ? "avance registrado" : "avances registrados")} en el
              período</summary> <ul class="mt-2 space-y-1 text-xs text-muted"><!--[-->`);
					const each_array_1 = ensure_array_like(item.progress_updates);
					for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
						let update = each_array_1[i];
						$$renderer.push(`<li><span class="text-ink">${escape_html(update.activity)}</span>: ${escape_html(update.from)} % → ${escape_html(update.to)} % ·
                  ${escape_html(update.note)} · ${escape_html(update.user)}, ${escape_html(formatDateTime(update.recorded_at))}</li>`);
					}
					$$renderer.push(`<!--]--></ul></details>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (item.evidences && item.evidences.length > 0) {
					$$renderer.push(`<!--[0--><div class="mt-3"><p class="text-xs text-muted">${escape_html(item.evidences.length)}
              ${escape_html(item.evidences.length === 1 ? "evidencia fotográfica" : "evidencias fotográficas")} del
              período</p> <ul class="mt-2 flex flex-wrap gap-2"><!--[-->`);
					const each_array_2 = ensure_array_like(item.evidences);
					for (let $$index_1 = 0, $$length = each_array_2.length; $$index_1 < $$length; $$index_1++) {
						let evidence = each_array_2[$$index_1];
						$$renderer.push(`<li class="w-24"><img${attr("src", evidenceImageUrl(evidence.uuid, "thumbnail"))}${attr("alt", `Evidencia de ${stringify(evidence.activity)}`)} loading="lazy" class="aspect-[4/3] w-full rounded border border-border object-cover"/> <span class="mt-0.5 block text-[11px] leading-tight text-muted">${escape_html(formatDateTime(evidence.captured_at))} `);
						if (!evidence.latitude) $$renderer.push(`<!--[0-->· sin ubicación`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></span></li>`);
					}
					$$renderer.push(`<!--]--></ul></div>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></article>`);
			}
		} else $$renderer.push(`<!--[!--><p class="text-muted">El contrato no tiene obligaciones registradas.</p>`);
		$$renderer.push(`<!--]--></section> `);
		if (content.contractor_notes) $$renderer.push(`<!--[0--><section><h3 class="font-semibold text-ink">Observaciones del contratista</h3> <p class="mt-1 whitespace-pre-line">${escape_html(content.contractor_notes)}</p></section>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <section><h3 class="font-semibold text-ink">Anexos incluidos</h3> `);
		if (content.documents.length > 0) {
			$$renderer.push(`<!--[0--><ul class="mt-1 list-disc pl-5 text-muted"><!--[-->`);
			const each_array_3 = ensure_array_like(content.documents);
			for (let $$index_3 = 0, $$length = each_array_3.length; $$index_3 < $$length; $$index_3++) {
				let doc = each_array_3[$$index_3];
				$$renderer.push(`<li><span class="text-ink">${escape_html(doc.name)}</span> · ${escape_html(doc.type)} · ${escape_html(formatBytes(doc.size_bytes))}</li>`);
			}
			$$renderer.push(`<!--]--></ul>`);
		} else $$renderer.push(`<!--[-1--><p class="mt-1 text-muted">Sin anexos.</p>`);
		$$renderer.push(`<!--]--></section></div>`);
	});
}
//#endregion
//#region src/lib/features/reports/ReportEditor.svelte
function ReportEditor($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Edición del informe por el contratista (borrador o con observaciones). */
		let { report, onchange } = $$props;
		let summary = report.summary ?? "";
		let notes = report.contractor_notes ?? "";
		let descriptions = Object.fromEntries(report.content.items.map((i) => [i.obligation_uuid, i.description ?? ""]));
		let saving = false;
		let error = null;
		let submitOpen = false;
		let submitting = false;
		let dirty = false;
		/** Observaciones de la última revisión, por obligación (para mostrarlas junto al campo). */
		const lastReview = derived(() => report.status === "observed" ? report.reviews[0] : void 0);
		const observationsFor = (uuid) => lastReview()?.observations.filter((o) => o.activity_uuid === uuid) ?? [];
		function input() {
			return {
				summary: summary.trim() || null,
				contractor_notes: notes.trim() || null,
				items: Object.entries(descriptions).map(([obligation, description]) => ({
					obligation,
					description
				}))
			};
		}
		async function confirmSubmit() {
			submitting = true;
			error = null;
			try {
				await saveReport(report.uuid, input());
				const result = await submitReport(report.uuid);
				submitOpen = false;
				dirty = false;
				await onchange();
				toasts.show(`Informe enviado al supervisor (versión ${result.current_version}).`);
			} catch (e) {
				error = e;
				submitOpen = false;
			} finally {
				submitting = false;
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-6" novalidate="">`);
			if (lastReview()) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "warning",
					children: ($$renderer) => {
						$$renderer.push(`<p class="font-medium">${escape_html(lastReview().decision === "reopened" ? "Informe reabierto" : "El supervisor pidió correcciones")}
        (${escape_html(lastReview().reviewer)}, ${escape_html(formatDateTime(lastReview().created_at))})</p> `);
						if (lastReview().comment) $$renderer.push(`<!--[0--><p class="mt-1 whitespace-pre-line">${escape_html(lastReview().comment)}</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> <!--[-->`);
						const each_array = ensure_array_like(observationsFor(null));
						for (let i = 0, $$length = each_array.length; i < $$length; i++) {
							let o = each_array[i];
							$$renderer.push(`<p class="mt-1">• ${escape_html(o.text)}</p>`);
						}
						$$renderer.push(`<!--]-->`);
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
			TextArea($$renderer, {
				label: "Resumen del período",
				required: true,
				rows: 5,
				maxlength: 1e4,
				hint: "Describa de forma general lo ejecutado en el período (mínimo 20 caracteres para enviar).",
				errors: fieldErrors(error, "summary"),
				get value() {
					return summary;
				},
				set value($$value) {
					summary = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <fieldset class="space-y-4"><legend class="text-sm font-semibold text-ink">Actividades por obligación</legend> `);
			if (fieldErrors(error, "items").length > 0) $$renderer.push(`<!--[0--><p class="text-sm text-danger">${escape_html(fieldErrors(error, "items").join(" "))}</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			const each_array_1 = ensure_array_like(report.content.items);
			if (each_array_1.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let index = 0, $$length = each_array_1.length; index < $$length; index++) {
					let item = each_array_1[index];
					$$renderer.push(`<div class="rounded-md border border-border p-4"><div class="mb-2 flex flex-wrap items-start justify-between gap-2"><p class="text-sm font-medium text-ink">${escape_html(index + 1)}. ${escape_html(item.title)}</p> <div class="w-40">`);
					ProgressBar($$renderer, {
						value: item.progress,
						label: `Avance de ${stringify(item.title)}`,
						size: "sm"
					});
					$$renderer.push(`<!----></div></div> <!--[-->`);
					const each_array_2 = ensure_array_like(observationsFor(item.obligation_uuid));
					for (let i = 0, $$length = each_array_2.length; i < $$length; i++) {
						let o = each_array_2[i];
						$$renderer.push(`<p class="mb-2 rounded bg-warning-soft px-3 py-2 text-sm text-warning">Observación: ${escape_html(o.text)}</p>`);
					}
					$$renderer.push(`<!--]--> `);
					TextArea($$renderer, {
						label: "Lo realizado en el período",
						rows: 3,
						maxlength: 5e3,
						get value() {
							return descriptions[item.obligation_uuid];
						},
						set value($$value) {
							descriptions[item.obligation_uuid] = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					if (item.progress_updates.length > 0) $$renderer.push(`<!--[0--><p class="mt-2 text-xs text-muted">Avances registrados en el período:
            ${escape_html(item.progress_updates.map((u) => `${u.activity} (${u.from} % → ${u.to} %)`).join("; "))}. Se incluyen automáticamente en el informe.</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (item.evidences && item.evidences.length > 0) $$renderer.push(`<!--[0--><p class="mt-1 text-xs text-muted">${escape_html(item.evidences.length)}
            ${escape_html(item.evidences.length === 1 ? "evidencia fotográfica del período se incluye" : "evidencias fotográficas del período se incluyen")} automáticamente.</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				}
			} else $$renderer.push(`<!--[!--><p class="text-sm text-muted">El contrato no tiene obligaciones registradas. Solicite a la dependencia que las registre.</p>`);
			$$renderer.push(`<!--]--></fieldset> `);
			TextArea($$renderer, {
				label: "Observaciones del contratista (opcional)",
				rows: 3,
				maxlength: 5e3,
				errors: fieldErrors(error, "contractor_notes"),
				get value() {
					return notes;
				},
				set value($$value) {
					notes = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> <div class="flex flex-wrap items-center gap-2">`);
			Button($$renderer, {
				type: "submit",
				variant: "secondary",
				loading: saving,
				children: ($$renderer) => {
					$$renderer.push(`<!---->Guardar borrador`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Button($$renderer, {
				onclick: () => submitOpen = true,
				children: ($$renderer) => {
					$$renderer.push(`<!---->Enviar al supervisor`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			if (dirty) $$renderer.push(`<!--[0--><span class="text-xs text-muted">Hay cambios sin guardar.</span>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div></form> `);
			ConfirmDialog($$renderer, {
				title: "Enviar informe",
				confirmLabel: "Enviar",
				loading: submitting,
				onconfirm: confirmSubmit,
				get open() {
					return submitOpen;
				},
				set open($$value) {
					submitOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<p>Se guardará el contenido actual y se enviará al supervisor como una nueva versión. Mientras esté
    en revisión no podrá modificarlo ni cambiar sus anexos.</p>`);
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
//#region src/lib/features/reports/ReportVersions.svelte
function ReportVersions($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Versiones enviadas (inmutables). Cada una se carga al abrirla. */
		let { reportUuid, versions, approvedVersion } = $$props;
		let selected = null;
		Card($$renderer, {
			title: "Versiones enviadas",
			description: "Cada envío congela el contenido. No se modifican.",
			padded: false,
			children: ($$renderer) => {
				$$renderer.push(`<ul class="divide-y divide-border">`);
				const each_array = ensure_array_like(versions);
				if (each_array.length !== 0) {
					$$renderer.push("<!--[-->");
					for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
						let v = each_array[$$index_1];
						$$renderer.push(`<li class="px-5 py-3 text-sm"><button type="button" class="flex w-full flex-wrap items-center justify-between gap-2 text-left"${attr("aria-expanded", selected === v.version)}><span><span class="font-medium text-primary">Versión ${escape_html(v.version)}</span> <span class="block text-xs text-muted">${escape_html(v.submitted_by)} · ${escape_html(formatDateTime(v.submitted_at))}</span></span> `);
						if (approvedVersion === v.version) {
							$$renderer.push("<!--[0-->");
							Badge($$renderer, {
								tone: "success",
								children: ($$renderer) => {
									$$renderer.push(`<!---->Aprobada`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></button> <p class="mt-1 text-xs">`);
						if (v.pdf.status === "ready") {
							$$renderer.push("<!--[0-->");
							DownloadLink($$renderer, {
								href: reportPdfUrl(reportUuid, v.version),
								children: ($$renderer) => {
									$$renderer.push(`<!---->Descargar PDF`);
								},
								$$slots: { default: true }
							});
							$$renderer.push(`<!----> <span class="text-muted">${escape_html(v.pdf.size_bytes ? formatBytes(v.pdf.size_bytes) : "")} · generado ${escape_html(formatDateTime(v.pdf.generated_at))}</span>`);
						} else if (v.pdf.status === "failed") $$renderer.push(`<!--[1--><span class="text-danger">No fue posible generar el PDF. La administración puede reintentarlo.</span>`);
						else $$renderer.push(`<!--[-1--><span class="text-muted">PDF en preparación (se genera en unos minutos).</span>`);
						$$renderer.push(`<!--]--></p> `);
						if (selected === v.version) {
							$$renderer.push(`<!--[0--><div class="mt-3 space-y-3">`);
							$$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></div>`);
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></li>`);
					}
				} else $$renderer.push(`<!--[!--><li class="px-5 py-6 text-sm text-muted">Aún no se ha enviado ninguna versión.</li>`);
				$$renderer.push(`<!--]--></ul>`);
			},
			$$slots: { default: true }
		});
	});
}
//#endregion
//#region src/routes/(app)/reports/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const report = derived(() => data.report);
		const refresh = () => invalidate("app:report");
		const DECISIONS = {
			observed: "Solicitó correcciones",
			approved: "Aprobó",
			rejected: "Rechazó",
			reopened: "Reabrió"
		};
		{
			function actions($$renderer) {
				Button($$renderer, {
					href: resolve("/(app)/contracts/[uuid]", { uuid: report().contract.uuid }),
					variant: "secondary",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Ver contrato`);
					},
					$$slots: { default: true }
				});
			}
			PageHeader($$renderer, {
				title: `Informe N.° ${stringify(report().number)} · ${stringify(report().contract.contract_number)}`,
				description: `${stringify(report().contract.contractor)} · ${stringify(formatDate(report().period_start))} – ${stringify(formatDate(report().period_end))}`,
				actions,
				$$slots: { actions: true }
			});
		}
		$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-3"><div class="space-y-6 lg:col-span-2">`);
		Card($$renderer, {
			title: report().can.edit ? "Contenido del informe" : `Versión ${report().current_version}`,
			children: ($$renderer) => {
				if (report().can.edit) {
					$$renderer.push(`<!--[0--><!---->`);
					ReportEditor($$renderer, {
						report: report(),
						onchange: refresh
					});
					$$renderer.push(`<!---->`);
				} else if (data.frozen) {
					$$renderer.push("<!--[1-->");
					ReportContentView($$renderer, { content: data.frozen.content });
				} else {
					$$renderer.push("<!--[-1-->");
					Alert($$renderer, {
						variant: "info",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Borrador en elaboración por el contratista.`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> <div class="mt-4">`);
					ReportContentView($$renderer, { content: report().content });
					$$renderer.push(`<!----></div>`);
				}
				$$renderer.push(`<!--]-->`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----> `);
		DocumentsPanel($$renderer, {
			owner: {
				kind: "report",
				uuid: report().uuid
			},
			list: data.documents,
			onchange: refresh,
			title: "Anexos del informe",
			empty: "El informe no tiene anexos."
		});
		$$renderer.push(`<!----> `);
		if (report().versions.length > 0) {
			$$renderer.push("<!--[0-->");
			ReportVersions($$renderer, {
				reportUuid: report().uuid,
				versions: report().versions,
				approvedVersion: report().approved_version
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		Card($$renderer, {
			title: "Historial",
			description: "Del más reciente al más antiguo.",
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
		$$renderer.push(`<!----></div> <div class="order-first space-y-6 lg:order-none">`);
		Card($$renderer, {
			title: "Estado",
			children: ($$renderer) => {
				$$renderer.push(`<div class="space-y-4 text-sm">`);
				ReportStatusBadge($$renderer, {
					status: report().status,
					label: report().status_label
				});
				$$renderer.push(`<!----> <dl class="space-y-2"><div><dt class="text-muted">Supervisor</dt> <dd>${escape_html(report().supervisor ?? "Sin asignar")}</dd></div> `);
				if (report().approved_at) $$renderer.push(`<!--[0--><div><dt class="text-muted">Aprobado</dt> <dd>Versión ${escape_html(report().approved_version)} · ${escape_html(report().approved_by)} · ${escape_html(formatDateTime(report().approved_at))}</dd></div>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></dl> `);
				if (report().status === "rejected") {
					$$renderer.push("<!--[0-->");
					Alert($$renderer, {
						variant: "danger",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Este informe fue rechazado. Para el mismo período debe elaborarse un informe nuevo desde
            el contrato.`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				ReviewActions($$renderer, {
					report: report(),
					onchange: refresh
				});
				$$renderer.push(`<!----></div>`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----> `);
		if (report().reviews.length > 0) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				title: "Revisiones",
				children: ($$renderer) => {
					$$renderer.push(`<ol class="space-y-4 text-sm"><!--[-->`);
					const each_array_1 = ensure_array_like(report().reviews);
					for (let $$index_2 = 0, $$length = each_array_1.length; $$index_2 < $$length; $$index_2++) {
						let review = each_array_1[$$index_2];
						$$renderer.push(`<li><p class="font-medium text-ink">${escape_html(DECISIONS[review.decision])} la versión ${escape_html(review.version)}</p> <p class="text-xs text-muted">${escape_html(review.reviewer)} · ${escape_html(formatDateTime(review.created_at))}</p> `);
						if (review.comment) $$renderer.push(`<!--[0--><p class="mt-1 whitespace-pre-line">${escape_html(review.comment)}</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (review.observations.length > 0) {
							$$renderer.push(`<!--[0--><ul class="mt-1 list-disc space-y-1 pl-5"><!--[-->`);
							const each_array_2 = ensure_array_like(review.observations);
							for (let i = 0, $$length = each_array_2.length; i < $$length; i++) {
								let o = each_array_2[i];
								$$renderer.push(`<li>`);
								if (o.activity_title) $$renderer.push(`<!--[0--><span class="block text-xs text-muted">${escape_html(o.activity_title)}</span>`);
								else $$renderer.push("<!--[-1-->");
								$$renderer.push(`<!--]--> ${escape_html(o.text)}</li>`);
							}
							$$renderer.push(`<!--]--></ul>`);
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></li>`);
					}
					$$renderer.push(`<!--]--></ol>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div>`);
	});
}
//#endregion
export { _page as default };
