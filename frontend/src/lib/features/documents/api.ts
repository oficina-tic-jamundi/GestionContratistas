import { api } from '$lib/api';
import type { ContractDocument, DocumentList, DocumentOwnerRef } from '$lib/types/documents';

const base = import.meta.env.VITE_API_BASE_URL || '/api/v1';

const ownerPath = (owner: DocumentOwnerRef) =>
  `/${owner.kind === 'report' ? 'reports' : 'contracts'}/${encodeURIComponent(owner.uuid)}/documents`;

export async function listDocuments(owner: DocumentOwnerRef): Promise<DocumentList> {
  return (await api.get<DocumentList>(ownerPath(owner))).data;
}

export function listContractDocuments(contractUuid: string): Promise<DocumentList> {
  return listDocuments({ kind: 'contract', uuid: contractUuid });
}

export async function uploadDocument(
  owner: DocumentOwnerRef,
  file: File,
  type: string,
  description: string | null
): Promise<ContractDocument> {
  const form = new FormData();
  form.set('file', file);
  form.set('type', type);
  if (description) form.set('description', description);
  return (await api.post<ContractDocument>(ownerPath(owner), form)).data;
}

/** Acta o informe creado desde la actividad con lo escrito o dictado y las fotos (ADR-023). */
export async function composeReportDocument(
  reportUuid: string,
  input: {
    template: 'acta' | 'informe';
    activity: string;
    fields: Record<string, string>;
    photos: { uuid: string; caption: string }[];
  }
): Promise<ContractDocument> {
  return (
    await api.post<ContractDocument>(
      `/reports/${encodeURIComponent(reportUuid)}/composed-documents`,
      input
    )
  ).data;
}

export async function withdrawDocument(uuid: string, reason: string): Promise<ContractDocument> {
  return (
    await api.post<ContractDocument>(`/documents/${encodeURIComponent(uuid)}/withdraw`, { reason })
  ).data;
}

/**
 * URL de descarga (misma sesión por cookie; la API verifica el acceso en cada descarga).
 * No es una ruta de la aplicación, por eso no pasa por resolve().
 */
export function documentDownloadUrl(uuid: string, inline = false): string {
  return `${base}/documents/${encodeURIComponent(uuid)}/download${inline ? '?inline=1' : ''}`;
}
