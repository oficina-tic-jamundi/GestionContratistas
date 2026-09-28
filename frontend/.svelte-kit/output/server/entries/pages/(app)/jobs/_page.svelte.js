import { T as escape_html, o as derived, s as ensure_array_like, t as attr_class } from "../../../../chunks/server.js";
import { t as toasts } from "../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { r as invalidate } from "../../../../chunks/client.js";
import "../../../../chunks/navigation.js";
import { t as Badge } from "../../../../chunks/Badge.js";
import { i as formatDateTime, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as Card } from "../../../../chunks/Card.js";
import { t as Alert } from "../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../chunks/forms.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
import { t as SelectField } from "../../../../chunks/SelectField.js";
import { r as retryJob } from "../../../../chunks/api12.js";
//#region src/routes/(app)/jobs/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let status = derived(() => data.query.status ?? "");
		let retrying = null;
		const TONES = {
			pending: "info",
			processing: "warning",
			completed: "success",
			failed: "danger"
		};
		const STATUS_OPTIONS = [
			{
				value: "failed",
				label: "Fallidos"
			},
			{
				value: "pending",
				label: "Pendientes"
			},
			{
				value: "processing",
				label: "En proceso"
			},
			{
				value: "completed",
				label: "Completados"
			}
		];
		function hrefFor(page) {
			return resolve(`/jobs?${buildQuery({
				...data.query,
				page: page === 1 ? null : page
			})}`);
		}
		async function retry(job) {
			retrying = job.uuid;
			try {
				await retryJob(job.uuid);
				await invalidate("app:jobs");
				toasts.show("La tarea se volverá a ejecutar en la próxima pasada del procesador.");
			} catch (e) {
				toasts.show(formMessage(e) ?? "No fue posible reintentar la tarea.", "error");
			} finally {
				retrying = null;
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					Button($$renderer, {
						variant: "secondary",
						onclick: () => invalidate("app:jobs"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Actualizar`);
						},
						$$slots: { default: true }
					});
				}
				PageHeader($$renderer, {
					title: "Tareas programadas",
					description: "Trabajos en segundo plano (PDF de informes, etc.). Los procesa el Cron del servidor cada 5 minutos.",
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <div class="mb-6 grid gap-4 lg:grid-cols-3">`);
			Card($$renderer, {
				title: "Procesador",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-3 text-sm">`);
					if (data.status.stale) {
						$$renderer.push("<!--[0-->");
						Alert($$renderer, {
							variant: "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(data.status.last_run ? `El procesador no se ejecuta desde hace más de ${data.status.stale_after_minutes} minutos.` : "El procesador nunca se ha ejecutado.")}
          Verifique el Cron de cPanel (<code>bin/console jobs:run</code> cada 5 minutos).`);
							},
							$$slots: { default: true }
						});
					} else {
						$$renderer.push("<!--[-1-->");
						Alert($$renderer, {
							variant: "success",
							children: ($$renderer) => {
								$$renderer.push(`<!---->El procesador se está ejecutando con normalidad.`);
							},
							$$slots: { default: true }
						});
					}
					$$renderer.push(`<!--]--> `);
					if (data.status.last_run) $$renderer.push(`<!--[0--><dl class="space-y-1"><div class="flex justify-between gap-2"><dt class="text-muted">Última ejecución</dt> <dd>${escape_html(formatDateTime(data.status.last_run.started_at))}</dd></div> <div class="flex justify-between gap-2"><dt class="text-muted">Procesados / con error</dt> <dd>${escape_html(data.status.last_run.processed)} / ${escape_html(data.status.last_run.failed)}</dd></div></dl>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> <div class="lg:col-span-2">`);
			Card($$renderer, {
				title: "Cola",
				children: ($$renderer) => {
					$$renderer.push(`<dl class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4"><!--[-->`);
					const each_array = ensure_array_like(STATUS_OPTIONS);
					for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
						let option = each_array[$$index];
						$$renderer.push(`<div class="rounded-md border border-border p-3"><dt class="text-muted">${escape_html(option.label)}</dt> <dd${attr_class(`text-2xl font-semibold ${option.value === "failed" && data.status.counts.failed > 0 ? "text-danger" : "text-ink"}`)}>${escape_html(data.status.counts[option.value])}</dd></div>`);
					}
					$$renderer.push(`<!--]--></dl>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></div> <form class="mb-4 flex flex-wrap items-end gap-3 rounded-2xl border border-border bg-surface p-4 shadow-card" role="search"><div class="min-w-48">`);
			SelectField($$renderer, {
				label: "Estado",
				placeholder: "Todos",
				options: STATUS_OPTIONS,
				get value() {
					return status();
				},
				set value($$value) {
					status($$value);
					$$settled = false;
				}
			});
			$$renderer.push(`<!----></div> `);
			Button($$renderer, {
				type: "submit",
				variant: "secondary",
				children: ($$renderer) => {
					$$renderer.push(`<!---->Filtrar`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></form> <ul class="space-y-3">`);
			const each_array_1 = ensure_array_like(data.result.items);
			if (each_array_1.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
					let job = each_array_1[$$index_1];
					$$renderer.push(`<li class="rounded-2xl border border-border bg-surface p-4 shadow-card text-sm shadow-xs"><div class="flex flex-wrap items-start justify-between gap-2"><div><p class="font-medium text-ink">${escape_html(job.type_label)}</p> <p class="text-xs text-muted">Creada ${escape_html(formatDateTime(job.created_at))} · intentos ${escape_html(job.attempts)} de ${escape_html(job.max_attempts)} `);
					if (job.status === "pending" && job.attempts > 0) $$renderer.push(`<!--[0-->· próximo intento ${escape_html(formatDateTime(job.available_at))}`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (job.completed_at) $$renderer.push(`<!--[0-->· completada ${escape_html(formatDateTime(job.completed_at))}`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></p></div> <div class="flex items-center gap-2">`);
					Badge($$renderer, {
						tone: TONES[job.status],
						children: ($$renderer) => {
							$$renderer.push(`<!---->${escape_html(job.status_label)}`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> `);
					if (job.status === "failed") {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							size: "sm",
							variant: "secondary",
							loading: retrying === job.uuid,
							onclick: () => retry(job),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Reintentar`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div></div> `);
					if (job.last_error) $$renderer.push(`<!--[0--><p class="mt-2 rounded bg-canvas px-3 py-2 text-xs break-words text-danger">${escape_html(job.last_error)}</p>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface px-4 py-8 text-center text-sm text-muted">No hay tareas para mostrar.</li>`);
			$$renderer.push(`<!--]--></ul> `);
			Pagination($$renderer, {
				pagination: data.result.pagination,
				hrefFor
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
