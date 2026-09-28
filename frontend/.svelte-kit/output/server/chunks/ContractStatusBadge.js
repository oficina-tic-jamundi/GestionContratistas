import { T as escape_html } from "./server.js";
import { t as Badge } from "./Badge.js";
//#region src/lib/features/contracts/ContractStatusBadge.svelte
function ContractStatusBadge($$renderer, $$props) {
	/** El texto del estado siempre es visible: el color solo lo refuerza. */
	let { status, label } = $$props;
	Badge($$renderer, {
		tone: {
			draft: "neutral",
			active: "success",
			suspended: "warning",
			terminated: "info",
			liquidated: "info",
			archived: "neutral"
		}[status],
		children: ($$renderer) => {
			$$renderer.push(`<!---->${escape_html(label)}`);
		},
		$$slots: { default: true }
	});
}
//#endregion
export { ContractStatusBadge as t };
