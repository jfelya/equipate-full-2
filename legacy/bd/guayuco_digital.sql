-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-01-2020 a las 03:15:37
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `guayuco_digital`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `id_detalle` int(255) NOT NULL,
  `id_factura` int(255) NOT NULL,
  `id_producto` int(255) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `precio` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`id_detalle`, `id_factura`, `id_producto`, `cantidad`, `precio`) VALUES
(50, 306439, 12, 1, 24000),
(52, 93032, 10, 1, 4300),
(53, 449274, 14, 1, 21800),
(54, 449274, 12, 1, 24000),
(55, 449274, 10, 1, 4300),
(56, 885476, 10, 1, 4300),
(57, 885476, 13, 1, 10200),
(58, 349073, 10, 1, 4300),
(59, 146360, 10, 3, 4300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(255) NOT NULL,
  `id_cliente` int(255) NOT NULL,
  `fecha` varchar(30) NOT NULL,
  `preciototal` int(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_cliente`, `fecha`, `preciototal`) VALUES
(60066, 13, '27-01-2020', 15000),
(93032, 14, '27-01-2020', 4300),
(146360, 14, '27-01-2020', 12900),
(306439, 12, '27-01-2020', 24000),
(349073, 14, '27-01-2020', 4300),
(449274, 14, '27-01-2020', 50100),
(593566, 12, '27-01-2020', 15000),
(885476, 14, '27-01-2020', 14500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(255) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `stock` int(30) NOT NULL,
  `precio` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `stock`, `precio`) VALUES
(10, 'Lavadora', 61, 4300),
(11, 'Televisor OLED 8K', 82, 11500),
(12, 'Modem de Internet', 68, 24000),
(13, 'Cafetera 30xx', 76, 10200),
(14, 'Microondas', 67, 21800);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_cliente` int(255) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `contrasena` varchar(30) NOT NULL,
  `nivel` varchar(30) NOT NULL,
  `pregunta_secreta` varchar(30) NOT NULL,
  `respuesta_secreta` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_cliente`, `nombre`, `apellido`, `usuario`, `correo`, `contrasena`, `nivel`, `pregunta_secreta`, `respuesta_secreta`) VALUES
(1, 'Felix', 'AÃ±ez', 'jfelya', 'fely@gmail.com', 'jfelya', 'admin', 'Comida Favorita', 'cotufas'),
(6, 'Nico', 'Gonzalez', 'nicoandroid', 'nicolas3lmipapi@gmail.com', 'android', 'admin', 'Color Favorito', 'azul'),
(12, 'Joselin', 'Castillo', 'josecas', 'josecas@gmail.com', 'hector', 'cliente', 'Color Favorito', 'rosado'),
(13, 'Daniel', 'Tornillo', 'danilo', 'danilo@gmail.com', 'cafe', 'cliente', 'Banda Favorita', 'red velvet'),
(14, 'Leslie', 'Betancor', 'leslie', 'leslie@gmail.com', 'asdf', 'cliente', 'Banda Favorita', 'bts');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`id_detalle`) USING BTREE,
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `id_detalle` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=885477;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_cliente` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD CONSTRAINT `detalle_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
