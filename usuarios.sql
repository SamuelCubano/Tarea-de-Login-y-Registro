-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2025 a las 19:42:14
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
-- Base de datos: `registro_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'Identificador único.',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario.',
  `email` varchar(150) NOT NULL COMMENT 'Correo electrónico (debe ser único).',
  `contrasena` varchar(255) NOT NULL COMMENT 'Contraseña cifrada.',
  `fecha_registro` datetime NOT NULL COMMENT 'Marca de tiempo del registro.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `fecha_registro`) VALUES
(1, 'Samuel Cubano', 'samule10cubano@gmail.com', '$2y$10$f/XDHJNdcKS3plH9Ul68H.SlocYmR3K521yiY9ZWsHn5xdHSBbrry', '2025-10-12 06:29:23'),
(4, 'jose gonzales', 'jose@gmail.com', '$2y$10$IyU86O7a34cX915nDCCeSu13p56X9vQHTsVTS/R1YZMkGqFm6sDf.', '2025-10-12 06:40:08'),
(5, 'yargely suarez', 'yargelysuarez@gmail.com', '$2y$10$exNm3NOr3igyjForeD95suTmiYrrVNXzZFEbNB9MyM97s3hnuf6mW', '2025-10-13 18:21:23');

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único.', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
