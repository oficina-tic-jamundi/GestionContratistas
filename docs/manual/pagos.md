# Manual de pagos

Este manual es para quienes tramitan los pagos de los contratos en SIGCON: quien los registra,
quien los aprueba y quien registra que se pagaron. Antes, lea la [guía general](README.md).

> **Pendiente de definición institucional.** La Alcaldía aún no ha definido qué dependencias o
> cargos cumplen cada función. Hoy el rol **Administrador** tiene todos los permisos de pagos.
> Cuando se definan, el administrador creará los roles correspondientes (ver
> [Manual de administración](administracion.md#2-roles-y-permisos)).

## Funciones y permisos

| Función | Permiso | Qué hace |
|---|---|---|
| Gestión de pagos | Registrar pagos… | Registra el pago, evalúa su elegibilidad, lo envía a aprobación, modifica el valor y anula |
| Aprobación | Aprobar o devolver pagos… | Aprueba o devuelve los pagos listos para aprobación |
| Tesorería | Registrar que un pago aprobado fue pagado | Registra la fecha y el comprobante del pago efectuado |
| Configuración | Configurar las reglas de elegibilidad | Activa, desactiva y ajusta las reglas |

**Separación de funciones**: quien registró o envió un pago **no puede aprobarlo**, aunque
tenga el permiso. SIGCON lo impide y lo indica en pantalla.

## Ciclo de un pago

```
Informe aprobado → Registrar pago → [En preparación] → Enviar a aprobación
  → [Listo para aprobación] → Aprobar → [Aprobado] → Registrar pago efectuado → [Pagado]
```

En **En preparación** o **Listo para aprobación**, el pago se puede **anular** con motivo.

## 1. Registrar un pago

Cada pago se sustenta en un **informe aprobado**, y cada informe admite un solo pago.

1. Abra el contrato y, en la sección **Pagos**, presione **Registrar pago**.
2. Elija el **informe aprobado** y escriba el **valor** en pesos (sin puntos ni signos).
3. Presione **Registrar**.

El valor debe ser mayor que cero y no puede superar el **saldo disponible** del contrato.
Arriba de la lista de pagos verá el valor del contrato, lo **comprometido** (pagos no
anulados), lo **pagado** y el **saldo disponible**.

## 2. Evaluar la elegibilidad

En el detalle del pago, **Evaluar elegibilidad** revisa las reglas vigentes y muestra el
resultado de cada una: ✓ cumple, ✕ no cumple o – desactivada, con la razón. El resultado queda
guardado con su nombre y la fecha.

| Regla | Qué verifica | Estado inicial |
|---|---|---|
| Contrato en un estado que permite pagos | El contrato está en un estado permitido (inicialmente, Activo) | Activa |
| Informe del período aprobado | El informe que sustenta el pago sigue aprobado | Activa |
| Sin observaciones pendientes | Ningún informe del contrato está **Con observaciones** | Activa |
| Documentos requeridos del contrato | El contrato tiene cargados los tipos de documento exigidos | Desactivada |
| Evidencias fotográficas mínimas del período | Hay al menos la cantidad de evidencias exigida en el período | Desactivada |
| Avance mínimo del contrato | El avance general alcanza el porcentaje exigido | **Activa, 100 %** |

Por decisión de la Alcaldía, **no se paga hasta completar el 100 % del avance**: esa regla está
activa. El contratista ve en su panel cuánto le falta. Las reglas que siguen desactivadas
dependen de definiciones institucionales pendientes: qué documentos son obligatorios y cuántas
evidencias se exigen.

## 3. Enviar a aprobación

Con el pago **En preparación**:

- **Modificar valor**, si hace falta.
- **Enviar a aprobación**. SIGCON vuelve a evaluar las reglas. Si alguna no se cumple, el pago
  no avanza y verá cuál falla.

El pago queda **Listo para aprobación**, y quienes pueden aprobar lo ven en su tablero
(**pagos listos para aprobación**).

## 4. Aprobar o devolver

Quien aprueba (una persona distinta de quien registró y envió el pago):

- **Aprobar**: SIGCON evalúa las reglas una vez más; si se cumplen, el pago queda **Aprobado**
  con su nombre y la fecha.
- **Devolver para ajustes**: indique el motivo; el pago vuelve a **En preparación** para que
  quien lo gestiona lo ajuste y lo envíe de nuevo.

## 5. Registrar el pago efectuado

Cuando Tesorería efectúa el pago, en el detalle del pago presione **Registrar pago efectuado**
e indique:

- la **fecha de pago**: no puede ser posterior a hoy ni anterior a la aprobación;
- el **número de comprobante**.

El pago queda **Pagado** y el valor pasa de "comprometido" a "pagado". El tablero de Tesorería
muestra los **pagos aprobados por registrar como pagados**.

## 6. Anular un pago

Un pago **En preparación** o **Listo para aprobación** se puede anular con **Anular pago**,
indicando el motivo. El valor deja de estar comprometido y el informe queda disponible para
registrar un pago nuevo. Un pago aprobado o pagado no se anula.

## 7. Consultas y exportaciones

- El menú **Pagos** lista los pagos de su alcance, con su estado.
- En el **Panel de control**, la sección **Reportes** permite exportar a CSV (se abre en Excel) los
  **contratos con avance y saldo** y los **pagos**. Requiere el permiso de exportación.

## 8. Configurar las reglas

Con el permiso de configuración, en **Pagos** → **Reglas de elegibilidad** puede activar o
desactivar cada regla y ajustar sus parámetros: estados permitidos, tipos de documento,
cantidad mínima de evidencias o avance mínimo. Cada cambio queda en la auditoría.

> Cambie las reglas solo según lo que defina la Alcaldía por escrito. Un cambio aplica a todas
> las evaluaciones siguientes.

## Trazabilidad

El detalle de cada pago muestra quién lo registró, quién lo aprobó, quién registró el pago
efectuado y el **historial** completo, con fecha, persona y comentario de cada paso.
