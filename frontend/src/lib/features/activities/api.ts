import { api } from '$lib/api';
import type {
  Activity,
  ActivityDetail,
  ActivityInput,
  ActivityPriority,
  ActivityTree,
  ObligationSuggestions,
  ProgressEntry
} from '$lib/types/activities';

const path = (uuid: string) => `/activities/${encodeURIComponent(uuid)}`;

export async function getActivityTree(contractUuid: string): Promise<ActivityTree> {
  return (await api.get<ActivityTree>(`/contracts/${encodeURIComponent(contractUuid)}/activities`))
    .data;
}

/** Una actividad con su contrato, su obligación y sus tareas (ADR-021). */
export async function getActivity(uuid: string): Promise<ActivityDetail> {
  return (await api.get<ActivityDetail>(path(uuid))).data;
}

/** Obligaciones propuestas desde el contrato firmado (no registra nada, ADR-022). */
export async function suggestObligations(contractUuid: string): Promise<ObligationSuggestions> {
  return (
    await api.get<ObligationSuggestions>(
      `/contracts/${encodeURIComponent(contractUuid)}/obligations/suggestions`
    )
  ).data;
}

/** Registra las obligaciones confirmadas, todas o ninguna. */
export async function createObligations(
  contractUuid: string,
  items: ActivityInput[]
): Promise<Activity[]> {
  return (
    await api.post<Activity[]>(`/contracts/${encodeURIComponent(contractUuid)}/obligations/bulk`, {
      items
    })
  ).data;
}

export async function createObligation(
  contractUuid: string,
  input: ActivityInput
): Promise<Activity> {
  return (
    await api.post<Activity>(`/contracts/${encodeURIComponent(contractUuid)}/obligations`, input)
  ).data;
}

export async function createChild(parentUuid: string, input: ActivityInput): Promise<Activity> {
  return (await api.post<Activity>(`${path(parentUuid)}/children`, input)).data;
}

export async function updateActivity(uuid: string, input: ActivityInput): Promise<Activity> {
  return (await api.put<Activity>(path(uuid), input)).data;
}

/** Solo la Alcaldía (contracts.manage) asigna la prioridad. null la quita. */
export async function setPriority(
  uuid: string,
  priority: ActivityPriority | null
): Promise<Activity> {
  return (await api.put<Activity>(`${path(uuid)}/priority`, { priority })).data;
}

export async function deleteActivity(uuid: string): Promise<void> {
  await api.delete(path(uuid));
}

export async function recordProgress(
  uuid: string,
  progress: number,
  note: string
): Promise<Activity> {
  return (await api.post<Activity>(`${path(uuid)}/progress`, { progress, note })).data;
}

export async function getProgressHistory(
  uuid: string
): Promise<{ activity: Activity; history: ProgressEntry[] }> {
  return (await api.get<{ activity: Activity; history: ProgressEntry[] }>(`${path(uuid)}/progress`))
    .data;
}
