CREATE DATABASE IF NOT EXISTS clinica
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE clinica;

CREATE TABLE IF NOT EXISTS especialidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL
) ENGINE=InnoDB;

INSERT INTO especialidades (nombre, descripcion) VALUES
('Cardiología', 'Especialidad dedicada al diagnóstico y tratamiento de las enfermedades del corazón.'),
('Dermatología', 'Trata enfermedades de la piel, cabello y uñas.'),
('Pediatría', 'Atiende a los niños desde el nacimiento hasta la adolescencia.');
