---
tipo: regla_negocio
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - regla-negocio
  - estados
estado: vigente
relacionado:
  - "[[Conexion - CHECK constraint protege estados en SQLite]]"
  - "[[Error - Contradiccion en regla de vencimiento]]"
archivos:
  - database/schema.sql
  - docs/02_reglas_negocio.md
---

# Regla - Estados validos de postulacion

## Regla de negocio

> Toda postulacion debe tener un estado valido. Los estados permitidos son: Postulado, Vencido y Rechazado.

Sin embargo, "Vencido" **no se almacena** en la base de datos (ver [[Decision - Vencido se calcula no se almacena]]). En la practica, la columna `estado` de la tabla `postulaciones` solo acepta `'Postulado'` o `'Rechazado'`.

## Implementacion

A nivel de base de datos, en `database/schema.sql`:

```sql
estado TEXT NOT NULL CHECK (estado IN ('Postulado', 'Rechazado'))
```

La clausula `CHECK` en SQLite garantiza que ningun INSERT o UPDATE pueda escribir un valor no permitido. Si alguien intenta `UPDATE postulaciones SET estado = 'Invalido'`, SQLite lanza un error.

A nivel de aplicacion, en `public/registrar.php` y `public/editar.php`:

```php
if (!in_array($datos['estado'], ['Postulado', 'Rechazado'], true)) {
    $errores[] = 'El estado seleccionado no es valido.';
}
```

La validacion en PHP es la primera barrera. El `CHECK` en SQLite es la segunda, por si el codigo PHP tiene un error o alguien modifica la base de datos directamente.

## Por que es importante

Los estados restringen el comportamiento del sistema. Si permitieras cualquier valor, la logica de vencimiento, las metricas del dashboard y los filtros dejarian de funcionar correctamente. Tener estados definidos y validados es lo que hace que el sistema sea predecible.

## Ejemplo simple

```
INSERT INTO postulaciones (estado) VALUES ('Vencido');
-> SQLite rechaza la insercion porque 'Vencido' no esta en el CHECK

INSERT INTO postulaciones (estado) VALUES ('Postulado');
-> Correcto, 'Postulado' esta permitido

UPDATE postulaciones SET estado = 'Rechazado' WHERE id = 1;
-> Correcto, 'Rechazado' esta permitido
```

## Que recordar

- Hay 3 estados en el dominio pero solo 2 se almacenan en BD
- El CHECK en SQLite protege contra datos invalidos a nivel de base de datos
- La validacion en PHP protege contra datos invalidos a nivel de aplicacion
- Las dos capas de validacion no son redundancia: son defensa en profundidad

## Relacionado

- [[Conexion - CHECK constraint protege estados en SQLite]]
- [[Decision - Vencido se calcula no se almacena]]
- [[Error - Contradiccion en regla de vencimiento]]
