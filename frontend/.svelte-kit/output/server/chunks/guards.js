import { t as session } from "./session.svelte.js";
import { error } from "@sveltejs/kit";
//#region src/lib/auth/guards.ts
/**
* Usar en el `load` de las páginas protegidas. Solo mejora la experiencia (evita mostrar
* una pantalla que el backend igual rechazaría); no es un control de seguridad.
*/
function requirePermission(...permissions) {
	if (!session.can(...permissions)) error(403, "No tiene permiso para acceder a esta sección.");
}
/** Igual que requirePermission, pero basta con tener uno de los permisos. */
function requireAnyPermission(...permissions) {
	if (!session.canAny(...permissions)) error(403, "No tiene permiso para acceder a esta sección.");
}
//#endregion
export { requirePermission as n, requireAnyPermission as t };
