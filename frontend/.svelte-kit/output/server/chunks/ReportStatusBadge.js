import { T as escape_html } from "./server.js";
import { t as Badge } from "./Badge.js";
//#region src/lib/features/reports/ReportStatusBadge.svelte
function ReportStatusBadge($$renderer, $$props) {
	/** El texto del estado siempre es visible: el color solo lo refuerza. */
	let { status, label } = $$props;
	Badge($$renderer, {
		tone: {
			draft: "neutral",
			submitted: "info",
			in_review: "info",
			observed: "warning",
			resubmitted: "info",
			approved: "success",
			rejected: "danger"
		}[status],
		children: ($$renderer) => {
			$$renderer.push(`<!---->${escape_html(label)}`);
		},
		$$slots: { default: true }
	});
}
//#endregion
export { ReportStatusBadge as t };
