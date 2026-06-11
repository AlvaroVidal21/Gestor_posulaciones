---
tipo: moc
proyecto: Gestor de Postulaciones
tags:
  - learning-notes
  - moc
  - obsidian
estado: vigente
---

# MOC - Learning Notes - Gestor de Postulaciones

## Mapa principal
- [[Concepto - Estado calculado vs almacenado]]
- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
- [[Concepto - PDO y SQLite en PHP]]
- [[Decision - Vencido se calcula no se almacena]]
- [[Decision - Empresa es atributo de Postulacion]]
- [[Decision - PHP puro sin frameworks]]
- [[Decision - SQLite como base de datos]]
- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Regla - Postulacion vencida puede reactivarse]]
- [[Regla - Estados validos de postulacion]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Conexion - CHECK constraint protege estados en SQLite]]
- [[Error - Contradiccion en regla de vencimiento]]
- [[Error - mb string functions sin mbstring instalado]]

## Reglas de negocio
- [[Regla - Vencimiento automatico a los 15 dias]]
  - relacionado con: [[Decision - Vencido se calcula no se almacena]], [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Regla - Postulacion vencida puede reactivarse]]
  - relacionado con: [[Regla - Vencimiento automatico a los 15 dias]], [[Concepto - Estado calculado vs almacenado]]
- [[Regla - Estados validos de postulacion]]
  - relacionado con: [[Conexion - CHECK constraint protege estados en SQLite]], [[Error - Contradiccion en regla de vencimiento]]

## Decisiones de diseno
- [[Decision - Vencido se calcula no se almacena]]
  - relacionado con: [[Concepto - Estado calculado vs almacenado]], [[Regla - Vencimiento automatico a los 15 dias]]
- [[Decision - Empresa es atributo de Postulacion]]
  - relacionado con: [[Decision - SQLite como base de datos]]
- [[Decision - PHP puro sin frameworks]]
  - relacionado con: [[Decision - SQLite como base de datos]]
- [[Decision - SQLite como base de datos]]
  - relacionado con: [[Concepto - PDO y SQLite en PHP]]

## Conceptos tecnicos
- [[Concepto - Estado calculado vs almacenado]]
  - relacionado con: [[Decision - Vencido se calcula no se almacena]], [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
  - relacionado con: [[Decision - PHP puro sin frameworks]]
- [[Concepto - PDO y SQLite en PHP]]
  - relacionado con: [[Decision - SQLite como base de datos]]

## Conexiones negocio-codigo
- [[Conexion - Regla de vencimiento en PHP y Python]]
  - relacionado con: [[Regla - Vencimiento automatico a los 15 dias]], [[Concepto - Estado calculado vs almacenado]]
- [[Conexion - CHECK constraint protege estados en SQLite]]
  - relacionado con: [[Regla - Estados validos de postulacion]], [[Error - Contradiccion en regla de vencimiento]]

## Errores y aprendizajes
- [[Error - Contradiccion en regla de vencimiento]]
  - relacionado con: [[Regla - Vencimiento automatico a los 15 dias]], [[Decision - Vencido se calcula no se almacena]]
- [[Error - mb string functions sin mbstring instalado]]
  - relacionado con: [[Concepto - AJAX con fetch para busqueda en tiempo real]]

## Archivos clave del proyecto
- `app/postulacion.php`
  - notas relacionadas: [[Concepto - Estado calculado vs almacenado]], [[Regla - Vencimiento automatico a los 15 dias]], [[Conexion - Regla de vencimiento en PHP y Python]]
- `public/index.php`
  - notas relacionadas: [[Concepto - AJAX con fetch para busqueda en tiempo real]]
- `app/config.php`
  - notas relacionadas: [[Concepto - PDO y SQLite en PHP]], [[Decision - SQLite como base de datos]]
- `database/schema.sql`
  - notas relacionadas: [[Conexion - CHECK constraint protege estados en SQLite]], [[Regla - Estados validos de postulacion]]
- `scripts/analytics.py`
  - notas relacionadas: [[Conexion - Regla de vencimiento en PHP y Python]]
