# ADR-023: Subir o crear el informe desde la actividad (acta o informe de actividades)

- Estado: Aceptado
- Fecha: 2026-09-24

## Contexto

El contratista pidió dos caminos claros dentro de cada actividad:

- **Subir informe**: ya tiene el acta o el informe hecho y solo quiere anexarlo.
- **Crear informe**: hacerlo en SIGCON, escribiendo o dictando por voz, tomando fotos que se
  van anexando, y que al final se genere el documento. Acta e informe son planillas distintas,
  así que primero elige el formato.

La Alcaldía aún no ha entregado sus planillas oficiales de acta e informe.

## Decisión

1. **Dos botones** en la actividad del contratista: *Subir informe* y *Crear informe*. Todo lo
   que se sube o se crea queda en el **borrador del informe del período** (ADR-015) y se envía
   al supervisor con un solo botón al final. No hay un canal paralelo al informe.
2. **Subir informe**: se elige *Acta* o *Informe de actividades*, se adjunta el archivo y se
   escribe o dicta un resumen breve de lo realizado (queda como descripción de la obligación).
3. **Crear informe**: se elige el formato y cada uno muestra **sus propios campos**
   (`ReportTemplate`, espejo en `activityReport.ts`):
   - *Informe de actividades*: fecha, actividades realizadas, resultados, dificultades u
     observaciones.
   - *Acta*: fecha, hora, lugar, tema u objetivo, asistentes (uno por línea), desarrollo,
     compromisos.

   Todos los campos de texto admiten dictado. Las fotos se toman con la cámara (o se eligen de
   la galería) y se ven en miniatura con su descripción; se quedan en el navegador hasta pulsar
   *Crear*, para no dejar anexos sueltos si el contratista se arrepiente.
4. **El PDF lo genera el servidor** (`POST /reports/{uuid}/composed-documents`) con dompdf, con
   las mismas garantías del PDF de versiones (ADR-017): sin acceso remoto, sin PHP ni JavaScript,
   todo el texto escapado. Las fotos se incrustan reducidas a 1200 px (JPEG 80). El documento
   queda como anexo del informe con el tipo `acta` o `informe`, y se abre en el visor para
   revisarlo.
5. **Mismas reglas que un anexo**: solo el contratista del contrato, con el informe en borrador
   o con observaciones; las fotos deben ser anexos vigentes **de ese mismo informe**; la
   actividad debe ser del contrato del informe. Queda en la auditoría como carga de documento.
6. **Reintentos seguros**: si algo falla a mitad de camino, reintentar no crea otro informe ni
   vuelve a subir las fotos (se reutiliza el anexo con la misma huella SHA-256). La misma foto
   no se puede agregar dos veces.
7. **Dictado**: se corrigió la transcripción, que repetía el texto de toda la sesión en cada
   frase; ahora solo agrega lo nuevo.
8. **Los botones siempre se ven, arriba de la actividad.** Si no se pueden usar, aparecen
   desactivados y la pantalla explica por qué (p. ej., ya hay un informe para cada período del
   contrato). Un informe en revisión **no** impide empezar el del período siguiente (el backend
   solo exige que los períodos no se crucen); tras una aprobación o un rechazo se ofrece el
   período que sigue o el que hay que repetir.

## Alternativas consideradas

- **Generar el PDF en el navegador:** dependería del dispositivo y no dejaría un formato
  controlado por la Alcaldía; se descartó.
- **Subir cada foto al tomarla:** deja anexos huérfanos si el contratista cancela; se prefirió
  subirlas al crear, con reintento seguro.

## Consecuencias

- Los formatos son **provisionales** y lo dicen en el propio PDF. Cuando la Alcaldía entregue
  sus planillas oficiales se cambian los campos en `ReportTemplate` y el diseño en
  `ComposedDocumentRenderer`, sin tocar el flujo.
- Pruebas: `ComposedDocumentTest` (acta con fotos e informe; cada formato con sus campos
  obligatorios; solo fotos del mismo informe; solo el contratista y con el informe editable) y
  el E2E de actividades (crear acta con fotos, subir informe, enviar, observar, reenviar,
  aprobar y empezar el período siguiente).
