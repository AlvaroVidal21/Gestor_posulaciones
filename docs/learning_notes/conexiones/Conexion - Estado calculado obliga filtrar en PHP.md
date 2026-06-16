#php

# Estado calculado obliga filtrar en PHP

## Que entender

Como `Vencido` no existe en la columna `estado`, un `WHERE estado = 'Vencido'` en SQL siempre fallaría o devolvería vacío.

El filtrado por estado visible debe ocurrir **después** de calcular `estado_real` en memoria.

## Por que importa

Es la consecuencia directa de [[Decision - Vencido se calcula no se almacena]]. Afecta rendimiento (cargar todas las filas antes de filtrar), pero con un volumen personal de postulaciones es aceptable y el código queda simple.

## Como aparece aqui

`buscar_postulaciones()` en `app/postulacion.php`:
1. Llama `obtener_todas()` (ya con `estado_real`)
2. Aplica `array_filter` comparando `$p['estado_real'] === $criterios['estado']`

El comentario del archivo lo documenta explícitamente (CU-003, CU-004).

El dashboard AJAX usa la misma función, así que filtros en tiempo real también dependen de este flujo.

## Que recordar

**Sin columna Vencido en SQL → filtro de estado en PHP sobre `estado_real`.**

## Relacionado
- [[Concepto - Estado calculado vs almacenado]]
- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
- `app/postulacion.php`