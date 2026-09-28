import { t as session } from "../../../chunks/session.svelte.js";
import { redirect } from "@sveltejs/kit";
//#region src/lib/utils/redirect.ts
/**
* Valida el destino de redirección tras el login. Solo se aceptan rutas internas
* ("/usuarios"), nunca URLs externas ("https://...", "//sitio") para evitar redirecciones abiertas.
*/
function safeRedirect(target, fallback = "/") {
	if (!target || !target.startsWith("/") || target.startsWith("//") || target.startsWith("/\\")) return fallback;
	if (target.startsWith("/login")) return fallback;
	return target;
}
//#endregion
//#region src/routes/login/+page.ts
var load = async ({ url }) => {
	if (await session.ensure().catch(() => null)) redirect(307, safeRedirect(url.searchParams.get("redirect")));
	return {
		expired: url.searchParams.has("expired"),
		redirectTo: safeRedirect(url.searchParams.get("redirect"))
	};
};
//#endregion
export { load };
