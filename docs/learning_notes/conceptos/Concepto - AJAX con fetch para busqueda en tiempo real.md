#javascript #php

# AJAX con fetch para busqueda en tiempo real

## Que entender

**AJAX** permite pedir datos al servidor sin recargar la página. `fetch()` es la API moderna de JavaScript para esas peticiones HTTP.

Aquí el servidor no devuelve JSON: devuelve un fragmento HTML listo para insertar en el DOM.

## Por que importa

Si el formulario de búsqueda recargara la página entera, el input perdería el foco al escribir. Actualizar solo `#resultados` mantiene la experiencia fluida.

## Como aparece aqui

En `public/index.php`:
1. El JS arma `index.php?ajax=1&busqueda=...&estado=...`
2. PHP detecta `ajax=1`, llama `resultados_html()` y hace `return` sin renderizar header ni métricas
3. `fetch(url).then(r => r.text())` reemplaza `innerHTML` del contenedor

La función `resultados_html()` se reutiliza en carga normal y en respuesta AJAX.

## Que recordar

**Mismo endpoint, dos modos: página completa o fragmento HTML según `?ajax=1`.**

## Relacionado
- [[Concepto - Debounce en busqueda en tiempo real]]
- [[Conexion - Estado calculado obliga filtrar en PHP]]
- `public/index.php`