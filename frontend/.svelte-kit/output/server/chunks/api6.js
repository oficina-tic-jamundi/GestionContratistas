import { t as api } from "./api.js";
//#region src/lib/features/documents/api.ts
var base = "/api/v1";
var ownerPath = (owner) => `/${owner.kind === "report" ? "reports" : "contracts"}/${encodeURIComponent(owner.uuid)}/documents`;
async function listDocuments(owner) {
	return (await api.get(ownerPath(owner))).data;
}
function listContractDocuments(contractUuid) {
	return listDocuments({
		kind: "contract",
		uuid: contractUuid
	});
}
/**
* URL de descarga (misma sesión por cookie; la API verifica el acceso en cada descarga).
* No es una ruta de la aplicación, por eso no pasa por resolve().
*/
function documentDownloadUrl(uuid, inline = false) {
	return `${base}/documents/${encodeURIComponent(uuid)}/download${inline ? "?inline=1" : ""}`;
}
//#endregion
export { listContractDocuments as n, listDocuments as r, documentDownloadUrl as t };
