import { T as escape_html } from "./server.js";
import { t as Badge } from "./Badge.js";
//#region src/lib/features/payments/PaymentStatusBadge.svelte
function PaymentStatusBadge($$renderer, $$props) {
	/** El texto del estado siempre es visible: el color solo lo refuerza. */
	let { status, label } = $$props;
	Badge($$renderer, {
		tone: {
			draft: "neutral",
			ready_for_approval: "info",
			approved: "warning",
			paid: "success",
			cancelled: "danger"
		}[status],
		children: ($$renderer) => {
			$$renderer.push(`<!---->${escape_html(label)}`);
		},
		$$slots: { default: true }
	});
}
//#endregion
export { PaymentStatusBadge as t };
