
-- Seleccionamos la base de datos
USE registro_db;

-- Creamos la tabla de usuarios si no existe
CREATE TABLE IF NOT EXISTS usuarios (

    -- Identificador único de cada usuario
    id INT AUTO_INCREMENT PRIMARY KEY,

    -- Nombre obligatorio
    nombre VARCHAR(100) NOT NULL,

    -- Apellidos obligatorios
    apellidos VARCHAR(150) NOT NULL,

    -- Correo obligatorio y sin duplicados
    email VARCHAR(255) NOT NULL UNIQUE,

    -- Contraseña almacenada mediante hash
    password VARCHAR(255) NOT NULL,

    -- Fecha y hora del registro
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
