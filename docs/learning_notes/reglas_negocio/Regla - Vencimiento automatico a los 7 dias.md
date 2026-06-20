#php

# Regla - Vencimiento automatico a los 7 dias

## Que entender

Si una postulacion esta almacenada como `Postulado` y pasan **mas de 7 dias** desde `fecha_ultima_actualizacion`, su estado real pasa a `Vencido` sin intervencion manual.

No hay cron ni trigger en BD: la transicion ocurre al leer los datos.

## Por que importa

Es la regla central del producto: recordar al usuario que postulaciones quedaron olvidadas. Los 7 dias son el umbral actual de seguimiento.

## Como aparece aqui

`calcular_estado()` compara `fecha_ultima_actualizacion` con hoy usando `DateTime::diff()->days` y retorna `Vencido` si `$diferencia > 7`.

Al registrar, `fecha_ultima_actualizacion` se iguala a `fecha_postulacion`. Al editar, `actualizar_postulacion()` la pone siempre en la fecha de hoy (`date('Y-m-d')`).

`public/vencidas.php` lista solo registros con `estado_real === 'Vencido'`.

## Ejemplo simple

```text
Postulacion a Google, actualizada hace 8 dias, estado "Postulado".
8 > 7 -> la postulacion se muestra como "Vencido".

Si el usuario la actualiza, fecha_ultima_actualizacion se renueva y la postulacion vuelve a "Postulado" por otros 7 dias.
```

## Que recordar

**El reloj cuenta desde la ultima actualizacion, no desde la fecha inicial ni desde hoy en abstracto.**

## Relacionado

- [[Regla - Postulacion vencida puede reactivarse]]
- [[Error - Contradiccion en regla de vencimiento]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- `app/postulacion.php`
