USE empresa_actividades;
ALTER TABLE activities
ADD COLUMN hour TIME NOT NULL AFTER date;