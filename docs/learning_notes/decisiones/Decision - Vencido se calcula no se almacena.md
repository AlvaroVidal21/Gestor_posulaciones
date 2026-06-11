---
tipo: decision
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - decision-diseno
  - estado
  - logica
estado: vigente
relacionado:
  - "[[Concepto - Estado calculado vs almacenado]]"
  - "[[Regla - Vencimiento automatico a los 15 dias]]"
  - "[[Error - Contradiccion en regla de vencimiento]]"
archivos:
  - app/postulacion.php
  - docs/05_decisiones.md
---

# Decision - Vencido se calcula no se almacena

## Problema que resuelve

Como saber que postulaciones estan vencidas sin ejecutar un proceso programado que actualice la base de datos cada dia.

## Decision tomada

El estado "Vencido" **no se guarda** en la columna `estado` de la tabla `postulaciones`. Se calcula en el momento de la consulta comparando `fecha_ultima_actualizacion` con la fecha actual.

## Motivo

Si almacenaras "Vencido" en la BD, necesitarian un cron job, tarea de Windows o scheduler que cada noche recorriera todas las postulaciones y cambiara las que cumplen la condicion. Eso agrega complejidad operativa y un punto de fallo.

Al calcularlo, el sistema siempre devuelve el estado correcto para el momento exacto de la consulta, sin depender de procesos externos.

Ademas, si la regla de negocio cambia (ej: de 15 a 30 dias), solo modificas el codigo. Los datos historicos no necesitan migracion.

## Alternativas consideradas

1. **Cron job que actualice la BD**: requeriria configurar un proceso externo. Demasiada complejidad para un proyecto personal.
2. **Trigger de SQLite al consultar**: los triggers en SQLite se ejecutan en INSERT/UPDATE/DELETE, no en SELECT. No es viable.
3. **Vista en SQLite**: una `CREATE VIEW` podria calcular el estado. Es una opcion valida, pero se opto por mantener la logica en PHP para que sea mas explicita y facil de modificar.

## Consecuencia

- La funcion `calcular_estado()` en `app/postulacion.php` debe ejecutarse cada vez que se leen postulaciones.
- El mismo calculo se replica en `scripts/analytics.py` para el dashboard de terminal.
- La BD solo guarda `'Postulado'` o `'Rechazado'` (reforzado por un `CHECK` en `schema.sql`).

## Que recordar

- DEC-003 en `docs/05_decisiones.md` documenta formalmente esta decision
- Es un ejemplo clasico de **derivar estado en vez de almacenarlo**
- Evita complejidad operativa (cron jobs, colas, etc.)
- Asegura que el estado siempre refleje el momento actual

## Relacionado

- [[Concepto - Estado calculado vs almacenado]]
- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Error - Contradiccion en regla de vencimiento]]
