# Learning Notes - Gestor de Postulaciones

## Notas

### Conceptos
- [[Concepto - Estado calculado vs almacenado]]
- [[Concepto - PDO y SQLite en PHP]]
- [[Concepto - AJAX con fetch para busqueda en tiempo real]]
- [[Concepto - Datalist para sugerir valores existentes]]
- [[Concepto - Debounce en busqueda en tiempo real]]

### Decisiones
- [[Decision - Empresa es atributo de Postulacion]]
- [[Decision - PHP puro sin frameworks]]
- [[Decision - SQLite como base de datos]]
- [[Decision - Vencido se calcula no se almacena]]

### Reglas de negocio
- [[Regla - Estados validos de postulacion]]
- [[Regla - Vencimiento automatico a los 7 dias]]
- [[Regla - Postulacion vencida puede reactivarse]]

### Errores
- [[Error - Contradiccion en regla de vencimiento]]

### Conexiones
- [[Conexion - CHECK constraint protege estados en SQLite]]
- [[Conexion - Regla de vencimiento en PHP y Python]]
- [[Conexion - Estado calculado obliga filtrar en PHP]]
- [[Conexion - Color de plataforma sin tabla extra]]

## Archivos clave
- `app/postulacion.php` -> [[Concepto - Estado calculado vs almacenado]], [[Regla - Vencimiento automatico a los 7 dias]]
- `app/config.php` -> [[Concepto - PDO y SQLite en PHP]], [[Decision - SQLite como base de datos]]
- `database/schema.sql` -> [[Conexion - CHECK constraint protege estados en SQLite]], [[Decision - Vencido se calcula no se almacena]]
- `public/index.php` -> [[Concepto - AJAX con fetch para busqueda en tiempo real]], [[Conexion - Estado calculado obliga filtrar en PHP]]
- `public/registrar.php` -> [[Concepto - Datalist para sugerir valores existentes]]
- `scripts/analytics.py` -> [[Conexion - Regla de vencimiento en PHP y Python]]
- `docs/02_reglas_negocio.md` -> [[Error - Contradiccion en regla de vencimiento]]