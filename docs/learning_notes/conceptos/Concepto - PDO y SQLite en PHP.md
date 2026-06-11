---
tipo: concepto
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - pdo
  - sqlite
  - php
estado: vigente
relacionado:
  - "[[Decision - SQLite como base de datos]]"
archivos:
  - app/config.php
  - app/postulacion.php
  - database/init.php
---

# Concepto - PDO y SQLite en PHP

## Idea central

PHP se conecta a SQLite a traves de PDO (PHP Data Objects), una capa de abstraccion que permite trabajar con la base de datos usando objetos y consultas preparadas, sin necesidad de un servidor de base de datos separado.

## Problema que resuelve

Los proyectos pequeños no deberian requerir instalar y configurar MySQL, PostgreSQL u otros motores. SQLite es un archivo. PDO es una extension de PHP. No hay servidor, no hay puertos, no hay credenciales. El archivo `.sqlite` se crea solo al abrir la conexion.

## Como aparece en este proyecto

En `app/config.php`:

```php
$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```

Tres configuraciones clave:
- `ERRMODE_EXCEPTION`: si una consulta falla, PHP lanza una excepcion que puedes capturar con `try/catch`. Sin esto, los errores pasan desapercibidos.
- `FETCH_ASSOC`: los resultados se devuelven como arrays asociativos (`['empresa' => 'Google']`) en vez de objetos o arrays numericos. Es mas legible.
- `PRAGMA foreign_keys = ON`: activa la verificacion de claves foraneas (aunque el proyecto actual tiene una sola tabla, es buena practica tenerlo).

En `database/init.php` se lee `database/schema.sql` y se ejecuta con `$pdo->exec($schema)`. El `exec()` se usa para sentencias que no devuelven filas (CREATE TABLE, INSERT, UPDATE, DELETE sin parametros).

Para consultas con parametros variables se usa `prepare()` + `execute()` con marcadores `:nombre`. Esto protege contra inyeccion SQL.

## Explicacion

**PDO** es una interfaz uniforme para conectarse a distintos motores de base de datos desde PHP. Cambiando la cadena de conexion de `'sqlite:ruta'` a `'mysql:host=...'` podrias usar MySQL sin modificar el resto del codigo (siempre que uses SQL estandar).

**SQLite** es una base de datos embebida: el motor y los datos estan en un solo archivo. No hay servidor escuchando en un puerto. Esto la hace ideal para proyectos de un solo usuario, herramientas personales y prototipos.

## Que recordar

- PDO es la opcion recomendada sobre `mysqli` porque es mas segura y portable
- Las consultas preparadas (`prepare` + `execute`) son obligatorias si usas datos del usuario (protegen contra inyeccion SQL)
- SQLite no requiere instalacion, solo tener PHP con la extension `pdo_sqlite`
- El modo `ERRMODE_EXCEPTION` deberia estar siempre activado en desarrollo
- El ciclo de vida tipico: `connect -> prepare -> execute -> fetch`

## Relacionado

- [[Decision - SQLite como base de datos]]
- [[Conexion - CHECK constraint protege estados en SQLite]]
