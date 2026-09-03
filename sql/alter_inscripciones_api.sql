-- Ejecutar una sola vez sobre la base "sistema_cursos" (MySQL)
-- Agrega los campos necesarios para que la APK pueda enviar
-- inscripciones de forma idempotente (sin duplicados) e identificar
-- su origen (web o app).

ALTER TABLE inscripciones
    ADD COLUMN uuid VARCHAR(64) NULL UNIQUE AFTER curso_id,
    ADD COLUMN origen VARCHAR(20) NOT NULL DEFAULT 'web' AFTER estado_pago,
    ADD COLUMN fecha_creacion DATETIME NULL AFTER origen;
