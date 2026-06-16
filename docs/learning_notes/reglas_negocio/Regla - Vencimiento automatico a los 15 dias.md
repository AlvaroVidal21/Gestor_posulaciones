#php

# Vencimiento automatico a los 15 dias

## Que entender

Si una postulación está almacenada como `Postulado` y pasan **más de 15 días** desde `fecha_ultima_actualizacion`, su estado real pasa a `Vencido` sin intervención manual.

No hay cron ni trigger en BD: la transición ocurre al leer los datos.

## Por que importa

Es la regla central del producto: recordar al usuario qué postulaciones quedaron olvidadas. Los 15 días son el umbral de "sin respuesta ni seguimiento".

## Como aparece aqui

`calcular_estado()` compara `fecha_ultima_actualizacion` con hoy usando `DateTime::diff()->days` y retorna `Vencido` si `$diferencia > 15`.

Al registrar, `fecha_ultima_actualizacion` se iguala a `fecha_postulacion`. Al editar, `actualizar_postulacion()` la pone siempre en la fecha de hoy (`date('Y-m-d')`).

`public/vencidas.php` lista solo registros con `estado_real === 'Vencido'`.

## Que recordar

**El reloj cuenta desde la última actualización, no desde hoy en abstracto.**

## Relacionado
- [[Regla - Postulacion vencida puede reactivarse]]
- [[Error - Contradiccion en regla de vencimiento]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- `app/postulacion.php`