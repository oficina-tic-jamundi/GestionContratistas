import { T as escape_html, c as head, o as derived } from "../../../chunks/server.js";
import { t as resolve } from "../../../chunks/paths.js";
import { t as page } from "../../../chunks/state.js";
import { t as Button } from "../../../chunks/Button.js";
//#region src/routes/(app)/+error.svelte
function _error($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const title = derived(() => page.status === 403 ? "Acceso restringido" : page.status === 404 ? "Página no encontrada" : "Ocurrió un error");
		head("mrbob4", $$renderer, ($$renderer) => {
			$$renderer.title(($$renderer) => {
				$$renderer.push(`<title>${escape_html(title())} · SIGCON</title>`);
			});
		});
		$$renderer.push(`<div class="mx-auto max-w-lg py-12 text-center"><p class="text-sm font-medium text-muted">Error ${escape_html(page.status)}</p> <h1 class="mt-2 text-2xl font-semibold text-ink">${escape_html(title())}</h1> <p class="mt-3 text-sm text-muted">${escape_html(page.error?.message ?? "No fue posible mostrar esta página.")}</p> <div class="mt-6">`);
		Button($$renderer, {
			href: resolve("/"),
			variant: "secondary",
			children: ($$renderer) => {
				$$renderer.push(`<!---->Volver al inicio`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----></div></div>`);
	});
}
//#endregion
export { _error as default };
