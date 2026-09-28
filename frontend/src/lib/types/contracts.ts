/** Contratos de la API (backend/src/Http/Resources/ContractResources.php). */

export type ActiveStatus = 'active' | 'inactive';
export type PersonType = 'natural' | 'juridica';
export type DocumentType = 'CC' | 'CE' | 'PPT' | 'PA' | 'NIT';
export type ContractStatus =
  'draft' | 'active' | 'suspended' | 'terminated' | 'liquidated' | 'archived';

export interface Department {
  uuid: string;
  code: string;
  name: string;
  status: ActiveStatus;
  status_label: string;
  active_contracts: number;
}

export interface DepartmentInput {
  code: string;
  name: string;
}

export interface Contractor {
  uuid: string;
  person_type: PersonType;
  person_type_label: string;
  document_type: DocumentType;
  document_number: string;
  verification_digit: number | null;
  document: string;
  name: string;
  email: string | null;
  phone: string | null;
  address: string | null;
  status: ActiveStatus;
  status_label: string;
  user: { uuid: string; email: string } | null;
  created_at: string;
  updated_at: string;
}

export interface ContractorInput {
  person_type: PersonType;
  document_type: DocumentType;
  document_number: string;
  verification_digit: string | null;
  name: string;
  email: string | null;
  phone: string | null;
  address: string | null;
  user: string | null;
}

export interface ContractTransition {
  status: ContractStatus;
  label: string;
  requires_comment: boolean;
}

export interface Contract {
  uuid: string;
  contract_number: string;
  object: string;
  status: ContractStatus;
  status_label: string;
  signed_at: string | null;
  start_date: string;
  end_date: string;
  /** Texto decimal en COP (ej. "42000000.00"). */
  total_value: string;
  /** Cuántos pagos se pactaron en el contrato; null si no se registró. */
  payment_count: number | null;
  secop_url: string | null;
  contractor: { uuid: string; name: string; document: string };
  department: { uuid: string; code: string; name: string };
  supervisor: { uuid: string; name: string } | null;
  actions: {
    edit: boolean;
    delete: boolean;
    change_supervisor: boolean;
    transitions: ContractTransition[];
  };
  created_at: string;
  updated_at: string;
}

export interface ContractInput {
  contract_number: string;
  object: string;
  contractor: string;
  department: string;
  supervisor: string | null;
  signed_at: string | null;
  start_date: string;
  end_date: string;
  total_value: string;
  /** Pagos pactados en el contrato (1 a 120); vacío si no se registró. */
  payment_count: string;
  secop_url: string | null;
}

export interface HistoryEntry {
  id: number;
  event: string;
  summary: string;
  comment: string | null;
  /** Estado anterior de la entidad (contrato o informe). */
  from_status: string | null;
  to_status: string | null;
  user: string | null;
  occurred_at: string;
  metadata: Record<string, unknown> | null;
}

export interface SupervisorOption {
  uuid: string;
  name: string;
  email: string;
  department: string | null;
}
