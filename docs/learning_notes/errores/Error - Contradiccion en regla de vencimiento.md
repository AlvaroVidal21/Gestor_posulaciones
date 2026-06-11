---
tipo: error
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - error
  - documentacion
estado: historico
relacionado:
  - "[[Regla - Vencimiento automatico a los 15 dias]]"
  - "[[Decision - Vencido se calcula no se almacena]]"
archivos:
  - docs/02_reglas_negocio.md
---

# Error - Contradiccion en regla de vencimiento

## Problema

La documentacion del proyecto contenia dos versiones distintas de la misma regla de negocio. La Regla 1 de `docs/02_reglas_negocio.md` decian:

> "Una postulacion se considera vencida cuando han transcurrido mas de 15 dias desde la **fecha de postulacion**"

Mientras que la Regla 5 del mismo documento y `AGENTS.md` decian:

> "Una postulacion ... pasa automaticamente a estado Vencido cuando transcurren mas de 15 dias desde su **ultima actualizacion**"

Son dos cosas distintas. La primera compara contra `fecha_postulacion` (cuando se creo el registro). La segunda contra `fecha_ultima_actualizacion` (cuando se modifico por ultima vez).

## Causa

La Regla 1 se escribio primero, cuando el modelo aun no distinguia entre "fecha de postulacion" y "fecha de ultima actualizacion". Al agregar el campo `fecha_ultima_actualizacion` y la Regla 5, la Regla 1 no se actualizo para reflejar el cambio.

Fue un error de mantenimiento de documentacion: se agrego una regla nueva sin verificar que la anterior no la contradijera.

## Correccion

La Regla 1 debe interpretarse a la luz de la Regla 5 y de `AGENTS.md`. La version correcta es:

> "Una postulacion se considera vencida cuando han transcurrido mas de 15 dias desde la **fecha de ultima actualizacion** y su estado es 'Postulado'."

En el codigo, `calcular_estado()` en `app/postulacion.php` usa `fecha_ultima_actualizacion`, que es la implementacion correcta.

## Aprendizaje

- Cuando agregas una regla nueva, revisa las existentes para detectar contradicciones
- La documentacion debe actualizarse en paralelo con el codigo
- Si dos reglas dicen lo mismo pero con distinto origen de datos, una esta mal
- `AGENTS.md` es la fuente de verdad definitiva para el agente; `docs/` es la fuente de verdad para el desarrollador

## Que recordar

- La regla correcta usa `fecha_ultima_actualizacion`, no `fecha_postulacion`
- Las contradicciones en documentacion son tan peligrosas como bugs en el codigo
- Este error se detecto durante la fase de planificacion del proyecto (ver el analisis del PLAN.md original)

## Relacionado

- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Decision - Vencido se calcula no se almacena]]
