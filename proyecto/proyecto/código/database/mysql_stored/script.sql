-- Script SQL para PredictiveMaintain
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS predictive_maintain;
USE predictive_maintain;

-- Tabla equipos
CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    horas_uso INT NOT NULL DEFAULT 0,
    umbral INT NOT NULL DEFAULT 200,
    visible TINYINT(1) NOT NULL DEFAULT 1
);

-- Tabla incidentes
CREATE TABLE incidentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    estado ENUM('abierto', 'cerrado', 'en_progreso') NOT NULL DEFAULT 'abierto',
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    equipo_id INT,
    visible TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);

-- Tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','usuario') NOT NULL DEFAULT 'usuario',
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla turnos
CREATE TABLE turnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    fecha DATETIME NOT NULL,
    estado ENUM('pendiente','confirmado','cancelado') NOT NULL DEFAULT 'pendiente',
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla mensajes de contacto
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    leido TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Stored procedure para equipos que necesitan mantenimiento
DELIMITER //

CREATE PROCEDURE sp_equipos_necesitan_mantenimiento()
BEGIN
    SELECT id, nombre, horas_uso, umbral
    FROM equipos
    WHERE horas_uso > umbral
      AND visible = 1;
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

INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@predictive.com', '$2y$10$c0rdBiYkoZKLx5qEMv4Sae01tAaMYwAuavwhJmALtfsJAMcvjxrom', 'admin'),
('Usuario Demo', 'user@predictive.com', '$2y$10$GQNrX8jLW9Zy/ADpaoPbY.YtWIp7GdLCSbFw9p2M3gltGgE/rM4Be', 'usuario');

INSERT INTO turnos (usuario_id, descripcion, fecha) VALUES
(2, 'Turno de mantenimiento preventivo', '2026-05-20 10:00:00'),
(2, 'Revisión general de maquinaria', '2026-05-22 15:30:00');

INSERT INTO pagos (usuario_id, descripcion, monto, fecha) VALUES
(2, 'Pago por servicio preventivo', 120.00, '2026-05-10 14:00:00'),
(2, 'Pago adicional de inspección', 80.50, '2026-05-15 09:30:00');

INSERT INTO mensajes (usuario_id, nombre, email, mensaje) VALUES
(2, 'Usuario Demo', 'user@predictive.com', 'Necesito información sobre el mantenimiento de la Maquina A.'),
(NULL, 'Cliente Nuevo', 'cliente@empresa.com', 'Quiero contratar un servicio de revisión semanal.');