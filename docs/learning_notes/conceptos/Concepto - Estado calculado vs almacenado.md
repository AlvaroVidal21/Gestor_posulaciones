#php

# Estado calculado vs almacenado

## Que entender

Un **estado almacenado** vive en la base de datos y se persiste con cada guardado. Un **estado calculado** se deriva en tiempo de lectura a partir de otros datos; no tiene columna propia.

En este proyecto, `Postulado` y `Rechazado` se almacenan. `Vencido` se calcula.

## Por que importa

Evita escribir en BD un valor que cambia solo con el paso del tiempo. Si guardaras "Vencido", habría que actualizar filas cada día con un cron o job, o quedarían datos desactualizados.

El trade-off: la lógica de vencimiento debe repetirse donde se lea el estado (PHP, Python, filtros).

## Como aparece aqui

`calcular_estado()` en `app/postulacion.php` lee `estado` y `fecha_ultima_actualizacion` y devuelve `estado_real`. Ese campo se agrega en memoria, no en SQLite.

El CHECK de `database/schema.sql` solo permite `Postulado` y `Rechazado` en la columna `estado`.

## Que recordar

**Vencido no es un valor de BD; es una interpretación de Postulado + tiempo sin actualizar.**

## Relacionado
- [[Decision - Vencido se calcula no se almacena]]
- [[Conexion - Estado calculado obliga filtrar en PHP]]
- `app/postulacion.php`