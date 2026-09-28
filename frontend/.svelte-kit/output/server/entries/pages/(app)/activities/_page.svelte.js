import { C as attr, T as escape_html, d as stringify, n as attr_style, s as ensure_array_like, t as attr_class } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { t as Icon } from "../../../../chunks/Icon.js";
import { r as formatDate } from "../../../../chunks/format.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as PriorityBadge } from "../../../../chunks/PriorityBadge.js";
import { t as ContractStatusBadge } from "../../../../chunks/ContractStatusBadge.js";
//#region src/routes/(app)/activities/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Mis actividades (ADR-021): una tarjeta por obligación del contrato. Al abrirla se ve qué
		* hay que hacer y desde allí se envía el informe.
		*/
		let { data } = $$props;
		const num = (v) => Math.max(0, Math.min(100, Number(v) || 0));
		const pct = (v) => `${num(v).toLocaleString("es-CO", { maximumFractionDigits: 0 })} %`;
		const fill = (v) => num(v) >= 100 ? "bg-success" : num(v) > 0 ? "bg-warning" : "bg-border";
		const countTasks = (item) => item.children.length === 0 ? 0 : item.children.length + item.children.reduce((total, c) => total + countTasks(c), 0);
		PageHeader($$renderer, {
			title: "Mis actividades",
			description: "Abra una actividad para ver qué debe hacer y enviar su informe."
		});
		$$renderer.push(`<!----> <div class="space-y-8">`);
		const each_array = ensure_array_like(data.sections);
		if (each_array.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let $$index_1 = 0, $$length = each_array.length; $$index_1 < $$length; $$index_1++) {
				let section = each_array[$$index_1];
				$$renderer.push(`<section${attr("aria-labelledby", `contrato-${stringify(section.contract.uuid)}`)}><div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div class="min-w-0"><h2${attr("id", `contrato-${stringify(section.contract.uuid)}`)} class="text-lg font-semibold text-ink">Contrato ${escape_html(section.contract.contract_number)}</h2> <p class="text-sm text-muted">${escape_html(section.contract.department.name)} · hasta ${escape_html(formatDate(section.contract.end_date))}</p></div> <div class="flex items-center gap-3">`);
				ContractStatusBadge($$renderer, {
					status: section.contract.status,
					label: section.contract.status_label
				});
				$$renderer.push(`<!----> <span class="text-sm font-semibold text-ink tabular-nums">${escape_html(pct(section.tree.progress))} de avance</span></div></div> <ul class="stagger grid gap-4 md:grid-cols-2 xl:grid-cols-3">`);
				const each_array_1 = ensure_array_like(section.tree.items);
				if (each_array_1.length !== 0) {
					$$renderer.push("<!--[-->");
					for (let $$index = 0, $$length = each_array_1.length; $$index < $$length; $$index++) {
						let activity = each_array_1[$$index];
						const tasks = countTasks(activity);
						$$renderer.push(`<li><a${attr("href", resolve("/(app)/activities/[uuid]", { uuid: activity.uuid }))} class="card-interactive flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card hover:bg-primary-soft"><span class="flex items-start justify-between gap-3"><span class="text-base font-semibold text-ink">${escape_html(activity.title)}</span> `);
						Icon($$renderer, {
							name: "chevron",
							class: "size-5 shrink-0 -rotate-90 text-muted"
						});
						$$renderer.push(`<!----></span> `);
						if (activity.description) $$renderer.push(`<!--[0--><span class="mt-1 line-clamp-2 text-sm text-muted">${escape_html(activity.description)}</span>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> <span class="mt-3 flex flex-wrap items-center gap-2">`);
						if (activity.priority && activity.priority_label) {
							$$renderer.push("<!--[0-->");
							PriorityBadge($$renderer, {
								priority: activity.priority,
								label: activity.priority_label
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (tasks > 0) $$renderer.push(`<!--[0--><span class="text-xs text-muted">${escape_html(tasks)} ${escape_html(tasks === 1 ? "tarea" : "tareas")}</span>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (activity.due_date) $$renderer.push(`<!--[0--><span class="text-xs text-muted">· entrega ${escape_html(formatDate(activity.due_date))}</span>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></span> <span class="mt-auto pt-4"><span class="flex items-center justify-between text-xs text-muted"><span>Avance</span> <span class="font-semibold text-ink tabular-nums">${escape_html(pct(activity.progress))}</span></span> <span class="mt-1 block h-2 overflow-hidden rounded-full bg-canvas" role="progressbar"${attr("aria-label", `Avance de ${stringify(activity.title)}`)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", num(activity.progress))}${attr("aria-valuetext", pct(activity.progress))}><span${attr_class(`block h-full rounded-full ${stringify(fill(activity.progress))}`)}${attr_style("", { width: `${stringify(num(activity.progress))}%` })}></span></span></span></a></li>`);
					}
				} else $$renderer.push(`<!--[!--><li class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted md:col-span-2 xl:col-span-3">Este contrato aún no tiene actividades registradas. La Alcaldía las registra al
            formalizar el contrato.</li>`);
				$$renderer.push(`<!--]--></ul></section>`);
			}
		} else $$renderer.push(`<!--[!--><p class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">No tiene contratos en ejecución. Cuando la Alcaldía active su contrato, aquí verá sus
      actividades.</p>`);
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
export { _page as default };
