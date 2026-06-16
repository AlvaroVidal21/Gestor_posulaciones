#php

# PHP puro sin frameworks

## Que entender

El proyecto usa PHP del servidor sin Laravel, Symfony ni similares. Las páginas en `public/` incluyen lógica de presentación; la lógica reutilizable vive en `app/`.

No hay router central, ORM ni capa MVC formal.

## Por que importa

Para un portafolio académico, el código debe ser legible de punta a punta. Un framework aporta convenciones útiles en proyectos grandes, pero aquí añadiría capas que dificultan explicar el flujo en una entrevista.

## Como aparece aqui

Estructura plana:
- `public/index.php`, `registrar.php`, `editar.php` — puntos de entrada HTTP
- `app/postulacion.php` — funciones de negocio y acceso a datos
- `public/_header.php` y `_footer.php` — layout compartido con `require_once`

Validación en servidor, redirecciones con `header('Location: ...')`, HTML con Bootstrap por CDN.

## Que recordar

**Simplicidad deliberada: cada archivo tiene un rol claro y el flujo se sigue leyendo el código.**

## Relacionado
- [[Concepto - PDO y SQLite en PHP]]
- `AGENTS.md`
- `public/`
- `app/`