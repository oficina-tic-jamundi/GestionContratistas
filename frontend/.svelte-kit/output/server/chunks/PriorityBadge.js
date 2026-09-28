import { T as escape_html } from "./server.js";
import { t as Badge } from "./Badge.js";
//#region src/lib/features/activities/PriorityBadge.svelte
function PriorityBadge($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Prioridad asignada por la Alcaldía (ADR-021). El texto siempre acompaña al color. */
		let { priority, label } = $$props;
		Badge($$renderer, {
			tone: {
				high: "danger",
				medium: "warning",
				low: "info"
			}[priority],
			children: ($$renderer) => {
				$$renderer.push(`<!---->Prioridad ${escape_html(label.toLowerCase())}`);
			},
			$$slots: { default: true }
		});
	});
}
//#endregion
export { PriorityBadge as t };
