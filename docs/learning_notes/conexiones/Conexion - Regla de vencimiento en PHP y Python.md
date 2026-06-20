#php #python

# Regla de vencimiento en PHP y Python

## Que entender

La misma regla de negocio vive en dos lenguajes: PHP sirve la app web; Python analiza la misma BD en terminal.

Ambos leen `estado` almacenado y `fecha_ultima_actualizacion`, aplican el umbral de 7 días y producen `estado_real` / `estado_real` en DataFrame.

## Por que importa

Demuestra que las reglas de dominio no pertenecen solo a la UI. Si cambias el umbral o la fecha de referencia, debes actualizar **ambos** sitios o las métricas del script divergirán del dashboard.

## Como aparece aqui

- PHP: `calcular_estado()` en `app/postulacion.php`
- Python: `calcular_estado_real()` en `scripts/analytics.py` con `DIAS_VENCIMIENTO = 7`

El script usa `pandas` + `sqlite3` sobre `database/gestor_postulaciones.sqlite` y replica la lógica con `(hoy - fecha_ultima_actualizacion).days > 7`.

## Que recordar

**Una regla, dos implementaciones: mantenerlas sincronizadas es parte del mantenimiento.**

## Relacionado
- [[Regla - Vencimiento automatico a los 7 dias]]
- [[Error - Contradiccion en regla de vencimiento]]
- `scripts/analytics.py`
- `app/postulacion.php`