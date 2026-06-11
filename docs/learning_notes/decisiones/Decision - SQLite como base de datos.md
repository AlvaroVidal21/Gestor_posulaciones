---
tipo: decision
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - decision-diseno
  - base-datos
  - sqlite
estado: vigente
relacionado:
  - "[[Concepto - PDO y SQLite en PHP]]"
  - "[[Decision - PHP puro sin frameworks]]"
archivos:
  - app/config.php
  - docs/05_decisiones.md
---

# Decision - SQLite como base de datos

## Problema que resuelve

Donde guardar los datos de las postulaciones sin complicar la instalacion ni el despliegue.

## Decision tomada

SQLite como motor de base de datos. Los datos viven en un archivo `gestor_postulaciones.sqlite` dentro de la carpeta `database/`.

## Motivo

SQLite es la base de datos mas simple que existe para proyectos de un solo usuario:
- No requiere servidor, no requiere configuracion, no requiere credenciales
- El archivo se crea automaticamente al abrir una conexion PDO
- Todo el respaldo del sistema es copiar un archivo
- Es la base de datos mas usada del mundo (navegadores, telefonos, IoT)

Para un gestor personal con un unico usuario, MySQL o PostgreSQL serian sobredimensionados, como instalar un generador electrico industrial para alimentar una lampara de escritorio.

## Alternativas consideradas

1. **MySQL**: requiere instalar un servidor, crear un usuario, configurar puertos. Demasiado para un proyecto personal.
2. **PostgreSQL**: similar a MySQL pero mas complejo. Justificado solo si necesitas caracteristicas avanzadas (transacciones concurrentes, tipos geometricos, etc.).
3. **JSON en archivo de texto**: aun mas simple que SQLite, pero sin capacidad de consulta, filtrado ni integridad de datos.
4. **SQLite (decision tomada)**: el punto justo entre simplicidad y funcionalidad.

## Consecuencia

- No hay que instalar ni configurar nada adicional (PHP ya incluye `pdo_sqlite`)
- La base de datos viaja con el proyecto (un solo archivo)
- No soporta concurrencia de escritura pesada, pero para un solo usuario es irrelevante
- La conexion se define en `app/config.php` con una linea: `new PDO('sqlite:' . DB_PATH)`

## Que recordar

- DEC-001 en `docs/05_decisiones.md` documenta formalmente esta decision
- SQLite es ideal para herramientas personales, prototipos y proyectos academicos
- Un solo archivo `.sqlite` contiene toda la base de datos
- El backup es literalmente `cp database/gestor_postulaciones.sqlite backup.sqlite`

## Relacionado

- [[Concepto - PDO y SQLite en PHP]]
- [[Decision - PHP puro sin frameworks]]
- [[Conexion - CHECK constraint protege estados en SQLite]]
