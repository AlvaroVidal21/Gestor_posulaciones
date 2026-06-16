#javascript

# Debounce en busqueda en tiempo real

## Que entender

**Debounce** retrasa la ejecución de una función hasta que el usuario deja de disparar el evento durante un intervalo (aquí, 400 ms).

Cada nueva tecla cancela el timer anterior con `clearTimeout()` y programa uno nuevo.

## Por que importa

Sin debounce, escribir "Microsoft" generaría 9 peticiones AJAX seguidas. Con debounce, solo una petición cuando el usuario pausa. Menos carga en servidor y menos parpadeo en la tabla.

Los `<select>` de filtros no necesitan debounce: cambian una sola vez por selección.

## Como aparece aqui

En `public/index.php`, el listener `input` del campo `#busqueda` guarda el timer en `this._timer` y llama `filtrar()` tras 400 ms de inactividad.

Los listeners `change` de estado y plataforma llaman `filtrar()` directamente.

## Que recordar

**Debounce en inputs de texto; cambio inmediato en selects.**

## Relacionado
- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
- `public/index.php`