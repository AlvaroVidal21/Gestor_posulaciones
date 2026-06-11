---
tipo: regla_negocio
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - regla-negocio
  - vencimiento
estado: vigente
relacionado:
  - "[[Decision - Vencido se calcula no se almacena]]"
  - "[[Concepto - Estado calculado vs almacenado]]"
  - "[[Error - Contradiccion en regla de vencimiento]]"
archivos:
  - app/postulacion.php
  - docs/02_reglas_negocio.md
---

# Regla - Vencimiento automatico a los 15 dias

## Regla de negocio

> Una postulacion en estado "Postulado" pasa automaticamente a estado "Vencido" cuando transcurren mas de 15 dias desde su **ultima actualizacion**.

## Implementacion

En `app/postulacion.php`, funcion `calcular_estado()`:

```php
if ($postulacion['estado'] === 'Postulado') {
    $fecha_actualizacion = new DateTime($postulacion['fecha_ultima_actualizacion']);
    $hoy = new DateTime();
    $diferencia = $fecha_actualizacion->diff($hoy)->days;
    if ($diferencia > 15) {
        return 'Vencido';
    }
}
```

La misma logica se replica en `scripts/analytics.py` para el dashboard de terminal.

## Por que es importante

Esta regla define el comportamiento central del sistema: **saber que necesita atencion**. Sin ella, el usuario tendria que revisar manualmente cada postulacion y recordar cuando fue la ultima vez que la actualizo. Con la regla, el sistema lo hace por el.

El plazo de 15 dias refleja un ritmo tipico de busqueda laboral: si una empresa no responde en dos semanas, probablemente no esta interesada o el proceso es lento. En cualquier caso, conviene hacer seguimiento.

## Ejemplo simple

```
Postulacion a Google, creada el 1 de mayo, estado "Postulado".
Hoy es 11 de junio. Pasaron 41 dias desde la ultima actualizacion.
41 > 15 -> la postulacion se muestra como "Vencido".

Si el usuario la actualiza (agrega una nota, cambia el estado),
fecha_ultima_actualizacion se renueva y la postulacion
vuelve a "Postulado" por otros 15 dias.
```

## Que recordar

- La regla usa `fecha_ultima_actualizacion`, no `fecha_postulacion` (ver [[Error - Contradiccion en regla de vencimiento]])
- El estado "Vencido" no se guarda en BD, se calcula al leer (ver [[Decision - Vencido se calcula no se almacena]])
- La regla esta documentada formalmente como Regla 5 en `docs/02_reglas_negocio.md`

## Relacionado

- [[Decision - Vencido se calcula no se almacena]]
- [[Concepto - Estado calculado vs almacenado]]
- [[Regla - Postulacion vencida puede reactivarse]]
- [[Error - Contradiccion en regla de vencimiento]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
