/** Notificaciones y tablero (backend/src/Controllers/NotificationController.php, DashboardService, ADR-019). */

export interface AppNotification {
  uuid: string;
  type: string;
  title: string;
  body: string | null;
  /** Ruta interna de la aplicación (se valida antes de navegar). */
  link: string | null;
  read: boolean;
  created_at: string;
}

export interface StatusCount {
  status: string;
  label: string;
  count: number;
}

export interface DashboardReport {
  uuid: string;
  number: number;
  contract_number: string;
  contractor: string;
  status: string;
  status_label: string;
  updated_at: string;
}

export interface Dashboard {
  contracts?: {
    by_status: StatusCount[];
    ending_soon: { uuid: string; contract_number: string; contractor: string; end_date: string }[];
    ending_within_days: number;
  };
  reports?: {
    by_status: StatusCount[];
    awaiting_my_review?: DashboardReport[];
    awaiting_my_action?: DashboardReport[];
  };
  payments?: {
    by_status: (StatusCount & { amount: string })[];
    to_approve: number | null;
    to_register: number | null;
  };
  /** Alertas preventivas por fecha objetivo de las tareas (ADR-021). */
  deadlines?: { within_days: number; tasks: TaskDeadline[] };
  system?: { jobs_stale: boolean; jobs_failed: number; jobs_pending: number };
  /** Panel del contratista (ADR-021). */
  my_contracts?: ContractOverview[];
}

export interface TaskDeadline {
  activity_uuid: string;
  title: string;
  /** Fecha objetivo que registró la Alcaldía al planear la tarea. */
  due_date: string;
  progress: string;
  contract_uuid: string;
  contract_number: string;
  contractor: string;
  department: string;
  /** Días que faltan; negativo si ya venció. */
  days_left: number;
}

/** Estado derivado de los informes (backend: WorkStatusResolver). */
export type WorkStatus = 'approved' | 'in_review' | 'observed' | 'pending';

export interface WorkItem {
  uuid: string;
  title: string;
  level: 'obligation' | 'task' | 'subtask';
  level_label: string;
  priority: 'high' | 'medium' | 'low' | null;
  priority_label: string | null;
  progress: string;
  status: WorkStatus;
  status_label: string;
}

export interface WorkCounts {
  total: number;
  approved: number;
  in_review: number;
  observed: number;
  pending: number;
}

export interface ContractOverview {
  contract: {
    uuid: string;
    contract_number: string;
    object: string;
    department: string;
    status: string;
    status_label: string;
    end_date: string;
  };
  progress: string;
  latest_report: { uuid: string; number: number; status: string; status_label: string } | null;
  general_observations: string[];
  /** Solo si la Alcaldía activó la regla de avance mínimo para pagos. */
  payment_notice: { rule: 'min_progress'; required: number; met: boolean } | null;
  counts: { obligations: WorkCounts; items: WorkCounts };
  obligations: (WorkItem & { observations: string[]; items: WorkItem[]; items_approved: number })[];
}

export type HistoryKind = 'progress' | 'evidence' | 'report' | 'payment' | 'contract';

export interface HistoryEvent {
  kind: HistoryKind;
  kind_label: string;
  occurred_at: string;
  summary: string;
  title: string | null;
  comment: string | null;
  from_progress: string | null;
  to_progress: string | null;
  ref_number: number | null;
  contract: { uuid: string; contract_number: string; contractor: string };
  target: { type: HistoryKind; uuid: string } | null;
  actor: string | null;
}
