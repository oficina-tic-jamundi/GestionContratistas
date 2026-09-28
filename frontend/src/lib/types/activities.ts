/** Obligaciones, tareas y subtareas (backend/src/Controllers/ActivityController.php). */

export type ActivityLevel = 'obligation' | 'task' | 'subtask';

/** Prioridad asignada por la Alcaldía (ADR-021). */
export type ActivityPriority = 'high' | 'medium' | 'low';

export const PRIORITY_OPTIONS: { value: ActivityPriority; label: string }[] = [
  { value: 'high', label: 'Alta' },
  { value: 'medium', label: 'Media' },
  { value: 'low', label: 'Baja' }
];

export interface Activity {
  uuid: string;
  level: ActivityLevel;
  level_label: string;
  position: number;
  title: string;
  description: string | null;
  /** Texto decimal ("1.00"). */
  weight: string;
  priority: ActivityPriority | null;
  priority_label: string | null;
  /** Texto decimal ("37.50"). */
  progress: string;
  /** true si tiene hijos: su avance es calculado, no declarado. */
  progress_is_computed: boolean;
  due_date: string | null;
  has_progress_updates: boolean;
  updated_at: string;
  children: Activity[];
}

export interface ActivityTree {
  progress: string;
  can: {
    manage_obligations: boolean;
    plan: boolean;
    record_progress: boolean;
    set_priority: boolean;
  };
  items: Activity[];
}

export interface ActivityInput {
  title: string;
  description: string | null;
  weight: string;
  due_date: string | null;
}

export interface ProgressEntry {
  uuid: string;
  previous_progress: string;
  new_progress: string;
  note: string;
  user: string;
  recorded_at: string;
}

/** Acciones que el árbol solicita al panel que lo contiene. */
export interface ActivityActions {
  add: (parent: Activity) => void;
  edit: (item: Activity) => void;
  remove: (item: Activity) => void;
  progress: (item: Activity) => void;
  history: (item: Activity) => void;
  priority: (item: Activity, priority: ActivityPriority | null) => void;
}

/** Una actividad abierta con su contexto (GET /activities/{uuid}). */
export interface ActivityDetail {
  activity: Activity;
  /** Obligación del contrato a la que pertenece (puede ser la misma actividad). */
  obligation: Activity;
  parent: Activity | null;
  children: Activity[];
  contract: {
    uuid: string;
    contract_number: string;
    object: string;
    status: string;
    status_label: string;
    start_date: string;
    end_date: string;
    department: string;
    supervisor: string | null;
  };
}

/** Obligación leída del contrato firmado, pendiente de confirmar (ADR-022). */
export interface ObligationSuggestion {
  title: string;
  /** Texto completo cuando el título se acortó. */
  description: string | null;
}

export interface ObligationSuggestions {
  document: { uuid: string; name: string; uploaded_at: string };
  items: ObligationSuggestion[];
  warnings: { code: string; message: string }[];
  existing_obligations: number;
}
