---
title: "Gestor de Postulaciones — seguimiento simple para tu búsqueda laboral"
description: "Dashboard web para registrar postulaciones y script de Python con analíticas visuales en terminal — todo sobre una misma base de datos SQLite."
pubDate: 2026-06-11
tags: ["php", "sqlite", "python", "pandas", "bootstrap", "proyecto-personal"]
---

Un dashboard web que te permite registrar y dar seguimiento a tus postulaciones laborales, acompañado de un script de Python que genera analíticas visuales directo en la terminal.

## Por qué lo hice?

Durante mi búsqueda de prácticas en TI, me encontré con un problema común: las postulaciones quedaban dispersas en LinkedIn, correos, WhatsApp y hojas de cálculo. Perdía el rastro de a qué empresas había postulado, cuándo lo hice y en qué estado estaba cada proceso.

En lugar de buscar una herramienta externa, decidí construir la mía propia.

## Qué hace?

Es un gestor personal de postulaciones laborales con dos caras:

**Web app (PHP + SQLite + Bootstrap):**
- Registrar postulaciones con empresa, puesto, plataforma y URL de la oferta.
- Seguimiento con estados: Postulado, Vencido o Rechazado.
- Si pasan más de 7 días sin novedades, la postulación se marca automáticamente como Vencida.
- Búsqueda y filtros en tiempo real sin recargar la página.
- Métricas rápidas: total, activas, vencidas y rechazadas.

**Dashboard en terminal (Python + pandas):**
- Un script que lee la misma base de datos SQLite y genera gráficos ASCII en la terminal.
- Distribución por plataforma, timeline por mes, top empresas, histograma de antigüedad.
- Ideal para complementar la web con un análisis más detallado.

## Tecnologías

- **PHP puro** — sin frameworks, para mantener el proyecto simple y entendible.
- **SQLite** — base de datos embebida que no necesita instalación ni servidor.
- **Bootstrap 5** — interfaz limpia y responsive.
- **JavaScript** — solo lo justo para filtros en tiempo real con fetch.
- **Python + pandas** — análisis de datos y visualización en terminal con plotext y rich.

## Qué aprendí?

- Modelar una sola entidad (Postulación) con reglas de negocio claras.
- Separar lógica de presentación en PHP sin sobreingenierizar.
- Integrar dos herramientas (web + terminal) compartiendo la misma base de datos.
- Que un proyecto pequeño, bien pensado, puede ser más útil que uno grande y lleno de funcionalidades que no usas.
