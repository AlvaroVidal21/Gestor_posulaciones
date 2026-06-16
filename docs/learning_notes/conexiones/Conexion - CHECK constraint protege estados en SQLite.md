#sqlite

# CHECK constraint protege estados en SQLite

## Que entender

Un **CHECK constraint** rechaza filas que no cumplen una condición al INSERT o UPDATE. Aquí limita `estado` a valores permitidos en BD.

## Por que importa

Complementa la validación PHP: aunque un formulario malicioso envíe `Vencido` o basura, SQLite lanza error y no persiste el dato. Refuerza la decisión de que `Vencido` no es almacenable.

## Como aparece aqui

En `database/schema.sql`:

```sql
estado TEXT NOT NULL CHECK (estado IN ('Postulado', 'Rechazado'))
```

Si alguien intentara guardar `Vencido`, `PDOException` sería capturada en `public/editar.php` o `public/registrar.php`.

Encadena con [[Decision - Vencido se calcula no se almacena]]: la BD solo acepta lo que el usuario puede elegir.

## Que recordar

**CHECK en esquema + validación en PHP = doble línea de defensa para estados.**

## Relacionado
- [[Decision - Vencido se calcula no se almacena]]
- [[Regla - Estados validos de postulacion]]
- `database/schema.sql`