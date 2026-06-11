-- database/schema.sql
-- Esquema de la base de datos del Gestor de Postulaciones.
--
-- La entidad principal (y única) es "postulaciones".
-- No se almacena el estado "Vencido" en BD (ver DEC-003 en docs/05_decisiones.md).
-- El estado "Vencido" se calcula en tiempo de consulta según la regla de negocio:
-- si pasaron más de 15 días desde fecha_ultima_actualizacion y el estado almacenado
-- es "Postulado", entonces se muestra como "Vencido".
--
-- Ver docs/03_entidades.md para la definición completa de atributos.

CREATE TABLE IF NOT EXISTS postulaciones (
    id                       INTEGER PRIMARY KEY AUTOINCREMENT,
    empresa                  TEXT    NOT NULL,
    puesto                   TEXT    NOT NULL,
    plataforma               TEXT    NOT NULL,
    url_oferta               TEXT,
    fecha_postulacion        TEXT    NOT NULL,  -- Formato ISO: YYYY-MM-DD
    fecha_ultima_actualizacion TEXT  NOT NULL,  -- Formato ISO: YYYY-MM-DD
    estado                   TEXT    NOT NULL CHECK (estado IN ('Postulado', 'Rechazado')),
    notas                    TEXT
);
