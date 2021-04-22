-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-03-2021 a las 14:34:32
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tc_soft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

DROP TABLE IF EXISTS `cargos`;
CREATE TABLE IF NOT EXISTS `cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_cargo` varchar(100) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `del` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `descripcion_cargo`, `fecha_registro`, `del`) VALUES
(1, 'GERENTE GENERAL', '2021-03-29 14:43:46', b'0'),
(2, 'DIRECTOR COMERCIAL', '2021-03-29 14:43:46', b'0'),
(3, 'DIRECTOR OPERATIVO', '2021-03-29 14:43:46', b'0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(100) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id`, `ciudad`, `id_departamento`) VALUES
(1, 'CALI', 1),
(2, 'ANTIOQUIA', 2),
(3, 'BOGOTA', 3),
(4, 'BARRANQUILLA', 4),
(5, 'ARMENIA', 5),
(6, 'POPAYAN', 6),
(7, 'CALOTO', 6),
(8, 'PALMIRA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE IF NOT EXISTS `departamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departamento` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `departamento`) VALUES
(1, 'VALLE DEL CAUCA'),
(2, 'ANTIOQUIA'),
(3, 'CUNDINAMARCA'),
(4, 'ATLANTICO'),
(5, 'QUINDIO'),
(6, 'CAUCA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_ciudad` int(11) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `numero_contacto` varchar(11) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `del` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `cedula`, `email`, `password`, `id_cargo`, `id_ciudad`, `fecha_nacimiento`, `numero_contacto`, `fecha_registro`, `del`) VALUES
(1, 'LEIDY JHOANA', 'ASTUDILLO', '1130586374', 'gerencia@tucelular.net.co', '$2y$10$Hktiu/o4S.wPr7MfGWAiz.Jk./f.NmOP5oxKDEjRM8B.QJYcXod6i', 1, 1, '2021-03-29', '3137510432', '2021-03-29 16:39:12', b'0'),
(2, 'ANDRES MAURICIO', 'PERAFAN', '16839435', 'directorcomercial@tucelular.net.co', '$2y$10$Hktiu/o4S.wPr7MfGWAiz.Jk./f.NmOP5oxKDEjRM8B.QJYcXod6i', 2, 1, '2021-03-29', '3118764557', '2021-03-29 16:39:12', b'0'),
(3, 'JOSE ANDRES', 'ASTUDILLO', '1112468475', 'soporte@tucelular.net.co', '$2y$10$Hktiu/o4S.wPr7MfGWAiz.Jk./f.NmOP5oxKDEjRM8B.QJYcXod6i', 3, 1, '2021-03-29', '3177247772', '2021-03-29 16:39:12', b'0'),
(4, 'sdsadasdsads', 'sdsadsadsadsad', '4323432423434', 'sdsadsa@dfdsfs.com', '$2y$10$6CyRCxDHEgILsYOE.b7vVefq3HvJEHmtNDF0.d0U9yczQWwn2ITHC', 3, 5, '2021-03-30', '3242342342', '2021-03-30 18:11:19', b'0'),
(5, 'sdsadasdsads', 'sdsadsadsadsad', '4323432423434', 'sdsadsa@dfdsfs.com', '$2y$10$qg6f.sEIvN.k4MYnq1m0n.Up3E0q5daWz8obiazrvfQtPEv7RFpCa', 3, 6, '2021-03-30', '3166862920', '2021-03-30 18:18:24', b'0'),
(6, 'sdsadasdsads', 'sdsadsadsadsad', '4323432423434', 'sdsadsa@dfdsfs.com', '$2y$10$6XaGjHlDaSLjGdn5Pkm1dOBzFCRPs8CD/Rr.Iea/CKXpvwCH0F5bu', 2, 7, '2021-03-30', '3166862920', '2021-03-31 14:21:57', b'0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
