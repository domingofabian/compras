-- --------------------------------------------------------
-- Tabla usuario_tarjetas - Versión corregida
-- Relaciona usuarios con sus medios de pago (formas_pago)
-- --------------------------------------------------------

-- Desactivar chequeo de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS=0;

-- Crear la tabla sin constraints inicialmente
CREATE TABLE IF NOT EXISTS `usuario_tarjetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` int(11) NOT NULL,
  `tarjeta` int(2) NOT NULL,
  `activa` tinyint(1) DEFAULT 1 COMMENT '1 = activa, 0 = inactiva',
  `fecha_agregada` timestamp DEFAULT CURRENT_TIMESTAMP,
  `notas` varchar(255) DEFAULT NULL,
  UNIQUE KEY `usuario_tarjeta` (`usuario_id`, `tarjeta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------
-- Insertar datos basados en los archivos: fabian.php, Denise.php, Pabla.php, Lucia.php
-- --------------------------------------------------------

-- Limpiar datos previos si existen
DELETE FROM `usuario_tarjetas`;

INSERT INTO `usuario_tarjetas` (`usuario_id`, `tarjeta`, `activa`, `notas`) VALUES

-- Usuario: Fabian (id=8, Compras de Fabian)
(8, 1, 1, 'Efectivo'),
(8, 3, 1, 'Adelanto Sueldo Colmaco'),
(8, 2, 1, 'Cabal Credito Fabian'),
(8, 5, 1, 'Cabal Debito Fabian'),
(8, 18, 1, 'Cuenta DNI Fabian'),
(8, 20, 1, 'Mercado Pago Fabian'),
(8, 26, 1, 'Vales Colmaco'),
(8, 27, 1, 'Visa Credito Fabian'),

-- Usuario: Denise (id=5, Compras de Denise)
(5, 1, 1, 'Efectivo'),
(5, 3, 1, 'Adelanto Sueldo Colmaco'),
(5, 15, 1, 'Cuenta DNI Denise'),
(5, 21, 1, 'Mercado Pago Denise'),
(5, 23, 1, 'Visa Debito Galicia Alicia'),
(5, 24, 1, 'Credito Sencosur Alicia'),

-- Usuario: Pabla (id=6, Compras de Pabla)
(6, 1, 1, 'Efectivo'),
(6, 3, 1, 'Adelanto Sueldo Colmaco'),
(6, 7, 1, 'Visa Debito Pabla'),
(6, 25, 1, 'Credito Sencosur Pabla'),
(6, 14, 1, 'Cabal Credito Pabla'),
(6, 19, 1, 'Mercado Pago Pabla'),
(6, 17, 1, 'Cuenta DNI Pabla'),
(6, 26, 1, 'Vales Colmaco'),

-- Usuario: Lucia (id=7, Compras de Lucy)
(7, 1, 1, 'Efectivo'),
(7, 22, 1, 'Mercado Pago Lucia'),
(7, 16, 1, 'Cuenta DNI Lucia');

-- --------------------------------------------------------
-- Crear índices para optimizar búsquedas
-- --------------------------------------------------------

CREATE INDEX idx_usuario_id ON `usuario_tarjetas`(`usuario_id`);
CREATE INDEX idx_tarjeta ON `usuario_tarjetas`(`tarjeta`);
CREATE INDEX idx_activa ON `usuario_tarjetas`(`activa`);

-- Reactivar chequeo de claves foráneas
SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- Verificación
-- --------------------------------------------------------
SELECT COUNT(*) as 'Total de registros insertados' FROM `usuario_tarjetas`;
SELECT * FROM `usuario_tarjetas` LIMIT 5;
