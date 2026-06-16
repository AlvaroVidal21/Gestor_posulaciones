#php

# Empresa es atributo de Postulacion

## Que entender

**Empresa** no es una entidad con tabla propia. Es un campo de texto (`empresa`) dentro de `postulaciones`.

No hay tabla `empresas`, ni claves foráneas, ni CRUD separado de empresas.

## Por que importa

El objetivo es trazabilidad personal de postulaciones, no un CRM. Modelar empresa como entidad añadiría joins, formularios y mantenimiento sin valor para un solo usuario.

En entrevista: demuestra que priorizas el dominio real (seguir postulaciones) sobre modelar todo como entidades.

## Como aparece aqui

`database/schema.sql` tiene una sola tabla `postulaciones` con columna `empresa TEXT NOT NULL`.

La búsqueda en `buscar_postulaciones()` filtra por coincidencia parcial en ese campo.

Documentado en DEC-002 de `docs/05_decisiones.md`.

## Que recordar

**Una postulación = un registro completo; empresa es solo un dato más del mismo registro.**

## Relacionado
- [[Decision - SQLite como base de datos]]
- `docs/05_decisiones.md`
- `docs/03_entidades.md`