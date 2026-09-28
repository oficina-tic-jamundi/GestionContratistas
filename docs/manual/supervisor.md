# Manual del supervisor

Este manual explica cómo hacer seguimiento en SIGCON a los contratos que usted supervisa y cómo
revisar los informes de los contratistas. Antes, lea la [guía general](README.md): ingreso,
contraseña y notificaciones.

## Qué puede hacer

- Consultar los contratos en los que usted figura como **supervisor**: obligaciones, avance,
  evidencias, documentos, informes, pagos e historial.
- **Revisar los informes**: aprobarlos, solicitar correcciones o rechazarlos.
- **Organizar las tareas** de cada obligación y **registrar su avance** al verificar lo
  ejecutado. El contratista no edita nada: solo consulta, sube evidencias y presenta informes.

La asignación de supervisores y los datos del contrato los gestiona la administración. Si un
contrato que supervisa no aparece, o aparece uno que no le corresponde, comuníquelo al
administrador.

## 1. Su tablero

En el **Panel de control**, la sección **Informes por revisar** lista los informes enviados o
corregidos que esperan su revisión. También recibe una notificación cada vez que un contratista
envía o reenvía un informe.

## 2. Contratistas

En el menú, **Contratistas** muestra únicamente a los contratistas de los contratos que usted
supervisa. Al abrir uno verá, por cada contrato, la **barra de avance**, sus obligaciones y
tareas, y el **último informe presentado** con el botón **Ver informe** para revisarlo.

Al pulsar el **nombre de una obligación** se abre la actividad: **qué debe hacer** el
contratista, sus tareas con el estado de cada una y, debajo, el **informe del período**: lo que
el contratista reportó en esa obligación, el resumen, los **anexos** (que puede previsualizar sin
salir de SIGCON) y **su decisión** —aprobar, solicitar correcciones o rechazar con el motivo—.
Son las mismas decisiones de la pantalla del informe (punto 5), tomadas donde está mirando el
trabajo.

Si allí dice que el contratista no ha enviado informes, es porque lo que tenga está **en
borrador**: los borradores y sus anexos solo los ve él hasta que los envía.

## 3. Contratos que supervisa

En el menú, **Contratos que supervisa** lista sus contratos. En cada uno puede revisar:

- **Obligaciones y avance**: las obligaciones con sus tareas y el avance que usted registra.
  **Ver avances** muestra el historial, con fecha, porcentaje y la nota de cada registro.
- **Evidencias fotográficas**: cada foto trae una marca de agua con la hora oficial del
  servidor, el contrato y la ubicación. En el detalle verá quién la registró, la hora oficial,
  la ubicación (con enlace a OpenStreetMap) y su precisión.
- **Documentos**: usted **sube el contrato firmado** y sus soportes, y con **Ver** los abre
  dentro de SIGCON (PDF e imágenes) sin descargarlos.
- **Informes**, **Pagos** e **Historial**.

**Cómo leer la ubicación de una evidencia:**

- **Precisión baja**: el dispositivo informó una ubicación poco exacta.
- **Sin ubicación**: el contratista no dio el permiso o el GPS no respondió.
- Las coordenadas las reporta el celular del contratista y pueden ser inexactas. La hora que
  vale es la **hora oficial del servidor**; la del dispositivo es solo informativa.

## 4. Tareas y avance

En **Obligaciones y avance** del contrato:

- **+ Agregar tarea** o **+ Agregar subtarea** desglosa una obligación. También puede
  **Editar** o **Eliminar** mientras no tengan avances registrados.
- **Registrar avance** en el nivel más detallado: indique el **avance acumulado** (el total
  logrado hasta hoy, no lo que avanzó esta vez) y describa **qué se verificó**. La nota es
  obligatoria y queda en **Ver avances** con su nombre y la fecha.
- El avance de las obligaciones y del contrato se calcula solo, como promedio ponderado.

Los avances se registran con el contrato **activo**. Apóyese en las evidencias fotográficas y
en los informes del contratista.

## 5. Revisar un informe

1. Abra el informe desde el tablero, la notificación o el menú **Informes**.
2. Presione **Iniciar revisión**. El informe queda **En revisión** y se habilitan las
   decisiones.
3. Revise el **resumen del período**, lo realizado en **cada obligación**, los avances y las
   evidencias incluidas, y los **Anexos del informe**. El **PDF** de la versión está en
   **Versiones** (**Descargar PDF**) cuando termina de generarse.
4. Decida:

| Acción | Cuándo | Qué pasa |
|---|---|---|
| **Aprobar** | El informe es correcto | Se aprueba la versión revisada, con su nombre y la fecha. El comentario es opcional. |
| **Solicitar correcciones** | Hay que ajustar algo | Escriba una **observación general** y, si quiere, observaciones en obligaciones específicas. El informe vuelve al contratista **Con observaciones**. |
| **Rechazar** | El informe no puede corregirse (por ejemplo, no corresponde al contrato o al período) | Indique el **motivo del rechazo**. El contratista deberá presentar un informe nuevo para ese período. |

El contratista recibe una notificación con su decisión. Cuando corrige y reenvía, el informe
llega como **Corregido (reenviado)** en una nueva versión. Vuelva a **Iniciar revisión**.

> Sea específico en las observaciones: el contratista las ve junto a cada obligación al
> corregir.

### Versiones y trazabilidad

Cada envío del contratista crea una **versión** del informe. SIGCON conserva todas, con su PDF,
y en **Revisiones** muestra cada decisión: quién la tomó, sobre qué versión, cuándo y con qué
comentario. Nada de esto se puede modificar después.

### Reabrir un informe aprobado

Si después de aprobar se descubre un error, la **reapertura** la hace el administrador. Queda
con motivo, y la aprobación anterior se conserva en el historial. No es posible reabrir un
informe si el pago que sustenta ya fue aprobado o pagado.

## 6. Pagos

En **Pagos** puede consultar los pagos de sus contratos y el saldo de cada uno. El registro,
la aprobación y el pago los realizan las personas encargadas de pagos (ver
[Manual de pagos](pagos.md)).

## 7. Historial y estadísticas

**Historial** lista, en orden de fecha, lo que ocurre en los contratos que usted supervisa:
avances y evidencias de sus contratistas, sus propias revisiones, cambios de estado y pagos,
con el nombre de quien actuó. Puede filtrar por tipo de evento.

**Estadísticas** muestra, para esos mismos contratos, el esquema de pagos: meses de plazo,
avance, pagos pactados, pagados, en trámite y cuántos faltan.

## Preguntas frecuentes

**No veo los botones Aprobar ni Solicitar correcciones.** Primero presione **Iniciar
revisión**. Si tampoco aparece ese botón, el informe no está esperando revisión, o usted no es
el supervisor asignado a ese contrato.

**No veo el borrador que el contratista está elaborando.** Los borradores solo los ve su autor
hasta que los envía.

**Aprobé por error.** Solicite al administrador la reapertura, con el motivo.
