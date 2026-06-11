---
tipo: concepto
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - ajax
  - fetch
  - javascript
estado: vigente
relacionado:
  - "[[Decision - PHP puro sin frameworks]]"
archivos:
  - public/index.php
---

# Concepto - AJAX con fetch para busqueda en tiempo real

## Idea central

Cuando el usuario escribe en el buscador, la pagina no se recarga. JavaScript hace una peticion al servidor en segundo plano (`fetch`), recibe solo el HTML de la tabla filtrada y reemplaza el contenido del `<div>` correspondiente. El resultado: el input nunca pierde el foco y todo se siente instantaneo.

## Problema que resuelve

Sin AJAX, cada vez que el usuario escribia una letra, el formulario se enviaba, el servidor devolvia la pagina completa y el navegador la renderizaba de nuevo. Esto:

- hacia parpadear la pantalla
- perdia el foco del input (el usuario debia hacer clic de nuevo para seguir escribiendo)
- consumia mas ancho de banda (se transferia HTML repetido: header, footer, navbar, metricas)

## Como aparece en este proyecto

En `public/index.php`:

**Lado servidor (PHP):**
Cuando la URL incluye `?ajax=1`, el PHP detecta que es una peticion AJAX, ejecuta `resultados_html()` con los filtros y hace `return` para no renderizar el resto de la pagina (header, footer, metricas, formulario).

**Lado cliente (JavaScript):**
Un `EventListener` en el input del buscador usa un **debounce de 400ms**: espera a que el usuario deje de escribir para ejecutar `filtrar()`. Esta funcion construye la URL con los filtros actuales usando `URLSearchParams` y llama a `fetch()`. Cuando el servidor responde con el HTML de la tabla, reemplaza el contenido del `<div id="resultados">`.

Los selects de filtro (`<select>`) tambien disparan `filtrar()` pero sin debounce, porque solo cambian cuando el usuario elige una opcion explicitamente.

## Explicacion

El flujo completo:

```
1. Usuario escribe "Micro" en el buscador
2. JavaScript espera 400ms (debounce)
3. fetch("index.php?ajax=1&busqueda=Micro")
4. PHP recibe ajax=1 -> solo ejecuta resultados_html() y termina
5. JavaScript recibe "<table>...Microsoft...</table>"
6. resultados.innerHTML = HTML recibido
7. El input conserva el foco, el cursor esta donde el usuario lo dejo
```

El **debounce** evita hacer una peticion por cada letra tipeada. Sin el, escribir "Micro" generaria 4 peticiones. Con el, solo una con el termino completo.

## Que recordar

- `fetch()` es la forma moderna de hacer AJAX (reemplaza XMLHttpRequest)
- `URLSearchParams` construye query strings sin errores de encoding
- El patron `?ajax=1` en el servidor es simple y evita crear rutas separadas
- `resultados_html()` es una funcion compartida entre carga normal y AJAX (DRY)
- Sin el debounce, cada tecla dispararia una peticion al servidor

## Relacionado

- [[Decision - PHP puro sin frameworks]]
