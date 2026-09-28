import { C as attr, t as attr_class, w as clsx } from "./server.js";
//#region src/lib/components/ui/DownloadLink.svelte
function DownloadLink($$renderer, $$props) {
	/**
	* Descarga de un archivo servido por la API (no es una ruta de la aplicación).
	* La API verifica la sesión y el acceso en cada descarga.
	*/
	let { href, children } = $$props;
	$$renderer.push(`<a${attr("href", href)}${attr_class(clsx("rounded px-2 py-1 text-xs font-medium text-primary hover:bg-primary-soft"))} download="">`);
	children($$renderer);
	$$renderer.push(`<!----></a>`);
}
//#endregion
export { DownloadLink as t };
