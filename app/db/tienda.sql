-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 09-06-2024 a las 21:49:29
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `idprenda` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `descripcion` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `talla` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(250) COLLATE utf8_unicode_ci not null,
  `precio` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Volcado de datos para la tabla `tienda`
--
INSERT INTO `tienda` (`descripcion`, `tipo`,`talla`,`color`,`precio`,`stock`) VALUES
('Mom', 'Pantalon', 'S', 'Azul', 10, 5),
('Chomba', 'Camiseta', 'M', 'Negra', 8, 6)




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
