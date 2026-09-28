import { C as attr, T as escape_html, a as bind_props, l as props_id, o as derived, s as ensure_array_like } from "./server.js";
import { t as Icon } from "./Icon.js";
//#region src/lib/components/ui/SelectField.svelte
function SelectField($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { label, value = "", options, placeholder, errors = [], hint, required = false, $$slots, $$events, ...rest } = $$props;
		const invalid = derived(() => errors.length > 0);
		const describedBy = derived(() => [invalid() ? `${uid}-error` : null, hint ? `${uid}-hint` : null].filter(Boolean).join(" ") || void 0);
		$$renderer.push(`<div class="space-y-1.5"><label${attr("for", uid)} class="block text-sm font-medium text-ink">${escape_html(label)} `);
		if (required) $$renderer.push(`<!--[0--><span class="text-danger" aria-hidden="true">*</span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></label> <div class="relative">`);
		$$renderer.select({
			id: uid,
			value,
			required,
			"aria-invalid": invalid(),
			"aria-describedby": describedBy(),
			class: `block h-11 w-full appearance-none rounded-xl border bg-field bg-none px-3.5 pr-10 text-sm text-ink transition-[border-color,box-shadow] duration-150 ease-out focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted ${invalid() ? "border-danger" : "border-border hover:border-border-strong"}`,
			...rest
		}, ($$renderer) => {
			if (placeholder !== void 0) {
				$$renderer.push("<!--[0-->");
				$$renderer.option({
					value: "",
					class: ""
				}, ($$renderer) => {
					$$renderer.push(`${escape_html(placeholder)}`);
				}, "svelte-1oyc7vz");
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--><!--[-->`);
			const each_array = ensure_array_like(options);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let option = each_array[$$index];
				$$renderer.option({
					value: option.value,
					class: ""
				}, ($$renderer) => {
					$$renderer.push(`${escape_html(option.label)}`);
				}, "svelte-1oyc7vz");
			}
			$$renderer.push(`<!--]-->`);
		});
		$$renderer.push(` `);
		Icon($$renderer, {
			name: "chevron",
			class: "pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2 text-muted"
		});
		$$renderer.push(`<!----></div> `);
		if (invalid()) {
			$$renderer.push(`<!--[0--><p${attr("id", `${uid}-error`)} class="flex items-start gap-1.5 text-xs text-danger">`);
			Icon($$renderer, {
				name: "alert",
				class: "mt-px size-4 shrink-0"
			});
			$$renderer.push(`<!----> <span>${escape_html(errors.join(" "))}</span></p>`);
		} else if (hint) $$renderer.push(`<!--[1--><p${attr("id", `${uid}-hint`)} class="text-xs text-muted">${escape_html(hint)}</p>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
		bind_props($$props, { value });
	});
}
//#endregion
export { SelectField as t };
