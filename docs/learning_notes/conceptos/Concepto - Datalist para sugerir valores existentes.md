#html

# Datalist para sugerir valores existentes

## Que entender

`<datalist>` es HTML nativo que ofrece sugerencias en un `<input type="text">` sin convertirlo en un `<select>` rígido. El usuario puede elegir una opción existente o escribir una nueva.

Se vincula con el atributo `list="id-del-datalist"` en el input.

## Por que importa

Las plataformas (LinkedIn, Get on Board, etc.) se repiten pero no son un catálogo cerrado. Un select obligaría a mantener opciones fijas; un input libre generaría duplicados por typos. El datalist equilibra ambos.

## Como aparece aqui

En `public/registrar.php` y `public/editar.php`:
- PHP consulta plataformas distintas con `obtener_plataformas()`
- Genera `<datalist id="lista-plataformas">` con `<option value="...">`
- El input de plataforma usa `list="lista-plataformas"`

`normalizar_plataforma()` en `app/postulacion.php` reduce variantes por espacios, no por mayúsculas.

## Que recordar

**Datalist = autocompletado sin perder la libertad de texto libre.**

## Relacionado
- `public/registrar.php`
- `public/editar.php`
- `app/postulacion.php`