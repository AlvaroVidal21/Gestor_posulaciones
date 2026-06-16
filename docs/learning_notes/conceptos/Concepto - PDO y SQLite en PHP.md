#php #sqlite

# PDO y SQLite en PHP

## Que entender

**PDO** (PHP Data Objects) es la capa estándar de PHP para hablar con bases de datos. **SQLite** guarda todo en un archivo local, sin servidor ni credenciales.

La conexión se abre con `new PDO('sqlite:' . ruta)` y las consultas usan `prepare()` + `execute()` para evitar inyección SQL.

## Por que importa

Para un gestor personal de un solo usuario, SQLite elimina configuración de servidor. PDO unifica el estilo de acceso a datos y permite cambiar de motor en el futuro sin reescribir toda la capa (aunque aquí no se prevé ese cambio).

## Como aparece aqui

`app/config.php` define `DB_PATH` y `obtener_conexion()` con:
- `ERRMODE_EXCEPTION` para fallar con excepciones claras
- `FETCH_ASSOC` para arrays asociativos por nombre de columna
- `PRAGMA foreign_keys = ON` por si más adelante hay relaciones

`app/postulacion.php` centraliza SELECT, UPDATE y DELETE sobre la tabla `postulaciones`.

## Que recordar

**Un archivo `.sqlite` + PDO prepared statements = persistencia simple y segura para este proyecto.**

## Relacionado
- [[Decision - SQLite como base de datos]]
- `app/config.php`
- `database/gestor_postulaciones.sqlite`