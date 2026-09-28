/** Estadísticas de pagos por contrato (backend/src/Controllers/StatisticsController.php, ADR-021). */

export interface ContractPaymentStats {
  uuid: string;
  contract_number: string;
  contractor: string;
  department: string;
  status: string;
  status_label: string;
  start_date: string;
  end_date: string;
  /** Meses que cubre el plazo del contrato. */
  months: number;
  total_value: string;
  /** Avance del contrato en porcentaje, como texto ("37.50"). */
  progress: string;
  payments: {
    /** Pagos pactados en el contrato; null si no se registraron. */
    agreed: number | null;
    paid: number;
    in_process: number;
    /** Pagos que faltan; null si el contrato no declara cuántos se pactaron. */
    pending: number | null;
    paid_amount: string;
    committed_amount: string;
  };
}

export interface PaymentStatistics {
  contracts: ContractPaymentStats[];
  totals: {
    contracts: number;
    agreed: number;
    paid: number;
    in_process: number;
    paid_amount: string;
    total_value: string;
    without_agreed: number;
  };
}
