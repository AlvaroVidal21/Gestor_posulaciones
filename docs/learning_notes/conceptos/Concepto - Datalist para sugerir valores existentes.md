---
tipo: concepto
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - html
  - usabilidad
estado: vigente
relacionado:
  - "[[Decision - Empresa es atributo de Postulacion]]"
  - "[[Decision - PHP puro sin frameworks]]"
archivos:
  - public/registrar.php
  - public/editar.php
  - app/postulacion.php
---

# Concepto - Datalist para sugerir valores existentes

## Idea central

Un `<datalist>` permite que un campo de texto muestre sugerencias sin dejar de aceptar valores nuevos. En este proyecto se usa para `plataforma`: el usuario puede elegir una plataforma ya registrada o escribir una nueva.

## Problema que resuelve

Antes, `plataforma` era solo texto libre. Eso funcionaba, pero obligaba a recordar como se habia escrito cada plataforma y facilitaba variantes por descuido, por ejemplo `LinkedIn`, `linkedin` o espacios extra.

Crear una tabla `plataformas` con CRUD propio habria resuelto el problema, pero tambien agregaria una entidad que el sistema no necesita.

## Como aparece en este proyecto

En `app/postulacion.php`, `obtener_plataformas()` consulta las plataformas unicas existentes en las postulaciones. Los formularios `public/registrar.php` y `public/editar.php` usan esa lista dentro de un `<datalist>`.

El campo sigue siendo un `<input type="text">`, por eso conserva la libertad de escribir una plataforma nueva. La funcion `normalizar_plataforma()` limpia espacios extra antes de guardar.

## Explicacion

Este patron es un punto medio entre texto libre y catalogo cerrado:

- texto libre: simple, pero propenso a variaciones
- select cerrado: ordenado, pero impide registrar una plataforma nueva
- datalist: sugiere valores existentes y permite nuevos

Para un gestor personal, ese equilibrio mantiene la interfaz ligera sin crear pantallas administrativas innecesarias.

## Que recordar

- `datalist` mejora la usabilidad sin cambiar el modelo de datos
- sirve cuando quieres sugerencias, no un catalogo obligatorio
- encaja con la decision de mantener plataformas como atributo de `Postulacion`
- normalizar espacios ayuda a reducir duplicados accidentales

## Relacionado

- [[Decision - Empresa es atributo de Postulacion]]
- [[Decision - PHP puro sin frameworks]]
