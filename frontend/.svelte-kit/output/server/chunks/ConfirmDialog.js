import { C as attr, T as escape_html, a as bind_props, l as props_id } from "./server.js";
import { t as Button } from "./Button.js";
//#region src/lib/components/ui/ConfirmDialog.svelte
function ConfirmDialog($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { open = false, title, confirmLabel = "Confirmar", variant = "primary", loading = false, onconfirm, children } = $$props;
		$$renderer.push(`<dialog${attr("aria-labelledby", `${uid}-title`)} class="modal m-auto w-[calc(100%-1.5rem)] max-w-md rounded-2xl border border-border bg-deep/90 p-0 text-ink shadow-pop backdrop-blur-xl backdrop:bg-backdrop backdrop:backdrop-blur-sm"><div class="space-y-3 p-5"><h2${attr("id", `${uid}-title`)} class="text-lg font-semibold text-ink">${escape_html(title)}</h2> <div class="text-sm text-muted">`);
		children($$renderer);
		$$renderer.push(`<!----></div></div> <div class="flex justify-end gap-2 border-t border-border bg-canvas px-5 py-3">`);
		Button($$renderer, {
			variant: "secondary",
			onclick: () => open = false,
			disabled: loading,
			children: ($$renderer) => {
				$$renderer.push(`<!---->Cancelar`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----> `);
		Button($$renderer, {
			variant,
			loading,
			onclick: onconfirm,
			children: ($$renderer) => {
				$$renderer.push(`<!---->${escape_html(confirmLabel)}`);
			},
			$$slots: { default: true }
		});
		$$renderer.push(`<!----></div></dialog>`);
		bind_props($$props, { open });
	});
}
//#endregion
export { ConfirmDialog as t };
