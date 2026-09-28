import { C as attr, T as escape_html, a as bind_props, l as props_id, o as derived, r as attributes, s as ensure_array_like } from "./server.js";
//#region src/lib/components/ui/TextArea.svelte
function TextArea($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { label, value = "", errors = [], hint, required = false, rows = 4, $$slots, $$events, ...rest } = $$props;
		const describedBy = derived(() => [errors.length > 0 ? `${uid}-error` : null, hint ? `${uid}-hint` : null].filter(Boolean).join(" ") || void 0);
		$$renderer.push(`<div class="space-y-1.5"><label${attr("for", uid)} class="block text-sm font-medium text-ink">${escape_html(label)} `);
		if (required) $$renderer.push(`<!--[0--><span class="text-danger" aria-hidden="true">*</span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></label> <textarea${attributes({
			id: uid,
			required,
			rows,
			"aria-invalid": errors.length > 0,
			"aria-describedby": describedBy(),
			class: `block w-full rounded-xl border bg-field px-3.5 py-2.5 text-sm text-ink transition-[border-color,box-shadow] duration-150 ease-out placeholder:text-muted/80 focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted ${errors.length > 0 ? "border-danger" : "border-border hover:border-border-strong"}`,
			...rest
		})}>`);
		const $$body = escape_html(value);
		if ($$body) $$renderer.push(`${$$body}`);
		$$renderer.push(`</textarea> `);
		if (hint) $$renderer.push(`<!--[0--><p${attr("id", `${uid}-hint`)} class="text-xs text-muted">${escape_html(hint)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (errors.length > 0) {
			$$renderer.push(`<!--[0--><ul${attr("id", `${uid}-error`)} class="space-y-0.5 text-xs text-danger"><!--[-->`);
			const each_array = ensure_array_like(errors);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let error = each_array[$$index];
				$$renderer.push(`<li>${escape_html(error)}</li>`);
			}
			$$renderer.push(`<!--]--></ul>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
		bind_props($$props, { value });
	});
}
//#endregion
export { TextArea as t };
