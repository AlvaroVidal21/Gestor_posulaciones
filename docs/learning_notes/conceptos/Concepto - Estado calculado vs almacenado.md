---
tipo: concepto
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - estado
  - logica-nivel-aplicacion
estado: vigente
relacionado:
  - "[[Decision - Vencido se calcula no se almacena]]"
  - "[[Conexion - Regla de vencimiento en PHP y Python]]"
archivos:
  - app/postulacion.php
---

# Concepto - Estado calculado vs almacenado

## Idea central

Un valor puede **no estar guardado en la base de datos** y obtenerse igual al consultar, aplicando una regla en el codigo. En este proyecto, el estado "Vencido" no existe en la tabla `postulaciones`; se determina en el momento de leer los datos comparando fechas.

## Problema que resuelve

Si almacenaras "Vencido" en la BD, cada dia tendrias que ejecutar un proceso (cron, tarea programada) que revise todas las postulaciones y actualice las que cumplen la condicion. Eso es mas complejo, fragil y dificil de mantener.

Al calcularlo, el estado siempre refleja el momento actual sin necesidad de procesos externos.

## Como aparece en este proyecto

En `app/postulacion.php`, funcion `calcular_estado()`:

```
1. Si el estado guardado es "Rechazado" -> se muestra "Rechazado"
2. Si el estado guardado es "Postulado" y pasaron mas de 15 dias
   desde fecha_ultima_actualizacion -> se muestra "Vencido"
3. En cualquier otro caso -> se muestra "Postulado"
```

La base de datos solo guarda `'Postulado'` o `'Rechazado'`. El `CHECK` en `schema.sql` lo exige.

## Explicacion

Hay dos valores distintos:

| Concepto | Donde vive | Ejemplo |
|----------|-----------|---------|
| Estado almacenado | Columna `estado` en SQLite | `'Postulado'` |
| Estado real | Se calcula al leer con PHP | `'Vencido'` (si pasaron >15 dias) |

Esto se conoce como **derivar estado desde datos base**. Es una alternativa a tener un proceso batch que actualice registros periodicamente.

La misma logia se replica en `scripts/analytics.py` con pandas, para que los graficos en terminal tambien muestren el estado correcto.

## Que recordar

- No todo lo que muestras necesita estar en la base de datos
- Calcular es mas simple que sincronizar
- Si cambia la regla (ej: de 15 a 30 dias), solo cambias el codigo, no los datos historicos
- Esta decision aparece documentada en `docs/05_decisiones.md` como DEC-003

## Relacionado

- [[Decision - Vencido se calcula no se almacena]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Regla - Vencimiento automatico a los 15 dias]]
