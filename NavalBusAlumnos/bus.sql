-- Crear la base de datos
drop database IF EXISTS  navalBus;
CREATE DATABASE navalBus;
USE navalBus;

-- Tabla de líneas
CREATE TABLE Lineas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) unique not null,
    origen VARCHAR(50) NOT NULL,
    destino VARCHAR(50) NOT NULL
);

-- Tabla de conductores
CREATE TABLE Conductores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreApe VARCHAR(50) NOT NULL,
    telefono VARCHAR(15),
    fechaContrato DATE NOT NULL
);

-- Tabla de billetes
CREATE TABLE Billetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conductor INT  NOT NULL,
    linea INT  NOT NULL,
    fecha DATETIME NOT NULL,
    tipo ENUM('General', 'Reducido') NOT NULL,
    precio FLOAT NOT NULL,
    FOREIGN KEY (conductor) REFERENCES Conductores(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (linea) REFERENCES Lineas(id) ON UPDATE CASCADE ON DELETE CASCADE
);
-- Tabla de servicios
CREATE TABLE Servicios (
	id INT AUTO_INCREMENT PRIMARY KEY,
    fecha datetime not null,
	linea int  NOT NULL,
    conductor int  NOT NULL,
    recaudacion float,
    finalizado boolean,
    FOREIGN KEY (conductor) REFERENCES Conductores(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (linea) REFERENCES Lineas(id) ON UPDATE CASCADE ON DELETE CASCADE
);
-- Crear tabla de precios de billetes
CREATE TABLE PreciosBilletes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('General', 'Reducido') NOT NULL,
    precio FLOAT NOT NULL,
    fechaInicio DATE NOT NULL,
    fechaFin DATE DEFAULT NULL
);

-- Insertar datos de ejemplo
INSERT INTO Lineas (nombre, origen, destino) VALUES
('Línea 1', 'Centro', 'Barrio Norte'),
('Línea 2', 'Estación', 'Zona Industrial'),
('Línea 3', 'Aeropuerto', 'Centro Histórico');

INSERT INTO Conductores (nombreApe, telefono, fechaContrato) VALUES
('Juan Pérez', '600123456', '2020-05-15'),
('María Gómez', '600654321', '2019-03-10'),
('Carlos López', '600789123', '2021-08-25');


INSERT INTO PreciosBilletes (tipo, precio, fechaInicio, fechaFin)
VALUES
('general', 1.50, '2024-01-01', '2024-12-31'),
('general', 1.60, '2025-01-01', NULL),
('reducido', 0.80, '2024-01-01', '2024-12-31'),
('reducido', 0.90, '2025-01-01', NULL);

DELIMITER $$

CREATE FUNCTION ObtenerPrecioActual(tipoBillete ENUM('General', 'Reducido'))
RETURNS FLOAT
DETERMINISTIC
BEGIN
    DECLARE precioActual FLOAT;

    SELECT precio
    INTO precioActual
    FROM PreciosBilletes
    WHERE tipo = tipoBillete
      AND CURDATE() >= fechaInicio
      AND (fechaFin IS NULL OR CURDATE() <= fechaFin)
    LIMIT 1;

    RETURN precioActual;
END$$

DELIMITER ;

