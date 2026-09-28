import { t as session } from "../../../chunks/session.svelte.js";
import { redirect } from "@sveltejs/kit";
//#region src/routes/(app)/+layout.ts
/**
* Todas las páginas del grupo (app) exigen sesión. Si no hay sesión se envía al login
* conservando el destino; si la contraseña es temporal, solo se permite cambiarla.
*/
var load = async ({ url }) => {
	const profile = await session.ensure();
	if (!profile) redirect(307, `/login?redirect=${encodeURIComponent(url.pathname + url.search)}`);
	if (profile.user.must_change_password && url.pathname !== "/account/password") redirect(307, "/account/password");
	return {};
};
//#endregion
export { load };
