import { C as attr, T as escape_html, d as stringify, o as derived, t as attr_class } from "./server.js";
import { t as Icon } from "./Icon.js";
//#region src/lib/components/ui/Alert.svelte
function Alert($$renderer, $$props) {
	let { variant = "info", title, children } = $$props;
	const styles = {
		info: {
			box: "border-info/40 bg-info-soft",
			icon: "alert",
			tint: "text-info"
		},
		success: {
			box: "border-success/40 bg-success-soft",
			icon: "check-list",
			tint: "text-success"
		},
		warning: {
			box: "border-warning/40 bg-warning-soft",
			icon: "alert",
			tint: "text-warning"
		},
		danger: {
			box: "border-danger/40 bg-danger-soft",
			icon: "alert",
			tint: "text-danger"
		}
	};
	const role = derived(() => variant === "danger" || variant === "warning" ? "alert" : "status");
	$$renderer.push(`<div${attr_class(`flex gap-3 rounded-xl border px-4 py-3 text-sm ${stringify(styles[variant].box)}`)}${attr("role", role())}><span${attr_class(`mt-0.5 shrink-0 ${stringify(styles[variant].tint)}`)} aria-hidden="true">`);
	Icon($$renderer, {
		name: styles[variant].icon,
		class: "size-5"
	});
	$$renderer.push(`<!----></span> <div class="min-w-0">`);
	if (title) $$renderer.push(`<!--[0--><p${attr_class(`font-semibold ${stringify(styles[variant].tint)}`)}>${escape_html(title)}</p>`);
	else $$renderer.push("<!--[-1-->");
	$$renderer.push(`<!--]--> <div class="text-ink">`);
	children($$renderer);
	$$renderer.push(`<!----></div></div></div>`);
}
//#endregion
export { Alert as t };
