#php

# Estados validos de postulacion

## Que entender

El dominio reconoce tres estados visibles:
- **Postulado** — proceso activo, en seguimiento
- **Rechazado** — cierre explícito por el usuario
- **Vencido** — sin novedades tras 15 días (calculado)

Solo Postulado y Rechazado se pueden elegir al registrar o editar.

## Por que importa

Los estados guían métricas del dashboard, filtros, colores de badge y la vista `vencidas.php`. Mezclar estado almacenado con estado mostrado genera bugs en conteos y filtros.

## Como aparece aqui

- Lista de estados: `docs/02_reglas_negocio.md` Regla 4
- Validación en `public/editar.php`: `in_array($datos['estado'], ['Postulado', 'Rechazado'])`
- Registro inicial siempre `Postulado` en `public/registrar.php`
- `match()` en `public/index.php` asigna color Bootstrap por `estado_real`

## Que recordar

**Tres estados para el usuario; dos valores persistidos en BD.**

## Relacionado
- [[Decision - Vencido se calcula no se almacena]]
- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Conexion - CHECK constraint protege estados en SQLite]]