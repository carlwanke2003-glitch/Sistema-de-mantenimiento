-- Script SQL para PredictiveMaintain
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS predictive_maintain;
USE predictive_maintain;

-- Tabla equipos
CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    horas_uso INT NOT NULL DEFAULT 0,
    umbral INT NOT NULL DEFAULT 200
);

-- Tabla incidentes
CREATE TABLE incidentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    estado ENUM('abierto', 'cerrado', 'en_progreso') NOT NULL DEFAULT 'abierto',
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    equipo_id INT,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);

-- Stored procedure para equipos que necesitan mantenimiento
DELIMITER //

CREATE PROCEDURE sp_equipos_necesitan_mantenimiento()
BEGIN
    SELECT id, nombre, horas_uso, umbral
    FROM equipos
    WHERE horas_uso > umbral;
END //

DELIMITER ;

-- Insertar datos de ejemplo
INSERT INTO equipos (nombre, horas_uso, umbral) VALUES
('Maquina A', 250, 200),
('Maquina B', 150, 200),
('Maquina C', 300, 250);

INSERT INTO incidentes (descripcion, estado, equipo_id) VALUES
('Falla en motor', 'abierto', 1),
('Mantenimiento rutinario', 'cerrado', 2);