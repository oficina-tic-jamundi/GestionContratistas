# Convenciones de la API

Base: `/api/v1`. Todo intercambio es JSON (UTF-8), salvo las subidas (`multipart/form-data`)
y las descargas de archivos.

## Formato de respuesta

Éxito:

```json
{
  "success": true,
  "data": { },
  "message": null,
  "errors": [],
  "meta": { }
}
```

Error:

```json
{
  "success": false,
  "data": null,
  "message": "Los datos enviados no son válidos.",
  "errors": [
    { "field": "email", "code": "required", "message": "El correo es obligatorio." }
  ],
  "meta": { "code": "validation_error", "request_id": "3f9a1c0b7d2e4a51" }
}
```

- `message` siempre es apto para mostrar al usuario final.
- `meta.code` es estable y legible por máquina. El frontend decide con él, nunca con el texto.
- `meta.request_id` también llega en la cabecera `X-Request-Id`. Se muestra al usuario como
  "código de referencia" para buscarlo en los logs.
- **Nunca** se devuelven trazas, SQL, rutas, hashes, tokens ni secretos.

## Códigos HTTP

`200` OK · `201` creado · `204` sin contenido · `401` no autenticado · `403` sin permiso ·
`404` no existe (o el usuario no puede saber que existe) · `405` método no permitido ·
`409` regla de negocio o conflicto de estado · `422` validación o archivo inválido ·
`429` límite de solicitudes · `500` error interno · `503` servicio o integración no disponible.

## Paginación, filtros y orden

```
GET /api/v1/contracts?page=1&per_page=25&search=obra&status=active&sort=start_date&direction=desc
```

- `per_page` por defecto es 25 y como máximo 100.
- `sort` solo acepta columnas de una lista blanca por recurso.
- `direction` acepta `asc` o `desc`.
- Un parámetro inválido produce `422`; nunca se ignora en silencio.

Respuesta:

```json
{
  "success": true,
  "data": [ ],
  "meta": { "pagination": { "page": 1, "per_page": 25, "total": 140, "total_pages": 6 } }
}
```

## Nombres

- Rutas en inglés, en plural y en kebab-case: `/contracts/{id}/activities`.
- Las acciones de flujo usan un subrecurso con `POST`: `/reports/{id}/submit`,
  `/reports/{id}/approve`.
- Los campos JSON van en `snake_case`.
- Fechas con hora en ISO 8601 con desfase; fechas sin hora como `AAAA-MM-DD` (ver ADR-010).
- Los valores monetarios se envían como **string decimal** (`"12500000.00"`) para no perder
  precisión.

## Autenticación y CSRF (Fase 1)

- Cookie de sesión `HttpOnly`, enviada automáticamente (mismo origen).
- Toda petición `POST`, `PUT`, `PATCH` o `DELETE` debe incluir la cabecera `X-CSRF-Token`.

## Endpoints

La especificación completa (parámetros, cuerpos, respuestas, errores y permisos) está en
**[openapi.yaml](openapi.yaml)** (OpenAPI 3.1). Resumen:

| Método | Ruta | Permiso | Fase |
|---|---|---|---|
| GET | `/health` | público | 0 |
| POST | `/auth/login` | público | 1 |
| GET | `/auth/me` | sesión | 1 |
| POST | `/auth/logout` | sesión | 1 |
| POST | `/auth/password` | sesión | 1 |
| GET | `/users` | `users.view` | 1 |
| POST | `/users` | `users.create` (+ `users.assign_roles` si envía roles) | 1 |
| GET | `/users/{uuid}` | `users.view` | 1 |
| PATCH | `/users/{uuid}` | `users.update` (datos) / `users.assign_roles` (roles) | 1 |
| POST | `/users/{uuid}/activate` · `/deactivate` | `users.deactivate` | 1 |
| POST | `/users/{uuid}/reset-password` | `users.reset_password` | 1 |
| GET | `/roles` · `/permissions` | `roles.view` | 1 |
| POST | `/roles` · PUT/DELETE `/roles/{code}` | `roles.manage` | 1 |
| GET | `/audit-logs` · `/audit-logs/actions` | `audit.view` | 1 |
| GET | `/departments` | `departments.view` \| `contracts.manage` \| `users.view` | 2 |
| POST · PUT | `/departments` · `/departments/{uuid}` (+ `/activate`, `/deactivate`) | `departments.manage` | 2 |
| GET | `/contractors` · `/contractors/{uuid}` | `contractors.view` (todos) \| `contracts.view_assigned` (solo los contratistas que supervisa, ADR-021) | 2 |
| POST · PUT | `/contractors` · `/contractors/{uuid}` (+ `/activate`, `/deactivate`) | `contractors.manage` | 2 |
| GET | `/contracts` · `/contracts/{uuid}` · `/contracts/{uuid}/history` | `contracts.view_all` \| `view_assigned` \| `view_own` (alcance, ADR-012) | 2 |
| POST · PUT · DELETE | `/contracts` · `/contracts/{uuid}` | `contracts.manage` | 2 |
| POST | `/contracts/{uuid}/transitions` · `/contracts/{uuid}/supervisor` | `contracts.manage` | 2 |
| GET | `/supervisors` | `contracts.manage` | 2 |
| GET | `/contracts/{uuid}/activities` · `/activities/{uuid}/progress` | alcance del contrato | 3 |
| POST | `/contracts/{uuid}/obligations` | `contracts.manage` (borrador) | 3 |
| POST · PUT · DELETE | `/activities/{uuid}/children` · `/activities/{uuid}` | `contracts.manage` \| contratista del contrato (ADR-013) | 3 |
| POST | `/activities/{uuid}/progress` | `activities.execute` + contratista del contrato | 3 |
| PUT | `/activities/{uuid}/priority` | `contracts.manage` (contrato en borrador, activo o suspendido; ADR-021) | 11 |
| GET | `/contracts/{uuid}/documents` · `/documents/{uuid}/download` | alcance del contrato | 4 |
| POST | `/contracts/{uuid}/documents` (multipart) | `contracts.manage` \| contratista del contrato | 4 |
| POST | `/documents/{uuid}/withdraw` | `contracts.manage` \| quien lo cargó (anexos de informe: solo con el informe editable) | 4 |
| GET | `/reports` · `/reports/{uuid}` · `/reports/{uuid}/versions/{n}` · `/reports/{uuid}/history` · `/reports/{uuid}/documents` | alcance del contrato; borradores solo del autor o `contracts.view_all` | 5 |
| POST · PUT | `/contracts/{uuid}/reports` · `/reports/{uuid}` · `/reports/{uuid}/submit` · `/reports/{uuid}/documents` | `reports.create` + contratista del contrato (ADR-015) | 5 |
| POST | `/reports/{uuid}/start-review` · `/reports/{uuid}/observe` | `reports.review` + supervisor asignado | 5 |
| POST | `/reports/{uuid}/approve` · `/reports/{uuid}/reject` | `reports.approve` + supervisor asignado | 5 |
| POST | `/reports/{uuid}/reopen` | `reports.reopen` | 5 |
| GET | `/contracts/{uuid}/evidences` · `/evidences/{uuid}/image` | alcance del contrato (`variant=original`: `evidence.view_original`, auditado) | 6 |
| POST | `/activities/{uuid}/evidences` (multipart) | `evidence.capture` + contratista del contrato activo | 6 |
| POST | `/evidences/{uuid}/withdraw` | quien la registró \| `contracts.manage` | 6 |
| GET | `/reports/{uuid}/versions/{n}/pdf` | alcance del informe (descarga auditada) | 7 |
| GET · POST | `/jobs` · `/jobs/status` · `/jobs/{uuid}/retry` | `jobs.manage` | 7 |
| GET | `/payments` · `/payments/{uuid}` · `/payments/{uuid}/history` · `/contracts/{uuid}/budget` | alcance del contrato | 8 |
| POST · PUT | `/payments` · `/payments/{uuid}` · `/payments/{uuid}/evaluate` · `/submit` · `/cancel` | `payments.manage` | 8 |
| POST | `/payments/{uuid}/approve` · `/return` | `payments.approve` (separación de funciones) | 8 |
| POST | `/payments/{uuid}/paid` | `payments.register` | 8 |
| GET · PUT | `/payment-rules` · `/payment-rules/{code}` | consulta: gestión/aprobación de pagos; cambio: `payments.configure` | 8 |
| GET · POST | `/dashboard` · `/notifications` · `/notifications/unread-count` · `/notifications/read-all` · `/notifications/{uuid}/read` | usuario autenticado (solo lo propio) | 9 |
| GET | `/exports/contracts` · `/exports/payments` | `exports.run` + alcance del contrato | 9 |
| GET | `/history` (`type`: progress, evidence, report, payment, contract) | alcance del contrato: la administración ve todo; el supervisor, lo que supervisa; el contratista, lo suyo (ADR-021) | 11 |
| GET | `/statistics/payments` | `contracts.view_all` \| `view_assigned` (esquema de pagos por contrato, ADR-021) | 11 |

La matriz de permisos ejecutable está en `backend/routes/api.php`.
