# ADR-013: Obligaciones, tareas, subtareas y cálculo del avance

- Estado: **Aceptado como propuesta técnica — pendiente de validación por Supervisión/Contratación.**
- Fecha: 2026-09-18

> **Actualizado por [ADR-021](ADR-021-panel-del-contratista.md) (2026-09-19):** el avance ya no
> lo declara el contratista. Lo registra el **supervisor** del contrato (`activities.progress`)
> y las tareas las planean la administración y el supervisor (`activities.plan`). El cálculo
> ponderado y las reglas de coherencia descritas aquí no cambian.

## Contexto

El §18 pide controlar el cumplimiento con la jerarquía contrato → obligación → tarea →
subtarea, y "evitar que el porcentaje de avance sea manipulado de forma incoherente". No existe
una regla institucional definida para calcularlo; por §84 se propone una regla simple,
explicable y centralizada.

## Decisión

### Estructura

Una tabla `activities` con tres niveles (`obligation`, `task`, `subtask`) y un árbol por
contrato. La base de datos impide niveles incoherentes (una obligación no tiene padre; los
demás sí).

### Quién hace qué

| Acción | Quién | Cuándo |
|---|---|---|
| Registrar/editar/eliminar obligaciones | `contracts.manage` | Contrato en **borrador** |
| Planear tareas y subtareas | Contratista del contrato (`activities.execute`) | Contrato **activo** |
| — | o `contracts.manage` | Borrador o activo |
| Declarar avance | **Solo el contratista del contrato** | Contrato **activo** |
| Consultar | Cualquiera con alcance sobre el contrato | Siempre |

El administrador y el supervisor **no** declaran avance: el avance es una declaración del
contratista que el supervisor verificará en los informes (Fase 5).

### Cálculo

- El avance se **declara** solo en las hojas (subtareas, o tareas/obligaciones sin hijos),
  como porcentaje entero 0–100, **siempre con una nota** de lo realizado (mín. 10 caracteres).
- Los nodos con hijos tienen avance **calculado**:
  `Σ(avance_hijo × peso_hijo) / Σ(peso_hijo)`, con peso 1 por defecto (configurable por
  elemento, 0,01–1000). Aritmética entera en centésimas, redondeo "mitad hacia arriba".
- Avance del contrato = promedio ponderado de sus obligaciones. Sin obligaciones = 0 %.
- El avance se recalcula en la misma transacción, con el contrato bloqueado (`FOR UPDATE`).

### Reglas de coherencia

- No se agregan hijos a un elemento que ya tiene avances declarados (evita que el avance
  "reportado" desaparezca al subdividirlo).
- No se eliminan elementos con hijos ni con avances registrados (trazabilidad).
- Las correcciones a la baja se permiten, quedan en el historial con su nota.
- Cada declaración queda en `progress_updates` (solo inserción): valor anterior, nuevo,
  nota, autor y fecha/hora del servidor.

## Decisiones pendientes

1. ¿El peso de las obligaciones debe venir del contrato (ej. proporcional al valor) o ser
   uniforme? Hoy es configurable y por defecto uniforme.
2. ¿Se exige un número mínimo de obligaciones para activar un contrato? Hoy no.
3. ¿Se debe impedir declarar avance antes de la fecha de inicio o después de la terminación?
4. Congelamiento del avance ya incluido en un informe aprobado (se definirá en la Fase 5).

## Consecuencias

- Cambiar la fórmula es modificar `ProgressCalculator` y sus pruebas.
- El historial de avances es la base de los informes periódicos.
