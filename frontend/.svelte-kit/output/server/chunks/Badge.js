import { d as stringify, t as attr_class } from "./server.js";
//#region src/lib/components/ui/Badge.svelte
function Badge($$renderer, $$props) {
	let { tone = "neutral", children } = $$props;
	$$renderer.push(`<span${attr_class(`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium ${stringify({
		neutral: "bg-canvas text-muted border-border",
		info: "bg-info-soft text-info border-info/30",
		success: "bg-success-soft text-success border-success/30",
		warning: "bg-warning-soft text-warning border-warning/30",
		danger: "bg-danger-soft text-danger border-danger/30"
	}[tone])}`)}>`);
	children($$renderer);
	$$renderer.push(`<!----></span>`);
}
//#endregion
export { Badge as t };
