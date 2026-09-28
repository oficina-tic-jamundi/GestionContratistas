import { C as attr } from "./server.js";
//#region src/lib/components/ui/ScrollRegion.svelte
function ScrollRegion($$renderer, $$props) {
	/**
	* Contenedor con desplazamiento horizontal (tablas anchas) alcanzable con el teclado:
	* con el foco se desplaza con las flechas (WCAG 2.1.1; regla axe "scrollable-region-focusable").
	*/
	let { label, children } = $$props;
	$$renderer.push(`<div class="overflow-x-auto focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none" tabindex="0" role="region"${attr("aria-label", label)}>`);
	children($$renderer);
	$$renderer.push(`<!----></div>`);
}
//#endregion
export { ScrollRegion as t };
