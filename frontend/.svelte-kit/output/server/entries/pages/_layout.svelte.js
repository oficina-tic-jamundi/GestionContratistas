import { T as escape_html, d as stringify, s as ensure_array_like, t as attr_class } from "../../chunks/server.js";
import { t as toasts } from "../../chunks/toasts.svelte.js";
//#region src/lib/components/ui/Toaster.svelte
function Toaster($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const styles = {
			success: "border-success bg-success-soft text-success",
			error: "border-danger bg-danger-soft text-danger",
			info: "border-info bg-info-soft text-info"
		};
		$$renderer.push(`<div class="pointer-events-none fixed inset-x-4 bottom-4 z-50 flex flex-col items-end gap-2 sm:inset-x-auto sm:right-4" aria-live="polite" role="status"><!--[-->`);
		const each_array = ensure_array_like(toasts.items);
		for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
			let toast = each_array[$$index];
			$$renderer.push(`<div${attr_class(`pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-lg border-l-4 bg-deep/90 px-4 py-3 text-sm shadow-lg shadow-black/40 backdrop-blur-xl ${stringify(styles[toast.kind])}`)}><p class="flex-1 text-ink">${escape_html(toast.message)}</p> <button type="button" class="text-muted hover:text-ink" aria-label="Cerrar notificación">×</button></div>`);
		}
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
//#region src/routes/+layout.svelte
function _layout($$renderer, $$props) {
	let { children } = $$props;
	children($$renderer);
	$$renderer.push(`<!----> `);
	Toaster($$renderer, {});
	$$renderer.push(`<!---->`);
}
//#endregion
export { _layout as default };
