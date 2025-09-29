-- Script para crear la base de datos y tabla de especialidades
-- Base de datos para gestión de especialidades médicas de una clínica

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS clinica_especialidades;

-- Usar la base de datos
USE clinica_especialidades;

-- Crear tabla de especialidades
CREATE TABLE IF NOT EXISTS especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Crear tabla de usuarios simples para autenticación
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla de perfil/datos del usuario (1 a 1)
CREATE TABLE IF NOT EXISTS user_profiles (
    user_id INT PRIMARY KEY,
    telefono VARCHAR(30) DEFAULT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    notas TEXT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertar datos de ejemplo
INSERT INTO especialidades (nombre, descripcion) VALUES
('Cardiología', 'Especialidad médica que se encarga del estudio, diagnóstico y tratamiento de las enfermedades del corazón y del aparato circulatorio. Los cardiólogos tratan condiciones como arritmias, insuficiencia cardíaca, hipertensión arterial, enfermedad coronaria y defectos cardíacos congénitos.'),

('Neurología', 'Especialidad médica que se dedica al estudio del sistema nervioso central y periférico. Los neurólogos diagnostican y tratan enfermedades como epilepsia, esclerosis múltiple, enfermedad de Parkinson, Alzheimer, migrañas y accidentes cerebrovasculares.'),

('Pediatría', 'Especialidad médica que se encarga de la atención médica de bebés, niños y adolescentes hasta los 18 años. Los pediatras se especializan en el crecimiento y desarrollo infantil, vacunación, enfermedades propias de la infancia y medicina preventiva pediátrica.'),

('Ginecología', 'Especialidad médica que se dedica al cuidado del aparato reproductor femenino. Los ginecólogos realizan exámenes preventivos, tratan infecciones, trastornos hormonales, problemas de fertilidad y realizan cirugías del sistema reproductor femenino.'),

('Traumatología', 'Especialidad médica que se encarga del diagnóstico y tratamiento de lesiones del sistema músculo-esquelético, incluyendo huesos, músculos, articulaciones, ligamentos y tendones. Los traumatólogos tratan fracturas, luxaciones, lesiones deportivas y realizan cirugías ortopédicas.'),

('Dermatología', 'Especialidad médica dedicada al diagnóstico y tratamiento de enfermedades de la piel, cabello y uñas. Los dermatólogos tratan condiciones como acné, eczema, psoriasis, cáncer de piel, alopecia y realizan procedimientos estéticos dermatológicos.'),

('Oftalmología', 'Especialidad médica que se encarga del diagnóstico y tratamiento de enfermedades del ojo y anexos oculares. Los oftalmólogos tratan problemas de visión, glaucoma, cataratas, degeneración macular, retinopatía diabética y realizan cirugías oculares.'),

('Psiquiatría', 'Especialidad médica que se dedica al diagnóstico, tratamiento y prevención de trastornos mentales y emocionales. Los psiquiatras tratan depresión, ansiedad, trastorno bipolar, esquizofrenia, trastornos de la alimentación y pueden prescribir medicamentos psiquiátricos.'),

('Gastroenterología', 'Especialidad médica que se encarga del estudio y tratamiento del aparato digestivo y sus enfermedades. Los gastroenterólogos tratan problemas del esófago, estómago, intestinos, hígado, páncreas y vesícula biliar, incluyendo úlceras, enfermedad de Crohn y hepatitis.'),

('Endocrinología', 'Especialidad médica que se dedica al diagnóstico y tratamiento de trastornos hormonales y enfermedades de las glándulas endocrinas. Los endocrinólogos tratan diabetes, problemas de tiroides, trastornos suprarrenales, obesidad y problemas hormonales reproductivos.');

-- Verificar que los datos se insertaron correctamente
SELECT * FROM especialidades;

-- Usuario demo (email: demo@clinic.com, password: Demo1234!)
-- Generado con password_hash por PHP; si prefieres, inicia sesión tras registrar uno real
INSERT INTO users (nombre, email, password_hash)
VALUES ('Usuario Demo', 'demo@clinic.com', '$2y$10$G7yQW8fQe4nI0b9w1xqGRe0rYb2F0iA5m3Qx0W7b9bq4a4w6M6v9e')
ON DUPLICATE KEY UPDATE email = email;
