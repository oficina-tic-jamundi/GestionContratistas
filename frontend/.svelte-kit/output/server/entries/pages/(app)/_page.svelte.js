import { C as attr, T as escape_html, d as stringify, i as await_block, n as attr_style, o as derived, s as ensure_array_like, t as attr_class, w as clsx } from "../../../chunks/server.js";
import { t as resolve } from "../../../chunks/paths.js";
import { t as Permission } from "../../../chunks/permissions.js";
import { t as Badge } from "../../../chunks/Badge.js";
import { t as Icon } from "../../../chunks/Icon.js";
import "../../../chunks/api.js";
import { t as session } from "../../../chunks/session.svelte.js";
import { t as exportUrl } from "../../../chunks/api2.js";
import { a as formatMoney, i as formatDateTime, r as formatDate } from "../../../chunks/format.js";
import { t as Card } from "../../../chunks/Card.js";
import { t as Alert } from "../../../chunks/Alert.js";
import { t as PageHeader } from "../../../chunks/PageHeader.js";
import { t as PriorityBadge } from "../../../chunks/PriorityBadge.js";
import { i as getBudget } from "../../../chunks/api3.js";
import { t as DownloadLink } from "../../../chunks/DownloadLink.js";
//#region src/lib/components/ui/StatCard.svelte
function StatCard($$renderer, $$props) {
	/**
	* Tarjeta de estadística: un dato importante, grande y legible de un vistazo.
	* El color solo refuerza; el significado siempre está en el texto.
	*/
	let { label, value, unit, hint, icon, tone = "primary", href, progress } = $$props;
	const tones = {
		primary: {
			chip: "bg-primary-soft text-primary",
			bar: "bg-primary"
		},
		info: {
			chip: "bg-info-soft text-info",
			bar: "bg-info"
		},
		success: {
			chip: "bg-success-soft text-success",
			bar: "bg-success"
		},
		warning: {
			chip: "bg-warning-soft text-warning",
			bar: "bg-warning"
		},
		attention: {
			chip: "bg-attention-soft text-attention",
			bar: "bg-attention"
		},
		danger: {
			chip: "bg-danger-soft text-danger",
			bar: "bg-danger"
		},
		neutral: {
			chip: "bg-canvas text-muted",
			bar: "bg-border-strong"
		}
	};
	const pct = derived(() => Math.max(0, Math.min(100, progress ?? 0)));
	function body($$renderer) {
		$$renderer.push(`<div class="flex items-start justify-between gap-3"><p class="label-eyebrow text-balance">${escape_html(label)}</p> `);
		if (icon) {
			$$renderer.push(`<!--[0--><span${attr_class(`grid size-9 shrink-0 place-items-center rounded-xl ${stringify(tones[tone].chip)}`)} aria-hidden="true">`);
			Icon($$renderer, {
				name: icon,
				class: "size-5"
			});
			$$renderer.push(`<!----></span>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div> <p class="mt-auto flex items-baseline gap-1.5 pt-3"><span class="value-kpi">${escape_html(value)}</span> `);
		if (unit) $$renderer.push(`<!--[0--><span class="text-sm font-medium text-muted">${escape_html(unit)}</span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></p> `);
		if (progress !== void 0) $$renderer.push(`<!--[0--><span class="mt-3 block h-1.5 overflow-hidden rounded-full bg-canvas" role="progressbar"${attr("aria-label", label)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", pct())}${attr("aria-valuetext", `${stringify(pct().toLocaleString("es-CO", { maximumFractionDigits: 0 }))} %`)}><span${attr_class(`block h-full rounded-full ${stringify(tones[tone].bar)}`)}${attr_style("", { width: `${stringify(pct())}%` })}></span></span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (hint) $$renderer.push(`<!--[0--><p class="mt-2 text-xs text-muted">${escape_html(hint)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]-->`);
	}
	if (href) {
		$$renderer.push(`<!--[0--><a${attr("href", href)} class="card-interactive flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card">`);
		body($$renderer);
		$$renderer.push(`<!----></a>`);
	} else {
		$$renderer.push(`<!--[-1--><div class="flex h-full flex-col rounded-2xl border border-border bg-surface p-5 shadow-card">`);
		body($$renderer);
		$$renderer.push(`<!----></div>`);
	}
	$$renderer.push(`<!--]-->`);
}
//#endregion
//#region src/lib/features/dashboard/DeadlineAlerts.svelte
function DeadlineAlerts($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Alertas preventivas de entrega (ADR-021): tareas con fecha objetivo próxima o vencida.
		* La fecha la registra la Alcaldía al planear la tarea; SIGCON no inventa plazos.
		*/
		let { tasks, withinDays, showContractor = false } = $$props;
		const overdue = derived(() => tasks.filter((t) => t.days_left < 0));
		const soon = derived(() => tasks.filter((t) => t.days_left >= 0));
		function whenText(task) {
			if (task.days_left < 0) {
				const days = Math.abs(task.days_left);
				return `venció hace ${days} ${days === 1 ? "día" : "días"}`;
			}
			if (task.days_left === 0) return "vence hoy";
			return `quedan ${task.days_left} ${task.days_left === 1 ? "día" : "días"}`;
		}
		if (tasks.length > 0) {
			$$renderer.push(`<!--[0--><section${attr_class(`rounded-2xl border px-5 py-4 ${overdue().length > 0 ? "border-danger/40 bg-danger-soft" : "border-warning/40 bg-warning-soft"}`)} aria-labelledby="alertas-entrega" role="status"><p${attr_class(`flex items-center gap-2 font-semibold ${overdue().length > 0 ? "text-danger" : "text-warning"}`)}>`);
			Icon($$renderer, {
				name: "alert",
				class: "size-5 shrink-0"
			});
			$$renderer.push(`<!----> <span id="alertas-entrega">`);
			if (overdue().length > 0) $$renderer.push(`<!--[0-->Entregas vencidas`);
			else $$renderer.push(`<!--[-1-->Entregas próximas`);
			$$renderer.push(`<!--]--></span></p> <p class="mt-1 text-sm text-muted">Tareas con fecha objetivo vencida o dentro de los próximos ${escape_html(withinDays)} días.</p> <ul class="mt-3 space-y-1.5 text-sm"><!--[-->`);
			const each_array = ensure_array_like([...overdue(), ...soon()]);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let task = each_array[$$index];
				$$renderer.push(`<li class="flex flex-wrap items-baseline gap-x-2"><span class="font-medium text-ink">${escape_html(task.title)}</span> <span${attr_class(clsx(task.days_left < 0 ? "font-semibold text-danger" : "font-semibold text-warning"))}>— ${escape_html(whenText(task))}</span> <span class="text-muted">(${escape_html(formatDate(task.due_date))})</span> <a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: task.contract_uuid }))} class="text-primary hover:underline">${escape_html(task.contract_number)}</a> `);
				if (showContractor) $$renderer.push(`<!--[0--><span class="text-muted">· ${escape_html(task.contractor)}</span>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></li>`);
			}
			$$renderer.push(`<!--]--></ul></section>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]-->`);
	});
}
//#endregion
//#region src/lib/features/dashboard/ContractorDashboard.svelte
function ContractorDashboard($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Panel del contratista (ADR-021): avance de cada contrato, estado de obligaciones y
		* actividades derivado de los informes, observaciones vigentes y el aviso de pago según las
		* reglas activas. El estado siempre se expresa con texto; el color solo lo refuerza.
		*/
		let { dashboard } = $$props;
		const contracts = derived(() => dashboard.my_contracts ?? []);
		const pendingReports = derived(() => dashboard.reports?.awaiting_my_action ?? []);
		const STATUS = {
			approved: {
				text: "text-success",
				soft: "bg-success-soft border-success/30",
				dot: "bg-success",
				bar: "bg-success"
			},
			in_review: {
				text: "text-warning",
				soft: "bg-warning-soft border-warning/30",
				dot: "bg-warning",
				bar: "bg-warning"
			},
			observed: {
				text: "text-attention",
				soft: "bg-attention-soft border-attention/30",
				dot: "bg-attention",
				bar: "bg-attention"
			},
			pending: {
				text: "text-danger",
				soft: "bg-danger-soft border-danger/30",
				dot: "bg-danger",
				bar: "bg-danger"
			}
		};
		const STATUS_ORDER = [
			{
				key: "approved",
				label: "Aprobadas",
				icon: "check-list",
				tone: "success"
			},
			{
				key: "in_review",
				label: "En revisión",
				icon: "clock",
				tone: "warning"
			},
			{
				key: "observed",
				label: "Con observaciones",
				icon: "alert",
				tone: "attention"
			},
			{
				key: "pending",
				label: "Pendientes",
				icon: "file",
				tone: "neutral"
			}
		];
		const num = (v) => Math.max(0, Math.min(100, Number(v) || 0));
		const pct = (v) => `${num(v).toLocaleString("es-CO", { maximumFractionDigits: 1 })} %`;
		const progressTone = (v) => num(v) >= 100 ? "text-success" : num(v) >= 50 ? "text-primary" : "text-warning";
		const progressFill = (v) => num(v) >= 100 ? "from-[#34d399] to-[#10b981]" : num(v) >= 50 ? "from-[#10b981] to-[#34d399]" : "from-[#f59e0b] to-[#eab308]";
		function reportAlert(report) {
			return report.status === "observed" ? {
				title: "Informe con observaciones",
				text: `El supervisor pidió correcciones al informe N.° ${report.number} del contrato ${report.contract_number}. Corríjalo y reenvíelo.`,
				tone: "border-danger/40 bg-danger-soft text-danger"
			} : {
				title: "Informe sin enviar",
				text: `El informe N.° ${report.number} del contrato ${report.contract_number} está en borrador. Envíelo al supervisor cuando esté completo.`,
				tone: "border-warning/40 bg-warning-soft text-warning"
			};
		}
		const CIRCUMFERENCE = 2 * Math.PI * 52;
		function statusPill($$renderer, status, label) {
			$$renderer.push(`<span${attr_class(`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-medium ${stringify(STATUS[status].soft)} ${stringify(STATUS[status].text)}`)}><span${attr_class(`size-1.5 rounded-full ${stringify(STATUS[status].dot)}`)} aria-hidden="true"></span> ${escape_html(label)}</span>`);
		}
		function overview($$renderer, item) {
			const counts = item.counts.obligations;
			const items = item.counts.items;
			$$renderer.push(`<section class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"${attr("aria-labelledby", `progreso-${stringify(item.contract.uuid)}`)}><div class="flex flex-wrap items-start justify-between gap-4"><div class="min-w-0"><h2${attr("id", `progreso-${stringify(item.contract.uuid)}`)} class="text-lg font-semibold text-ink">Progreso general del contrato</h2> <p class="mt-0.5 text-sm text-muted"><a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: item.contract.uuid }))} class="text-primary hover:underline">${escape_html(item.contract.contract_number)}</a> · ${escape_html(item.contract.department)} `);
			if (item.contract.status !== "active") $$renderer.push(`<!--[0-->· <span class="text-warning">${escape_html(item.contract.status_label)}</span>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></p></div> <div class="text-right"><p${attr_class(`text-4xl font-bold tabular-nums ${stringify(progressTone(item.progress))}`)}>${escape_html(pct(item.progress))}</p> <p class="text-xs text-muted">completado</p></div></div> <div class="mt-4 h-3 overflow-hidden rounded-full bg-canvas ring-1 ring-border" role="progressbar"${attr("aria-label", `Avance del contrato ${stringify(item.contract.contract_number)}`)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", num(item.progress))}${attr("aria-valuetext", pct(item.progress))}><div${attr_class(`h-full rounded-full bg-linear-to-r ${stringify(progressFill(item.progress))}`)}${attr_style("", { width: `${stringify(num(item.progress))}%` })}></div></div> `);
			if (item.payment_notice) {
				$$renderer.push("<!--[0-->");
				const missing = Math.max(0, item.payment_notice.required - num(item.progress));
				$$renderer.push(`<div${attr_class(`mt-5 flex items-center gap-4 rounded-xl border p-4 ${item.payment_notice.met ? "border-success/40 bg-success-soft" : "border-danger/40 bg-danger-soft"}`)} role="status"><span${attr_class(`inline-flex size-10 shrink-0 items-center justify-center rounded-xl ${item.payment_notice.met ? "bg-success/15 text-success" : "bg-danger/15 text-danger"}`)} aria-hidden="true">`);
				Icon($$renderer, { name: item.payment_notice.met ? "shield" : "lock" });
				$$renderer.push(`<!----></span> <div class="min-w-0 flex-1">`);
				if (item.payment_notice.met) $$renderer.push(`<!--[0--><p class="font-semibold text-success">Avance suficiente para pago</p> <p class="text-sm text-ink">La Alcaldía exige <strong>${escape_html(item.payment_notice.required)} %</strong> de avance para habilitar
              el pago, y usted ya lo cumple. El pago además depende de las demás reglas y de un informe
              aprobado.</p>`);
				else $$renderer.push(`<!--[-1--><p class="font-semibold text-danger">Pago bloqueado</p> <p class="text-sm text-ink">La Alcaldía exige <strong>${escape_html(item.payment_notice.required)} %</strong> de avance para
              habilitar el pago. Faltan <strong class="text-danger">${escape_html(pct(missing))}</strong>.</p>`);
				$$renderer.push(`<!--]--></div></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></section> <ul class="stagger grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-5"><li class="col-span-2 lg:col-span-1">`);
			StatCard($$renderer, {
				label: "Obligaciones",
				value: counts.total,
				icon: "clipboard",
				tone: "primary"
			});
			$$renderer.push(`<!----></li> <!--[-->`);
			const each_array = ensure_array_like(STATUS_ORDER);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let s = each_array[$$index];
				$$renderer.push(`<li>`);
				StatCard($$renderer, {
					label: s.label,
					value: counts[s.key],
					icon: s.icon,
					tone: s.tone
				});
				$$renderer.push(`<!----></li>`);
			}
			$$renderer.push(`<!--]--></ul> <section class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"${attr("aria-labelledby", `avance-${stringify(item.contract.uuid)}`)}><div class="flex flex-wrap items-baseline justify-between gap-2"><h2${attr("id", `avance-${stringify(item.contract.uuid)}`)} class="text-lg font-semibold text-ink">Avance por obligación</h2> <p class="text-sm text-muted">${escape_html(items.approved)} de ${escape_html(items.total)} actividades aprobadas</p></div> `);
			if (item.general_observations.length > 0) {
				$$renderer.push(`<!--[0--><div class="mt-4 rounded-xl border border-attention/30 bg-attention-soft px-4 py-3 text-sm"><!--[-->`);
				const each_array_1 = ensure_array_like(item.general_observations);
				for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
					let text = each_array_1[i];
					$$renderer.push(`<p><span class="font-semibold text-attention">Observación general:</span> <span class="text-ink">${escape_html(text)}</span></p>`);
				}
				$$renderer.push(`<!--]--></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <ul class="mt-4 space-y-6">`);
			const each_array_2 = ensure_array_like(item.obligations);
			if (each_array_2.length !== 0) {
				$$renderer.push("<!--[-->");
				for (let $$index_4 = 0, $$length = each_array_2.length; $$index_4 < $$length; $$index_4++) {
					let obligation = each_array_2[$$index_4];
					$$renderer.push(`<li><div class="flex flex-wrap items-center justify-between gap-2"><p class="flex min-w-0 flex-wrap items-center gap-2 font-medium text-ink">${escape_html(obligation.title)} `);
					if (obligation.priority && obligation.priority_label) {
						$$renderer.push("<!--[0-->");
						PriorityBadge($$renderer, {
							priority: obligation.priority,
							label: obligation.priority_label
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></p> <p class="flex items-center gap-2">`);
					statusPill($$renderer, obligation.status, obligation.status_label);
					$$renderer.push(`<!----> <span${attr_class(`w-14 text-right text-sm font-semibold tabular-nums ${stringify(STATUS[obligation.status].text)}`)}>${escape_html(pct(obligation.progress))}</span></p></div> <div class="mt-2 h-2 overflow-hidden rounded-full bg-canvas" role="progressbar"${attr("aria-label", `Avance de ${stringify(obligation.title)}`)}${attr("aria-valuemin", 0)}${attr("aria-valuemax", 100)}${attr("aria-valuenow", num(obligation.progress))}${attr("aria-valuetext", pct(obligation.progress))}><div${attr_class(`h-full rounded-full bg-linear-to-r ${stringify(progressFill(obligation.progress))}`)}${attr_style("", { width: `${stringify(num(obligation.progress))}%` })}></div></div> `);
					if (obligation.items.length > 0) {
						$$renderer.push(`<!--[0--><ul class="mt-2 flex flex-wrap gap-2"${attr("aria-label", `Actividades de ${stringify(obligation.title)}`)}><!--[-->`);
						const each_array_3 = ensure_array_like(obligation.items);
						for (let $$index_2 = 0, $$length = each_array_3.length; $$index_2 < $$length; $$index_2++) {
							let activity = each_array_3[$$index_2];
							$$renderer.push(`<li${attr_class(`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs ${stringify(STATUS[activity.status].soft)} ${stringify(STATUS[activity.status].text)}`)}><span${attr_class(`size-1.5 rounded-full ${stringify(STATUS[activity.status].dot)}`)} aria-hidden="true"></span> ${escape_html(activity.title)} <span class="sr-only">: ${escape_html(activity.status_label)}, ${escape_html(pct(activity.progress))}</span> `);
							if (activity.priority_label) $$renderer.push(`<!--[0--><span class="sr-only">, prioridad ${escape_html(activity.priority_label.toLowerCase())}</span>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></li>`);
						}
						$$renderer.push(`<!--]--></ul>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> <!--[-->`);
					const each_array_4 = ensure_array_like(obligation.observations);
					for (let i = 0, $$length = each_array_4.length; i < $$length; i++) {
						let text = each_array_4[i];
						$$renderer.push(`<p class="mt-2 rounded-xl border border-attention/30 bg-attention-soft px-4 py-2.5 text-sm"><span class="font-semibold text-attention">Observación del supervisor:</span> <span class="text-ink">${escape_html(text)}</span></p>`);
					}
					$$renderer.push(`<!--]--></li>`);
				}
			} else $$renderer.push(`<!--[!--><li class="text-sm text-muted">Este contrato aún no tiene obligaciones registradas. Solicite a la dependencia que las
          registre.</li>`);
			$$renderer.push(`<!--]--></ul></section> <div class="grid gap-4 lg:grid-cols-3 lg:gap-6"><section class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6 lg:col-span-2"${attr("aria-labelledby", `estadisticas-${stringify(item.contract.uuid)}`)}><h2${attr("id", `estadisticas-${stringify(item.contract.uuid)}`)} class="text-lg font-semibold text-ink">Estadísticas detalladas</h2> <div class="mt-4 grid items-center gap-6 sm:grid-cols-[1fr_auto]"><div><p class="mb-3 text-xs font-semibold tracking-wider text-muted uppercase">Estado de actividades</p> <dl class="space-y-3"><!--[-->`);
			const each_array_5 = ensure_array_like(STATUS_ORDER);
			for (let $$index_5 = 0, $$length = each_array_5.length; $$index_5 < $$length; $$index_5++) {
				let s = each_array_5[$$index_5];
				$$renderer.push(`<div class="flex items-center gap-3"><dt class="flex flex-1 items-center gap-2 text-sm text-ink"><span${attr_class(`size-2 rounded-full ${stringify(STATUS[s.key].dot)}`)} aria-hidden="true"></span> ${escape_html(s.label)}</dt> <dd class="flex items-center gap-3"><span class="h-1.5 w-24 overflow-hidden rounded-full bg-canvas sm:w-32" aria-hidden="true"><span${attr_class(`block h-full rounded-full ${stringify(STATUS[s.key].bar)}`)}${attr_style("", { width: `${stringify(items.total === 0 ? 0 : items[s.key] / items.total * 100)}%` })}></span></span> <span${attr_class(`w-6 text-right font-semibold tabular-nums ${stringify(STATUS[s.key].text)}`)}>${escape_html(items[s.key])}</span></dd></div>`);
			}
			$$renderer.push(`<!--]--></dl></div> <figure class="flex flex-col items-center"><svg viewBox="0 0 120 120" class="size-36" role="img"${attr("aria-label", `Avance del contrato: ${stringify(pct(item.progress))}`)}><circle cx="60" cy="60" r="52" fill="none" stroke="var(--color-border)" stroke-width="12"></circle><circle cx="60" cy="60" r="52" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round"${attr("stroke-dasharray", CIRCUMFERENCE)}${attr("stroke-dashoffset", CIRCUMFERENCE * (1 - num(item.progress) / 100))} transform="rotate(-90 60 60)"${attr_class(clsx(progressTone(item.progress)))}></circle><text x="60" y="60" text-anchor="middle" dominant-baseline="central"${attr_class(`fill-current text-[22px] font-bold ${stringify(progressTone(item.progress))}`)}>${escape_html(Math.round(num(item.progress)))}%</text></svg> <figcaption class="mt-2 text-center text-sm text-muted">Progreso global<br/>del contrato</figcaption></figure></div></section> <section class="rounded-2xl border border-border bg-surface p-5 shadow-lg shadow-black/20 sm:p-6"${attr("aria-labelledby", `pagos-${stringify(item.contract.uuid)}`)}><h2${attr("id", `pagos-${stringify(item.contract.uuid)}`)} class="text-lg font-semibold text-ink">Pagos</h2> `);
			await_block($$renderer, getBudget(item.contract.uuid), () => {
				$$renderer.push(`<p class="mt-4 text-sm text-muted" role="status">Consultando…</p>`);
			}, (budget) => {
				$$renderer.push(`<dl class="mt-4 space-y-2 text-sm"><div class="flex justify-between gap-3"><dt class="text-muted">Valor del contrato</dt> <dd class="font-medium text-ink tabular-nums">${escape_html(formatMoney(budget.total))}</dd></div> <div class="flex justify-between gap-3"><dt class="text-muted">Pagado</dt> <dd class="font-medium text-success tabular-nums">${escape_html(formatMoney(budget.paid))}</dd></div> <div class="flex justify-between gap-3"><dt class="text-muted">En trámite</dt> <dd class="font-medium text-ink tabular-nums">${escape_html(formatMoney(String(Number(budget.committed) - Number(budget.paid))))}</dd></div> <div class="flex justify-between gap-3 border-t border-border pt-2"><dt class="text-muted">Saldo por pagar</dt> <dd class="font-semibold text-ink tabular-nums">${escape_html(formatMoney(budget.available))}</dd></div></dl>`);
			});
			$$renderer.push(`<!--]--> <p class="mt-4 text-xs text-muted">Termina el ${escape_html(formatDate(item.contract.end_date))}.</p> <a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: item.contract.uuid }))} class="mt-3 inline-block text-sm font-medium text-primary hover:underline">Ver pagos y detalle del contrato</a></section></div>`);
		}
		$$renderer.push(`<div class="space-y-6">`);
		if (dashboard.deadlines) {
			$$renderer.push("<!--[0-->");
			DeadlineAlerts($$renderer, {
				tasks: dashboard.deadlines.tasks,
				withinDays: dashboard.deadlines.within_days
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <!--[-->`);
		const each_array_6 = ensure_array_like(pendingReports());
		for (let $$index_6 = 0, $$length = each_array_6.length; $$index_6 < $$length; $$index_6++) {
			let report = each_array_6[$$index_6];
			const alert = reportAlert(report);
			$$renderer.push(`<a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: report.uuid }))}${attr_class(`flex items-start gap-3 rounded-2xl border px-5 py-4 transition hover:brightness-110 ${stringify(alert.tone)}`)}>`);
			Icon($$renderer, {
				name: "alert",
				class: "mt-0.5 size-5 shrink-0"
			});
			$$renderer.push(`<!----> <span><span class="block font-semibold">${escape_html(alert.title)}</span> <span class="block text-sm text-ink">${escape_html(alert.text)}</span></span></a>`);
		}
		$$renderer.push(`<!--]--> `);
		const each_array_7 = ensure_array_like(contracts());
		if (each_array_7.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let $$index_7 = 0, $$length = each_array_7.length; $$index_7 < $$length; $$index_7++) {
				let item = each_array_7[$$index_7];
				$$renderer.push(`<div class="space-y-4 sm:space-y-6">`);
				overview($$renderer, item);
				$$renderer.push(`<!----></div>`);
			}
		} else $$renderer.push(`<!--[!--><div class="rounded-2xl border border-border bg-surface p-6 text-sm text-muted">No tiene contratos en ejecución. Cuando la Alcaldía active su contrato, aquí verá su avance.</div>`);
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
//#region src/lib/features/dashboard/DashboardStats.svelte
function DashboardStats($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Cifras de cabecera del tablero: lo primero que se mira al entrar. Salen del mismo
		* tablero que calcula el backend según el alcance de cada persona (ADR-019).
		*/
		let { dashboard } = $$props;
		const stats = derived(() => {
			const contracts = Object.fromEntries((dashboard.contracts?.by_status ?? []).map((s) => [s.status, s.count]));
			const reports = Object.fromEntries((dashboard.reports?.by_status ?? []).map((s) => [s.status, s.count]));
			const pendingReview = (reports["submitted"] ?? 0) + (reports["resubmitted"] ?? 0);
			const endingSoon = dashboard.contracts?.ending_soon.length ?? 0;
			const payments = dashboard.payments;
			return [
				{
					label: "Contratos activos",
					value: contracts["active"] ?? 0,
					icon: "clipboard",
					tone: "primary",
					hint: endingSoon > 0 ? `${endingSoon} ${endingSoon === 1 ? "termina" : "terminan"} pronto` : "Ninguno termina pronto"
				},
				{
					label: "Informes por revisar",
					value: pendingReview,
					icon: "file",
					tone: pendingReview > 0 ? "warning" : "success",
					hint: pendingReview > 0 ? "Esperan su decisión" : "Sin cola de revisión"
				},
				{
					label: "Pagos en trámite",
					value: (payments?.to_approve ?? 0) + (payments?.to_register ?? 0),
					icon: "wallet",
					tone: "info",
					hint: `${payments?.to_approve ?? 0} por aprobar · ${payments?.to_register ?? 0} por pagar`
				},
				{
					label: "Con observaciones",
					value: reports["observed"] ?? 0,
					icon: "alert",
					tone: "attention",
					hint: "Devueltos al contratista"
				}
			];
		});
		if (dashboard.contracts || dashboard.reports || dashboard.payments) {
			$$renderer.push(`<!--[0--><ul class="stagger mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><!--[-->`);
			const each_array = ensure_array_like(stats());
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let stat = each_array[$$index];
				$$renderer.push(`<li class="h-full">`);
				StatCard($$renderer, {
					label: stat.label,
					value: stat.value,
					icon: stat.icon,
					tone: stat.tone,
					hint: stat.hint
				});
				$$renderer.push(`<!----></li>`);
			}
			$$renderer.push(`<!--]--></ul>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]-->`);
	});
}
//#endregion
//#region src/lib/components/ui/Skeleton.svelte
function Skeleton($$renderer, $$props) {
	/**
	* Marcador de posición mientras llegan los datos: ocupa el mismo sitio que el contenido
	* real, así la pantalla no salta al cargar. Se anuncia una sola vez a lectores de pantalla.
	*/
	let { lines = 3, label = "Cargando…", card = true } = $$props;
	const widths = [
		"w-2/3",
		"w-full",
		"w-5/6",
		"w-3/4",
		"w-1/2"
	];
	$$renderer.push(`<div${attr_class(clsx(card ? "rounded-2xl border border-border bg-surface p-5 shadow-card" : ""))} role="status" aria-live="polite"><span class="sr-only">${escape_html(label)}</span> <div class="space-y-3" aria-hidden="true"><!--[-->`);
	const each_array = ensure_array_like(Array.from({ length: lines }, (_, i) => i));
	for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
		let line = each_array[$$index];
		$$renderer.push(`<div${attr_class(`skeleton h-4 ${stringify(widths[line % widths.length])}`)}></div>`);
	}
	$$renderer.push(`<!--]--></div></div>`);
}
//#endregion
//#region src/lib/features/notifications/DashboardPanels.svelte
function reportList($$renderer, items, empty) {
	$$renderer.push(`<ul class="divide-y divide-border">`);
	const each_array = ensure_array_like(items);
	if (each_array.length !== 0) {
		$$renderer.push("<!--[-->");
		for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
			let report = each_array[$$index];
			$$renderer.push(`<li><a${attr("href", resolve("/(app)/reports/[uuid]", { uuid: report.uuid }))} class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm hover:text-primary"><span><span class="font-medium">${escape_html(report.contract_number)} · Informe N.° ${escape_html(report.number)}</span> <span class="block text-xs text-muted">${escape_html(report.contractor)} · ${escape_html(report.status_label)} · ${escape_html(formatDateTime(report.updated_at))}</span></span></a></li>`);
		}
	} else $$renderer.push(`<!--[!--><li class="py-2 text-sm text-muted">${escape_html(empty)}</li>`);
	$$renderer.push(`<!--]--></ul>`);
}
function DashboardPanels($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Tablero de inicio: cifras y pendientes calculados por el backend según el alcance. */
		let { dashboard } = $$props;
		const nonZero = (items) => items.filter((i) => i.count > 0);
		/**
		* Semáforo de entregables: agrupa los informes por el estado en que los ve la supervisión
		* (ADR-021). Las cifras son las mismas del backend, solo agrupadas.
		*/
		const SEMAPHORE = [
			{
				label: "Revisados",
				statuses: ["approved"],
				text: "text-success",
				dot: "bg-success",
				soft: "border-success/30 bg-success-soft"
			},
			{
				label: "En revisión",
				statuses: ["in_review"],
				text: "text-warning",
				dot: "bg-warning",
				soft: "border-warning/30 bg-warning-soft"
			},
			{
				label: "Con observaciones",
				statuses: ["observed"],
				text: "text-attention",
				dot: "bg-attention",
				soft: "border-attention/30 bg-attention-soft"
			},
			{
				label: "Pendientes por revisar",
				statuses: ["submitted", "resubmitted"],
				text: "text-danger",
				dot: "bg-danger",
				soft: "border-danger/30 bg-danger-soft"
			}
		];
		const semaphore = derived(() => {
			const byStatus = Object.fromEntries((dashboard.reports?.by_status ?? []).map((s) => [s.status, s.count]));
			return SEMAPHORE.map((group) => ({
				...group,
				count: group.statuses.reduce((total, status) => total + (byStatus[status] ?? 0), 0)
			}));
		});
		function counts($$renderer, items, empty) {
			if (nonZero(items).length > 0) {
				$$renderer.push(`<!--[0--><ul class="flex flex-wrap gap-2 text-sm"><!--[-->`);
				const each_array_1 = ensure_array_like(nonZero(items));
				for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
					let item = each_array_1[$$index_1];
					$$renderer.push(`<li class="rounded-md border border-border px-3 py-1.5"><span class="text-muted">${escape_html(item.label)}:</span> <span class="font-semibold">${escape_html(item.count)}</span></li>`);
				}
				$$renderer.push(`<!--]--></ul>`);
			} else $$renderer.push(`<!--[-1--><p class="text-sm text-muted">${escape_html(empty)}</p>`);
			$$renderer.push(`<!--]-->`);
		}
		$$renderer.push(`<div class="space-y-6">`);
		if (dashboard.deadlines) {
			$$renderer.push("<!--[0-->");
			DeadlineAlerts($$renderer, {
				tasks: dashboard.deadlines.tasks,
				withinDays: dashboard.deadlines.within_days,
				showContractor: true
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (dashboard.reports) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				title: "Estado de los entregables",
				description: "Informes por estado de revisión.",
				children: ($$renderer) => {
					$$renderer.push(`<ul class="grid grid-cols-2 gap-3 sm:grid-cols-4"><!--[-->`);
					const each_array_2 = ensure_array_like(semaphore());
					for (let $$index_2 = 0, $$length = each_array_2.length; $$index_2 < $$length; $$index_2++) {
						let group = each_array_2[$$index_2];
						$$renderer.push(`<li${attr_class(`rounded-xl border p-4 ${stringify(group.soft)}`)}><p${attr_class(`text-3xl font-bold tabular-nums ${stringify(group.text)}`)}>${escape_html(group.count)}</p> <p class="mt-1 flex items-center gap-1.5 text-sm text-muted"><span${attr_class(`size-2 shrink-0 rounded-full ${stringify(group.dot)}`)} aria-hidden="true"></span> ${escape_html(group.label)}</p></li>`);
					}
					$$renderer.push(`<!--]--></ul>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (dashboard.system && (dashboard.system.jobs_stale || dashboard.system.jobs_failed > 0)) {
			$$renderer.push("<!--[0-->");
			Alert($$renderer, {
				variant: "danger",
				children: ($$renderer) => {
					if (dashboard.system.jobs_stale) $$renderer.push(`<!--[0-->El procesador de tareas en segundo plano (Cron) no se está
        ejecutando.`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (dashboard.system.jobs_failed > 0) $$renderer.push(`<!--[0-->Hay ${escape_html(dashboard.system.jobs_failed)} tarea(s) fallida(s).`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> <a class="font-medium underline"${attr("href", resolve("/jobs"))}>Revisar tareas programadas</a>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (dashboard.reports?.awaiting_my_action || dashboard.reports?.awaiting_my_review || dashboard.payments?.to_approve || dashboard.payments?.to_register) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				title: "Pendientes",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-4">`);
					if (dashboard.reports?.awaiting_my_action) {
						$$renderer.push(`<!--[0--><section><h3 class="text-sm font-semibold text-ink">Informes por completar o corregir</h3> `);
						reportList($$renderer, dashboard.reports.awaiting_my_action, "No tiene informes pendientes.");
						$$renderer.push(`<!----></section>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (dashboard.reports?.awaiting_my_review) {
						$$renderer.push(`<!--[0--><section><h3 class="text-sm font-semibold text-ink">Informes por revisar</h3> `);
						reportList($$renderer, dashboard.reports.awaiting_my_review, "No hay informes esperando su revisión.");
						$$renderer.push(`<!----></section>`);
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (dashboard.payments?.to_approve) $$renderer.push(`<!--[0--><a class="block rounded-md border border-border p-3 text-sm hover:border-primary"${attr("href", resolve("/payments?status=ready_for_approval"))}><span class="font-semibold">${escape_html(dashboard.payments.to_approve)}</span> pago(s) listos para aprobación</a>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					if (dashboard.payments?.to_register) $$renderer.push(`<!--[0--><a class="block rounded-md border border-border p-3 text-sm hover:border-primary"${attr("href", resolve("/payments?status=approved"))}><span class="font-semibold">${escape_html(dashboard.payments.to_register)}</span> pago(s) aprobados por
            registrar como pagados</a>`);
					else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--></div>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (dashboard.contracts) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				title: "Contratos",
				children: ($$renderer) => {
					$$renderer.push(`<div class="space-y-4">`);
					counts($$renderer, dashboard.contracts.by_status, "No hay contratos en su alcance.");
					$$renderer.push(`<!----> <section><h3 class="text-sm font-semibold text-ink">Terminan en los próximos ${escape_html(dashboard.contracts.ending_within_days)} días</h3> <ul class="divide-y divide-border">`);
					const each_array_3 = ensure_array_like(dashboard.contracts.ending_soon);
					if (each_array_3.length !== 0) {
						$$renderer.push("<!--[-->");
						for (let $$index_3 = 0, $$length = each_array_3.length; $$index_3 < $$length; $$index_3++) {
							let contract = each_array_3[$$index_3];
							$$renderer.push(`<li><a${attr("href", resolve("/(app)/contracts/[uuid]", { uuid: contract.uuid }))} class="flex flex-wrap justify-between gap-2 py-2 text-sm hover:text-primary"><span class="font-medium">${escape_html(contract.contract_number)}</span> <span class="text-muted">${escape_html(contract.contractor)} · termina ${escape_html(formatDate(contract.end_date))}</span></a></li>`);
						}
					} else $$renderer.push(`<!--[!--><li class="py-2 text-sm text-muted">Ningún contrato activo termina en ese plazo.</li>`);
					$$renderer.push(`<!--]--></ul></section></div>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (dashboard.reports || dashboard.payments) {
			$$renderer.push(`<!--[0--><div class="grid gap-6 sm:grid-cols-2">`);
			if (dashboard.reports) {
				$$renderer.push("<!--[0-->");
				Card($$renderer, {
					title: "Informes",
					children: ($$renderer) => {
						counts($$renderer, dashboard.reports.by_status, "Aún no hay informes.");
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (dashboard.payments) {
				$$renderer.push("<!--[0-->");
				Card($$renderer, {
					title: "Pagos",
					children: ($$renderer) => {
						if (dashboard.payments.by_status.some((p) => p.count > 0)) {
							$$renderer.push(`<!--[0--><dl class="space-y-1 text-sm"><!--[-->`);
							const each_array_4 = ensure_array_like(dashboard.payments.by_status.filter((p) => p.count > 0));
							for (let $$index_4 = 0, $$length = each_array_4.length; $$index_4 < $$length; $$index_4++) {
								let item = each_array_4[$$index_4];
								$$renderer.push(`<div class="flex justify-between gap-2"><dt class="text-muted">${escape_html(item.label)} (${escape_html(item.count)})</dt> <dd class="font-medium">${escape_html(formatMoney(item.amount))}</dd></div>`);
							}
							$$renderer.push(`<!--]--></dl>`);
						} else $$renderer.push(`<!--[-1--><p class="text-sm text-muted">Aún no hay pagos.</p>`);
						$$renderer.push(`<!--]-->`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (session.can(Permission.ExportsRun)) {
			$$renderer.push("<!--[0-->");
			Card($$renderer, {
				title: "Reportes",
				description: "Archivos CSV para Excel, dentro de su alcance. Cada descarga queda en la auditoría.",
				children: ($$renderer) => {
					$$renderer.push(`<div class="flex flex-wrap gap-2">`);
					DownloadLink($$renderer, {
						href: exportUrl("contracts"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Contratos con avance y saldo`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----> `);
					DownloadLink($$renderer, {
						href: exportUrl("payments"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Pagos`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----></div>`);
				},
				$$slots: { default: true }
			});
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
//#region src/routes/(app)/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const contractorView = derived(() => session.can(Permission.ActivitiesExecute) && !session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned));
		PageHeader($$renderer, {
			title: "Panel de control",
			description: session.user ? `Bienvenido(a), ${session.user.first_name}.` : void 0
		});
		$$renderer.push(`<!----> `);
		if (contractorView()) {
			$$renderer.push("<!--[0-->");
			await_block($$renderer, data.dashboard, () => {
				Skeleton($$renderer, {
					lines: 5,
					label: "Cargando el panel…"
				});
			}, (dashboard) => {
				ContractorDashboard($$renderer, { dashboard });
			});
			$$renderer.push(`<!--]-->`);
		} else {
			$$renderer.push("<!--[-1-->");
			await_block($$renderer, data.dashboard, () => {}, (dashboard) => {
				DashboardStats($$renderer, { dashboard });
			});
			$$renderer.push(`<!--]--> <div class="grid gap-6 lg:grid-cols-3"><div class="space-y-6 lg:col-span-2">`);
			await_block($$renderer, data.dashboard, () => {
				Skeleton($$renderer, {
					lines: 4,
					label: "Cargando el tablero…"
				});
			}, (dashboard) => {
				DashboardPanels($$renderer, { dashboard });
			});
			$$renderer.push(`<!--]--></div> <div class="space-y-6">`);
			Card($$renderer, {
				title: "Estado del sistema",
				children: ($$renderer) => {
					await_block($$renderer, data.health, () => {
						Skeleton($$renderer, {
							lines: 3,
							card: false,
							label: "Consultando el estado…"
						});
					}, (health) => {
						$$renderer.push(`<dl class="space-y-2 text-sm"><div class="flex items-center justify-between"><dt class="text-muted">API</dt> <dd>`);
						Badge($$renderer, {
							tone: health.status === "ok" ? "success" : "warning",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(health.status === "ok" ? "Operativa" : "Degradada")}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></dd></div> <div class="flex items-center justify-between"><dt class="text-muted">Base de datos</dt> <dd>`);
						Badge($$renderer, {
							tone: health.checks.database === "ok" ? "success" : "danger",
							children: ($$renderer) => {
								$$renderer.push(`<!---->${escape_html(health.checks.database === "ok" ? "Conectada" : "No disponible")}`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----></dd></div> <div class="flex items-center justify-between"><dt class="text-muted">Hora del servidor</dt> <dd>${escape_html(formatDateTime(health.server_time))}</dd></div> <div class="flex items-center justify-between"><dt class="text-muted">Versión</dt> <dd class="font-mono">${escape_html(health.version)}</dd></div></dl>`);
					});
					$$renderer.push(`<!--]-->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div></div>`);
		}
		$$renderer.push(`<!--]-->`);
	});
}
//#endregion
export { _page as default };
