import { C as attr, T as escape_html, s as ensure_array_like, t as attr_class } from "./server.js";
import { t as page } from "./state.js";
//#region src/lib/components/ui/Tabs.svelte
function Tabs($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/** Pestañas de navegación entre pantallas hermanas (por ejemplo, Roles y Usuarios). */
		let { label, items } = $$props;
		$$renderer.push(`<nav${attr("aria-label", label)} class="mb-6"><ul class="inline-flex gap-1 rounded-xl border border-border bg-surface p-1"><!--[-->`);
		const each_array = ensure_array_like(items);
		for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
			let item = each_array[$$index];
			const current = page.url.pathname.startsWith(item.href);
			$$renderer.push(`<li><a${attr("href", item.href)}${attr("aria-current", current ? "page" : void 0)}${attr_class(`block rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-150 ${current ? "bg-primary-soft text-primary shadow-card" : "text-muted hover:bg-white/5 hover:text-ink"}`)}>${escape_html(item.label)}</a></li>`);
		}
		$$renderer.push(`<!--]--></ul></nav>`);
	});
}
//#endregion
export { Tabs as t };
