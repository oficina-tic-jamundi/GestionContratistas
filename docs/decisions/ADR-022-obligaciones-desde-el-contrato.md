# ADR-022: Obligaciones leídas del contrato firmado al registrar un contratista

- Estado: Aceptado
- Fecha: 2026-09-24

## Contexto

La Alcaldía pidió que, al crear un contratista, la administración cargue también su contrato
para que SIGCON arme las actividades a partir de él. Hasta ahora las obligaciones se escribían
una por una en el contrato, copiándolas del PDF firmado.

Restricciones:

- La IA está deshabilitada hasta que exista una decisión institucional (ADR-008). No se puede
  enviar el contrato a un servicio externo ni simular un análisis.
- El sistema corre en hosting compartido (cPanel): sin binarios del sistema (`pdftotext`) ni
  servicios en segundo plano.
- No hay aún contratos reales de muestra de la Alcaldía para calibrar la lectura.

## Decisión

1. **Asistente de tres pasos** en *Contratistas → Nuevo contratista*: (1) datos del
   contratista, (2) contrato con el **PDF firmado obligatorio**, (3) obligaciones propuestas.
   Cada paso guarda lo suyo; si algo falla se reintenta solo lo pendiente, sin duplicar el
   contratista ni el contrato. Quien no gestiona contratos solo ve el primer paso. Se puede
   terminar después del paso 1 ("Terminar ahora") y registrar el contrato más tarde.
2. **Lectura determinista, sin IA** (`ObligationExtractor`): se extrae el texto del PDF en el
   servidor con `smalot/pdfparser` (PHP puro, LGPL-3.0, sin dependencias del sistema) y se busca
   la sección **"Obligaciones específicas (del contratista)"**; si no existe, la de
   **"Obligaciones del contratista"**, con aviso de que puede incluir obligaciones generales.
   La sección termina en el siguiente encabezado (obligaciones de la entidad, del municipio o
   del supervisor; CLÁUSULA, VALOR, PLAZO, FORMA DE PAGO, SUPERVISIÓN, GARANTÍAS...). Los ítems
   se toman de la lista numerada **consecutiva** (1, 2, 3...), de modo que valores
   ("1.500.000") o cantidades ("2 jornadas") no abren ítems falsos; si no hay números, de la
   lista con letras.
3. **Es una propuesta, no un registro.** `GET /contracts/{uuid}/obligations/suggestions` solo
   lee. La administración corrige el texto, quita, agrega y ajusta el peso; al confirmar,
   `POST /contracts/{uuid}/obligations/bulk` registra todas en una transacción (todo o nada,
   máximo 60, mismas reglas que una obligación individual: contrato en borrador y
   `contracts.manage`). Queda en la auditoría con el origen `contract_document`.
4. **Títulos cortos, texto completo aparte.** Las obligaciones de más de 180 caracteres se
   acortan en el título (corte en palabra, con "…") y el texto íntegro queda en la descripción.
5. **Límites dichos, no ocultos.** PDF escaneado (sin texto), protegido o dañado: se informa y
   las obligaciones se escriben a mano en la misma pantalla. Sin OCR. Solo PDF (leer DOCX
   exigiría la extensión `zip`, que no se puede asumir en el hosting).
6. La misma lectura está en el contrato (*Obligaciones y avance → Leer del contrato firmado*)
   mientras siga en borrador, para contratos creados fuera del asistente. Si ya hay
   obligaciones, se avisa y las nuevas se agregan a esas.

## Alternativas consideradas

- **IA (análisis semántico del contrato):** tolera mejor formatos variados, pero exige el ADR
  previsto en ADR-008 (proveedor, base legal, minimización, retención). Queda como mejora: la
  propuesta revisable permite cambiar la fuente sin cambiar el flujo.
- **Registro automático sin revisión:** descartado; un error de lectura crearía obligaciones
  que, al activar el contrato, ya no se pueden corregir (ADR-012).
- **`pdftotext` del sistema:** extrae mejor el texto, pero no está disponible en hosting
  compartido.

## Consecuencias

- La lectura se calibró con textos ficticios con la estructura habitual de un contrato de
  prestación de servicios. **Debe verificarse con contratos reales de la Alcaldía** antes de
  confiar en ella; los ajustes quedan en `ObligationExtractor` y en su prueba unitaria.
- Nueva dependencia: `smalot/pdfparser` (LGPL-3.0, usada como librería sin modificarla).
- Pruebas: `ObligationExtractorTest` (unitaria) y `ObligationImportTest` (integración con PDF
  reales generados con dompdf: lectura, PDF sin texto, sin contrato firmado, todo o nada, solo
  en borrador, el supervisor no puede).
