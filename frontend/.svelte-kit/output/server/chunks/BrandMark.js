import { d as stringify, t as attr_class } from "./server.js";
import { t as Icon } from "./Icon.js";
//#region src/lib/components/ui/BrandMark.svelte
function BrandMark($$renderer, $$props) {
	/** Marca de SIGCON: edificio sobre una placa verde con degradado. Decorativa. */
	let { size = "sm" } = $$props;
	const styles = {
		sm: {
			box: "size-9 rounded-xl",
			icon: "size-5"
		},
		md: {
			box: "size-11 rounded-xl",
			icon: "size-6"
		},
		lg: {
			box: "size-20 rounded-3xl",
			icon: "size-10"
		}
	};
	$$renderer.push(`<span${attr_class(`inline-flex shrink-0 items-center justify-center bg-linear-135 from-[#34d399] to-[#059669] text-white shadow-lg shadow-emerald-500/30 ${stringify(styles[size].box)}`)} aria-hidden="true">`);
	Icon($$renderer, {
		name: "building",
		class: styles[size].icon
	});
	$$renderer.push(`<!----></span>`);
}
//#endregion
export { BrandMark as t };
