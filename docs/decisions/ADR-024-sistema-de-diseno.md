# ADR-024: Sistema de diseño de la interfaz

- Estado: Aceptado
- Fecha: 2026-09-24

## Contexto

La interfaz creció pantalla por pantalla: cada una resolvía su caso con clases sueltas. El
resultado funcionaba y cumplía accesibilidad, pero no se leía como un solo producto: había tres
radios de esquina, dos alturas de botón, tablas con el estilo repetido en cada página y el menú
principal escondido tras un botón incluso en pantallas grandes, donde sobra espacio.

## Decisión

1. **Los tokens mandan.** `app.css` define color, curvatura (`--radius-*`), sombra
   (`--shadow-card`, `--shadow-pop`, `--shadow-glow`) y movimiento (`--ease-out-soft` y tres
   duraciones). Ninguna pantalla inventa valores: usa los tokens o las utilidades que los usan.
2. **Cuatro utilidades de tipografía** (`title-page`, `title-section`, `label-eyebrow`,
   `value-kpi`) fijan la jerarquía. Los títulos usan `text-wrap: balance` y los párrafos
   `pretty`; las cifras, `tabular-nums`, para que las columnas se comparen a simple vista.
3. **Menú lateral fijo en pantalla grande** (`lg` en adelante), contraíble a solo iconos y con
   la preferencia recordada en el navegador. En celular sigue siendo un panel deslizante sobre
   `<dialog>` (foco atrapado, Escape, fondo inerte). La misma lista alimenta a los dos, así que
   no hay dos menús que mantener.
4. **Una sola tabla de datos.** La utilidad `data-table` da encabezado fijo al desplazar,
   filas con realce al pasar el puntero y columnas numéricas (`class="num"`) alineadas a la
   derecha con cifras de ancho fijo. Las siete tablas del sistema la usan; antes cada página
   repetía las clases.
5. **Componentes compartidos nuevos**: `StatCard` (cifra grande con rótulo, icono y barra
   opcional), `EmptyState` (nunca una lista en blanco sin explicación) y `Skeleton` (marca el
   sitio del contenido mientras carga, en vez de un "Cargando…" que salta al llegar).
6. **Formularios coherentes**: altura única de 44 px (táctil cómodo), un solo estilo de foco,
   el error con icono junto al campo, y los formularios largos partidos en secciones con
   título. Los campos de contraseña traen el botón de mostrar u ocultar.
7. **Movimiento discreto y con propósito**: entrada del contenido al cambiar de pantalla,
   escalonado de tarjetas (`stagger`), elevación al pasar el puntero (`card-interactive`) y
   diálogos que entran con un impulso corto. Solo se animan `transform` y `opacity`; nunca
   `transition: all`. Con `prefers-reduced-motion` todo llega igual, sin animación.
8. **Los datos de la pantalla no envejecen en silencio.** La aplicación vuelve a pedirlos al
   regresar a la pestaña tras medio minuto fuera y cada dos minutos mientras se está mirando,
   y la barra superior tiene un botón **Actualizar** para hacerlo de inmediato. Antes, un
   tablero abierto seguía mostrando cifras viejas aunque el servidor ya tuviera otras.
9. **Celular de verdad**: zonas seguras (`env(safe-area-inset-*)`) en barra superior y pie,
   `touch-action: manipulation` (sin el retardo de 300 ms), sin destello gris al tocar,
   `overscroll-behavior: contain` en diálogos y ninguna pantalla con desplazamiento horizontal.

## Alternativas consideradas

- **Traer una biblioteca de componentes** (shadcn-svelte, Skeleton UI): resolvería rápido, pero
  añade dependencia y peso a un sistema que ya tiene sus componentes probados y auditados; el
  costo real estaba en la falta de tokens, no en la falta de componentes.
- **Rediseñar cada pantalla a mano**: más control, pero el problema volvería con la siguiente
  pantalla. Los tokens y las utilidades compartidas lo evitan.

## Consecuencias

- La apariencia se cambia desde `app.css`: color institucional, curvatura o densidad se ajustan
  en un lugar y toda la aplicación sigue.
- Las pruebas E2E que abrían el menú con el botón "Abrir menú" ahora lo hacen solo cuando
  existe (en celular); en escritorio el menú ya está a la vista.
- El escáner de contraste (`dark-text-scan.mjs`) entiende ahora `oklab()`/`oklch()`, que es lo
  que devuelve el navegador cuando un color lleva transparencia; antes los daba por oscuros.
- Verificación tras el rediseño: 115/115 en las nueve fases E2E, 35/35 en actividades, 12/12 en
  el asistente de contratista, axe sin violaciones a 1280 y 390 px, 0 textos ilegibles y las
  pruebas y el build del frontend en verde.
