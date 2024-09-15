CREATE DATABASE empresa_actividades;

USE empresa_actividades;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID autoincremental
    identification_number VARCHAR(20) NOT NULL, -- Número de identificación (cédula o documento)
    username VARCHAR(100) NOT NULL, -- Nombre de usuario (puede ser la cédula también o nombre único)
    password VARCHAR(255) NOT NULL, -- Contraseña en formato hash
    email VARCHAR(255) NOT NULL, -- Correo corporativo
    role ENUM('empleado', 'jefe') NOT NULL, -- Rol del usuario (empleado o jefe)
    UNIQUE(identification_number) -- Asegura que el número de identificación sea único
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID autoincremental para la actividad
    user_id INT, -- Relación con la tabla de usuarios
    activity TEXT NOT NULL, -- Descripción de la actividad
    date DATE NOT NULL, -- Fecha de la actividad
    FOREIGN KEY (user_id) REFERENCES users(id) -- Clave foránea que relaciona con la tabla de usuarios
);
