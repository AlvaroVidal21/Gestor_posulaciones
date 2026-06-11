# Gestor de Postulaciones

Aplicación web para registrar, consultar y dar seguimiento a postulaciones laborales durante la búsqueda de empleo en TI.

## Propósito

Mantener trazabilidad de las postulaciones laborales para evitar que la información quede dispersa en diferentes medios (correos, hojas de cálculo, mensajes). El sistema permite tener una visión clara del progreso y saber qué postulaciones requieren atención.

## Tecnologías

- **PHP** — Lógica del servidor sin frameworks
- **SQLite** — Base de datos embebida, sin configuración de servidor
- **Bootstrap 5** — Interfaz responsive
- **JavaScript** — Solo para interacciones simples (filtrado automático y AJAX)
- **Python + pandas** — Análisis de datos y gráficos en terminal (scripts/analytics.py)

## Características

- Registrar postulaciones con empresa, puesto, plataforma, URL y notas
- Dashboard con métricas: totales, activas, vencidas y rechazadas
- Búsqueda textual sobre empresa, puesto y plataforma
- Filtros combinables por estado y plataforma
- Editar y eliminar postulaciones
- Cálculo automático de postulaciones vencidas (+15 días sin actualización)
- Vista dedicada a postulaciones vencidas

## Requisitos

- PHP 8.1 o superior con extensión PDO SQLite
- SQLite3
- Python 3.10+ y [uv](https://docs.astral.sh/uv/) (para dashboard de terminal con pandas)

## Instalación y ejecución

```bash
# 1. Inicializar la base de datos
php database/init.php

# 2. Iniciar el servidor de desarrollo
php -S localhost:8000 -t public/

# 3. Abrir en el navegador
# http://localhost:8000
```

## Estructura del proyecto

```
app/            Lógica PHP reutilizable
├── config.php          Conexión a SQLite
└── postulacion.php     CRUD y reglas de negocio

public/         Páginas visibles
├── index.php           Dashboard con métricas y filtros
├── registrar.php       Formulario de registro
├── editar.php          Formulario de edición
├── eliminar.php        Confirmación y eliminación
├── vencidas.php        Vista de postulaciones vencidas
├── _header.php         Encabezado y navbar
└── _footer.php         Pie de página

database/       Base de datos
├── schema.sql          Definición de la tabla postulaciones
└── init.php            Script de inicialización

scripts/        Herramientas complementarias
└── analytics.py        Dashboard de análisis en terminal (pandas + plotext)

docs/           Documentación del proyecto
```

## Estados de postulación

| Estado | Descripción |
|--------|-------------|
| Postulado | Postulación activa, dentro del plazo de seguimiento |
| Vencido | Más de 15 días sin actualización — requiere atención |
| Rechazado | La empresa rechazó la postulación |

El estado Vencido se calcula automáticamente en cada consulta; no se almacena en la base de datos.

## Dashboard de terminal (análisis con pandas)

El script `scripts/analytics.py` genera un informe visual en la terminal con métricas, gráficos de barras, timeline y un histograma de antigüedad.

```bash
uv run scripts/analytics.py
```

Utiliza **pandas** para procesar los datos y **plotext + rich** para los gráficos y tablas en terminal.

## Documentación

Los documentos en `docs/` describen el modelo de negocio, reglas, entidades, casos de uso y decisiones técnicas.

## Licencia

Proyecto académico y de portafolio personal.
