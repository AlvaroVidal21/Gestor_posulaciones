---
tipo: conexion
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - conexion
  - sqlite
  - validacion
estado: vigente
relacionado:
  - "[[Regla - Estados validos de postulacion]]"
  - "[[Concepto - PDO y SQLite en PHP]]"
archivos:
  - database/schema.sql
  - public/registrar.php
  - public/editar.php
---

# Conexion - CHECK constraint protege estados en SQLite

## Idea central

La base de datos tiene una restriccion que solo permite guardar `'Postulado'` o `'Rechazado'` en la columna `estado`. Esto actua como red de seguridad por si la validacion del codigo PHP falla.

## Regla o flujo de negocio

Los estados validos de una postulacion son Postulado, Vencido y Rechazado. Pero Vencido no se almacena (se calcula). La BD solo debe aceptar los dos valores que realmente se persisten.

## Representacion en el codigo

En `database/schema.sql`:

```sql
estado TEXT NOT NULL CHECK (estado IN ('Postulado', 'Rechazado'))
```

Esta linea le dice a SQLite: "cada vez que alguien intente insertar o actualizar esta columna, verifica que el valor este en la lista. Si no, rechaza la operacion".

Luego, en `public/registrar.php` y `public/editar.php`, el codigo PHP valida antes de enviar a la BD:

```php
if (!in_array($datos['estado'], ['Postulado', 'Rechazado'], true)) {
    $errores[] = 'El estado seleccionado no es valido.';
}
```

## Por que importa

Son dos capas de defensa:

```
Entrada del usuario
  -> PHP valida (si falla, muestra error al usuario)
    -> SQLite CHECK valida (si falla, lanza excepcion PDO)
```

Si el PHP tuviera un bug o alguien modificara la base de datos directamente con SQL, el `CHECK` evita que datos invalidos entren al sistema. Es una practica de seguridad llamada **defense in depth** (defensa en profundidad).

## Que recordar

- El `CHECK` en SQLite es una restriccion a nivel de tabla
- Se define en el `CREATE TABLE` y se aplica automaticamente en cada INSERT/UPDATE
- No necesita logica en PHP para funcionar (aunque el PHP tambien valida)
- Si intentas insertar un estado no valido, SQLite lanza: `CHECK constraint failed: postulaciones`
- La lista en el CHECK coincide exactamente con los valores que el PHP permite enviar

## Relacionado

- [[Regla - Estados validos de postulacion]]
- [[Concepto - PDO y SQLite en PHP]]
