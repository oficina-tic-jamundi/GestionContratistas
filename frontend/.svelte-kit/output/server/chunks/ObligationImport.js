import "./internal.js";
import { o as derived } from "./server.js";
import "./toasts.svelte.js";
import "./Button.js";
import "./Alert.js";
import "./TextField.js";
import "./forms.js";
import "./api4.js";
import "./TextArea.js";
//#region src/lib/features/activities/ObligationImport.svelte
function ObligationImport($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		/**
		* Obligaciones tomadas del contrato firmado (ADR-022). SIGCON lee el PDF y PROPONE la lista;
		* la administración la revisa (corrige, quita o agrega) y solo al confirmar se registra.
		* Si el PDF no se puede leer, queda el registro a mano en la misma pantalla.
		*/
		let { contractUuid, onimported, oncancel } = $$props;
		let rows = [];
		derived(() => rows.length > 0 && rows.every((r) => r.title.trim().length >= 3));
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			$$renderer.push(`<!--[0--><p class="text-sm text-muted" role="status">Leyendo el contrato firmado…</p>`);
			$$renderer.push(`<!--]-->`);
		}
		do {
			$$settled = true;
			$$inner_renderer = $$renderer.copy();
			$$render_inner($$inner_renderer);
		} while (!$$settled);
		$$renderer.subsume($$inner_renderer);
	});
}
//#endregion
export { ObligationImport as t };
