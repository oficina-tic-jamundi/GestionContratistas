import { t as api } from "./api.js";
import { t as buildQuery } from "./format.js";
//#region src/lib/features/evidence/api.ts
var base = "/api/v1";
async function listEvidences(contractUuid, activityUuid) {
	const qs = buildQuery({ activity: activityUuid ?? null });
	return (await api.get(`/contracts/${encodeURIComponent(contractUuid)}/evidences${qs ? `?${qs}` : ""}`)).data;
}
async function captureEvidence(activityUuid, photo, fields) {
	const form = new FormData();
	form.set("photo", photo, photo instanceof File ? photo.name : "foto.jpg");
	for (const [key, value] of Object.entries(fields)) form.set(key, value);
	return (await api.post(`/activities/${encodeURIComponent(activityUuid)}/evidences`, form)).data;
}
/**
* URL de una imagen de la evidencia (la API verifica sesión y acceso). No es una ruta de la
* aplicación, por eso no pasa por resolve().
*/
function evidenceImageUrl(uuid, variant = "official", download = false) {
	const qs = buildQuery({
		variant: variant === "official" ? null : variant,
		download: download ? 1 : null
	});
	return `${base}/evidences/${encodeURIComponent(uuid)}/image${qs ? `?${qs}` : ""}`;
}
//#endregion
export { evidenceImageUrl as n, listEvidences as r, captureEvidence as t };
