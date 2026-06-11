---
tipo: decision
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - decision-diseno
  - php
estado: vigente
relacionado:
  - "[[Decision - SQLite como base de datos]]"
archivos:
  - AGENTS.md
---

# Decision - PHP puro sin frameworks

## Problema que resuelve

Elegir la herramienta adecuada para construir un gestor personal de postulaciones.

## Decision tomada

Usar PHP puro, sin Laravel, Symfony ni ningun framework. La logica se organiza en archivos separados (`app/config.php`, `app/postulacion.php`) pero sin seguir un patron MVC impuesto por un framework.

## Motivo

El proyecto es academico y de portafolio. Usar un framework como Laravel agregaria:
- Curva de aprendizaje (rutas, controladores, Eloquent, Blade, migraciones, etc.)
- Archivos de configuracion que no se usarian
- Dependencias externas que pueden fallar o quedar obsoletas
- Tiempo de instalacion y configuracion que no aporta al aprendizaje del dominio

Con PHP puro, cada archivo hace algo especifico y se entiende sin necesidad de conocer las convenciones de un framework. Esto es valioso para un estudiante que quiere mostrar su codigo en una entrevista y explicarlo linea por linea.

## Alternativas consideradas

1. **Laravel**: el framework PHP mas popular. Descartado porque su estructura opaca la logica simple del proyecto.
2. **Slim**: micro-framework de rutas. Mas liviano que Laravel pero sigue agregando una capa de abstraccion innecesaria.
3. **Sin framework (decision tomada)**: archivos PHP que reciben peticiones, procesan datos y devuelven HTML directamente.

## Consecuencia

- Las URLs son archivos `.php` concretos (`/registrar.php`, `/editar.php?id=1`)
- No hay enrutador centralizado
- El codigo es mas verboso pero mas explicito
- Cualquier persona con PHP basico puede entender el proyecto sin leer documentacion del framework

## Que recordar

- Elegir un framework no siempre es la mejor decision
- Para proyectos pequenos y academicos, la simplicidad del codigo es mas importante que seguir tendencias
- Esta decision esta alineada con la filosofia del proyecto documentada en `AGENTS.md`

## Relacionado

- [[Decision - SQLite como base de datos]]
