# Seguridad de SIGCON

SIGCON manejará datos personales (Ley 1581 de 2012), información contractual y evidencias que
soportan pagos públicos. La seguridad es un requisito, no una fase final.

## Controles implementados (Fase 0)

| Control | Dónde |
|---|---|
| Código, `vendor/`, `.env` y `storage/` fuera del document root | Estructura de despliegue (`docs/deployment/cpanel.md`) |
| `.htaccess` con `Require all denied` en `storage/` | `backend/storage/.htaccess` |
| Bloqueo de archivos ocultos y listado de directorios | `.htaccess` del frontend y de la API |
| Cabeceras en la API: `nosniff`, `X-Frame-Options: DENY`, CSP `default-src 'none'`, `Cache-Control: no-store`, `Referrer-Policy`, HSTS en producción | `SecurityHeadersMiddleware` |
| Cabeceras en el frontend: CSP (más un hash del script de arranque generado en el build), `Permissions-Policy` con cámara y geolocalización solo para el mismo origen, HSTS, redirección a HTTPS | `frontend/static/.htaccess`, `svelte.config.js` |
| No se revela `X-Powered-By` | `public/index.php`, `.htaccess` |
| Errores: mensaje genérico al usuario, detalle solo en el log, código de referencia | `ErrorHandler` |
| `APP_DEBUG=true` rechazado en producción | `Settings::fromEnv` |
| PDO con prepared statements reales (`EMULATE_PREPARES=false`), `sql_mode` estricto | `Database` |
| Redacción automática de claves sensibles en los logs | `SensitiveDataRedactor` |
| Contraseña de base de datos excluida de `var_dump` y las trazas | `DatabaseSettings` (`#[SensitiveParameter]`, `__debugInfo`) |
| Secretos fuera de Git (`.gitignore`), solo `.env.example` versionado | raíz |
| Sistema interno marcado como no indexable | `robots.txt`, `<meta name="robots">` |

## Controles implementados (Fase 1)

| Control | Dónde | Prueba |
|---|---|---|
| Sesiones en servidor, token de 256 bits, solo su SHA-256 en BD | `SessionService`, `user_sessions` | `AuthenticationTest` |
| Cookie `HttpOnly; SameSite=Lax; Secure` + prefijo `__Host-` en producción | `SessionCookie` | `SecurityComponentsTest` |
| Expiración por inactividad (30 min) y absoluta (10 h) | `SessionService::resolve` | `AuthenticationTest` |
| CSRF: token HMAC por sesión en `X-CSRF-Token` | `CsrfMiddleware`, `CsrfTokens` | `AuthenticationTest` |
| Rechazo de peticiones con `Origin` ajeno (incluye login) | `OriginCheckMiddleware` | `AuthenticationTest` |
| Argon2id (OWASP), rehash transparente | `PasswordHasher` | `AuthenticationTest` |
| Política de contraseñas (12+, no comunes, sin correo) | `PasswordPolicy` | `SecurityComponentsTest` |
| Límite de intentos por cuenta e IP, 429 + `Retry-After` | `LoginThrottle` | `AuthenticationTest` |
| Anti-enumeración (mensaje y tiempo equivalentes) | `AuthService`, hash señuelo | `AuthenticationTest` |
| Contraseña temporal obliga a cambiarla | `RequirePasswordChangedMiddleware` | `AuthenticationTest` + E2E |
| RBAC por permisos, matriz declarada en rutas | `AuthorizeMiddleware`, `routes/api.php` | `UserManagementTest`, `RolesAndAuditTest` |
| Escalamiento de privilegios bloqueado (roles requieren `users.assign_roles`; nadie cambia sus propios roles) | `UserController`, `UserService` | `UserManagementTest` |
| Siempre queda un administrador activo | `UserService` (con `FOR UPDATE`) | `UserManagementTest` |
| Desactivar / restablecer contraseña revoca sesiones | `UserService` | `UserManagementTest` + E2E |
| IDs públicos UUID (no enumerables); UUID inválido → 404 | `Uuid`, `UserService::get` | `UserManagementTest` |
| Orden y paginación solo por listas blancas | `PageRequest` | `ValidationAndPagingTest` |
| Auditoría transaccional sin secretos | `AuditLogger` + redacción | `RolesAndAuditTest` |
| Frontend: sin tokens en `localStorage`; redirecciones solo internas | `session.svelte.ts`, `safeRedirect` | Vitest + E2E |

## Controles implementados (Fase 2)

| Control | Dónde | Prueba |
|---|---|---|
| Autorización por **alcance**: todos / asignados / propios; fuera de alcance = 404 | `ContractScope`, `ContractRepository` (toda lectura exige un alcance) | `ContractsTest::testVisibilityIsScopedByProfile` + E2E |
| El contratista no ve borradores | `ContractScope` | `ContractsTest` + E2E |
| Conflicto de interés: el supervisor no puede ser el contratista | `ContractService::eligibleSupervisor` | `ContractsTest` |
| Supervisar ≠ administrar (sin acciones de gestión) | Rutas (`contracts.manage`) + `actions` calculadas en el backend | `ContractsTest` + E2E |
| Minimización de datos personales del contratista | Esquema `contractors` | Revisión |
| Transiciones concurrentes seguras | `lockStatus` (`FOR UPDATE`) | `ContractsTest` |
| Reglas duplicadas en la base (CHECK) | Migraciones Fase 2 | `ContractsTest::testCheckConstraintsBackTheApplicationRules` |
| Enlaces externos sin `window.opener` | `ExternalLink.svelte` | Revisión |

## Controles implementados (Fase 4: documentos)

| Control | Dónde | Prueba |
|---|---|---|
| Archivos fuera del web root, nombre interno UUID, ruta nunca expuesta | `LocalFileStorage` | `DocumentsTest` |
| Lista blanca de extensión + MIME real (finfo) + verificación de contenido | `UploadValidator` | `DocumentsTest::testDangerousOrInvalidFilesAreRejected` |
| Ejecutable renombrado, HTML disfrazado de imagen, doble extensión: rechazados | `UploadValidator` | ídem |
| Límite de tamaño configurable | `UploadValidator` | `DocumentsTest` |
| Descarga solo vía API, con alcance del contrato, `nosniff`, CSP `sandbox`, `no-store` | `DocumentController`, `DocumentService` | `DocumentsTest` + E2E |
| Visor integrado: `?inline=1` permite enmarcar solo en SIGCON (`frame-ancestors 'self'`) y sirve el archivo con `default-src 'none'`; en PDF se omite `sandbox` porque desactiva el visor del navegador (ADR-021) | `DocumentController::csp()` | `DocumentsTest` |
| Rutas de almacenamiento sin `..` ni caracteres especiales | `LocalFileStorage::absolute` | Revisión |
| Carga/descarga/retiro auditados | `DocumentService` | `DocumentsTest` |
| La CSP específica de una respuesta no es sobrescrita por la global (defecto encontrado y corregido) | `SecurityHeadersMiddleware` | `DocumentsTest` |

## Controles implementados (Fases 5 a 9)

| Control | Dónde | Prueba |
|---|---|---|
| Informes: solo el contratista autor edita; solo el supervisor **asignado** revisa y aprueba; borradores invisibles para terceros | `ReportAccess` | `ReportsTest` |
| Versiones de informe inmutables con huella SHA-256 sobre JSON canónico; alteración directa en la BD detectada (`hash_valid`) | `ReportService` | `ReportsTest` |
| Decisiones concurrentes seguras (informes y pagos): `FOR UPDATE` y validación contra el estado real | `ReportService`, `PaymentService` | `ReportsTest`, `PaymentsTest` |
| Evidencias: validación por contenido real, límite de resolución antes de decodificar (memoria), HEIC rechazado con mensaje claro | `EvidenceImageProcessor` | `EvidenceImageProcessorTest` |
| Marca de agua con la hora **del servidor**; copia oficial sin metadatos EXIF del original | `EvidenceImageProcessor` | ídem |
| Coordenadas nunca inventadas: estado explícito + CHECK en la BD | `EvidenceController`, migración | `EvidenceTest` |
| Foto original solo con `evidence.view_original`, sin caché y **auditada** | `EvidenceService` | `EvidenceTest` |
| `Permissions-Policy`: cámara y GPS solo para el mismo origen | `frontend/static/.htaccess` | E2E |
| PDF con dompdf sin recursos remotos, sin PHP/JS embebido, con `chroot`; todo texto escapado | `ReportPdfRenderer` | `ReportPdfRendererTest` |
| Cola: reclamo exclusivo, bloqueo con vencimiento, errores guardados sin rutas del servidor, payload solo con identificadores | `JobRepository`, `JobRunner` | `JobsTest` |
| Pagos: presupuesto en centavos enteros con el contrato bloqueado; **separación de funciones** (quien registra o envía no aprueba) | `PaymentService`, `Money` | `PaymentsTest` |
| Reglas de elegibilidad configurables solo con `payments.configure`, validadas y auditadas (antes/después) | `PaymentEligibilityService` | `PaymentsTest` |
| Notificaciones: solo el destinatario las ve o marca; enlaces solo internos (CHECK + servicio + `safeRedirect`) | `NotificationService` | `NotificationsDashboardTest` |
| Exportaciones CSV dentro del alcance, auditadas y **protegidas contra inyección de fórmulas** | `ExportService`, `CsvWriter` | `CsvWriterTest`, `NotificationsDashboardTest` |

## Controles implementados (Fase 10: endurecimiento)

| Control | Dónde | Prueba |
|---|---|---|
| Límite de peticiones por usuario para operaciones costosas: cargas (60), evidencias (60), exportaciones (20) y PDF (120) por 10 minutos; 429 + `Retry-After` | `RateLimiter`, `RateLimitMiddleware`, `routes/api.php` | `RateLimitTest` |
| Verificación del despliegue: extensiones, límites, `AUTH_SECRET`, HTTPS, cookies seguras, almacenamiento fuera del sitio público, migraciones, Cron | `system:check` | `SystemCheckTest` |
| Backups cifrados (XChaCha20-Poly1305, autenticado): sin clave no hay backup; credenciales de MySQL nunca en la línea de comandos | `backup:database`, `BackupCipher` | `BackupCipherTest`, `DatabaseBackupTest` |
| Paquete de despliegue solo con archivos reconocidos por Git, sin `.env` ni credenciales, sin dependencias de desarrollo | `scripts/package-release.ps1` | Verificación del ZIP |
| IA deshabilitada por diseño: la configuración rechaza cualquier proveedor sin decisión institucional; minimización de datos definida | `DisabledAIProvider`, `Settings`, `ReportAssistant` | `AiPreparationTest` |
| Dependencias sin vulnerabilidades conocidas en producción | `composer audit`, `npm audit --omit=dev` | Fase 10 |

## Riesgos aceptados

| Riesgo | Motivo |
|---|---|
| `npm audit` reporta 3 vulnerabilidades **bajas** en `cookie` (dependencia de `@sveltejs/kit`) | Solo afecta al servidor de desarrollo de SvelteKit. SIGCON se publica como SPA estática (`adapter-static`): el paquete no llega a producción. La "corrección" que propone npm degradaría SvelteKit a 0.0.30 |
| La CSP del frontend incluye `'unsafe-inline'` en `script-src` | El navegador aplica **a la vez** la cabecera y la CSP con hash que genera SvelteKit en el HTML: la política efectiva exige el hash. `'unsafe-inline'` en `style-src` es necesario para los estilos de Svelte |
| Las coordenadas de las evidencias las reporta el dispositivo | Pueden ser inexactas o manipuladas; se registran con su precisión y la interfaz lo advierte (ADR-016) |

## Pendientes

- Recuperación de contraseña por correo: requiere el canal de correo (credenciales SMTP, ADR-019).
  Hoy la restablece un administrador.
- Cadena de hash en `audit_logs` (evidencia de manipulación a nivel de base de datos): a evaluar
  con control interno. Hoy la aplicación no expone ninguna operación de modificación o borrado
  de la auditoría.
- Verificación de cabeceras y de `system:check` en el servidor real de producción, durante la
  primera instalación (`docs/deployment/cpanel.md`, sección 6).
- Google Drive: la credencial de la cuenta de servicio irá fuera del repositorio y del sitio
  público, con permisos `0600`, cuando se habilite (diferido).

## Reglas para todo el código

1. El backend es la autoridad final: toda entrada se valida en el servidor.
2. Autorización en **cada** endpoint: permiso (`contracts.view`) **y** alcance (¿este
   contrato es del usuario?). Un ID ajeno responde `404`, no `403`, para no confirmar que
   existe.
3. SQL solo en los repositorios y solo con parámetros enlazados. Los nombres de columna
   dinámicos (orden) solo se aceptan desde una lista blanca.
4. Nunca registrar en el log contraseñas, tokens, cookies, cabeceras `Authorization` ni claves
   privadas.
5. Nunca devolver `password_hash`, tokens ni rutas físicas en las respuestas.
6. Salida en el frontend: Svelte escapa por defecto. `{@html}` está prohibido salvo con
   contenido sanitizado y revisado.

## Revisiones

- [Septiembre de 2026](review-2026-09.md): revisión completa al terminar las 10 fases (1 hallazgo alto, corregido).

## Reporte de vulnerabilidades

Reportar de forma privada al equipo responsable de SIGCON en la Alcaldía. No abrir issues
públicos con detalles de vulnerabilidades.
