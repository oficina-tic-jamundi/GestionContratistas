import { C as attr, T as escape_html, o as derived, t as attr_class, w as clsx } from "./server.js";
//#region src/lib/components/ui/Pagination.svelte
function Pagination($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { pagination, hrefFor } = $$props;
		const first = derived(() => pagination.total === 0 ? 0 : (pagination.page - 1) * pagination.per_page + 1);
		const last = derived(() => Math.min(pagination.page * pagination.per_page, pagination.total));
		const linkClass = "inline-flex h-9 items-center rounded-lg border border-border px-3 text-ink transition-colors hover:border-primary/50 hover:bg-primary-soft";
		$$renderer.push(`<nav class="flex flex-wrap items-center justify-between gap-3 px-1 py-3 text-sm" aria-label="Paginación"><p class="text-muted">`);
		if (pagination.total === 0) $$renderer.push(`<!--[0-->Sin resultados`);
		else $$renderer.push(`<!--[-1-->Mostrando ${escape_html(first())}–${escape_html(last())} de ${escape_html(pagination.total)}`);
		$$renderer.push(`<!--]--></p> `);
		if (pagination.total_pages > 1) {
			$$renderer.push(`<!--[0--><div class="flex items-center gap-2">`);
			if (pagination.page > 1) $$renderer.push(`<!--[0--><a${attr("href", hrefFor(pagination.page - 1))}${attr_class(clsx(linkClass))}>Anterior</a>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <span class="text-muted">Página ${escape_html(pagination.page)} de ${escape_html(pagination.total_pages)}</span> `);
			if (pagination.page < pagination.total_pages) $$renderer.push(`<!--[0--><a${attr("href", hrefFor(pagination.page + 1))}${attr_class(clsx(linkClass))}>Siguiente</a>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></nav>`);
	});
}
//#endregion
export { Pagination as t };
