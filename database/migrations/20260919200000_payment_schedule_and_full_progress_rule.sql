-- Pagos pactados del contrato y exigencia de avance total para pagar (ADR-021).
--
-- 1. payment_count: cuántos pagos se pactaron en el contrato. Lo registra la Alcaldía; permite
--    mostrar cuántos pagos lleva el contratista y cuántos le faltan. NULL = no registrado.
-- 2. La Alcaldía decidió que no se paga hasta completar el 100 % del avance: se activa la regla
--    de elegibilidad "Avance mínimo del contrato" con 100 %.

ALTER TABLE contracts
    ADD COLUMN payment_count SMALLINT UNSIGNED NULL AFTER total_value,
    ADD CONSTRAINT chk_contracts_payment_count CHECK (payment_count IS NULL OR (payment_count >= 1 AND payment_count <= 120));

UPDATE payment_rule_settings
   SET enabled = 1, params = JSON_OBJECT('min_percent', 100), updated_at = UTC_TIMESTAMP()
 WHERE code = 'min_progress';
