import { n as ApiError } from "./api.js";
//#region src/lib/utils/forms.ts
/** Mensajes de error de la API para un campo del formulario. */
function fieldErrors(error, field) {
	return error instanceof ApiError ? error.errorsFor(field) : [];
}
/** Mensaje general del error (los errores por campo se muestran junto a cada campo). */
function formMessage(error) {
	if (!error) return null;
	if (error instanceof ApiError) return error.kind === "validation" && error.fieldErrors.length > 0 ? "Revise los campos marcados." : error.message;
	return "Ocurrió un error inesperado.";
}
//#endregion
export { formMessage as n, fieldErrors as t };
