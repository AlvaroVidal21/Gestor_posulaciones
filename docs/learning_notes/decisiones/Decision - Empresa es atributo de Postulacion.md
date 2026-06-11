---
tipo: decision
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - decision-diseno
  - modelo-datos
estado: vigente
relacionado:
  - "[[Decision - SQLite como base de datos]]"
archivos:
  - database/schema.sql
  - docs/05_decisiones.md
---

# Decision - Empresa es atributo de Postulacion

## Problema que resuelve

Como modelar la relacion entre una postulacion y la empresa que ofrece el puesto.

## Decision tomada

`empresa` es una columna de texto dentro de la tabla `postulaciones`, no una entidad independiente con su propia tabla.

## Motivo

El sistema es un gestor personal, no un CRM empresarial. No necesitas mantener un catalogo de empresas con direccion, telefono, RUC, etc. Solo el nombre para identificar donde postulaste.

Crear una tabla `empresas` separada agregaria complejidad sin beneficio real:
- JOIN en cada consulta
- CRUD adicional
- Validacion de duplicados
- Mas archivos que mantener

Para el alcance del proyecto (una persona buscando practicas), un campo de texto es suficiente. Si el usuario escribe "Google" en dos postulaciones, se entiende que es la misma empresa por contexto.

## Alternativas consideradas

1. **Tabla empresas separada**: la opcion clasica en sistemas multiusuario o CRM. Descartada por sobreingenieria.
2. **Autocompletado desde valores existentes**: el dashboard lista plataformas unicas para el filtro, pero no se implemento para empresas porque no hay un caso de uso fuerte que lo justifique.

## Consecuencia

- Buscar postulaciones de una misma empresa requiere usar el buscador textual.
- No hay normalizacion: si escribes "Google" en una y "Google Inc." en otra, el sistema no las relaciona.
- La estructura de la base de datos es plana y facil de entender.

## Que recordar

- No toda relacion debe ser una tabla separada
- Para proyectos personales, prioriza la simplicidad sobre la normalizacion
- DEC-002 en `docs/05_decisiones.md` formaliza esta regla

## Relacionado

- [[Decision - SQLite como base de datos]]
