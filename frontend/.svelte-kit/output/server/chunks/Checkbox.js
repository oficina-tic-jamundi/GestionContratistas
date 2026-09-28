import { C as attr, T as escape_html, a as bind_props, l as props_id, r as attributes } from "./server.js";
//#region src/lib/components/ui/Checkbox.svelte
function Checkbox($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { label, description, checked = false, $$slots, $$events, ...rest } = $$props;
		$$renderer.push(`<div class="flex items-start gap-3"><input${attributes({
			id: uid,
			type: "checkbox",
			checked,
			"aria-describedby": description ? `${uid}-desc` : void 0,
			class: "mt-0.5 size-4 rounded border-border text-primary focus:ring-primary/30 disabled:opacity-50",
			...rest
		}, void 0, void 0, void 0, 4)}/> <div class="text-sm"><label${attr("for", uid)} class="font-medium text-ink">${escape_html(label)}</label> `);
		if (description) $$renderer.push(`<!--[0--><p${attr("id", `${uid}-desc`)} class="text-xs text-muted">${escape_html(description)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div>`);
		bind_props($$props, { checked });
	});
}
//#endregion
export { Checkbox as t };
