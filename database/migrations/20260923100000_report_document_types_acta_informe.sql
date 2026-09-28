-- Tipos de anexo que el contratista elige al enviar su entregable (ADR-021): acta o informe.
-- Cada uno se guarda clasificado, de modo que el supervisor sepa qué está revisando.
-- Los formatos oficiales de la Alcaldía se agregarán como plantilla descargable cuando estén.

INSERT INTO document_types (code, name, entity_type, position) VALUES
    ('informe', 'Informe de actividades', 'report', 2),
    ('acta', 'Acta', 'report', 3);
