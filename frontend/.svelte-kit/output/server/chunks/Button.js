import { C as attr, o as derived, r as attributes, t as attr_class, w as clsx } from "./server.js";
//#region src/lib/components/ui/Button.svelte
function Button($$renderer, $$props) {
	/**
	* Botón de la aplicación. Alturas fijas por tamaño (mínimo 44 px en táctil), un solo estilo
	* de foco y una respuesta breve al pulsar, para que todos los botones se sientan iguales.
	*/
	let { variant = "primary", size = "md", loading = false, href, disabled = false, type = "button", class: className = "", children, $$slots, $$events, ...rest } = $$props;
	const variants = {
		primary: "btn-primary border-transparent font-semibold hover:brightness-110",
		secondary: "bg-field text-ink border-border hover:border-primary/50 hover:bg-primary-soft hover:text-ink",
		danger: "bg-danger-strong text-white hover:brightness-110 border-transparent",
		ghost: "bg-transparent text-primary border-transparent hover:bg-primary-soft"
	};
	const sizes = {
		sm: "h-9 px-3 text-xs",
		md: "h-11 px-4 text-sm",
		lg: "h-13 px-6 text-base"
	};
	const classes = derived(() => `inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border font-medium
     transition-[background-color,border-color,color,box-shadow,transform,filter] duration-150 ease-out
     active:scale-[0.98] disabled:pointer-events-none disabled:opacity-55 disabled:active:scale-100
     ${variants[variant]} ${sizes[size]} ${className}`);
	if (href) {
		$$renderer.push(`<!--[0--><a${attr("href", href)}${attr_class(clsx(classes()))}>`);
		children($$renderer);
		$$renderer.push(`<!----></a>`);
	} else {
		$$renderer.push(`<!--[-1--><button${attributes({
			type,
			class: clsx(classes()),
			disabled: disabled || loading,
			"aria-busy": loading,
			...rest
		})}>`);
		if (loading) $$renderer.push(`<!--[0--><span class="size-4 animate-spin rounded-full border-2 border-current border-t-transparent" aria-hidden="true"></span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		children($$renderer);
		$$renderer.push(`<!----></button>`);
	}
	$$renderer.push(`<!--]-->`);
}
//#endregion
export { Button as t };
