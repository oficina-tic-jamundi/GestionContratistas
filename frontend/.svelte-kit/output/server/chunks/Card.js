import { T as escape_html, t as attr_class, w as clsx } from "./server.js";
import { t as Icon } from "./Icon.js";
//#region src/lib/components/ui/Card.svelte
function Card($$renderer, $$props) {
	/**
	* Panel de contenido. Es la unidad visual de todas las pantallas: mismo radio, mismo borde
	* y misma sombra, para que la interfaz se lea como un solo sistema.
	*/
	let { title, description, icon, children, actions, padded = true } = $$props;
	$$renderer.push(`<section class="rounded-2xl border border-border bg-surface shadow-card">`);
	if (title) {
		$$renderer.push(`<!--[0--><header class="flex items-start justify-between gap-3 border-b border-border px-5 py-4"><div class="flex min-w-0 items-start gap-3">`);
		if (icon) {
			$$renderer.push(`<!--[0--><span class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary" aria-hidden="true">`);
			Icon($$renderer, {
				name: icon,
				class: "size-5"
			});
			$$renderer.push(`<!----></span>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <div class="min-w-0"><h2 class="title-section">${escape_html(title)}</h2> `);
		if (description) $$renderer.push(`<!--[0--><p class="mt-0.5 text-xs text-muted">${escape_html(description)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div> `);
		if (actions) {
			$$renderer.push(`<!--[0--><div class="shrink-0">`);
			actions($$renderer);
			$$renderer.push(`<!----></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></header>`);
	} else $$renderer.push("<!--[-1-->");
	$$renderer.push(`<!--]--> <div${attr_class(clsx(padded ? "p-5" : ""))}>`);
	children($$renderer);
	$$renderer.push(`<!----></div></section>`);
}
//#endregion
export { Card as t };
