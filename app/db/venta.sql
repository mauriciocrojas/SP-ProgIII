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
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `fechaventa` DATE NOT NULL,
  `stock` int(11) NOT NULL) 
  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE venta
add talla varchar(250) not null

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`email`, `descripcion`, `tipo`, `fechaventa`, `stock`) VALUES
('adiaz@gmail.com', 'Chomba', 'Camiseta', '2024-06-27', 1),
('bcruz@gmail.com', 'Mom', 'Pantalon', '2024-06-26', 2);


--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
