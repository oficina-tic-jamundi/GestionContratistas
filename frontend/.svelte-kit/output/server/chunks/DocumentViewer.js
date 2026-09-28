import { C as attr, T as escape_html, a as bind_props, d as stringify, l as props_id, t as attr_class } from "./server.js";
import { t as Icon } from "./Icon.js";
import { i as formatDateTime, n as formatBytes } from "./format.js";
import { t as Button } from "./Button.js";
import { t as DownloadLink } from "./DownloadLink.js";
import { t as documentDownloadUrl } from "./api6.js";
//#region src/lib/components/ui/Modal.svelte
function Modal($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { open = false, title, size = "md", onclose, children, footer } = $$props;
		$$renderer.push(`<dialog${attr("aria-labelledby", `${uid}-title`)}${attr_class(`modal m-auto max-h-[92dvh] w-[calc(100%-1.5rem)] ${size === "lg" ? "max-w-3xl" : "max-w-lg"} rounded-2xl border border-border bg-deep/90 p-0 text-ink shadow-pop backdrop-blur-xl backdrop:bg-backdrop backdrop:backdrop-blur-sm`)}><div class="flex items-center justify-between gap-3 border-b border-border px-5 py-3"><h2${attr("id", `${uid}-title`)} class="text-lg font-semibold text-ink">${escape_html(title)}</h2> <button type="button" class="inline-flex size-9 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink" aria-label="Cerrar">`);
		Icon($$renderer, {
			name: "close",
			class: "size-5"
		});
		$$renderer.push(`<!----></button></div> <div class="p-5 text-sm">`);
		children($$renderer);
		$$renderer.push(`<!----></div> `);
		if (footer) {
			$$renderer.push(`<!--[0--><div class="flex flex-wrap justify-end gap-2 border-t border-border bg-canvas px-5 py-3">`);
			footer($$renderer);
			$$renderer.push(`<!----></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></dialog>`);
		bind_props($$props, { open });
	});
}
//#endregion
//#region src/lib/features/documents/DocumentViewer.svelte
var VIEWABLE = [
	"application/pdf",
	"image/jpeg",
	"image/png"
];
function isViewable(mimeType) {
	return VIEWABLE.includes(mimeType);
}
function DocumentViewer($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Visor integrado de documentos: muestra PDF e imágenes sin salir de SIGCON. El archivo lo
		* sirve la API con la sesión del usuario y en un marco aislado (sin scripts).
		*/
		let { document: doc = null, onclose } = $$props;
		let open = false;
		function close() {
			doc = null;
			onclose?.();
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			if (doc) {
				$$renderer.push("<!--[0-->");
				const file = doc;
				const url = documentDownloadUrl(file.uuid, true);
				{
					function footer($$renderer) {
						DownloadLink($$renderer, {
							href: documentDownloadUrl(file.uuid),
							children: ($$renderer) => {
								$$renderer.push(`<!---->Descargar`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!----> `);
						Button($$renderer, {
							variant: "secondary",
							onclick: close,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Cerrar`);
							},
							$$slots: { default: true }
						});
						$$renderer.push(`<!---->`);
					}
					Modal($$renderer, {
						title: doc.original_name,
						size: "lg",
						onclose: close,
						get open() {
							return open;
						},
						set open($$value) {
							open = $$value;
							$$settled = false;
						},
						footer,
						children: ($$renderer) => {
							$$renderer.push(`<p class="mb-3 text-xs text-muted">${escape_html(doc.type.name)} · ${escape_html(formatBytes(doc.size_bytes))} · ${escape_html(doc.uploaded_by)} · ${escape_html(formatDateTime(doc.created_at))} `);
							if (doc.status === "withdrawn") $$renderer.push(`<!--[0-->· <span class="text-warning">Retirado</span>`);
							else $$renderer.push("<!--[-1-->");
							$$renderer.push(`<!--]--></p> `);
							if (doc.mime_type === "application/pdf") $$renderer.push(`<!--[0--><iframe${attr("src", url)}${attr("title", `Documento ${stringify(doc.original_name)}`)} class="h-[70dvh] w-full rounded-lg border border-border bg-white"></iframe>`);
							else if (doc.mime_type.startsWith("image/")) $$renderer.push(`<!--[1--><img${attr("src", url)}${attr("alt", `Documento ${stringify(doc.original_name)}`)} class="max-h-[70dvh] w-full rounded-lg border border-border object-contain"/>`);
							else $$renderer.push(`<!--[-1--><p class="text-sm text-muted">Este tipo de archivo no se puede previsualizar. Descárguelo para abrirlo.</p>`);
							$$renderer.push(`<!--]-->`);
						},
						$$slots: {
							footer: true,
							default: true
						}
					});
				}
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]-->`);
		}
		do {
			$$settled = true;
			$$inner_renderer = $$renderer.copy();
			$$render_inner($$inner_renderer);
		} while (!$$settled);
		$$renderer.subsume($$inner_renderer);
		bind_props($$props, { document: doc });
	});
}
//#endregion
export { isViewable as n, Modal as r, DocumentViewer as t };
