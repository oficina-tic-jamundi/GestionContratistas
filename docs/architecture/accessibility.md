# Accesibilidad

SIGCON es un sistema de una entidad pública colombiana: debe poder usarlo cualquier persona,
también con lector de pantalla, solo con el teclado o desde un celular. El objetivo es
**WCAG 2.1 nivel AA**, referencia de la NTC 5854 y de la Resolución 1519 de 2020 (MinTIC).

## Criterios que sigue el código

- **El estado nunca se comunica solo con color**: las insignias siempre llevan texto
  (`Badge`, `*StatusBadge`), y las reglas de elegibilidad dicen "cumple", "no cumple" o
  "desactivada" para lectores de pantalla.
- **Formularios**: cada campo tiene `<label>` asociado; los errores se enlazan con
  `aria-describedby` y `aria-invalid` (`TextField`, `TextArea`, `SelectField`).
- **Diálogos**: `<dialog>` nativo con `showModal()`, que da foco atrapado, cierre con Escape y
  fondo inerte, más un título enlazado con `aria-labelledby` (`Modal`, `ConfirmDialog`).
- **Navegación**: enlace "Saltar al contenido", `aria-current` en el menú, campana con nombre
  accesible que incluye el número de avisos sin leer. El menú lateral es un `<dialog>` nativo:
  al abrirlo el foco entra al panel, Escape lo cierra y el foco vuelve al botón que lo abrió.
- **Contraste en el tema oscuro**: las superficies de vidrio son translúcidas, así que el
  contraste se calculó sobre el color resultante en las zonas más claras del fondo degradado
  (todas ≥ 4,5:1). El botón principal usa texto oscuro sobre el verde esmeralda (6,2:1); con
  texto blanco sería 2,5:1.
- **Tablas anchas**: `ScrollRegion` hace enfocable la región desplazable (se recorre con las
  flechas del teclado).
- **Imágenes**: texto alternativo descriptivo en las evidencias; la vista previa de la marca de
  agua (un canvas) se expone como imagen con nombre.
- **Mensajes dinámicos**: avisos (`Toaster`) y estado de la ubicación en regiones `aria-live`.
- **Celular**: todas las pantallas se verifican a 390 px sin desplazamiento horizontal de la página.

## Verificación

La auditoría automática usa **axe-core** con las reglas `wcag2a`, `wcag2aa`, `wcag21a` y
`wcag21aa`, en Edge a 1280 px y a 390 px. Cubre 25 pantallas de los tres perfiles, incluido el
diálogo de captura de evidencias.

| Fecha | Resultado |
|---|---|
| 2026-09-18 | 0 incumplimientos en escritorio y celular. Se corrigieron el contraste del contador de notificaciones (2,59:1 → 5,8:1) y el acceso por teclado a las tablas desplazables |
| 2026-09-19 | Rediseño (tema oscuro con vidrio y menú lateral): 0 incumplimientos en escritorio, celular y con el menú abierto. Se corrigió el enlace del logo sin nombre accesible en celular |
| 2026-09-19 | Panel del contratista, Mis actividades e Historial (ADR-021): 0 incumplimientos. Se agregó una revisión propia de texto oscuro sobre el tema oscuro (axe no mide sobre fondos translúcidos), que detectó y permitió corregir el color `base` que chocaba con la clase `text-base` de Tailwind |

Las herramientas automáticas detectan solo una parte de los problemas. Antes de producción
conviene una **revisión manual** con lector de pantalla (NVDA en Windows, TalkBack en Android)
de los flujos principales: iniciar sesión, elaborar y enviar un informe, registrar una
evidencia y revisar un informe.
