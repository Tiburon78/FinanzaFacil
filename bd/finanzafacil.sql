-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2025 a las 16:50:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `finanzafacil`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `usuario_id`, `nombre`, `tipo`, `saldo`) VALUES
(5, 2, 'Banco Galicia', 'Banco', 671000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cuenta_id` int(11) DEFAULT NULL,
  `nota` text DEFAULT NULL,
  `tipo` enum('ingreso','egreso') DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `forma_pago` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `usuario_id`, `cuenta_id`, `nota`, `tipo`, `monto`, `categoria`, `forma_pago`, `fecha`, `descripcion`) VALUES
(4, 2, NULL, NULL, 'ingreso', 86000.00, 'TAO SPORTS', 'efectivo', '2025-06-02', 'Trabajos Varios de Sublimacion'),
(5, 2, NULL, NULL, 'egreso', 5000.00, 'Cigarrillos', 'efectivo', '2025-06-03', ''),
(7, 2, NULL, NULL, 'egreso', 206000.00, 'CREDICUOTAS', 'transferencia', '2025-06-02', 'Pago Cuota 8/24'),
(8, 2, NULL, NULL, 'egreso', 20000.00, 'Credito Anses 1', 'transferencia', '2025-06-01', 'Credito Anses Numero 1'),
(9, 2, NULL, NULL, 'egreso', 20000.00, 'Credito Anses 2', 'transferencia', '2025-06-01', 'Credito Anses 2'),
(10, 2, NULL, NULL, 'egreso', 98000.00, 'Comida', 'tarjeta', '2025-06-02', ''),
(12, 2, 2, '', 'ingreso', 671000.00, 'Sueldo VEA', 'transferencia', '2025-06-04', NULL),
(13, 2, 4, '', 'egreso', 65000.00, 'Comida', 'tarjeta', '2025-06-04', NULL),
(14, 2, 2, '', 'egreso', 15000.00, 'Cigarrillos', 'efectivo', '2025-06-04', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`id`, `usuario_id`, `categoria`, `monto`, `mes`, `anio`) VALUES
(2, 2, 'Comida', 150000.00, 2025, NULL),
(4, 2, 'Cigarrillos', 60000.00, 2025, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `prueba_activa` tinyint(1) DEFAULT 1,
  `fecha_vencimiento` date DEFAULT NULL,
  `plan` enum('gratuito','standard','premium') NOT NULL DEFAULT 'gratuito',
  `pais` varchar(100) DEFAULT 'Argentina',
  `moneda` varchar(10) DEFAULT 'ARS',
  `idioma` varchar(10) DEFAULT 'es',
  `simbolo_moneda` varchar(5) DEFAULT '$'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_registro`, `prueba_activa`, `fecha_vencimiento`, `plan`, `pais`, `moneda`, `idioma`, `simbolo_moneda`) VALUES
(1, 'Admin', 'admin@demo.com', '$2y$10$SIniLMx5lg3F95emO4UK1ehasHLMpURjDzE.PGtQ29qWByVPnUH/K', '2025-06-03', 1, '2025-06-10', 'gratuito', 'Argentina', 'ARS', 'es', '$'),
(2, 'Dario Guillermo', 'demo@gmail.com', '$2y$10$Q9aIIPf8nvZNnXVXsJrCh.ZzmJtVylDwDAJErXRLt2jSGWq6gGUUu', '2025-06-03', 1, '2025-06-11', 'gratuito', 'Estados Unidos', 'USD', 'es', '$'),
(3, 'Usuario Standard', 'standard@demo.com', '$2b$12$G2WOgnWdkD.QAzK40iCF0.trNqQg8Is0GReRXV8KUP6LGxqgMwZWa', '2025-06-04', 0, NULL, 'standard', 'Argentina', 'ARS', 'es', '$'),
(4, 'Usuario Premium', 'premium@demo.com', '$2b$12$u2FzID2n6vEilezL/Mk2zu/xm4Bav31grxo139f84OktPa9yhxR6q', '2025-06-04', 0, NULL, 'premium', 'Estados Unidos', 'USD', 'es', '$');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
