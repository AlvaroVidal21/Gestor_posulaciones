#sqlite

# SQLite como base de datos

## Que entender

SQLite persiste datos en un único archivo en disco. No requiere proceso de base de datos aparte ni usuario/contraseña.

Es adecuado para aplicaciones de un solo usuario con concurrencia baja.

## Por que importa

Reduce fricción de instalación y despliegue: copiar el proyecto incluye (o recrea) el `.sqlite`. Para un gestor personal en portafolio, es la opción más simple que sigue siendo SQL real.

Limitación consciente: no escala a múltiples usuarios concurrentes escribiendo.

## Como aparece aqui

- Archivo: `database/gestor_postulaciones.sqlite`
- Esquema: `database/schema.sql`
- Inicialización: `database/init.php`
- Conexión: `DB_PATH` en `app/config.php`

DEC-001 en `docs/05_decisiones.md`.

## Que recordar

**Un usuario, pocos datos, cero ops de servidor → SQLite encaja.**

## Relacionado
- [[Concepto - PDO y SQLite en PHP]]
- [[Decision - Empresa es atributo de Postulacion]]
- `database/schema.sql`