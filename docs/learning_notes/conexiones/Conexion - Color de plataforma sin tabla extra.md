#php

# Color de plataforma sin tabla extra

## Que entender

Cada plataforma muestra un badge con color Bootstrap consistente sin guardar colores en BD. El color se **deriva** del nombre con `crc32()` y módulo sobre una lista fija de colores.

## Por que importa

Evita tabla `plataformas` o campo `color` solo para estética. Misma idea que el estado calculado: derivar en lectura lo que no necesitas persistir.

Trade-off: no puedes elegir manualmente el color de LinkedIn; aceptas asignación pseudoaleatoria pero estable.

## Como aparece aqui

`obtener_color_plataforma()` y `badge_plataforma_html()` en `app/postulacion.php`.

`normalizar_plataforma()` unifica espacios antes del hash para que "LinkedIn" y "LinkedIn " compartan color.

Usado en métricas y tabla de `public/index.php`.

## Que recordar

**Hash del nombre → índice en array de colores → badge estable sin schema extra.**

## Relacionado
- [[Decision - Empresa es atributo de Postulacion]]
- [[Concepto - Datalist para sugerir valores existentes]]
- `app/postulacion.php`