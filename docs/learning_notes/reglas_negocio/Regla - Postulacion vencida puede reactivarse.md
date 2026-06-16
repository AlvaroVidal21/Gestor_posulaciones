#php

# Postulacion vencida puede reactivarse

## Que entender

"Vencido" no es un estado terminal como "Rechazado". Si el usuario edita una postulación vencida y guarda (típicamente dejando estado `Postulado`), el sistema renueva `fecha_ultima_actualizacion` y vuelve a mostrar `Postulado`.

No hace falta un botón "reactivar": cualquier actualización válida reinicia el contador.

## Por que importa

Refleja la realidad del proceso de búsqueda: a veces retomas contacto con una empresa semanas después. La regla evita crear un cuarto estado o flujos especiales de "desarchivar".

## Como aparece aqui

Regla 6 en `docs/02_reglas_negocio.md`.

`actualizar_postulacion()` siempre escribe `fecha_ultima_actualizacion = date('Y-m-d')`.

`public/editar.php` muestra el `estado_real` actual y explica que al guardar se renueva la fecha.

Tras guardar, `calcular_estado()` ya no devuelve `Vencido` mientras no pasen otros 15 días.

## Que recordar

**Editar = nueva señal de vida; el vencimiento se resetea con la fecha de hoy.**

## Relacionado
- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Decision - Vencido se calcula no se almacena]]
- `public/editar.php`