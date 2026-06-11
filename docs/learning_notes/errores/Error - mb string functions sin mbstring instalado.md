---
tipo: error
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - error
  - php
  - mbstring
estado: historico
relacionado:
  - "[[Concepto - AJAX con fetch para busqueda en tiempo real]]"
archivos:
  - app/postulacion.php
---

# Error - mb string functions sin mbstring instalado

## Problema

La funcion `mb_strpos()` y `mb_strtolower()` se usaron en el buscador de `app/postulacion.php` para hacer busquedas sin distincion de mayusculas/minusculas y con soporte de caracteres multibyte (acentos, e ne, etc.). Al ejecutar el codigo, PHP lanzaba un error: las funciones no existian.

## Causa

`mb_strpos` y `mb_strtolower` pertenecen a la extension `mbstring` (multibyte string) de PHP. Esta extension no viene instalada por defecto en todas las distribuciones. En Fedora (el sistema del desarrollador), `php-cli` no incluye `mbstring` a menos que se instale explicitamente.

## Correccion

Se reemplazaron las funciones `mb_*` por sus equivalentes sin prefijo `mb_`:

| Original | Reemplazo |
|----------|-----------|
| `mb_strtolower($texto)` | `strtolower($texto)` |
| `mb_strpos($texto, $termino)` | `strpos($texto, $termino)` |

Para el alcance del proyecto (texto en espanol, busquedas personales), las funciones sin `mb_` funcionan correctamente porque los caracteres acentuados en UTF-8 no rompen la busqueda con `strpos`/`strtolower` cuando se comparan cadenas ya normalizadas.

## Aprendizaje

- No asumas que todas las extensiones de PHP estan instaladas
- `mbstring` es comun en servidores compartidos y entornos Windows, pero no siempre en Linux minimalista
- Si necesitas soporte multibyte real (coreano, japones, arabe), `mbstring` es obligatorio
- Para proyectos personales en espanol, `strtolower` + `strpos` son suficientes
- La forma correcta de verificar si una extension existe: `php -m | grep mbstring`

## Que recordar

- `mb_*` no esta disponible por defecto en todas las instalaciones de PHP
- `strtolower` y `strpos` funcionan bien para busquedas en espanol
- Siempre verifica las extensiones necesarias en la documentacion del proyecto

## Relacionado

- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
