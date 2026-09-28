# Architecture Decision Records (ADR)

Cada decisión arquitectónica importante se registra aquí. Un ADR aceptado no se edita para
cambiar la decisión: se crea uno nuevo que lo reemplaza y se marca el anterior como
"Reemplazado por ADR-XXX".

| ADR | Título | Estado |
|---|---|---|
| [001](ADR-001-frontend-backend-separados.md) | Frontend y backend separados, servidos desde el mismo origen | Aceptado |
| [002](ADR-002-autenticacion.md) | Autenticación con sesiones del lado del servidor (sin JWT) | Aceptado — implementado (Fase 1) |
| [003](ADR-003-almacenamiento.md) | Almacenamiento local privado como fuente primaria de archivos | Aceptado |
| [004](ADR-004-google-shared-drive.md) | Google Shared Drive con Service Account | Aceptado — implementación diferida |
| [005](ADR-005-sincronizacion-asincrona.md) | Sincronización asíncrona con cola en MySQL y Cron | Aceptado |
| [006](ADR-006-fotografias-originales.md) | Conservación del original y marca de agua generada en el servidor | Aceptado |
| [007](ADR-007-pagos.md) | Elegibilidad de pagos centralizada y configurable | Aceptado |
| [008](ADR-008-inteligencia-artificial.md) | Preparación para IA mediante una interfaz de proveedor | Aceptado |
| [009](ADR-009-stack-y-versiones.md) | Stack tecnológico y versiones | Aceptado |
| [010](ADR-010-fechas-y-zona-horaria.md) | Fechas en UTC y hora oficial del servidor | Aceptado |
| [011](ADR-011-migraciones.md) | Migraciones SQL versionadas, solo hacia adelante | Aceptado |
| [014](ADR-014-documentos.md) | Documentos: carga validada, descarga autorizada y retiro sin eliminación | Aceptado — tipos institucionales pendientes |
| [015](ADR-015-informes.md) | Informes: flujo de revisión, versiones inmutables y aprobación | Aceptado — periodicidad y formato pendientes |
| [016](ADR-016-evidencias-fotograficas.md) | Evidencias fotográficas: captura, marca de agua en el servidor y acceso al original | Aceptado — GPS obligatorio y acceso al original pendientes |
| [017](ADR-017-cola-de-trabajos-y-pdf.md) | Cola de trabajos (implementación) y PDF de las versiones de informe | Aceptado — formato institucional del PDF pendiente |
| [018](ADR-018-pagos.md) | Pagos: trámite, elegibilidad configurable y separación de funciones | Aceptado — roles responsables y reglas adicionales pendientes |
| [019](ADR-019-notificaciones-tablero-reportes.md) | Notificaciones internas, tablero por rol y reportes CSV | Aceptado — correo pendiente de credenciales SMTP |
| [020](ADR-020-endurecimiento-y-operacion.md) | Endurecimiento y operación: límites de peticiones, verificación, backups cifrados y empaquetado | Aceptado |
| [021](ADR-021-panel-del-contratista.md) | Panel del contratista: prioridad asignada por la Alcaldía, estados derivados de los informes e historial | Aceptado |
| [022](ADR-022-obligaciones-desde-el-contrato.md) | Obligaciones leídas del contrato firmado al registrar un contratista (sin IA, propuesta revisable) | Aceptado |
| [023](ADR-023-subir-o-crear-informe.md) | Subir o crear el informe desde la actividad: acta o informe, dictado y fotos | Aceptado |
| [024](ADR-024-sistema-de-diseno.md) | Sistema de diseño: tokens, tipografía, menú lateral fijo, tabla de datos única y movimiento discreto | Aceptado |
| [013](ADR-013-avance-contractual.md) | Obligaciones, tareas, subtareas y cálculo del avance | Aceptado — **pendiente de validación** |
| [012](ADR-012-ciclo-de-vida-del-contrato.md) | Ciclo de vida del contrato, alcance de visibilidad y datos del contratista | Aceptado — **pendiente de validación jurídica** |

## Plantilla

```markdown
# ADR-XXX: Título

- Estado: Propuesto | Aceptado | Reemplazado por ADR-YYY
- Fecha: AAAA-MM-DD

## Contexto
## Decisión
## Alternativas consideradas
## Consecuencias
```
