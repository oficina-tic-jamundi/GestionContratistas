-- El contratista no edita nada: solo consulta sus tareas, sube evidencias y presenta informes
-- (decisión de la Alcaldía, 2026-09-19; ADR-021).
--
-- - activities.plan: crear, modificar y eliminar tareas y subtareas. Administración y supervisor,
--   también con el contrato en ejecución. Las obligaciones siguen siendo del contrato (ADR-012).
-- - activities.progress: registrar el avance verificado de una tarea. Lo hace el supervisor del
--   contrato: el avance deja de ser una declaración del contratista.
-- - activities.execute pasa a significar "actuar como contratista del contrato" (subir
--   documentos, evidencias e informes), sin planear ni registrar avance.

INSERT INTO permissions (code, module, description) VALUES
    ('activities.plan', 'activities', 'Crear y modificar tareas y subtareas de los contratos a su cargo'),
    ('activities.progress', 'activities', 'Registrar el avance verificado de las tareas de los contratos que supervisa');

UPDATE permissions
   SET description = 'Actuar como contratista del contrato: subir documentos, evidencias e informes'
 WHERE code = 'activities.execute';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.code = 'activities.plan' WHERE r.code IN ('admin', 'supervisor');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.code = 'activities.progress' WHERE r.code = 'supervisor';
