---
tipo: regla_negocio
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - regla-negocio
  - vencimiento
estado: vigente
relacionado:
  - "[[Regla - Vencimiento automatico a los 15 dias]]"
  - "[[Concepto - Estado calculado vs almacenado]]"
archivos:
  - app/postulacion.php
  - public/editar.php
  - docs/02_reglas_negocio.md
---

# Regla - Postulacion vencida puede reactivarse

## Regla de negocio

> Una postulacion en estado "Vencido" puede volver a estado "Postulado" si recibe una actualizacion posterior.

## Implementacion

Esta regla no necesita codigo explicito porque es consecuencia de como funciona el calculo de vencimiento.

Cuando el usuario edita una postulacion desde `public/editar.php`, el formulario siempre establece `fecha_ultima_actualizacion` a la fecha actual (`date('Y-m-d')`). Al momento de leer la postulacion, la funcion `calcular_estado()` compara la nueva fecha con hoy. Si la diferencia es menor o igual a 15 dias, el estado real vuelve a ser "Postulado".

```
Estado actual en BD: 'Postulado' (nunca cambio)
fecha_ultima_actualizacion: 2026-05-01 (antigua)
Estado real calculado: "Vencido" (pasaron mas de 15 dias)

Usuario edita la postulacion (ej: agrega una nota)
fecha_ultima_actualizacion se actualiza a: 2026-06-11 (hoy)

Ahora calcular_estado() ve que paso 0 dias
Estado real calculado: "Postulado"
```

## Por que es importante

Completa el ciclo de vida de una postulacion: no es un estado terminal como "Rechazado". Una postulacion vencida puede reactivarse si el usuario decide darle seguimiento. Esto refleja la realidad de la busqueda laboral: a veces enviar un correo de seguimiento o actualizar el CV reactiva un proceso que parecia muerto.

## Ejemplo simple

```
Postulacion a StartupXYZ -> se vence a los 15 dias
Usuario la edita: agrega nota "Envie correo de seguimiento"
fecha_ultima_actualizacion = hoy
La postulacion vuelve a mostrarse como "Postulado"
El contador de 15 dias se reinicia
```

## Que recordar

- No hay codigo especial para esta regla, es un efecto secundario de como funciona `calcular_estado()`
- El estado almacenado nunca cambia a "Vencido" (ver [[Decision - Vencido se calcula no se almacena]]), por lo que reactivar solo requiere modificar la fecha
- Esta regla esta documentada como Regla 6 en `docs/02_reglas_negocio.md`

## Relacionado

- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Concepto - Estado calculado vs almacenado]]
- [[Decision - Vencido se calcula no se almacena]]
