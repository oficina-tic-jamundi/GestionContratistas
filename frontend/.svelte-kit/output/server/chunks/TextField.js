import { C as attr, T as escape_html, a as bind_props, d as stringify, l as props_id, o as derived, r as attributes } from "./server.js";
import { t as Icon } from "./Icon.js";
//#region src/lib/components/ui/TextField.svelte
function TextField($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const uid = props_id($$renderer);
		let { label, value = "", errors = [], hint, required = false, type = "text", class: className = "", $$slots, $$events, ...rest } = $$props;
		const invalid = derived(() => errors.length > 0);
		const describedBy = derived(() => [invalid() ? `${uid}-error` : null, hint ? `${uid}-hint` : null].filter(Boolean).join(" ") || void 0);
		let revealed = false;
		const isPassword = derived(() => type === "password");
		const inputType = derived(() => (isPassword(), type));
		$$renderer.push(`<div class="space-y-1.5"><label${attr("for", uid)} class="block text-sm font-medium text-ink">${escape_html(label)} `);
		if (required) $$renderer.push(`<!--[0--><span class="text-danger" aria-hidden="true">*</span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></label> <div class="relative"><input${attributes({
			id: uid,
			type: inputType(),
			value,
			required,
			"aria-invalid": invalid(),
			"aria-describedby": describedBy(),
			class: `block h-11 w-full rounded-xl border bg-field px-3.5 text-sm text-ink transition-[border-color,box-shadow] duration-150 ease-out placeholder:text-muted/80 focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted ${invalid() ? "border-danger" : "border-border hover:border-border-strong"} ${isPassword() ? "pr-12" : ""} ${stringify(className)}`,
			...rest
		}, void 0, void 0, void 0, 4)}/> `);
		if (isPassword()) {
			$$renderer.push(`<!--[0--><button type="button" class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center rounded-r-xl text-muted transition-colors hover:text-ink"${attr("aria-label", "Mostrar la contraseña")}${attr("aria-pressed", revealed)}>`);
			Icon($$renderer, {
				name: "eye",
				class: "size-5"
			});
			$$renderer.push(`<!----></button>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div> `);
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
export { TextField as t };
