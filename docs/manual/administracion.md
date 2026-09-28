# Manual de administración

Este manual es para quienes administran SIGCON: usuarios, roles, dependencias, contratistas y
contratos, además de la auditoría y las tareas programadas. Antes, lea la
[guía general](README.md). Para los pagos, vea el [Manual de pagos](pagos.md).

## Orden recomendado para empezar

1. **Dependencias** de la Alcaldía.
2. **Usuarios**: supervisores, contratistas y demás funcionarios, con sus roles.
3. **Contratistas**: la persona natural o jurídica, vinculada a su cuenta de SIGCON.
4. **Contratos**: datos, supervisor y obligaciones. Después se **activan**.

## 1. Usuarios

Menú **Usuarios**.

### Crear un usuario

1. Presione **Nuevo usuario**. Complete **Nombres**, **Apellidos**, **Correo electrónico**,
   **Teléfono** y **Dependencia**, y marque los **roles**.
2. Guarde. SIGCON muestra una **contraseña temporal una sola vez**. Entréguela a la persona
   por un medio seguro; no la envíe por correo junto con el usuario. Al ingresar, la persona
   deberá cambiarla.

### Acciones sobre un usuario

- **Desactivar usuario**: no podrá iniciar sesión y sus sesiones abiertas se cierran de
  inmediato. Úselo cuando alguien deja la entidad o termina su contrato. Los usuarios no se
  eliminan, para conservar la trazabilidad.
- **Activar usuario**: la persona vuelve a ingresar con su contraseña actual.
- **Restablecer contraseña**: genera una contraseña temporal nueva y cierra las sesiones
  abiertas. Verifique la identidad de quien la solicita antes de hacerlo.
- **Ver historial de auditoría de este usuario**.

Sus propios roles solo puede cambiarlos otro administrador.

## 2. Roles y permisos

Menú **Roles y permisos**. SIGCON trae tres roles del sistema:

| Rol | Para | Permisos principales |
|---|---|---|
| **Administrador** | Administración del sistema y de la contratación | Todos |
| **Supervisor** | Supervisores de contratos | Consultar los contratos asignados; planear tareas y registrar su avance; revisar, observar, aprobar y rechazar informes |
| **Contratista** | Contratistas | Consultar sus contratos y tareas; registrar evidencias y presentar informes |

Los permisos del rol Administrador no se modifican desde la aplicación, para que el sistema no
quede sin administración.

### Crear un rol

Presione **Nuevo rol**. Indique **Código**, **Nombre** y **Descripción**, marque los permisos
(agrupados por módulo) y presione **Crear rol**. Casos típicos:

- **Pagos**: roles separados para quien gestiona (registrar pagos…), quien aprueba y quien
  registra el pago efectuado (ver [Manual de pagos](pagos.md)). Asigne cada rol a personas
  distintas: la separación de funciones es el propósito.
- **Consulta**: funcionarios que solo consultan todos los contratos, con el permiso de ver
  todos los contratos y sin permisos de gestión.

Un rol creado se puede modificar o eliminar (**Eliminar rol**); la pantalla muestra cuántos
usuarios lo tienen. Los roles del sistema no se eliminan.

> Para que alguien pueda ser **supervisor** de un contrato, su rol debe tener el permiso de
> supervisar contratos. El rol Supervisor ya lo tiene.

## 3. Dependencias

Menú **Dependencias**: código y nombre de cada dependencia, con el número de contratos en
ejecución. Puede crearlas (**Nueva dependencia**), **Editar**, **Desactivar** y **Activar**.
Una dependencia inactiva no recibe contratos nuevos.

## 4. Contratistas

**Contratistas** es su punto de partida: cada tarjeta muestra el nombre, el documento y el
estado. Al abrir una verá:

- un resumen (estado, tipo, cuántos contratos y la cuenta de acceso);
- **cada contrato con su avance**, sus obligaciones y sus tareas;
- el **último informe presentado**, con el botón para abrirlo;
- **Editar información** y **Activar/Desactivar**.


Para registrar uno nuevo: **Nuevo contratista**. Es un asistente de tres pasos:

1. **Datos del contratista**
   - **Identificación**: tipo de persona, tipo y número de documento (con dígito de
     verificación si es NIT).
   - **Contacto**: correo, teléfono y dirección.
   - **Cuenta de acceso a SIGCON**: vincule la cuenta de usuario con rol **Contratista**. Sin
     cuenta vinculada, la persona no puede consultar sus contratos ni presentar informes.
2. **Contrato firmado**: adjunte el **PDF del contrato** (obligatorio) y complete los datos del
   contrato (número, dependencia, objeto, fechas, valor, supervisor). Se guarda en borrador.
3. **Obligaciones**: SIGCON lee la sección **"Obligaciones específicas del contratista"** del
   PDF y le propone la lista. **Revísela**: corrija el texto, quite las que no correspondan,
   agregue las que falten y ajuste el peso. Solo al pulsar **Registrar** se crean.

Tenga en cuenta:

- La lectura necesita un **PDF con texto**. Si el contrato está **escaneado** (es una imagen),
  protegido o no tiene una lista numerada de obligaciones, SIGCON lo dice y usted las escribe
  en la misma pantalla.
- No se usa inteligencia artificial ni se envía el contrato a ningún servicio externo: la
  lectura se hace en el servidor de la Alcaldía.
- Si aún no tiene el contrato, use **Terminar ahora** después del paso 1 y regístrelo más
  tarde desde **Contratos**. En un contrato en borrador, **Leer del contrato firmado** hace la
  misma lectura.

Un contratista **desactivado** no recibe contratos nuevos; sus contratos en ejecución no
cambian.

## 5. Contratos

Menú **Contratos** → **Nuevo contrato**.

### Crear el contrato (borrador)

Complete **Número de contrato**, **Dependencia**, contratista, **Objeto del contrato**,
**Fecha de suscripción**, **Fecha de inicio**, **Fecha de terminación**, **Valor total (COP)**,
**Pagos pactados**, **Supervisor** y el **enlace al proceso en SECOP II**. El contrato queda en
**Borrador**.

**Pagos pactados** es cuántos pagos contempla el contrato (por ejemplo, 11). Con ese dato,
**Estadísticas** puede decir cuántos pagos lleva el contratista y cuántos le faltan. Si se deja
vacío, esa columna aparece en blanco. Solo se puede registrar o corregir mientras el contrato
está en borrador.

Mientras está en borrador puede **Editar borrador**, **Cambiar supervisor** o **Descartar
borrador**. Un borrador descartado deja de aparecer y su número no puede reutilizarse.

### Registrar las obligaciones

En **Obligaciones y avance**, presione **+ Agregar obligación** e indique **Título**,
**Descripción**, **Peso** y **Fecha objetivo**. El peso define cuánto aporta cada obligación
al avance general.

Registre **todas** las obligaciones antes de activar. Después de activar el contrato, las
obligaciones ya no se pueden modificar: hacerlo requeriría una modificación contractual
(otrosí), que aún no está habilitada en SIGCON. Sí puede agregar **tareas** dentro de las
obligaciones.

### Tareas y subtareas

Dentro de cada obligación, la administración y el **supervisor del contrato** pueden crear y
editar **tareas** y **subtareas**, también con el contrato en ejecución. El contratista no las
edita: solo las consulta. El **avance** de cada tarea lo registra el supervisor al verificar lo
ejecutado.

### Prioridad de obligaciones y tareas

En **Obligaciones y avance**, cada obligación, tarea o subtarea tiene un selector de
**prioridad**: alta, media, baja o sin prioridad. El contratista la ve en su panel y en sus
actividades, pero no puede cambiarla. Se puede asignar con el contrato en borrador, activo o
suspendido, y cada cambio queda en la auditoría.

### Activar

Presione **Activar contrato**. SIGCON exige un **supervisor** habilitado (distinto del propio
contratista), un contratista activo y una dependencia activa. Desde ese momento el contratista
puede registrar evidencias e informes, y el supervisor puede planear tareas y registrar su avance.

### Cambios de estado

| Acción | Desde | Observación |
|---|---|---|
| **Suspender** | Activo | Obligatoria (motivo o acto administrativo) |
| **Reanudar** | Suspendido | Obligatoria |
| **Terminar** | Activo o Suspendido | Obligatoria |
| **Liquidar** | Terminado | Obligatoria |
| **Archivar** | Terminado o Liquidado | Opcional |

Los botones aparecen en **Estado** (por ejemplo, **Suspender contrato**). Cada cambio pide
confirmación y queda en el **Historial** del contrato y en la auditoría.

- En un contrato **suspendido** no se registran avances ni evidencias.
- En un contrato **terminado** el contratista aún puede presentar informes, por ejemplo el
  del último período.
- **Archivado** es el estado final: ya no admite cambios.

**Cambiar el supervisor** de un contrato activo o suspendido exige indicar el motivo (por
ejemplo, el acto de designación). El nuevo supervisor revisa los informes siguientes.

## 6. Documentos, evidencias e informes

Como administrador puede:

- cargar y retirar **documentos** de cualquier contrato no archivado;
- retirar **evidencias** registradas por error, con motivo;
- descargar la **foto original** de una evidencia, sin marca de agua. La descarga queda en la
  auditoría;
- **Reabrir informe** aprobado, con motivo, si se descubre un error. El informe vuelve al
  contratista con observaciones y la aprobación anterior se conserva en el historial. No es
  posible si el pago que sustenta ya fue aprobado o pagado.

## 7. Auditoría

Menú **Auditoría**: registro de las acciones relevantes (inicios de sesión, cambios de estado,
descargas de originales, cambios de roles y permisos, etc.), con fecha, usuario, detalle y
dirección IP. Puede filtrar por **Acción** y por fechas (**Desde**, **Hasta**). Desde el
detalle de un usuario se abre su historial.

El registro de auditoría no se puede modificar ni borrar desde la aplicación.

## 8. Tareas programadas

Menú **Tareas programadas**. SIGCON realiza algunos trabajos en segundo plano, como generar el
PDF de los informes. Un procesador los ejecuta cada 5 minutos mediante el Cron del servidor.

- Si aparece "El procesador no se ejecuta desde hace más de… minutos" o "nunca se ha
  ejecutado", avise al responsable técnico: el Cron del servidor no está funcionando.
- Una tarea **fallida** muestra el error. Cuando la causa esté resuelta, use **Reintentar**.
  SIGCON ya reintenta automáticamente varias veces antes de marcarla como fallida.
- El Panel de control avisa si hay tareas fallidas.

## 9. Historial y estadísticas

El **Panel de control** avisa de las **entregas próximas o vencidas**: tareas cuya fecha
objetivo se cumple dentro de los próximos 15 días o ya pasó, con el contrato y el contratista.
También muestra el **semáforo de entregables**: informes revisados, en revisión, con
observaciones y pendientes por revisar.

**Historial** muestra, en orden de fecha, todo lo que ocurre en los contratos: avances y
evidencias de los contratistas, revisiones de los supervisores, cambios de estado y pagos, con
el nombre de quien actuó. Se puede filtrar por tipo de evento. Cada rol ve lo que le
corresponde: la administración ve todo, el supervisor los contratos que supervisa y el
contratista los suyos.

**Estadísticas** muestra el esquema de pagos por contrato: meses de plazo, avance, pagos
pactados, pagados, en trámite y cuántos faltan, además de los totales. También la ven los
supervisores, limitada a sus contratos.

## 10. Reportes

En el **Panel de control**, la sección **Reportes** exporta a CSV (se abre en Excel) los **contratos con
avance y saldo** y los **pagos**, dentro de su alcance.

## Buenas prácticas

- Desactive a tiempo las cuentas de quienes dejan la entidad.
- Use el principio de mínimo privilegio: asigne solo los roles necesarios.
- Revise periódicamente la **Auditoría** y las **Tareas programadas**.
- No comparta la cuenta de administrador. Cada persona debe tener su propia cuenta.
