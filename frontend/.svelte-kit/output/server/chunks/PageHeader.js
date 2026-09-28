import { T as escape_html, c as head } from "./server.js";
//#region src/lib/components/ui/PageHeader.svelte
function PageHeader($$renderer, $$props) {
	/**
	* Encabezado de pantalla: el único <h1> de la página, con su descripción y las acciones
	* principales a la derecha (debajo en celular).
	*/
	let { title, description, actions } = $$props;
	head("9ptycp", $$renderer, ($$renderer) => {
		$$renderer.title(($$renderer) => {
			$$renderer.push(`<title>${escape_html(title)} · SIGCON</title>`);
		});
	});
	$$renderer.push(`<div class="mb-6 flex flex-wrap items-end justify-between gap-x-4 gap-y-3"><div class="min-w-0"><h1 class="title-page">${escape_html(title)}</h1> `);
	if (description) $$renderer.push(`<!--[0--><p class="mt-1 max-w-2xl text-sm text-muted">${escape_html(description)}</p>`);
	else $$renderer.push("<!--[-1-->");
	$$renderer.push(`<!--]--></div> `);
	if (actions) {
		$$renderer.push(`<!--[0--><div class="flex flex-wrap items-center gap-2">`);
		actions($$renderer);
		$$renderer.push(`<!----></div>`);
	} else $$renderer.push("<!--[-1-->");
	$$renderer.push(`<!--]--></div>`);
}
//#endregion
export { PageHeader as t };
