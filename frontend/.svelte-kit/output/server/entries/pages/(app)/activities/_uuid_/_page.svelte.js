import { i as onDestroy } from "../../../../../chunks/internal.js";
import { C as attr, T as escape_html, d as stringify, o as derived, s as ensure_array_like, t as attr_class } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { r as invalidate } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as Icon } from "../../../../../chunks/Icon.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { i as formatDateTime, n as formatBytes, r as formatDate } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { t as PriorityBadge } from "../../../../../chunks/PriorityBadge.js";
import { t as TextField } from "../../../../../chunks/TextField.js";
import { n as formMessage, t as fieldErrors } from "../../../../../chunks/forms.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { n as isViewable, t as DocumentViewer } from "../../../../../chunks/DocumentViewer.js";
import "../../../../../chunks/api6.js";
import { t as TextArea } from "../../../../../chunks/TextArea.js";
import { f as submitReport } from "../../../../../chunks/api7.js";
import { t as suggestPeriod } from "../../../../../chunks/period.js";
import { t as ReportStatusBadge } from "../../../../../chunks/ReportStatusBadge.js";
import { t as ReviewActions } from "../../../../../chunks/ReviewActions.js";
//#region src/lib/features/reports/ReportAnnexList.svelte
function ReportAnnexList($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Anexos vigentes de un informe, con previsualización dentro de SIGCON. Lo usan el
		* contratista (para comprobar qué adjuntó) y quien revisa (para leerlo antes de decidir).
		*/
		let { documents, empty = "Todavía no hay documentos anexos." } = $$props;
		const items = derived(() => documents?.items.filter((d) => d.status === "active") ?? []);
		let viewing = null;
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			if (items().length === 0) $$renderer.push(`<!--[0--><p class="mt-1 text-sm text-muted">${escape_html(empty)}</p>`);
			else {
				$$renderer.push(`<!--[-1--><ul class="mt-2 divide-y divide-border"><!--[-->`);
				const each_array = ensure_array_like(items());
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let doc = each_array[$$index];
					$$renderer.push(`<li class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm"><span class="min-w-0"><span class="text-ink">${escape_html(doc.original_name)}</span> <span class="block text-xs text-muted">${escape_html(doc.type.name)} · ${escape_html(formatBytes(doc.size_bytes))} · ${escape_html(formatDateTime(doc.created_at))}</span></span> `);
					if (isViewable(doc.mime_type)) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							size: "sm",
							variant: "secondary",
							onclick: () => viewing = doc,
							children: ($$renderer) => {
								Icon($$renderer, {
									name: "file",
									class: "size-4"
								});
								$$renderer.push(`<!----> Previsualizar`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push(`<!--[-1--><span class="text-xs text-muted">Se descarga desde el informe</span>`);
					$$renderer.push(`<!--]--></li>`);
				}
				$$renderer.push(`<!--]--></ul>`);
			}
			$$renderer.push(`<!--]--> `);
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
//#region src/lib/features/reports/DictationButton.svelte
function DictationButton($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Dictado por voz con el reconocimiento del navegador (Edge y Chrome). El audio lo transcribe
		* el navegador en sus propios servidores: por eso se avisa siempre y nunca se activa solo.
		* Si el navegador no lo admite, el botón no aparece y queda la escritura normal.
		*
		* `ontext` recibe SOLO lo nuevo de cada frase reconocida, para agregarlo al texto que ya hay.
		*/
		let { ontext, label = "Dictar con el micrófono", notice = true } = $$props;
		const api = derived(() => {
			if (typeof window === "undefined") return null;
			const w = window;
			return w.SpeechRecognition ?? w.webkitSpeechRecognition ?? null;
		});
		let listening = false;
		if (api()) {
			$$renderer.push(`<!--[0--><div class="space-y-2"><button type="button"${attr_class(`inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium transition border-border bg-field text-ink hover:border-primary/50`)}${attr("aria-pressed", listening)}>`);
			Icon($$renderer, {
				name: "mic",
				class: "size-4"
			});
			$$renderer.push(`<!----> ${escape_html(label)}</button> `);
			$$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			$$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (notice) $$renderer.push(`<!--[0--><p class="text-xs text-muted">El dictado lo transcribe su navegador (Edge o Chrome), que envía el audio a su propio
        servicio. No dicte información reservada; siempre puede escribir el texto.</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]-->`);
	});
}
//#endregion
//#region src/lib/features/reports/activityReport.ts
/**
* Pasos comunes de "Subir informe" y "Crear informe" desde la actividad (ADR-021, ADR-023).
* Todo queda en el informe del período: lo escrito es la descripción de la obligación y los
* archivos son anexos del informe.
*/
/** Período en el que caerá el documento: el del informe abierto o el siguiente sugerido. */
function periodFor(detail, reports, report) {
	return report ? {
		start: report.period_start,
		end: report.period_end
	} : suggestPeriod(detail.contract.start_date, detail.contract.end_date, reports);
}
var TEMPLATES = {
	informe: {
		label: "Informe de actividades",
		description: "Lo que ejecutó en la actividad, sus resultados y las dificultades.",
		main: "actividades",
		fields: [
			{
				code: "fecha",
				label: "Fecha del informe",
				kind: "date",
				required: true,
				max: 10
			},
			{
				code: "actividades",
				label: "Actividades realizadas",
				kind: "text",
				required: true,
				min: 20,
				max: 1e4
			},
			{
				code: "resultados",
				label: "Resultados obtenidos",
				kind: "text",
				required: false,
				max: 5e3
			},
			{
				code: "observaciones",
				label: "Dificultades u observaciones",
				kind: "text",
				required: false,
				max: 5e3
			}
		]
	},
	acta: {
		label: "Acta",
		description: "Registro de una reunión o jornada: lugar, asistentes, desarrollo y compromisos.",
		main: "desarrollo",
		fields: [
			{
				code: "fecha",
				label: "Fecha",
				kind: "date",
				required: true,
				max: 10
			},
			{
				code: "hora",
				label: "Hora",
				kind: "time",
				required: false,
				max: 5
			},
			{
				code: "lugar",
				label: "Lugar",
				kind: "line",
				required: true,
				min: 3,
				max: 200
			},
			{
				code: "tema",
				label: "Tema u objetivo",
				kind: "line",
				required: true,
				min: 3,
				max: 300
			},
			{
				code: "asistentes",
				label: "Asistentes",
				kind: "text",
				required: false,
				max: 3e3,
				hint: "Un asistente por línea."
			},
			{
				code: "desarrollo",
				label: "Desarrollo",
				kind: "text",
				required: true,
				min: 20,
				max: 1e4
			},
			{
				code: "compromisos",
				label: "Compromisos",
				kind: "text",
				required: false,
				max: 5e3
			}
		]
	}
};
//#endregion
//#region src/lib/features/reports/ReportComposer.svelte
function ReportComposer($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* "Crear informe" (ADR-023): el contratista elige el formato (acta o informe, cada uno con
		* sus campos), escribe o dicta, toma fotos y las va anexando, y al final SIGCON genera el
		* documento en PDF dentro del informe del período.
		*
		* Las fotos se quedan en el navegador hasta pulsar "Crear": así no quedan anexos sueltos si
		* el contratista cambia de idea.
		*/
		let { detail, reports, report, ondone, oncancel } = $$props;
		const MAX_PHOTOS = 20;
		(/* @__PURE__ */ new Date()).toLocaleDateString("en-CA", { timeZone: "America/Bogota" });
		/** SHA-256 del archivo: evita agregar dos veces la misma foto. */
		/** Anexo ya creado en el informe (si se reintenta, no se vuelve a subir). */
		let template = null;
		let values = {};
		let photos = [];
		let busy = false;
		let error = null;
		const spec = derived(() => template ? TEMPLATES[template] : null);
		const missing = derived(() => spec() ? spec().fields.filter((f) => f.required && (values[f.code] ?? "").trim().length < Math.max(1, f.min ?? 1)) : []);
		function removePhoto(key) {
			const photo = photos.find((p) => p.key === key);
			if (photo) URL.revokeObjectURL(photo.url);
			photos = photos.filter((p) => p.key !== key);
		}
		function dictate(field, text) {
			const current = values[field.code] ?? "";
			values[field.code] = current ? `${current} ${text}` : text;
		}
		onDestroy(() => photos.forEach((p) => URL.revokeObjectURL(p.url)));
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			if (!spec()) {
				$$renderer.push(`<!--[0--><div class="space-y-4"><div><h3 class="text-base font-semibold text-ink">¿Qué va a crear?</h3> <p class="mt-1 text-sm text-muted">Cada formato tiene su propia planilla.</p></div> <div class="grid gap-3 sm:grid-cols-2"><!--[-->`);
				const each_array = ensure_array_like(Object.entries(TEMPLATES));
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let [code, option] = each_array[$$index];
					$$renderer.push(`<button type="button" class="rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft"><span class="flex items-center gap-2 text-base font-semibold text-ink">`);
					Icon($$renderer, {
						name: code === "acta" ? "users" : "file",
						class: "size-5 text-primary"
					});
					$$renderer.push(`<!----> ${escape_html(option.label)}</span> <span class="mt-1 block text-sm text-muted">${escape_html(option.description)}</span></button>`);
				}
				$$renderer.push(`<!--]--></div> `);
				Button($$renderer, {
					variant: "ghost",
					onclick: oncancel,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Volver`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div>`);
			} else {
				$$renderer.push(`<!--[-1--><form class="space-y-5"><div class="flex flex-wrap items-center justify-between gap-2"><h3 class="text-base font-semibold text-ink">Crear ${escape_html(spec().label.toLowerCase())}</h3> `);
				Button($$renderer, {
					size: "sm",
					variant: "ghost",
					onclick: () => template = null,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Cambiar formato`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div> `);
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
				$$renderer.push(`<!--]--> <div class="grid gap-4 sm:grid-cols-2"><!--[-->`);
				const each_array_1 = ensure_array_like(spec().fields.filter((f) => f.kind !== "text"));
				for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
					let field = each_array_1[$$index_1];
					TextField($$renderer, {
						label: field.label,
						type: field.kind === "date" ? "date" : field.kind === "time" ? "time" : "text",
						required: field.required,
						maxlength: field.kind === "line" ? field.max : void 0,
						errors: fieldErrors(error, `fields.${field.code}`),
						get value() {
							return values[field.code];
						},
						set value($$value) {
							values[field.code] = $$value;
							$$settled = false;
						}
					});
				}
				$$renderer.push(`<!--]--></div> <!--[-->`);
				const each_array_2 = ensure_array_like(spec().fields.filter((f) => f.kind === "text"));
				for (let index = 0, $$length = each_array_2.length; index < $$length; index++) {
					let field = each_array_2[index];
					$$renderer.push(`<div class="space-y-2">`);
					TextArea($$renderer, {
						label: field.label,
						rows: field.code === spec().main ? 6 : 3,
						required: field.required,
						maxlength: field.max,
						hint: field.hint ?? (field.required ? `Escríbalo o díctelo. Mínimo ${field.min ?? 1} caracteres.` : void 0),
						errors: fieldErrors(error, `fields.${field.code}`),
						get value() {
							return values[field.code];
						},
						set value($$value) {
							values[field.code] = $$value;
							$$settled = false;
						}
					});
					$$renderer.push(`<!----> `);
					DictationButton($$renderer, {
						label: `Dictar ${stringify(field.label.toLowerCase())}`,
						notice: index === 0,
						ontext: (text) => dictate(field, text)
					});
					$$renderer.push(`<!----></div>`);
				}
				$$renderer.push(`<!--]--> <section class="space-y-3" aria-labelledby="fotos-titulo"><div class="flex flex-wrap items-center justify-between gap-2"><h4 id="fotos-titulo" class="text-sm font-semibold text-ink">Fotos (${escape_html(photos.length)} de 20)</h4> <div class="flex flex-wrap gap-2"><label class="btn-primary inline-flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold">`);
				Icon($$renderer, {
					name: "camera",
					class: "size-4"
				});
				$$renderer.push(`<!----> Tomar foto <input type="file" accept="image/jpeg,image/png" capture="environment" class="sr-only"${attr("disabled", photos.length >= MAX_PHOTOS, true)}/></label> <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink hover:border-primary/50">`);
				Icon($$renderer, {
					name: "upload",
					class: "size-4"
				});
				$$renderer.push(`<!----> Elegir de la galería <input type="file" accept="image/jpeg,image/png" multiple="" class="sr-only"${attr("disabled", photos.length >= MAX_PHOTOS, true)}/></label></div></div> `);
				$$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (photos.length === 0) $$renderer.push(`<!--[0--><p class="rounded-xl border border-dashed border-border p-4 text-sm text-muted">Tome fotos de la actividad y se irán agregando aquí. Aparecerán en el documento, con su
          descripción.</p>`);
				else {
					$$renderer.push(`<!--[-1--><ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"><!--[-->`);
					const each_array_3 = ensure_array_like(photos);
					for (let index = 0, $$length = each_array_3.length; index < $$length; index++) {
						let photo = each_array_3[index];
						$$renderer.push(`<li class="overflow-hidden rounded-xl border border-border bg-canvas"><img${attr("src", photo.url)}${attr("alt", `Foto ${stringify(index + 1)} de la actividad`)} class="aspect-[4/3] w-full object-cover"/> <div class="space-y-2 p-3">`);
						TextField($$renderer, {
							label: `Descripción de la foto ${stringify(index + 1)}`,
							maxlength: 200,
							get value() {
								return photo.caption;
							},
							set value($$value) {
								photo.caption = $$value;
								$$settled = false;
							}
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							size: "sm",
							variant: "ghost",
							"aria-label": `Quitar la foto ${stringify(index + 1)}`,
							onclick: () => removePhoto(photo.key),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Quitar`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></div></li>`);
					}
					$$renderer.push(`<!--]--></ul>`);
				}
				$$renderer.push(`<!--]--></section> `);
				$$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <div class="flex flex-wrap gap-3">`);
				Button($$renderer, {
					type: "submit",
					loading: busy,
					disabled: missing().length > 0,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Crear ${escape_html(spec().label.toLowerCase())}`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----> `);
				Button($$renderer, {
					variant: "ghost",
					onclick: oncancel,
					disabled: busy,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Volver`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div> `);
				if (missing().length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-muted">Falta completar: ${escape_html(missing().map((f) => f.label.toLowerCase()).join(", "))}.</p>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <p class="text-xs text-muted">Formato provisional de SIGCON mientras la Alcaldía adopta sus planillas oficiales.</p></form>`);
			}
			$$renderer.push(`<!--]-->`);
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
//#region src/lib/features/reports/ReportUploadForm.svelte
function ReportUploadForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* "Subir informe": el contratista ya tiene el acta o el informe hecho (PDF, Word, imagen) y
		* lo anexa. Se pide una descripción breve de lo realizado para el informe del período.
		*/
		let { detail, reports, report, ondone, oncancel } = $$props;
		const ACCEPT = ".pdf,.docx,.xlsx,.jpg,.jpeg,.png";
		let kind = "informe";
		let summary = "";
		let busy = false;
		let error = null;
		const ready = derived(() => false);
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<form class="space-y-5"><div><h3 class="text-base font-semibold text-ink">Subir un informe ya elaborado</h3> <p class="mt-1 text-sm text-muted">Anexe el archivo del acta o del informe que ya tiene.</p></div> `);
			if (formMessage(error) && fieldErrors(error, "file").length === 0) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <fieldset class="space-y-2"><legend class="text-sm font-medium text-ink">¿Qué documento es?</legend> <div class="flex flex-wrap gap-2"><!--[-->`);
			const each_array = ensure_array_like(Object.entries(TEMPLATES));
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let [code, template] = each_array[$$index];
				$$renderer.push(`<label${attr_class(`flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-sm transition ${kind === code ? "border-primary/50 bg-primary-soft text-ink" : "border-border bg-field text-muted hover:border-primary/40"}`)}><input type="radio" name="tipo-subir"${attr("value", code)}${attr("checked", kind === code, true)} class="size-4"/> ${escape_html(template.label)}</label>`);
			}
			$$renderer.push(`<!--]--></div></fieldset> <div class="space-y-2"><label class="block text-sm font-medium text-ink" for="archivo-informe"><span class="inline-flex items-center gap-2">`);
			Icon($$renderer, {
				name: "upload",
				class: "size-4"
			});
			$$renderer.push(`<!----> Archivo <span class="text-danger" aria-hidden="true">*</span></span></label> <input id="archivo-informe" type="file"${attr("accept", ACCEPT)} required="" aria-describedby="archivo-informe-ayuda" class="w-full rounded-lg border border-border bg-field px-3 py-2 text-sm text-ink file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1 file:text-on-primary"/> <p id="archivo-informe-ayuda" class="text-xs text-muted">PDF, Word, Excel o imagen.</p> `);
			if (fieldErrors(error, "file").length > 0) $$renderer.push(`<!--[0--><p class="text-sm text-danger" role="alert">${escape_html(fieldErrors(error, "file").join(" "))}</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> <div class="space-y-2">`);
			TextArea($$renderer, {
				label: "Resumen de lo realizado",
				rows: 3,
				required: true,
				maxlength: 5e3,
				hint: "Dos o tres frases para el informe del período. Mínimo 20 caracteres.",
				get value() {
					return summary;
				},
				set value($$value) {
					summary = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			DictationButton($$renderer, { ontext: (t) => summary = summary ? `${summary} ${t}` : t });
			$$renderer.push(`<!----></div> <div class="flex flex-wrap gap-3">`);
			Button($$renderer, {
				type: "submit",
				loading: busy,
				disabled: !ready(),
				children: ($$renderer) => {
					Icon($$renderer, {
						name: "upload",
						class: "size-4"
					});
					$$renderer.push(`<!----> Anexar informe`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			Button($$renderer, {
				variant: "ghost",
				onclick: oncancel,
				children: ($$renderer) => {
					$$renderer.push(`<!---->Volver`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></form>`);
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
//#region src/lib/features/reports/ActivityReportForm.svelte
function ActivityReportForm($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Informes desde la actividad del contratista (ADR-021, ADR-023). Dos caminos:
		* - **Subir informe**: anexar el acta o el informe que ya tiene hecho.
		* - **Crear informe**: elegir acta o informe, escribir o dictar, tomar fotos y generar el PDF.
		* Lo creado o subido queda en el borrador del informe del período; al final se envía.
		*/
		let { detail, reports, report, documents, onchange } = $$props;
		let mode = null;
		let viewing = null;
		let created = null;
		let confirmOpen = false;
		let sending = false;
		let error = null;
		const period = derived(() => periodFor(detail, reports, report));
		const coveredUntil = derived(() => reports.filter((r) => r.status !== "rejected").map((r) => r.period_end).sort().at(-1) ?? null);
		const underReview = derived(() => reports.filter((r) => [
			"submitted",
			"in_review",
			"resubmitted"
		].includes(r.status)));
		const hasDocuments = derived(() => (documents?.items ?? []).some((d) => d.status === "active"));
		async function done(document) {
			mode = null;
			created = document;
			await onchange();
			toasts.show(`${document.type.name}: listo.`);
			if (isViewable(document.mime_type)) viewing = document;
		}
		async function send() {
			if (!report) return;
			sending = true;
			error = null;
			try {
				await submitReport(report.uuid);
				confirmOpen = false;
				created = null;
				await onchange();
				toasts.show("Informe enviado al supervisor.");
			} catch (e) {
				error = e;
				confirmOpen = false;
			} finally {
				sending = false;
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="informes-actividad"><h2 id="informes-actividad" class="text-lg font-semibold text-ink">Subir o crear informe</h2> `);
			if (period()) {
				$$renderer.push(`<!--[0--><p class="mt-1 text-sm text-muted">${escape_html(report ? "Informe abierto del período" : "Nuevo informe del período")}
      ${escape_html(formatDate(period().start))} a ${escape_html(formatDate(period().end))} `);
				if (detail.contract.supervisor) $$renderer.push(`<!--[0-->· revisa ${escape_html(detail.contract.supervisor)}`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></p>`);
			} else {
				$$renderer.push(`<!--[-1--><div class="mt-3">`);
				Alert($$renderer, {
					variant: "info",
					title: "No hay un período pendiente de informe",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Ya hay un informe por cada período del contrato${escape_html(coveredUntil() ? ` (el último cubre hasta el ${formatDate(coveredUntil())}, fin del contrato)` : "")}. `);
						if (underReview().length > 0) $$renderer.push(`<!--[0-->${escape_html(underReview().length === 1 ? "Uno está" : `${underReview().length} están`)} en revisión con el supervisor:
          si le hace observaciones, aquí se habilitan de nuevo estos botones para corregirlo.`);
						else $$renderer.push(`<!--[-1-->Para corregir uno, el supervisor debe devolverlo con observaciones.`);
						$$renderer.push(`<!--]-->`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----> <div class="mt-3 flex flex-wrap gap-2">`);
				if (underReview()[0]) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						size: "sm",
						variant: "secondary",
						href: resolve("/(app)/reports/[uuid]", { uuid: underReview()[0].uuid }),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Ver el informe en revisión`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				Button($$renderer, {
					size: "sm",
					variant: "ghost",
					href: resolve("/(app)/reports"),
					children: ($$renderer) => {
						$$renderer.push(`<!---->Ver todos mis informes`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div></div>`);
			}
			$$renderer.push(`<!--]--> `);
			if (formMessage(error)) {
				$$renderer.push(`<!--[0--><div class="mt-4">`);
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div class="mt-5">`);
			if (mode === "upload") {
				$$renderer.push("<!--[0-->");
				ReportUploadForm($$renderer, {
					detail,
					reports,
					report,
					ondone: done,
					oncancel: () => mode = null
				});
			} else if (mode === "create") {
				$$renderer.push("<!--[1-->");
				ReportComposer($$renderer, {
					detail,
					reports,
					report,
					ondone: done,
					oncancel: () => mode = null
				});
			} else {
				$$renderer.push(`<!--[-1--><div class="grid gap-3 sm:grid-cols-2"><button type="button" class="flex items-start gap-4 rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft disabled:cursor-not-allowed disabled:opacity-60"${attr("disabled", !period(), true)}><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary">`);
				Icon($$renderer, {
					name: "upload",
					class: "size-6"
				});
				$$renderer.push(`<!----></span> <span><span class="block text-base font-semibold text-ink">Subir informe</span> <span class="mt-1 block text-sm text-muted">Ya tengo el acta o el informe hecho y lo quiero anexar.</span></span></button> <button type="button" class="flex items-start gap-4 rounded-2xl border border-border bg-field p-5 text-left transition hover:border-primary/50 hover:bg-primary-soft disabled:cursor-not-allowed disabled:opacity-60"${attr("disabled", !period(), true)}><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary">`);
				Icon($$renderer, {
					name: "mic",
					class: "size-6"
				});
				$$renderer.push(`<!----></span> <span><span class="block text-base font-semibold text-ink">Crear informe</span> <span class="mt-1 block text-sm text-muted">Escribo o dicto, tomo fotos y SIGCON arma el acta o el informe.</span></span></button></div> `);
				if (created) {
					$$renderer.push(`<!--[0--><div class="mt-4">`);
					Alert($$renderer, {
						variant: "success",
						children: ($$renderer) => {
							$$renderer.push(`<!---->${escape_html(created.type.name)} listo: ${escape_html(created.original_name)}. Revíselo en la lista y, cuando
            termine, pulse "Enviar al supervisor".`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----></div>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				if (report) {
					$$renderer.push(`<!--[0--><div class="mt-6 border-t border-border pt-4"><h3 class="text-sm font-semibold text-ink">Lo que lleva en este período</h3> `);
					ReportAnnexList($$renderer, {
						documents,
						empty: "Todavía no ha subido ni creado documentos."
					});
					$$renderer.push(`<!----> <div class="mt-4 flex flex-wrap items-center gap-3">`);
					Button($$renderer, {
						disabled: !hasDocuments(),
						onclick: () => confirmOpen = true,
						children: ($$renderer) => {
							$$renderer.push(`<!---->Enviar al supervisor`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> <p class="text-xs text-warning">Es un borrador: el supervisor no lo ve hasta que pulse "Enviar al supervisor".</p></div></div>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]-->`);
			}
			$$renderer.push(`<!--]--></div></section> `);
			DocumentViewer($$renderer, {
				get document() {
					return viewing;
				},
				set document($$value) {
					viewing = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Enviar al supervisor",
				confirmLabel: "Enviar",
				loading: sending,
				onconfirm: send,
				get open() {
					return confirmOpen;
				},
				set open($$value) {
					confirmOpen = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<!---->El informe del período, con todos sus documentos, quedará en revisión y no podrá editarlo hasta
  que el supervisor responda.`);
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
//#region src/lib/features/reports/ActivityReportReview.svelte
function ActivityReportReview($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Revisión del informe desde la actividad (ADR-021): el supervisor lee lo que el contratista
		* reportó en esta obligación, previsualiza los anexos sin salir de SIGCON y decide.
		* Las decisiones son las mismas del informe (`ReviewActions`): el backend las valida igual.
		*/
		let { obligation, report, documents, onchange } = $$props;
		const item = derived(() => report?.content.items.find((i) => i.obligation_uuid === obligation.uuid) ?? null);
		const lastReview = derived(() => report?.reviews[0] ?? null);
		$$renderer.push(`<section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="revision"><div class="flex flex-wrap items-start justify-between gap-3"><div><h2 id="revision" class="text-lg font-semibold text-ink">Informe del período</h2> `);
		if (report) $$renderer.push(`<!--[0--><p class="mt-1 text-sm text-muted">N.º ${escape_html(report.number)} · ${escape_html(formatDate(report.period_start))} a ${escape_html(formatDate(report.period_end))}
          · versión ${escape_html(report.current_version)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div> `);
		if (report) {
			$$renderer.push("<!--[0-->");
			ReportStatusBadge($$renderer, {
				status: report.status,
				label: report.status_label
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div> `);
		if (!report) $$renderer.push(`<!--[0--><p class="mt-4 text-sm text-muted">El contratista no ha enviado ningún informe de este contrato. Los borradores en elaboración no
      se ven hasta que él los envía; cuando lo haga, podrá leerlo y decidir aquí mismo.</p>`);
		else {
			$$renderer.push(`<!--[-1--><div class="mt-5 space-y-5"><div><h3 class="text-sm font-semibold text-ink">Lo reportado en esta actividad</h3> <p${attr_class(`mt-1 text-sm whitespace-pre-line ${item()?.description ? "text-ink" : "text-muted italic"}`)}>${escape_html(item()?.description ?? "El contratista no describió esta obligación en el período.")}</p> `);
			if (item()) $$renderer.push(`<!--[0--><p class="mt-2 text-xs text-muted">Avance reportado: ${escape_html(item().progress)} %</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> `);
			if (report.content.summary) $$renderer.push(`<!--[0--><div><h3 class="text-sm font-semibold text-ink">Resumen del período</h3> <p class="mt-1 text-sm whitespace-pre-line text-ink">${escape_html(report.content.summary)}</p></div>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (item() && item().evidences && item().evidences.length > 0) $$renderer.push(`<!--[0--><p class="text-xs text-muted">${escape_html(item().evidences.length)}
          ${escape_html(item().evidences.length === 1 ? "evidencia fotográfica" : "evidencias fotográficas")} en el período.
          Véalas completas en el informe.</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div><h3 class="text-sm font-semibold text-ink">Documentos anexos</h3> `);
			ReportAnnexList($$renderer, {
				documents,
				empty: "El informe no tiene anexos."
			});
			$$renderer.push(`<!----></div> `);
			if (lastReview()) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: lastReview().decision === "approved" ? "success" : "info",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Última decisión: ${escape_html(lastReview().decision === "approved" ? "aprobado" : lastReview().decision === "rejected" ? "rechazado" : lastReview().decision === "reopened" ? "reabierto" : "con observaciones")} por ${escape_html(lastReview().reviewer)} el ${escape_html(formatDateTime(lastReview().created_at))}${escape_html(lastReview().comment ? `: ${lastReview().comment}` : ".")}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <div class="border-t border-border pt-4"><h3 class="text-sm font-semibold text-ink">Su decisión</h3> <p class="mt-1 mb-3 text-xs text-muted">Aprobar, solicitar correcciones con las observaciones por obligación, o rechazar indicando
          el motivo. Todo queda registrado con su nombre y fecha.</p> `);
			ReviewActions($$renderer, {
				report,
				onchange
			});
			$$renderer.push(`<!----> `);
			if (!report.can.start_review && !report.can.observe && !report.can.approve && !report.can.reject && !report.can.reopen) $$renderer.push(`<!--[0--><p class="text-sm text-muted">No hay decisiones pendientes: el informe está en manos del contratista o ya quedó en
            firme.</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div></div>`);
		}
		$$renderer.push(`<!--]--></section>`);
	});
}
//#endregion
//#region src/routes/(app)/activities/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Una actividad con su contexto (ADR-021). El contratista ve qué debe hacer y envía su
		* informe; quien revisa ve lo mismo y, además, el informe del período con sus decisiones.
		* La actividad no se edita aquí: eso se hace desde el contrato.
		*/
		let { data } = $$props;
		const activity = derived(() => data.detail.activity);
		const contract = derived(() => data.detail.contract);
		const reviewer = derived(() => session.can(Permission.ReportsReview));
		const num = (v) => Math.max(0, Math.min(100, Number(v) || 0));
		const pct = (v) => `${num(v).toLocaleString("es-CO", { maximumFractionDigits: 0 })} %`;
		const state = (item) => num(item.progress) >= 100 ? "Terminada" : num(item.progress) > 0 ? "En curso" : "Pendiente";
		const observations = derived(() => (data.report?.reviews[0]?.observations ?? []).filter((o) => o.activity_uuid === null || o.activity_uuid === data.detail.obligation.uuid));
		const refresh = () => invalidate("app:activities");
		$$renderer.push(`<a${attr("href", resolve("/(app)/activities"))} class="mb-4 inline-flex items-center gap-1 text-sm text-muted hover:text-ink">`);
		Icon($$renderer, {
			name: "chevron",
			class: "size-4 rotate-90"
		});
		$$renderer.push(`<!----> Mis actividades</a> `);
		PageHeader($$renderer, {
			title: activity().title,
			description: `Contrato ${stringify(contract().contract_number)} · ${stringify(contract().department)}`
		});
		$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-[2fr_1fr]"><div class="space-y-6">`);
		if (reviewer()) {
			$$renderer.push(`<!--[0--><section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="que-hacer"><h2 id="que-hacer" class="text-lg font-semibold text-ink">Qué debe hacer</h2> <p class="mt-2 text-sm whitespace-pre-line text-ink">${escape_html(activity().description ?? "La Alcaldía no registró una descripción para esta actividad.")}</p> `);
			if (data.detail.parent) $$renderer.push(`<!--[0--><p class="mt-4 text-sm text-muted">Hace parte de: <span class="text-ink">${escape_html(data.detail.parent.title)}</span></p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (data.detail.children.length > 0) {
				$$renderer.push(`<!--[0--><h3 class="mt-6 text-sm font-semibold text-ink">Tareas de esta actividad</h3> <ul class="mt-2 divide-y divide-border"><!--[-->`);
				const each_array = ensure_array_like(data.detail.children);
				for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
					let child = each_array[$$index];
					$$renderer.push(`<li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="text-ink">${escape_html(child.title)}</span> <span class="shrink-0 text-muted tabular-nums">${escape_html(state(child))} · ${escape_html(pct(child.progress))}</span></li>`);
				}
				$$renderer.push(`<!--]--></ul>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></section> `);
			if (observations().length > 0) {
				$$renderer.push(`<!--[0--><section class="rounded-2xl border border-warning/40 bg-warning-soft p-6" aria-labelledby="observaciones"><h2 id="observaciones" class="text-lg font-semibold text-ink">Observaciones del supervisor</h2> <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-ink"><!--[-->`);
				const each_array_1 = ensure_array_like(observations());
				for (let index = 0, $$length = each_array_1.length; index < $$length; index++) {
					let observation = each_array_1[index];
					$$renderer.push(`<li>${escape_html(observation.text)}</li>`);
				}
				$$renderer.push(`<!--]--></ul> <p class="mt-3 text-xs text-muted">${escape_html(reviewer() ? "Son las observaciones de la última revisión de este informe." : "Corrija lo señalado y vuelva a enviar el informe del período.")}</p></section>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			ActivityReportReview($$renderer, {
				obligation: data.detail.obligation,
				report: data.report,
				documents: data.documents,
				onchange: refresh
			});
			$$renderer.push(`<!---->`);
		} else {
			$$renderer.push("<!--[-1-->");
			if (observations().length > 0) {
				$$renderer.push(`<!--[0--><section class="rounded-2xl border border-warning/40 bg-warning-soft p-6" aria-labelledby="observaciones"><h2 id="observaciones" class="text-lg font-semibold text-ink">Observaciones del supervisor</h2> <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-ink"><!--[-->`);
				const each_array_2 = ensure_array_like(observations());
				for (let index = 0, $$length = each_array_2.length; index < $$length; index++) {
					let observation = each_array_2[index];
					$$renderer.push(`<li>${escape_html(observation.text)}</li>`);
				}
				$$renderer.push(`<!--]--></ul> <p class="mt-3 text-xs text-muted">${escape_html(reviewer() ? "Son las observaciones de la última revisión de este informe." : "Corrija lo señalado y vuelva a enviar el informe del período.")}</p></section>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			ActivityReportForm($$renderer, {
				detail: data.detail,
				reports: data.reports,
				report: data.report?.can.edit ? data.report : null,
				documents: data.report?.can.edit ? data.documents : null,
				onchange: refresh
			});
			$$renderer.push(`<!----> `);
			if (data.report && !data.report.can.edit) {
				$$renderer.push("<!--[0-->");
				const sent = data.report;
				$$renderer.push(`<section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="enviado"><div class="flex flex-wrap items-start justify-between gap-3"><div><h2 id="enviado" class="text-lg font-semibold text-ink">${escape_html(sent.status === "approved" ? "Informe aprobado" : sent.status === "rejected" ? "Informe rechazado" : "Informe enviado")}</h2> <p class="mt-1 text-sm text-muted">N.º ${escape_html(sent.number)} · ${escape_html(formatDate(sent.period_start))} a ${escape_html(formatDate(sent.period_end))}</p></div> `);
				ReportStatusBadge($$renderer, {
					status: sent.status,
					label: sent.status_label
				});
				$$renderer.push(`<!----></div> <p class="mt-3 text-sm text-muted">`);
				if (sent.status === "approved") $$renderer.push(`<!--[0-->Lo aprobó ${escape_html(sent.approved_by ?? contract().supervisor ?? "su supervisor")}${escape_html(sent.approved_at ? ` el ${formatDateTime(sent.approved_at)}` : "")}.
              Con esto la Alcaldía puede tramitar el pago del período.`);
				else if (sent.status === "rejected") $$renderer.push(`<!--[1-->Debe presentar un informe nuevo para este período. El motivo está en las observaciones
              del supervisor.`);
				else $$renderer.push(`<!--[-1-->Está con ${escape_html(contract().supervisor ?? "su supervisor")}. Podrá editarlo si le hace
              observaciones.`);
				$$renderer.push(`<!--]--></p> <h3 class="mt-5 text-sm font-semibold text-ink">Lo que reportó en esta actividad</h3> <p class="mt-1 text-sm whitespace-pre-line text-ink">${escape_html(sent.content.items.find((i) => i.obligation_uuid === data.detail.obligation.uuid)?.description ?? "Sin descripción para esta obligación.")}</p> <h3 class="mt-5 text-sm font-semibold text-ink">Documentos enviados</h3> `);
				ReportAnnexList($$renderer, {
					documents: data.documents,
					empty: "El informe se envió sin anexos."
				});
				$$renderer.push(`<!----></section>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="que-hacer"><h2 id="que-hacer" class="text-lg font-semibold text-ink">Qué debe hacer</h2> <p class="mt-2 text-sm whitespace-pre-line text-ink">${escape_html(activity().description ?? "La Alcaldía no registró una descripción para esta actividad.")}</p> `);
			if (data.detail.parent) $$renderer.push(`<!--[0--><p class="mt-4 text-sm text-muted">Hace parte de: <span class="text-ink">${escape_html(data.detail.parent.title)}</span></p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (data.detail.children.length > 0) {
				$$renderer.push(`<!--[0--><h3 class="mt-6 text-sm font-semibold text-ink">Tareas de esta actividad</h3> <ul class="mt-2 divide-y divide-border"><!--[-->`);
				const each_array_3 = ensure_array_like(data.detail.children);
				for (let $$index_3 = 0, $$length = each_array_3.length; $$index_3 < $$length; $$index_3++) {
					let child = each_array_3[$$index_3];
					$$renderer.push(`<li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="text-ink">${escape_html(child.title)}</span> <span class="shrink-0 text-muted tabular-nums">${escape_html(state(child))} · ${escape_html(pct(child.progress))}</span></li>`);
				}
				$$renderer.push(`<!--]--></ul>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></section>`);
		}
		$$renderer.push(`<!--]--></div> <aside class="space-y-6"><section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="resumen"><h2 id="resumen" class="text-lg font-semibold text-ink">Resumen</h2> <dl class="mt-3 space-y-3 text-sm"><div class="flex items-center justify-between gap-3"><dt class="text-muted">Avance</dt> <dd class="font-semibold text-ink tabular-nums">${escape_html(pct(activity().progress))}</dd></div> <div class="flex items-center justify-between gap-3"><dt class="text-muted">Estado</dt> <dd class="text-ink">${escape_html(state(activity()))}</dd></div> `);
		if (activity().priority && activity().priority_label) {
			$$renderer.push(`<!--[0--><div class="flex items-center justify-between gap-3"><dt class="text-muted">Prioridad</dt> <dd>`);
			PriorityBadge($$renderer, {
				priority: activity().priority,
				label: activity().priority_label
			});
			$$renderer.push(`<!----></dd></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (activity().due_date) $$renderer.push(`<!--[0--><div class="flex items-center justify-between gap-3"><dt class="text-muted">Fecha de entrega</dt> <dd class="text-ink">${escape_html(formatDate(activity().due_date))}</dd></div>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <div class="flex items-center justify-between gap-3"><dt class="text-muted">Supervisor</dt> <dd class="text-ink">${escape_html(contract().supervisor ?? "Sin asignar")}</dd></div> <div class="flex items-center justify-between gap-3"><dt class="text-muted">Actualizada</dt> <dd class="text-ink">${escape_html(formatDateTime(activity().updated_at))}</dd></div></dl> <p class="mt-4 text-xs text-muted">El avance lo registra su supervisor con base en los informes aprobados.</p></section> `);
		if (data.reports.length > 0) {
			$$renderer.push(`<!--[0--><section class="rounded-2xl border border-border bg-surface p-6" aria-labelledby="mis-informes"><h2 id="mis-informes" class="text-lg font-semibold text-ink">Informes del contrato</h2> <ul class="mt-3 space-y-2 text-sm"><!--[-->`);
			const each_array_4 = ensure_array_like(data.reports);
			for (let $$index_4 = 0, $$length = each_array_4.length; $$index_4 < $$length; $$index_4++) {
				let item = each_array_4[$$index_4];
				$$renderer.push(`<li class="flex items-center justify-between gap-3"><a class="text-ink hover:text-primary"${attr("href", resolve("/(app)/reports/[uuid]", { uuid: item.uuid }))}>N.º ${escape_html(item.number)} · ${escape_html(formatDate(item.period_start))}</a> `);
				ReportStatusBadge($$renderer, {
					status: item.status,
					label: item.status_label
				});
				$$renderer.push(`<!----></li>`);
			}
			$$renderer.push(`<!--]--></ul></section>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></aside></div>`);
	});
}
//#endregion
export { _page as default };
