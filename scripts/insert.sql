USE empresa_actividades;

-- Insertar un usuario administrador
INSERT INTO users (identification_number, username, password, email, role)
VALUES ('1234567890', 'jhonfre', 'admin', 'admin@papeleria-cvf.com', 'administrador');

-- Insertar tres usuarios con rol de jefe
INSERT INTO users (identification_number, username, password, email, role)
VALUES 
('2234567890', 'flor', 'flor', 'flor@papeleria-cvf.com', 'jefe'),
('3234567890', 'marina', 'marina', 'marina@papeleria-cvf.com', 'jefe'),
('4234567890', 'ramon', 'ramon', 'ramon@papeleria-cvf.com', 'jefe');

-- Insertar diez usuarios con rol de empleado
INSERT INTO users (identification_number, username, password, email, role)
VALUES 
('5234567890', 'lorena', 'lorena', 'lorena@papeleria-cvf.com', 'empleado'),
('6234567890', 'angie', 'angie', 'angie@papeleria-cvf.com', 'empleado'),
('7234567890', 'fredy', 'fredy', 'fredy@papeleria-cvf.com', 'empleado'),
('8234567890', 'camila', 'camila', 'camila@papeleria-cvf.com', 'empleado');
