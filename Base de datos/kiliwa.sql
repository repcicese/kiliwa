-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2015 a las 23:31:36
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `kiliwa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_archivos`
--

CREATE TABLE IF NOT EXISTS `arecurso_archivos` (
  `id` int(255) NOT NULL,
  `nombre` text,
  `tipo` varchar(128) DEFAULT NULL,
  `tamano` varchar(64) DEFAULT NULL,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_articulos`
--

CREATE TABLE IF NOT EXISTS `arecurso_articulos` (
  `id` int(255) NOT NULL,
  `id_archivo` int(255) DEFAULT NULL,
  `isbn_diez` varchar(10) DEFAULT NULL,
  `isbn_trece` varchar(13) DEFAULT NULL,
  `issn` varchar(13) DEFAULT NULL,
  `titulo` text,
  `autores` text,
  `anno` text,
  `revista` text,
  `lugar` text,
  `volumen` varchar(5) DEFAULT NULL,
  `numero` varchar(5) DEFAULT NULL,
  `paginas` varchar(255) DEFAULT NULL,
  `url` text,
  `id_usuario` int(255) DEFAULT NULL,
  `resumen` longtext,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_noticias`
--

CREATE TABLE IF NOT EXISTS `arecurso_noticias` (
  `id` int(255) NOT NULL,
  `titulo` text,
  `contenido` longtext,
  `id_usuario` int(255) DEFAULT NULL,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_software`
--

CREATE TABLE IF NOT EXISTS `arecurso_software` (
  `id` int(255) NOT NULL,
  `id_archivo` int(255) DEFAULT NULL,
  `titulo` text,
  `url` text,
  `id_usuario` int(255) DEFAULT NULL,
  `resumen` longtext,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_tutoriales`
--

CREATE TABLE IF NOT EXISTS `arecurso_tutoriales` (
  `id` int(255) NOT NULL,
  `id_archivo` int(255) DEFAULT NULL,
  `titulo` text,
  `url` text,
  `id_usuario` int(255) DEFAULT NULL,
  `resumen` longtext,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_usuarios`
--

CREATE TABLE IF NOT EXISTS `arecurso_usuarios` (
  `id` int(255) NOT NULL,
  `id_perfil` int(10) DEFAULT NULL,
  `nombres` text,
  `apellidos` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sexo` tinytext,
  `website` varchar(255) DEFAULT NULL,
  `notificar` tinyint(1) DEFAULT '0',
  `activo` tinyint(1) DEFAULT '0',
  `password` varchar(128) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 COMMENT='sexo\n\n''m'' 	= 	masculino\n''f''	=	femenino';

--
-- Volcado de datos para la tabla `arecurso_usuarios`
--

INSERT INTO `arecurso_usuarios` (`id`, `id_perfil`, `nombres`, `apellidos`, `email`, `sexo`, `website`, `notificar`, `activo`, `password`, `fecha_registro`, `ultima_actualizacion_fecha`) VALUES
(1, 1, 'admin', 'admin', 'admin', 'm', 'http://localhost/kiliwa/', 0, 1, 'crrC/bL/nxi/Y', '2015-07-07', '2015-07-07 21:30:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arecurso_vinculos`
--

CREATE TABLE IF NOT EXISTS `arecurso_vinculos` (
  `id` int(255) NOT NULL,
  `url` text,
  `descripcion` text,
  `id_usuario` int(255) DEFAULT NULL,
  `ultima_actualizacion_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kcontrol_diccionario_etiquetas`
--

CREATE TABLE IF NOT EXISTS `kcontrol_diccionario_etiquetas` (
  `id` int(255) NOT NULL,
  `id_recurso` int(255) DEFAULT NULL,
  `tipo_recurso` int(10) DEFAULT NULL,
  `id_etiqueta` int(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=354 DEFAULT CHARSET=latin1 COMMENT='tipo_recurso\n\n0.-Null\n1.-Artículos\n2.-Software\n3.-Tutoriale';

--
-- Volcado de datos para la tabla `kcontrol_diccionario_etiquetas`
--

INSERT INTO `kcontrol_diccionario_etiquetas` (`id`, `id_recurso`, `tipo_recurso`, `id_etiqueta`) VALUES
(1, 1, 4, 1),
(2, 2, 4, 1),
(3, 3, 4, 2),
(5, 5, 4, 3),
(6, 6, 4, 4),
(7, 7, 4, 3),
(8, 8, 4, 3),
(9, 9, 4, 3),
(10, 10, 4, 5),
(11, 11, 4, 2),
(12, 12, 4, 3),
(13, 13, 4, 5),
(14, 14, 4, 5),
(15, 15, 4, 4),
(16, 16, 4, 3),
(17, 17, 4, 3),
(18, 18, 4, 3),
(19, 19, 4, 3),
(20, 20, 4, 3),
(21, 21, 4, 3),
(22, 22, 4, 3),
(23, 23, 4, 3),
(24, 24, 4, 2),
(25, 25, 4, 2),
(26, 26, 4, 4),
(27, 27, 4, 4),
(28, 28, 4, 4),
(29, 29, 4, 4),
(30, 30, 4, 4),
(31, 31, 4, 4),
(32, 32, 4, 3),
(33, 34, 4, 3),
(34, 35, 4, 3),
(35, 36, 4, 3),
(36, 39, 4, 5),
(37, 40, 4, 3),
(38, 41, 4, 3),
(39, 42, 4, 3),
(40, 43, 4, 3),
(41, 44, 4, 3),
(43, 2, 3, 6),
(44, 13, 3, 7),
(45, 1, 2, 6),
(46, 2, 2, 6),
(47, 3, 2, 8),
(50, 49, 2, 11),
(51, 6, 2, 8),
(52, 7, 2, 12),
(53, 8, 2, 13),
(54, 9, 2, 13),
(55, 10, 2, 13),
(56, 11, 2, 13),
(57, 12, 2, 13),
(58, 13, 2, 14),
(59, 14, 2, 8),
(60, 15, 2, 8),
(61, 16, 2, 8),
(62, 17, 2, 8),
(63, 18, 2, 8),
(64, 19, 2, 8),
(65, 20, 2, 8),
(66, 21, 2, 8),
(67, 22, 2, 8),
(68, 23, 2, 8),
(69, 24, 2, 14),
(70, 25, 2, 15),
(71, 26, 2, 15),
(72, 27, 2, 8),
(73, 28, 2, 8),
(74, 29, 2, 8),
(75, 30, 2, 8),
(76, 31, 2, 16),
(77, 32, 2, 14),
(78, 33, 2, 16),
(79, 34, 2, 17),
(80, 35, 2, 17),
(81, 36, 2, 13),
(82, 37, 2, 13),
(83, 38, 2, 13),
(84, 39, 2, 8),
(85, 40, 2, 14),
(86, 41, 2, 18),
(87, 42, 2, 19),
(88, 43, 2, 14),
(89, 44, 2, 20),
(92, 47, 2, 22),
(93, 48, 2, 23),
(94, 49, 2, 24),
(95, 50, 2, 24),
(96, 51, 2, 25),
(98, 97, 2, 27),
(99, 53, 2, 27),
(100, 99, 2, 28),
(101, 55, 2, 22),
(102, 56, 2, 22),
(103, 57, 2, 8),
(106, 60, 2, 29),
(107, 61, 2, 29),
(108, 62, 2, 29),
(109, 63, 2, 29),
(110, 64, 2, 29),
(111, 65, 2, 29),
(112, 66, 2, 29),
(113, 67, 2, 29),
(114, 68, 2, 29),
(115, 69, 2, 29),
(116, 70, 2, 29),
(118, 72, 2, 27),
(119, 73, 2, 8),
(120, 74, 2, 30),
(121, 75, 2, 8),
(122, 76, 2, 8),
(125, 79, 2, 31),
(126, 80, 2, 31),
(127, 2, 1, 32),
(128, 3, 1, 33),
(130, 6, 1, 35),
(131, 7, 1, 36),
(132, 8, 1, 36),
(133, 132, 1, 36),
(134, 9, 1, 37),
(135, 10, 1, 38),
(136, 11, 1, 39),
(137, 136, 1, 39),
(138, 12, 1, 39),
(139, 138, 1, 32),
(140, 13, 1, 32),
(141, 14, 1, 40),
(142, 15, 1, 35),
(143, 16, 1, 38),
(144, 17, 1, 32),
(145, 18, 1, 36),
(146, 19, 1, 38),
(147, 20, 1, 38),
(148, 21, 1, 41),
(149, 22, 1, 42),
(150, 23, 1, 42),
(151, 24, 1, 42),
(152, 151, 1, 34),
(153, 25, 1, 34),
(154, 26, 1, 43),
(155, 27, 1, 34),
(156, 28, 1, 44),
(157, 29, 1, 32),
(158, 30, 1, 32),
(159, 31, 1, 40),
(330, 160, 1, 66),
(161, 160, 1, 33),
(162, 32, 1, 36),
(163, 162, 1, 42),
(164, 33, 1, 42),
(165, 34, 1, 42),
(166, 35, 1, 42),
(167, 36, 1, 32),
(168, 37, 1, 44),
(169, 38, 1, 42),
(170, 39, 1, 44),
(171, 40, 1, 44),
(172, 41, 1, 39),
(173, 42, 1, 36),
(174, 43, 1, 34),
(175, 44, 1, 34),
(176, 45, 1, 37),
(177, 46, 1, 37),
(178, 47, 1, 36),
(179, 48, 1, 36),
(180, 179, 1, 33),
(181, 180, 1, 34),
(182, 49, 1, 34),
(183, 50, 1, 39),
(184, 183, 1, 43),
(185, 51, 1, 34),
(186, 52, 1, 43),
(187, 53, 1, 36),
(188, 187, 1, 36),
(189, 54, 1, 39),
(190, 189, 1, 36),
(191, 55, 1, 36),
(192, 56, 1, 36),
(193, 57, 1, 42),
(194, 193, 1, 32),
(195, 58, 1, 40),
(196, 195, 1, 40),
(197, 59, 1, 42),
(198, 197, 1, 42),
(199, 60, 1, 36),
(200, 61, 1, 45),
(201, 200, 1, 45),
(202, 62, 1, 34),
(203, 63, 1, 34),
(204, 64, 1, 34),
(205, 65, 1, 34),
(206, 66, 1, 34),
(207, 67, 1, 34),
(208, 68, 1, 34),
(209, 69, 1, 34),
(210, 70, 1, 40),
(211, 71, 1, 34),
(212, 211, 1, 34),
(213, 212, 1, 34),
(214, 72, 1, 34),
(215, 73, 1, 34),
(216, 74, 1, 36),
(217, 75, 1, 36),
(218, 76, 1, 33),
(219, 77, 1, 37),
(220, 78, 1, 34),
(221, 220, 1, 42),
(222, 79, 1, 40),
(223, 80, 1, 40),
(224, 81, 1, 36),
(225, 82, 1, 45),
(226, 83, 1, 46),
(227, 84, 1, 36),
(228, 227, 1, 32),
(229, 85, 1, 42),
(230, 86, 1, 47),
(231, 87, 1, 47),
(232, 231, 1, 36),
(233, 88, 1, 48),
(234, 89, 1, 41),
(235, 90, 1, 41),
(236, 91, 1, 41),
(237, 92, 1, 34),
(238, 93, 1, 49),
(239, 94, 1, 33),
(240, 95, 1, 33),
(241, 240, 1, 32),
(242, 241, 1, 32),
(243, 242, 1, 32),
(244, 96, 1, 43),
(245, 97, 1, 38),
(246, 98, 1, 41),
(247, 99, 1, 37),
(248, 100, 1, 37),
(249, 101, 1, 40),
(250, 102, 1, 39),
(251, 103, 1, 35),
(252, 104, 1, 35),
(253, 252, 1, 36),
(254, 105, 1, 36),
(255, 106, 1, 42),
(256, 107, 1, 33),
(257, 108, 1, 32),
(258, 109, 1, 40),
(259, 110, 1, 33),
(260, 111, 1, 38),
(261, 112, 1, 38),
(262, 113, 1, 32),
(263, 114, 1, 43),
(264, 115, 1, 36),
(265, 117, 1, 44),
(266, 265, 1, 45),
(267, 266, 1, 45),
(268, 267, 1, 35),
(269, 118, 1, 34),
(270, 269, 1, 34),
(271, 119, 1, 40),
(272, 271, 1, 37),
(273, 120, 1, 40),
(274, 273, 1, 32),
(275, 121, 1, 37),
(276, 122, 1, 50),
(277, 123, 1, 37),
(278, 277, 1, 38),
(279, 124, 1, 51),
(280, 279, 1, 50),
(281, 125, 1, 51),
(282, 126, 1, 38),
(283, 127, 1, 52),
(284, 128, 1, 53),
(285, 129, 1, 53),
(286, 130, 1, 54),
(287, 131, 1, 34),
(288, 132, 1, 34),
(289, 133, 1, 41),
(290, 289, 1, 55),
(291, 134, 1, 51),
(292, 135, 1, 47),
(293, 136, 1, 51),
(294, 137, 1, 34),
(295, 138, 1, 56),
(296, 139, 1, 54),
(297, 140, 1, 53),
(298, 141, 1, 56),
(299, 142, 1, 32),
(301, 144, 1, 42),
(302, 145, 1, 57),
(303, 146, 1, 32),
(304, 303, 1, 35),
(305, 147, 1, 35),
(306, 148, 1, 58),
(307, 149, 1, 58),
(308, 307, 1, 36),
(309, 150, 1, 59),
(310, 151, 1, 59),
(311, 152, 1, 56),
(312, 153, 1, 35),
(313, 155, 1, 54),
(314, 156, 1, 60),
(315, 157, 1, 60),
(329, 160, 1, 53),
(328, 160, 1, 54),
(327, 5, 1, 53),
(326, 5, 1, 56),
(338, 163, 1, 54),
(337, 163, 1, 62),
(336, 163, 1, 33),
(335, 162, 1, 62),
(331, 160, 1, 67),
(332, 161, 1, 66),
(333, 161, 1, 67),
(334, 161, 1, 51),
(339, 163, 1, 68),
(340, 164, 1, 62),
(341, 164, 1, 69),
(342, 165, 1, 62),
(343, 165, 1, 54),
(344, 165, 1, 69),
(348, 167, 1, 62),
(349, 167, 1, 67),
(350, 167, 1, 54),
(351, 167, 1, 66),
(352, 81, 2, 33),
(353, 81, 2, 61);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kcontrol_diccionario_etiquetas_grupos_trabajo`
--

CREATE TABLE IF NOT EXISTS `kcontrol_diccionario_etiquetas_grupos_trabajo` (
  `id` int(255) NOT NULL,
  `id_grupo` int(255) NOT NULL DEFAULT '0',
  `id_etiqueta` int(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `kcontrol_diccionario_etiquetas_grupos_trabajo`
--

INSERT INTO `kcontrol_diccionario_etiquetas_grupos_trabajo` (`id`, `id_grupo`, `id_etiqueta`) VALUES
(1, 1, 36),
(2, 1, 33),
(3, 1, 61),
(4, 1, 62),
(5, 1, 63),
(6, 1, 48),
(7, 1, 42);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kcontrol_diccionario_etiquetas_usuarios`
--

CREATE TABLE IF NOT EXISTS `kcontrol_diccionario_etiquetas_usuarios` (
  `id` int(255) NOT NULL,
  `id_usuario` int(255) NOT NULL DEFAULT '0',
  `id_etiqueta` int(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `kcontrol_diccionario_etiquetas_usuarios`
--

INSERT INTO `kcontrol_diccionario_etiquetas_usuarios` (`id`, `id_usuario`, `id_etiqueta`) VALUES
(1, 45, 63),
(2, 45, 42),
(3, 45, 33),
(4, 45, 36),
(5, 45, 61),
(6, 48, 36),
(7, 48, 62),
(8, 48, 63),
(9, 48, 54),
(10, 50, 70),
(11, 51, 36),
(12, 51, 42),
(13, 51, 62),
(14, 51, 13),
(15, 51, 48),
(16, 51, 33),
(26, 52, 75),
(25, 52, 73),
(24, 52, 72),
(23, 52, 71),
(22, 52, 66),
(27, 53, 10),
(28, 53, 36),
(29, 53, 61),
(30, 53, 63),
(31, 53, 11),
(32, 53, 1),
(33, 53, 20),
(34, 54, 62),
(35, 54, 61),
(36, 54, 63),
(37, 54, 28),
(38, 55, 63),
(39, 1, 48),
(40, 1, 33),
(41, 55, 48),
(42, 55, 62);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kcontrol_etiquetas`
--

CREATE TABLE IF NOT EXISTS `kcontrol_etiquetas` (
  `id` int(255) NOT NULL,
  `nombre` text
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `kcontrol_etiquetas`
--

INSERT INTO `kcontrol_etiquetas` (`id`, `nombre`) VALUES
(1, 'librerias-digitales'),
(2, 'aplicaciones'),
(3, 'investigadores'),
(4, 'conferencias'),
(5, 'paginas-personales'),
(6, 'latex'),
(7, 'animaciones'),
(8, 'edgardo-aviles'),
(9, 'iconos'),
(10, 'articulos'),
(11, 'presentaciones'),
(12, 'correo'),
(13, 'tinyos'),
(14, 'ivan-estrella'),
(15, 'clipart'),
(16, 'soa'),
(17, 'redes'),
(18, 'software-de-kits'),
(19, 'surgetmote'),
(20, 'posters'),
(21, 'carlos-caloca'),
(22, 'noe-garcía'),
(23, 'agricultura-y-wsn'),
(24, 'instalacion-en-un-invernadero'),
(25, 'utilitarios'),
(26, '2007-09-04'),
(27, 'java'),
(28, 'javascript'),
(29, 'lafmi-2007'),
(30, 'cesar-olea'),
(31, 'curso-de-agricultura-protegida'),
(32, 'service-oriented-archs-'),
(33, 'hardware'),
(34, 'security-in-wsn'),
(35, 'data-retrieval'),
(36, 'active-sensor-networks'),
(37, 'operating-systems'),
(38, 'challenges-&amp;-applications'),
(39, 'macroprograming-&amp;-local-behavior'),
(40, 'surveys'),
(41, 'data-aggregation-and-dissemination'),
(42, 'middleware'),
(43, 'algorithms'),
(44, 'biologically-inspired'),
(45, 'simulators'),
(46, 'qos'),
(47, 'network-services-&amp;-tools'),
(48, 'routing'),
(49, 'criptography'),
(50, 'zigbee/802-15-4'),
(51, 'location'),
(52, 'rfid'),
(53, 'monitoreo-ambiental'),
(54, 'ubiquitous-computing'),
(55, 'mwsn'),
(56, 'agricultura-y-ti'),
(57, 'research-work'),
(58, 'tam'),
(59, 'vehicular-networks'),
(60, 'vanets'),
(61, 'sofware'),
(62, 'ubiquitous'),
(63, 'computing'),
(64, 'casa123'),
(65, 'acm'),
(66, 'urban-computing'),
(67, 'smart-cities'),
(68, 'prototyping'),
(69, 'internet-of-things'),
(70, 'ubicom\r\nurban-location'),
(71, 'ubicomp'),
(72, 'hci'),
(73, 'education'),
(74, 'ai-'),
(75, 'ai');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zsistema_grupos_trabajo`
--

CREATE TABLE IF NOT EXISTS `zsistema_grupos_trabajo` (
  `id` int(255) NOT NULL,
  `nombre` text
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `zsistema_grupos_trabajo`
--

INSERT INTO `zsistema_grupos_trabajo` (`id`, `nombre`) VALUES
(1, 'Ubicom');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zsistema_perfil`
--

CREATE TABLE IF NOT EXISTS `zsistema_perfil` (
  `id` int(10) NOT NULL,
  `nombre` text,
  `crear_usuarios` int(2) DEFAULT '0',
  `crear_recursos` int(2) DEFAULT '0',
  `actualizar_recursos` int(2) DEFAULT '0',
  `borrar_recursos` int(2) DEFAULT '0',
  `configuracion_perfil` int(2) DEFAULT '0',
  `configuracion_grupos` int(2) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `zsistema_perfil`
--

INSERT INTO `zsistema_perfil` (`id`, `nombre`, `crear_usuarios`, `crear_recursos`, `actualizar_recursos`, `borrar_recursos`, `configuracion_perfil`, `configuracion_grupos`) VALUES
(1, 'Administrador', 1, 1, 1, 1, 1, 1),
(2, 'Practicante', 0, 0, 0, 0, 0, 0),
(3, 'Alumno', 0, 1, 1, 1, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arecurso_archivos`
--
ALTER TABLE `arecurso_archivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `arecurso_articulos`
--
ALTER TABLE `arecurso_articulos`
  ADD PRIMARY KEY (`id`), ADD KEY `id_archivo_idxfk` (`id_archivo`), ADD KEY `id_usuario_idxfk` (`id_usuario`);

--
-- Indices de la tabla `arecurso_noticias`
--
ALTER TABLE `arecurso_noticias`
  ADD PRIMARY KEY (`id`), ADD KEY `id_usuario_idxfk_1` (`id_usuario`);

--
-- Indices de la tabla `arecurso_software`
--
ALTER TABLE `arecurso_software`
  ADD PRIMARY KEY (`id`), ADD KEY `id_archivo_idxfk_2` (`id_archivo`), ADD KEY `id_usuario_idxfk_3` (`id_usuario`);

--
-- Indices de la tabla `arecurso_tutoriales`
--
ALTER TABLE `arecurso_tutoriales`
  ADD PRIMARY KEY (`id`), ADD KEY `id_archivo_idxfk_1` (`id_archivo`), ADD KEY `id_usuario_idxfk_2` (`id_usuario`);

--
-- Indices de la tabla `arecurso_usuarios`
--
ALTER TABLE `arecurso_usuarios`
  ADD PRIMARY KEY (`id`), ADD KEY `id_perfil_idxfk` (`id_perfil`);

--
-- Indices de la tabla `arecurso_vinculos`
--
ALTER TABLE `arecurso_vinculos`
  ADD PRIMARY KEY (`id`), ADD KEY `id_usuario_idxfk_4` (`id_usuario`);

--
-- Indices de la tabla `kcontrol_diccionario_etiquetas`
--
ALTER TABLE `kcontrol_diccionario_etiquetas`
  ADD PRIMARY KEY (`id`), ADD KEY `id_etiqueta_idxfk` (`id_etiqueta`);

--
-- Indices de la tabla `kcontrol_diccionario_etiquetas_grupos_trabajo`
--
ALTER TABLE `kcontrol_diccionario_etiquetas_grupos_trabajo`
  ADD PRIMARY KEY (`id`), ADD KEY `id_grupo_idxfk` (`id_grupo`), ADD KEY `id_etiqueta_idxfk_2` (`id_etiqueta`);

--
-- Indices de la tabla `kcontrol_diccionario_etiquetas_usuarios`
--
ALTER TABLE `kcontrol_diccionario_etiquetas_usuarios`
  ADD PRIMARY KEY (`id`), ADD KEY `id_usuario_idxfk_5` (`id_usuario`), ADD KEY `id_etiqueta_idxfk_1` (`id_etiqueta`);

--
-- Indices de la tabla `kcontrol_etiquetas`
--
ALTER TABLE `kcontrol_etiquetas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `zsistema_grupos_trabajo`
--
ALTER TABLE `zsistema_grupos_trabajo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `zsistema_perfil`
--
ALTER TABLE `zsistema_perfil`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `arecurso_archivos`
--
ALTER TABLE `arecurso_archivos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `arecurso_articulos`
--
ALTER TABLE `arecurso_articulos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `arecurso_noticias`
--
ALTER TABLE `arecurso_noticias`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `arecurso_software`
--
ALTER TABLE `arecurso_software`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `arecurso_tutoriales`
--
ALTER TABLE `arecurso_tutoriales`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `arecurso_usuarios`
--
ALTER TABLE `arecurso_usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT de la tabla `arecurso_vinculos`
--
ALTER TABLE `arecurso_vinculos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kcontrol_diccionario_etiquetas`
--
ALTER TABLE `kcontrol_diccionario_etiquetas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=354;
--
-- AUTO_INCREMENT de la tabla `kcontrol_diccionario_etiquetas_grupos_trabajo`
--
ALTER TABLE `kcontrol_diccionario_etiquetas_grupos_trabajo`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `kcontrol_diccionario_etiquetas_usuarios`
--
ALTER TABLE `kcontrol_diccionario_etiquetas_usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT de la tabla `kcontrol_etiquetas`
--
ALTER TABLE `kcontrol_etiquetas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT de la tabla `zsistema_grupos_trabajo`
--
ALTER TABLE `zsistema_grupos_trabajo`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `zsistema_perfil`
--
ALTER TABLE `zsistema_perfil`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
