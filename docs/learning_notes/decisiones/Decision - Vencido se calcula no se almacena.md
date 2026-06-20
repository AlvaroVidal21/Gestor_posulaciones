#php #sqlite

# Vencido se calcula no se almacena

## Que entender

La columna `estado` en BD solo guarda `Postulado` o `Rechazado`. Cuando una postulación lleva más de 7 días sin actualizarse, el sistema **muestra** `Vencido` pero **no escribe** ese valor en la tabla.

DEC-003 formaliza esta decisión.

## Por que importa

"Vencido" depende de la fecha actual. Guardarlo obligaría a jobs de actualización o dejaría registros mentirosos al día siguiente.

Calcularlo garantiza coherencia en cada lectura, a costa de duplicar la regla en PHP (y en `scripts/analytics.py`).

## Como aparece aqui

- `calcular_estado()` en `app/postulacion.php`
- CHECK en `database/schema.sql`: `estado IN ('Postulado', 'Rechazado')`
- Formularios de edición solo ofrecen Postulado y Rechazado
- Dashboard y métricas usan `estado_real`, no `estado` crudo

## Que recordar

**La BD guarda intención del usuario; el tiempo transforma Postulado en Vencido al consultar.**

## Relacionado
- [[Concepto - Estado calculado vs almacenado]]
- [[Regla - Vencimiento automatico a los 7 dias]]
- [[Conexion - CHECK constraint protege estados en SQLite]]
- `docs/05_decisiones.md`