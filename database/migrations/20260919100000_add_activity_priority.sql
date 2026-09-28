-- Prioridad de obligaciones, tareas y subtareas (ADR-021).
-- La asigna la Alcaldía (quien gestiona contratos); el contratista solo la consulta.
-- NULL = sin prioridad asignada.

ALTER TABLE activities
    ADD COLUMN priority VARCHAR(10) NULL AFTER weight,
    ADD CONSTRAINT chk_activities_priority CHECK (priority IS NULL OR priority IN ('high', 'medium', 'low'));
