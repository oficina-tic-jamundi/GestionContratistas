# ADR-021: Panel del contratista, prioridad de actividades e historial

- Estado: Aceptado
- Fecha: 2026-09-19

## Contexto

La Alcaldía pidió rediseñar la experiencia del contratista con base en unas maquetas: un panel
con el progreso del contrato, el estado de cada tarea y subtarea (aprobada, en revisión, con
observaciones, pendiente), la prioridad (alta, media, baja), un aviso de pago bloqueado, un
aviso de entrega próxima y un menú propio (Panel de control, Mis actividades, Historial).

Varios de esos elementos no existían en SIGCON y no pueden inventarse (reglas críticas):

- no había prioridad;
- el supervisor aprueba u observa **el informe completo**, con observaciones por obligación,
  no cada subtarea;
- no hay plazos de entrega definidos para los informes;
- la regla "pago con 100 % de avance" existe, pero desactivada hasta una decisión institucional
  (ADR-018).

## Decisión

Decisiones tomadas por el responsable del proyecto el 2026-09-19:

1. **Prioridad**: la asigna la Alcaldía. Es una columna nueva `activities.priority`
   (`high` | `medium` | `low` | NULL). Solo quien tiene `contracts.manage` la cambia, con el
   endpoint propio `PUT /activities/{uuid}/priority`, y el cambio queda en la auditoría. Se
   permite con el contrato en borrador, activo o suspendido, porque no altera las obligaciones
   pactadas. El contratista solo la consulta.
2. **Estado de tareas y subtareas**: se **deriva de los informes**, sin crear un flujo de
   aprobación nuevo (`WorkStatusResolver`, sin almacenamiento propio):
   - *Aprobada*: al 100 % y llegó al 100 % dentro de un período ya cubierto por un informe
     aprobado.
   - *Con observaciones*: su obligación fue observada en la última revisión del último informe
     presentado. Lo ya aprobado se conserva aprobado.
   - *En revisión*: tuvo avances dentro del período del informe que espera revisión.
   - *Pendiente*: en cualquier otro caso.
   - Los elementos con hijos toman el estado más urgente de sus hojas (observación > revisión)
     o "Aprobada" si todas lo están. Una obligación observada siempre se muestra "Con
     observaciones".
3. **Avisos**: solo con reglas ya configuradas. El aviso de pago aparece únicamente si la regla
   de elegibilidad "Avance mínimo del contrato" está activa, con el porcentaje que la Alcaldía
   haya fijado. **No hay aviso de vencimiento** mientras no existan plazos institucionales. En
   su lugar, el panel avisa de hechos reales: informes con observaciones y borradores sin enviar.
4. **Menú del contratista**: Panel de control, Mis actividades, Mis informes e Historial. Las
   notificaciones están en la campana y los pagos en el panel. Aplica a quien ejecuta
   actividades sin perfil de supervisión ni de administración. Los demás roles conservan su menú.

### Implementación

- `GET /dashboard` agrega `my_contracts` para quien tiene `activities.execute`
  (`ContractorOverviewService`). Cubre los contratos propios activos, suspendidos o terminados.
- `GET /history` une avances, evidencias y el historial de negocio de contratos, informes y
  pagos, con el alcance del usuario. Es paginado y se puede filtrar por tipo.
- Frontend: `ContractorDashboard` (progreso, indicadores, avance por obligación,
  estadísticas, pagos), páginas `/activities` y `/history`, y selector de prioridad en el árbol
  de obligaciones para la administración.

### Ampliaciones del 2026-09-19 (segunda tanda de decisiones)

5. **Historial por rol**: `GET /history` reemplaza a `/me/history` y aplica el alcance de
   contratos: la administración ve todo (quién hizo cada cosa), el supervisor ve los contratos
   que supervisa —lo suyo y lo de sus contratistas— y el contratista ve los suyos, incluidas
   las decisiones del supervisor sobre sus informes. El menú "Historial" aparece en los tres
   perfiles.
6. **Pago solo con el 100 % de avance**: por decisión de la Alcaldía, la regla de elegibilidad
   "Avance mínimo del contrato" queda **activa con 100 %** (migración
   `20260919200000`). Sigue siendo configurable desde "Reglas de elegibilidad".
7. **Pagos pactados** (`contracts.payment_count`, 1 a 120, opcional): lo registra la Alcaldía
   con el contrato en borrador. Permite decir cuántos pagos lleva el contratista y cuántos le
   faltan sin suponer una periodicidad. Si no se registra, "faltan" queda vacío.
8. **Estadísticas** (`GET /statistics/payments`, administración y supervisión): por contrato,
   meses de plazo, avance, pagos pactados, pagados, en trámite y pendientes, con totales.
9. **El contratista no edita nada**: solo consulta sus tareas, sube evidencias y presenta
   informes. Cambia lo definido en ADR-013:
   - `activities.plan` (nuevo, administración y supervisor): crear, modificar y eliminar tareas
     y subtareas, también con el contrato en ejecución. Las obligaciones siguen siendo del
     contrato y solo se tocan en borrador (ADR-012).
   - `activities.progress` (nuevo, supervisor del contrato): registrar el avance. **El avance
     deja de ser una declaración del contratista y pasa a ser una verificación del supervisor**,
     con su nota y su historial.
   - `activities.execute` (contratista) queda como "actuar como contratista del contrato":
     subir documentos, evidencias e informes.
   - Migración `20260919300000`.

### Ampliaciones del 2026-09-20 (estructura pedida por la Alcaldía)

10. **Alertas preventivas de entrega**: el tablero muestra las tareas con **fecha objetivo**
    vencida o dentro de los próximos 15 días, con los días que faltan. La fecha la registra
    quien planea la tarea (campo que ya existía): SIGCON no inventa plazos. Cada rol ve las de
    su alcance.
11. **Semáforo de entregables** en el tablero de administración y supervisión: informes
    Revisados (verde), En revisión (amarillo), Con observaciones (naranja) y Pendientes por
    revisar (rojo). Son las mismas cifras del tablero, agrupadas por estado de revisión.
12. **El supervisor sube el contrato** y sus soportes en los contratos que supervisa
    (`contracts.supervise`), sin poder retirar documentos de otros.
13. **Visor de documentos integrado**: PDF e imágenes se previsualizan en un diálogo dentro de
    SIGCON, sin salir de la plataforma ni descargar el archivo.
    - El archivo se sirve con `frame-ancestors 'self'` solo cuando se pide en línea
      (`?inline=1`); descargado conserva `frame-ancestors 'none'`.
    - Para el PDF no se usa la directiva `sandbox`, porque desactiva el visor de PDF del
      navegador. El archivo se sigue sirviendo con `default-src 'none'` (no puede cargar ni
      ejecutar nada) y con `X-Content-Type-Options: nosniff` (no puede interpretarse como HTML).

### Ampliaciones del 2026-09-23 (estructura por rol)

14. **Un menú corto por rol**, para que cada persona vea solo lo que usa:
    - Contratista: Panel de control y Mis actividades.
    - Supervisor: Panel de control y Contratistas.
    - Administración: Panel de control, Contratistas, Historial, Estadísticas y Roles y
      permisos (con Usuarios como pestaña de esa sección).
    Contratos, pagos, dependencias, auditoría y tareas programadas siguen disponibles desde las
    pantallas donde se necesitan; ya no ocupan el menú.
15. **Contratistas es el punto de entrada** de la administración y de la supervisión: tarjetas
    con nombre, documento y estado, y al abrir una, la ficha del contratista con el **avance de
    cada contrato**, sus obligaciones y tareas, su información y el último informe presentado
    (con acceso directo a revisarlo).
16. **El supervisor ve solo a sus contratistas**: `GET /contractors` y `/contractors/{uuid}`
    aplican el alcance de los contratos que supervisa (antes eran exclusivos de la
    administración). No puede crearlos ni editarlos.

17. **Mis actividades** se presenta como tarjetas agrupadas por contrato (título, prioridad,
    número de tareas, fecha de entrega y avance), ordenadas con los contratos en ejecución
    primero. El contratista no edita nada allí: solo consulta y abre.
18. **Cada actividad tiene su propia pantalla** (`GET /activities/{uuid}` y
    `/actividades/{uuid}`): qué debe hacer, de qué obligación depende, sus tareas con el estado,
    las observaciones que el supervisor dejó sobre esa obligación y el resumen del contrato.
19. **El informe se envía desde la actividad**, no desde una pantalla aparte. El texto escrito o
    dictado se guarda como la descripción de esa obligación en el informe abierto del período; el
    archivo se adjunta clasificado como **Acta** o **Informe de actividades** (tipos nuevos de
    `document_types` para informes) y las fotos como anexo. Si no hay informe abierto, se crea con
    el período sugerido al enviar. El envío usa el flujo existente de `PUT /reports/{uuid}` +
    `POST /reports/{uuid}/submit`: no hay una ruta nueva ni un estado nuevo.
20. **El dictado es del navegador** (Web Speech API, Edge o Chrome): se activa solo cuando el
    contratista pulsa el botón, se avisa en pantalla que el audio va al servicio del navegador y
    siempre queda la opción de escribir. Si el navegador no lo admite, el botón no aparece.
    Las plantillas oficiales de Acta e Informe se publicarán cuando la Alcaldía las entregue.

21. **El supervisor revisa desde la actividad.** Al abrir una obligación desde la ficha del
    contratista ve, en la misma pantalla, qué debe hacerse y el informe del período: lo reportado
    en esa obligación, el resumen, los anexos previsualizables en el visor integrado y las
    decisiones (aprobar, solicitar correcciones, rechazar con motivo). Reutiliza
    `ReviewActions`, así que no hay reglas ni estados nuevos: el backend valida lo mismo que en
    `/reports/{uuid}`.
22. **La pantalla se adapta al rol, no a la ruta**: `/actividades/{uuid}` muestra el formulario
    de envío a quien presenta el informe y el panel de revisión a quien tiene `reports.review`.
    El informe que se carga es el que está en revisión (para quien revisa) o el abierto (para el
    contratista); si no hay ninguno, el último presentado, como consulta.

23. **Los anexos se ven donde se trabaja.** Una sola lista (`ReportAnnexList`) muestra los
    documentos vigentes del informe con previsualización en el visor integrado: al contratista
    mientras arma el borrador y después de enviarlo, y a quien revisa antes de decidir.
24. **El borrador se anuncia como tal.** Mientras el informe no se envía, el contratista lee
    "el supervisor no lo ve hasta que pulse Enviar al supervisor", y quien revisa, cuando no hay
    informe enviado, lee que los borradores no se ven hasta que el contratista los envía. Es la
    regla de ADR-015 (el borrador es privado), ahora explicada donde aparece la duda.
25. **La pantalla nombra el estado real**: "Informe enviado", "Informe aprobado" (con quién y
    cuándo lo aprobó) o "Informe rechazado", con lo reportado en la obligación y sus anexos.

## Consecuencias

- El contratista ve su situación sin inventar datos: todo sale de avances, informes y reglas
  reales. Con la regla de avance mínimo en 100 %, el panel le dice cuánto le falta para
  habilitar el pago.
- "Aprobada" significa "cubierta por un informe aprobado". Si en el futuro se exige aprobación
  por subtarea, será un flujo nuevo del supervisor y reemplazará la derivación.
- Cuando la Alcaldía defina plazos de entrega de informes, podrá agregarse el aviso de
  vencimiento con esa regla explícita.
- El avance que bloquea el pago lo registra ahora el supervisor, no el contratista: el
  porcentaje del panel es una verificación de la Alcaldía.
- Quien administra puede planear tareas pero no registrar avance; si la Alcaldía quiere que
  también lo registre, basta con darle el permiso `activities.progress` desde "Roles y permisos".
- Exigir el 100 % de avance cambia el trámite de pagos: las pruebas que verifican otras reglas
  la desactivan explícitamente en su preparación.
- Enviar desde la actividad no crea un canal paralelo: el supervisor sigue revisando informes
  por período, con su historial de versiones y su hash. La actividad es solo la puerta de entrada.
- Pruebas: `WorkStatusResolverTest` (unitaria) y `ContractorPanelTest` (prioridad, estados
  derivados, aviso de pago, historial por rol y estadísticas de pagos). La matriz de
  autorización cubre las rutas nuevas.
