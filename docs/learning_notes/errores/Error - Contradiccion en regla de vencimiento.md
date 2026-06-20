#php

# Contradiccion en regla de vencimiento

## Que entender

En `docs/02_reglas_negocio.md` coexisten dos redacciones distintas:
- **Regla 1** habla de "7 días desde la **fecha de postulación**"
- **Regla 5** habla de "7 días desde su **última actualización**"

El código implementa la segunda: usa `fecha_ultima_actualizacion` en `calcular_estado()`.

## Por que importa

Documentación y código desalineados confunden al releer el proyecto y pueden generar bugs si alguien implementa según Regla 1. En entrevista, conviene citar explícitamente qué fuente manda (el código y Regla 5/6).

## Como aparece aqui

- Regla 1 vs Regla 5 en `docs/02_reglas_negocio.md`
- Implementación real: `app/postulacion.php` → `calcular_estado()`
- Misma lógica en `scripts/analytics.py` → `calcular_estado_real()` con `fecha_ultima_actualizacion`
- Al registrar: `fecha_ultima_actualizacion` = `fecha_postulacion` (coinciden solo al inicio)

## Que recordar

**Fuente de verdad operativa: `fecha_ultima_actualizacion`. Regla 1 en docs está desactualizada.**

## Relacionado
- [[Regla - Vencimiento automatico a los 7 dias]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- `docs/02_reglas_negocio.md`