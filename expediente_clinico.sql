-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2019 a las 19:09:34
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `expediente_clinico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anexo`
--

CREATE TABLE `anexo` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `nombre_archivo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camilla`
--

CREATE TABLE `camilla` (
  `id` int(11) NOT NULL,
  `habitacion_id` int(11) DEFAULT NULL,
  `numero_camilla` int(11) NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `expediente_id` int(11) NOT NULL,
  `fecha_reservacion` datetime NOT NULL,
  `consulta_por` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`id`, `usuario_id`, `expediente_id`, `fecha_reservacion`, `consulta_por`, `creado_en`, `actualizado_en`, `fecha_fin`) VALUES
(1, 2, 1, '2019-05-08 08:00:00', 'Motivo 1', NULL, NULL, '2019-05-08 08:30:00'),
(2, 2, 2, '2019-05-08 08:30:00', 'Motivo 2', NULL, NULL, '2019-05-08 09:00:00'),
(4, 3, 1, '2019-06-04 12:30:00', 'Me duele el estomago', NULL, NULL, '2019-06-04 13:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinica`
--

CREATE TABLE `clinica` (
  `id` int(11) NOT NULL,
  `nombre_clinica` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clinica`
--

INSERT INTO `clinica` (`id`, `nombre_clinica`, `direccion`, `telefono`, `email`, `creado_en`, `actualizado_en`) VALUES
(1, 'CLINICA 1', 'San Salvador', '2257-7777', 'gggg@gmail.com', NULL, NULL),
(2, 'CLINICA 2', 'San Salvador', '1111-1111', 'go14002@ues.edu.sv', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico`
--

CREATE TABLE `diagnostico` (
  `id` int(11) NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL,
  `codigo_categoria` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `diagnostico`
--

INSERT INTO `diagnostico` (`id`, `descripcion`, `creado_en`, `actualizado_en`, `codigo_categoria`) VALUES
(1, 'Ciertas enfermedades infecciosas y parasitarias', NULL, NULL, 'A00-B99'),
(2, 'Neoplasias', NULL, NULL, 'C00-D48'),
(3, 'Enfermedades de la sangre y de los órganos hematopoyéticos y otros trastornos que afectan el mecanismo de la inmunidad', NULL, NULL, 'D50-D89'),
(4, 'Enfermedades endocrinas, nutricionales y metabólicas', NULL, NULL, 'E00-E90'),
(5, 'Trastornos mentales y del comportamiento', NULL, NULL, 'F00-F99'),
(6, 'Enfermedades del sistema nervioso', NULL, NULL, 'G00-G99'),
(7, 'Enfermedades del ojo y sus anexos', NULL, NULL, 'H00-H59'),
(8, 'Enfermedades del oído y de la apófisis mastoides', NULL, NULL, 'H60-H95'),
(9, 'Enfermedades del sistema circulatorio', NULL, NULL, 'I00-I99'),
(10, 'Enfermedades del sistema respiratorio', NULL, NULL, 'J00-J99'),
(11, 'Enfermedades del aparato digestivo', NULL, NULL, 'K00-K93'),
(12, 'Enfermedades de la piel y el tejido subcutáneo', NULL, NULL, 'L00-L99'),
(13, 'Enfermedades del sistema osteomuscular y del tejido conectivo', NULL, NULL, 'M00-M99'),
(14, 'Enfermedades del aparato genitourinario', NULL, NULL, 'N00-N99'),
(15, 'Embarazo, parto y puerperio', NULL, NULL, 'O00-O99'),
(16, 'Ciertas afecciones originadas en el periodo perinatal', NULL, NULL, 'P00-P96'),
(17, 'Malformaciones congénitas, deformidades y anomalías cromosómicas', NULL, NULL, 'Q00-Q99'),
(18, 'Síntomas, signos y hallazgos anormales clínicos y de laboratorio, no clasificados en otra parte', NULL, NULL, 'R00-R99'),
(19, 'Traumatismos, envenenamientos y algunas otras consecuencias de causa externa', NULL, NULL, 'S00-T98'),
(20, 'Causas externas de morbilidad y de mortalidad', NULL, NULL, 'V01-Y98'),
(21, 'Factores que influyen en el estado de salud y contacto con los servicios de salud', NULL, NULL, 'Z00-Z99'),
(22, 'Códigos para situaciones especiales', NULL, NULL, 'U00-U99');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id` int(11) NOT NULL,
  `nombre_especialidad` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id`, `nombre_especialidad`, `creado_en`, `actualizado_en`) VALUES
(1, 'Ortopedia', NULL, NULL),
(2, 'Neurocirugia', NULL, NULL),
(3, 'Psiquiatria', NULL, NULL),
(4, 'Pediatria', NULL, NULL),
(5, 'Cardiologia', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_heces_macroscopico`
--

CREATE TABLE `examen_heces_macroscopico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `aspecto` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `consistencia` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `olor` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `presencia_de_sangre` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `restos_alimenticios` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `presencia_moco` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_heces_microscopico`
--

CREATE TABLE `examen_heces_microscopico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `hematies` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `leucocito` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `flora_bacteriana` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `levadura` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_heces_quimico`
--

CREATE TABLE `examen_heces_quimico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `ph` double NOT NULL,
  `azucares_reductores` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sangre_oculta` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_hematologico`
--

CREATE TABLE `examen_hematologico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `tipo_serie` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidad` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_referencia` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_orina_cristaluria`
--

CREATE TABLE `examen_orina_cristaluria` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `uratos_amorfos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `acido_urico` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `oxalatos_calcicos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fosfatos_amorfos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fosfatos_calcicos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fosfatos_amonicos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `riesgo_litogenico` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_orina_macroscopico`
--

CREATE TABLE `examen_orina_macroscopico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `color` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `aspecto` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sedimento` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_orina_microscopico`
--

CREATE TABLE `examen_orina_microscopico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `uretral` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `urotelio` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `renal` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `leucocitos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `piocitos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `eritrocitos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bacteria` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parasitos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `funguria` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `filamento_de_mucina` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `proteina_uromocoide` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cilindros` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_orina_quimico`
--

CREATE TABLE `examen_orina_quimico` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `densidad` double NOT NULL,
  `ph` double NOT NULL,
  `glucosa` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `proteinas` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `hemoglobina` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuerpo_cetonico` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pigmento_biliar` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `urobilinogeno` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `nitritos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examen_orina_quimico`
--

INSERT INTO `examen_orina_quimico` (`id`, `examen_solicitado_id`, `densidad`, `ph`, `glucosa`, `proteinas`, `hemoglobina`, `cuerpo_cetonico`, `pigmento_biliar`, `urobilinogeno`, `nitritos`, `creado_en`, `actualizado_en`) VALUES
(5, 2, 0, 0, '*', '*', '*', '*', '*', '*', '*', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_quimica_sanguinea`
--

CREATE TABLE `examen_quimica_sanguinea` (
  `id` int(11) NOT NULL,
  `examen_solicitado_id` int(11) NOT NULL,
  `parametro` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultado` double NOT NULL,
  `comentario` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rango` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_solicitado`
--

CREATE TABLE `examen_solicitado` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `tipo_examen` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examen_solicitado`
--

INSERT INTO `examen_solicitado` (`id`, `cita_id`, `tipo_examen`, `categoria`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Orina', 'Cristaluria', NULL, NULL),
(2, 1, 'Orina', 'Quimico', NULL, NULL),
(3, 1, 'Orina', 'Macroscopico', NULL, NULL),
(4, 1, 'Orina', 'Microscopico', NULL, NULL),
(5, 2, 'Orina', 'Quimico', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expediente`
--

CREATE TABLE `expediente` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL,
  `numero_expediente` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `direccion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_casada` longtext COLLATE utf8mb4_unicode_ci,
  `estado_civil` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expediente`
--

INSERT INTO `expediente` (`id`, `usuario_id`, `genero_id`, `numero_expediente`, `fecha_nacimiento`, `direccion`, `telefono`, `apellido_casada`, `estado_civil`, `creado_en`, `actualizado_en`, `habilitado`) VALUES
(1, 4, 1, 'U0001-2019', '1996-05-01 00:00:00', 'San Salvador', '2257-7777', '-', 'Soltero', NULL, NULL, 1),
(2, 5, 1, 'U0002-2019', '1994-09-03 00:00:00', 'San Salvador', '2257-7777', '-', 'Soltero', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiar`
--

CREATE TABLE `familiar` (
  `id` int(11) NOT NULL,
  `nombres` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `telefono` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiares_expediente`
--

CREATE TABLE `familiares_expediente` (
  `id` int(11) NOT NULL,
  `familiar_id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL,
  `responsable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `descripcion`, `creado_en`, `actualizado_en`) VALUES
(1, 'MASCULINO', NULL, NULL),
(2, 'FEMENINO', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion`
--

CREATE TABLE `habitacion` (
  `id` int(11) NOT NULL,
  `sala_id` int(11) NOT NULL,
  `tipo_habitacion_id` int(11) DEFAULT NULL,
  `numero_habitacion` int(11) NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_familiar`
--

CREATE TABLE `historial_familiar` (
  `id` int(11) NOT NULL,
  `familiar_id` int(11) NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_propio`
--

CREATE TABLE `historial_propio` (
  `id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_medica`
--

CREATE TABLE `historia_medica` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `diagnostico_id` int(11) NOT NULL,
  `consulta_por` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `signos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sintomas` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL,
  `codigo_especifico` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresado`
--

CREATE TABLE `ingresado` (
  `id` int(11) NOT NULL,
  `camilla_id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190601040117', '2019-06-01 04:02:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL,
  `permiso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_tabla` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `permiso`, `descripcion`, `nombre_tabla`, `creado_en`, `actualizado_en`) VALUES
(1, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'GENERO', NULL, NULL),
(2, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'GENERO', NULL, NULL),
(3, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'GENERO', NULL, NULL),
(4, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'GENERO', NULL, NULL),
(5, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'GENERO', NULL, NULL),
(6, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CLINICA', NULL, NULL),
(7, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CLINICA', NULL, NULL),
(8, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CLINICA', NULL, NULL),
(9, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CLINICA', NULL, NULL),
(10, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CLINICA', NULL, NULL),
(11, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ESPECIALIDAD', NULL, NULL),
(12, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ESPECIALIDAD', NULL, NULL),
(13, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ESPECIALIDAD', NULL, NULL),
(14, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ESPECIALIDAD', NULL, NULL),
(15, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ESPECIALIDAD', NULL, NULL),
(16, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'EXPEDIENTE', NULL, NULL),
(17, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'EXPEDIENTE', NULL, NULL),
(18, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'EXPEDIENTE', NULL, NULL),
(19, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'EXPEDIENTE', NULL, NULL),
(20, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'EXPEDIENTE', NULL, NULL),
(21, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'PERMISOS_POR_ROL', NULL, NULL),
(22, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'PERMISOS_POR_ROL', NULL, NULL),
(23, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'PERMISOS_POR_ROL', NULL, NULL),
(24, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'PERMISOS_POR_ROL', NULL, NULL),
(25, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'PERMISOS_POR_ROL', NULL, NULL),
(26, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ROL', NULL, NULL),
(27, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ROL', NULL, NULL),
(28, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ROL', NULL, NULL),
(29, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ROL', NULL, NULL),
(30, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ROL', NULL, NULL),
(31, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'USER', NULL, NULL),
(32, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'USER', NULL, NULL),
(33, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'USER', NULL, NULL),
(34, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'USER', NULL, NULL),
(35, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'USER', NULL, NULL),
(36, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CITA', NULL, NULL),
(37, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CITA', NULL, NULL),
(38, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CITA', NULL, NULL),
(39, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CITA', NULL, NULL),
(40, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CITA', NULL, NULL),
(41, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'SALA', NULL, NULL),
(42, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'SALA', NULL, NULL),
(43, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'SALA', NULL, NULL),
(44, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'SALA', NULL, NULL),
(45, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'SALA', NULL, NULL),
(46, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'TIPO_HABITACION', NULL, NULL),
(47, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'TIPO_HABITACION', NULL, NULL),
(48, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'TIPO_HABITACION', NULL, NULL),
(49, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'TIPO_HABITACION', NULL, NULL),
(50, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'TIPO_HABITACION', NULL, NULL),
(51, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'HABITACION', NULL, NULL),
(52, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'HABITACION', NULL, NULL),
(53, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'HABITACION', NULL, NULL),
(54, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'HABITACION', NULL, NULL),
(55, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'HABITACION', NULL, NULL),
(56, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CAMILLA', NULL, NULL),
(57, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CAMILLA', NULL, NULL),
(58, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CAMILLA', NULL, NULL),
(59, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CAMILLA', NULL, NULL),
(60, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CAMILLA', NULL, NULL),
(61, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'HISTORIAL_PROPIO', NULL, NULL),
(62, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'HISTORIAL_PROPIO', NULL, NULL),
(63, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'HISTORIAL_PROPIO', NULL, NULL),
(64, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'HISTORIAL_PROPIO', NULL, NULL),
(65, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'HISTORIAL_PROPIO', NULL, NULL),
(66, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'FAMILIAR', NULL, NULL),
(67, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'FAMILIAR', NULL, NULL),
(68, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'FAMILIAR', NULL, NULL),
(69, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'FAMILIAR', NULL, NULL),
(70, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'FAMILIAR', NULL, NULL),
(71, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'HISTORIAL_FAMILIAR', NULL, NULL),
(72, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'HISTORIAL_FAMILIAR', NULL, NULL),
(73, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'HISTORIAL_FAMILIAR', NULL, NULL),
(74, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'HISTORIAL_FAMILIAR', NULL, NULL),
(75, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'HISTORIAL_FAMILIAR', NULL, NULL),
(76, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'SIGNO_VITAL', NULL, NULL),
(77, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'SIGNO_VITAL', NULL, NULL),
(78, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'SIGNO_VITAL', NULL, NULL),
(79, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'SIGNO_VITAL', NULL, NULL),
(80, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'SIGNO_VITAL', NULL, NULL),
(81, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'INGRESADO', NULL, NULL),
(82, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'INGRESADO', NULL, NULL),
(83, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'INGRESADO', NULL, NULL),
(84, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'INGRESADO', NULL, NULL),
(85, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'INGRESADO', NULL, NULL),
(86, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'HISTORIA_MEDICA', NULL, NULL),
(87, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'HISTORIA_MEDICA', NULL, NULL),
(88, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'HISTORIA_MEDICA', NULL, NULL),
(89, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'HISTORIA_MEDICA', NULL, NULL),
(90, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'HISTORIA_MEDICA', NULL, NULL),
(91, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'PLAN_TRATAMIENTO', NULL, NULL),
(92, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'PLAN_TRATAMIENTO', NULL, NULL),
(93, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'PLAN_TRATAMIENTO', NULL, NULL),
(94, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'PLAN_TRATAMIENTO', NULL, NULL),
(95, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'PLAN_TRATAMIENTO', NULL, NULL),
(96, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'EXAMEN_SOLICITADO', NULL, NULL),
(97, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'EXAMEN_SOLICITADO', NULL, NULL),
(98, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'EXAMEN_SOLICITADO', NULL, NULL),
(99, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'EXAMEN_SOLICITADO', NULL, NULL),
(100, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'EXAMEN_SOLICITADO', NULL, NULL),
(101, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'EXAMENES', NULL, NULL),
(102, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'EXAMENES', NULL, NULL),
(103, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'EXAMENES', NULL, NULL),
(104, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'EXAMENES', NULL, NULL),
(105, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'EXAMENES', NULL, NULL),
(106, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ANEXO', NULL, NULL),
(107, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ANEXO', NULL, NULL),
(108, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ANEXO', NULL, NULL),
(109, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ANEXO', NULL, NULL),
(110, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ANEXO', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_rol`
--

CREATE TABLE `permiso_rol` (
  `permiso_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permiso_rol`
--

INSERT INTO `permiso_rol` (`permiso_id`, `rol_id`) VALUES
(1, 1),
(1, 7),
(2, 1),
(2, 7),
(3, 1),
(3, 7),
(4, 1),
(4, 7),
(5, 1),
(5, 7),
(6, 1),
(6, 7),
(7, 1),
(7, 7),
(8, 1),
(8, 7),
(9, 1),
(9, 7),
(10, 1),
(10, 7),
(11, 1),
(11, 7),
(12, 1),
(12, 7),
(13, 1),
(13, 7),
(14, 1),
(14, 7),
(15, 1),
(15, 7),
(16, 1),
(16, 2),
(16, 3),
(16, 5),
(16, 6),
(16, 7),
(16, 8),
(17, 1),
(17, 3),
(17, 7),
(18, 1),
(18, 2),
(18, 3),
(18, 5),
(18, 6),
(18, 7),
(18, 8),
(19, 1),
(19, 3),
(19, 7),
(20, 1),
(20, 3),
(20, 7),
(21, 1),
(21, 7),
(22, 1),
(22, 7),
(23, 1),
(23, 7),
(24, 1),
(24, 7),
(25, 1),
(25, 7),
(26, 1),
(26, 7),
(27, 1),
(27, 7),
(28, 1),
(28, 7),
(29, 1),
(29, 7),
(30, 1),
(30, 7),
(31, 1),
(31, 7),
(32, 1),
(32, 7),
(33, 1),
(33, 7),
(34, 1),
(34, 7),
(35, 1),
(35, 7),
(36, 1),
(36, 2),
(36, 4),
(36, 5),
(36, 6),
(36, 7),
(36, 8),
(37, 1),
(37, 4),
(37, 7),
(37, 8),
(38, 1),
(38, 2),
(38, 4),
(38, 5),
(38, 6),
(38, 7),
(38, 8),
(39, 1),
(39, 4),
(39, 7),
(39, 8),
(40, 1),
(40, 4),
(40, 7),
(40, 8),
(41, 1),
(41, 7),
(42, 1),
(42, 7),
(43, 1),
(43, 7),
(44, 1),
(44, 7),
(45, 1),
(45, 7),
(46, 1),
(46, 7),
(47, 1),
(47, 7),
(48, 1),
(48, 7),
(49, 1),
(49, 7),
(50, 1),
(50, 7),
(51, 1),
(51, 7),
(52, 1),
(52, 7),
(53, 1),
(53, 7),
(54, 1),
(54, 7),
(55, 1),
(55, 7),
(56, 1),
(56, 7),
(57, 1),
(57, 7),
(58, 1),
(58, 7),
(59, 1),
(59, 7),
(60, 1),
(60, 7),
(61, 1),
(61, 3),
(61, 7),
(62, 1),
(62, 3),
(62, 7),
(63, 1),
(63, 3),
(63, 7),
(64, 1),
(64, 3),
(64, 7),
(65, 1),
(65, 3),
(65, 7),
(66, 1),
(66, 3),
(66, 7),
(67, 1),
(67, 3),
(67, 7),
(68, 1),
(68, 3),
(68, 7),
(69, 1),
(69, 3),
(69, 7),
(70, 1),
(70, 3),
(70, 7),
(71, 1),
(71, 3),
(71, 7),
(72, 1),
(72, 3),
(72, 7),
(73, 1),
(73, 3),
(73, 7),
(74, 1),
(74, 3),
(74, 7),
(75, 1),
(75, 3),
(75, 7),
(76, 1),
(76, 5),
(76, 7),
(77, 1),
(77, 5),
(77, 7),
(78, 1),
(78, 5),
(78, 7),
(79, 1),
(79, 5),
(79, 7),
(80, 1),
(80, 5),
(80, 7),
(81, 1),
(81, 7),
(82, 1),
(82, 7),
(83, 1),
(83, 7),
(84, 1),
(84, 7),
(85, 1),
(85, 7),
(86, 1),
(86, 2),
(86, 7),
(87, 1),
(87, 2),
(87, 7),
(88, 1),
(88, 2),
(88, 7),
(89, 1),
(89, 2),
(89, 7),
(90, 1),
(90, 2),
(90, 7),
(91, 1),
(91, 2),
(91, 7),
(92, 1),
(92, 2),
(92, 7),
(93, 1),
(93, 2),
(93, 7),
(94, 1),
(94, 2),
(94, 7),
(95, 1),
(95, 2),
(95, 7),
(96, 1),
(96, 6),
(96, 7),
(97, 1),
(97, 6),
(97, 7),
(98, 1),
(98, 6),
(98, 7),
(99, 1),
(99, 6),
(99, 7),
(100, 1),
(100, 6),
(100, 7),
(101, 1),
(101, 6),
(101, 7),
(102, 1),
(102, 6),
(102, 7),
(103, 1),
(103, 6),
(103, 7),
(104, 1),
(104, 6),
(104, 7),
(105, 1),
(105, 6),
(105, 7),
(106, 1),
(106, 7),
(107, 1),
(107, 7),
(108, 1),
(108, 7),
(109, 1),
(109, 7),
(110, 1),
(110, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_tratamiento`
--

CREATE TABLE `plan_tratamiento` (
  `id` int(11) NOT NULL,
  `historia_medica_id` int(11) NOT NULL,
  `dosis` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `medicamento` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `frecuencia` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_medicamento` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre_rol`, `descripcion`, `creado_en`, `actualizado_en`) VALUES
(1, 'ROLE_SA', 'Rol de Super Administrador', NULL, NULL),
(2, 'ROLE_DOCTOR', 'Rol de Doctor', NULL, NULL),
(3, 'ROLE_ARCHIVISTA', 'Rol de Archivista', NULL, NULL),
(4, 'ROLE_PACIENTE', 'Rol de paciente', NULL, NULL),
(5, 'ROLE_ENFERMERA', 'Rol de Enfermería', NULL, NULL),
(6, 'ROLE_LABORATORISTA', 'Rol de Laboratorista', NULL, NULL),
(7, 'ROLE_ADMIN_CLINICA', 'Rol de Administrador de Clinica', NULL, NULL),
(8, 'ROLE_SECRETARIA', 'Rol de Secretaria', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `id` int(11) NOT NULL,
  `clinica_id` int(11) NOT NULL,
  `nombre_sala` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `signo_vital`
--

CREATE TABLE `signo_vital` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `peso` double NOT NULL,
  `temperatura` double NOT NULL,
  `estatura` double NOT NULL,
  `presion_arterial` double NOT NULL,
  `ritmo_cardiaco` double NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_habitacion`
--

CREATE TABLE `tipo_habitacion` (
  `id` int(11) NOT NULL,
  `tipo_habitacion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `clinica_id` int(11) DEFAULT NULL,
  `usuario_especialidades_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombres` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `emergencia` tinyint(1) DEFAULT NULL,
  `planta` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `rol_id`, `clinica_id`, `usuario_especialidades_id`, `email`, `password`, `nombres`, `apellidos`, `is_active`, `emergencia`, `planta`) VALUES
(1, 1, NULL, NULL, 'superusuario@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 1', 'Usuario 1', 1, 0, 0),
(2, 2, 1, 1, 'doctor1@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 2', 'Usuario 2', 1, 0, 0),
(3, 2, 1, 3, 'doctor2@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 3', 'Usuario 3', 1, 1, 0),
(4, 4, 1, NULL, 'paciente1@usuario.com', '$2y$10$R5krUXRnjMEhHI.IWtsy/eZNiHUjNX0GXeqBYCehtuHIfDvQUd322', 'Usuario 4', 'Usuario 4', 1, 0, 0),
(5, 4, 2, NULL, 'paciente2@usuario.com', '$2y$12$lQlW4LjkrLpCs2MlX77zxO80xF/.uNnPAnRtEXpVEEq7TMO1emtsS', 'Usuario 5', 'Usuario 5', 1, 0, 0),
(9, 5, 1, NULL, 'enfermera@usuario.com', '$2y$10$R7GZb.neiuDaF9fF8zMW4uy.5nk0wb6FCx6aAGIrqjezGRc.GsvPe', 'Enfermera 1', 'Lo que sea', 1, 0, 0),
(10, 3, 1, NULL, 'archivista@usuario.com', '$2y$10$4DziEky9z7wanf0Fn3lPZOSOpx6.VdkX1tWHDWXYeKQLKdjt0z5UO', 'Archivista 1', 'Lo que sea', 1, 0, 0),
(11, 6, 1, NULL, 'laboratorista@usuario.com', '$2y$10$f2FPJhq2OJM4eUsZuizXR.SH9iCamvKrXlQ/DbHtvH6C45oyS/6jy', 'Laboratorista 1', 'Lo que sea', 1, 0, 0),
(12, 7, 1, NULL, 'administrador1@usuario.com', '$2y$10$/huqZHDSCTsDHYpluNwIVuHrzCdUd.oTuwVtz0BMW3Xg/U7J/Ved.', 'administrador clinica 1', 'Lo que sea', 1, 0, 0),
(13, 8, 1, NULL, 'secretaria1@usuario.com', '$2y$10$Kko4noJPiYVxMPS4UdBJpOUJAqPk2j70vUcl87AzmW7svHuA0exru', 'Secretaria 1', 'Lo que sea', 1, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anexo`
--
ALTER TABLE `anexo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CD7EAF2C43CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `camilla`
--
ALTER TABLE `camilla`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_712619ADB009290D` (`habitacion_id`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3E379A62DB38439E` (`usuario_id`),
  ADD KEY `IDX_3E379A624BF37E4E` (`expediente_id`);

--
-- Indices de la tabla `clinica`
--
ALTER TABLE `clinica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examen_heces_macroscopico`
--
ALTER TABLE `examen_heces_macroscopico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B368264143CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_heces_microscopico`
--
ALTER TABLE `examen_heces_microscopico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9ABC70443CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_heces_quimico`
--
ALTER TABLE `examen_heces_quimico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_49A805AC43CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_hematologico`
--
ALTER TABLE `examen_hematologico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AE3CB97343CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_orina_cristaluria`
--
ALTER TABLE `examen_orina_cristaluria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7AC4955A43CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_orina_macroscopico`
--
ALTER TABLE `examen_orina_macroscopico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C81E2D0043CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_orina_microscopico`
--
ALTER TABLE `examen_orina_microscopico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_72DDCC4543CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_orina_quimico`
--
ALTER TABLE `examen_orina_quimico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D422CE0343CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_quimica_sanguinea`
--
ALTER TABLE `examen_quimica_sanguinea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8F766BE43CA3347` (`examen_solicitado_id`);

--
-- Indices de la tabla `examen_solicitado`
--
ALTER TABLE `examen_solicitado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_779218FB1E011DDF` (`cita_id`);

--
-- Indices de la tabla `expediente`
--
ALTER TABLE `expediente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D59CA413DB38439E` (`usuario_id`),
  ADD KEY `IDX_D59CA413BCE7B795` (`genero_id`);

--
-- Indices de la tabla `familiar`
--
ALTER TABLE `familiar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `familiares_expediente`
--
ALTER TABLE `familiares_expediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B24733AA10C20D71` (`familiar_id`),
  ADD KEY `IDX_B24733AA4BF37E4E` (`expediente_id`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F45995BAC51CDF3F` (`sala_id`),
  ADD KEY `IDX_F45995BAB0BA7A53` (`tipo_habitacion_id`);

--
-- Indices de la tabla `historial_familiar`
--
ALTER TABLE `historial_familiar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_523A50F910C20D71` (`familiar_id`);

--
-- Indices de la tabla `historial_propio`
--
ALTER TABLE `historial_propio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_60EB986D4BF37E4E` (`expediente_id`);

--
-- Indices de la tabla `historia_medica`
--
ALTER TABLE `historia_medica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_328E741C1E011DDF` (`cita_id`),
  ADD UNIQUE KEY `UNIQ_328E741C7A94BA1A` (`diagnostico_id`);

--
-- Indices de la tabla `ingresado`
--
ALTER TABLE `ingresado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6682CB4BFEEC2797` (`camilla_id`),
  ADD KEY `IDX_6682CB4B4BF37E4E` (`expediente_id`),
  ADD KEY `IDX_6682CB4BDB38439E` (`usuario_id`);

--
-- Indices de la tabla `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permiso_rol`
--
ALTER TABLE `permiso_rol`
  ADD PRIMARY KEY (`permiso_id`,`rol_id`),
  ADD KEY `IDX_DD501D066CEFAD37` (`permiso_id`),
  ADD KEY `IDX_DD501D064BAB96C` (`rol_id`);

--
-- Indices de la tabla `plan_tratamiento`
--
ALTER TABLE `plan_tratamiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_951D7817322E8DC3` (`historia_medica_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E226041C9CD3F6D6` (`clinica_id`);

--
-- Indices de la tabla `signo_vital`
--
ALTER TABLE `signo_vital`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_75668911E011DDF` (`cita_id`);

--
-- Indices de la tabla `tipo_habitacion`
--
ALTER TABLE `tipo_habitacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D6494BAB96C` (`rol_id`),
  ADD KEY `IDX_8D93D6499CD3F6D6` (`clinica_id`),
  ADD KEY `IDX_8D93D649AF3A97F` (`usuario_especialidades_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anexo`
--
ALTER TABLE `anexo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `camilla`
--
ALTER TABLE `camilla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clinica`
--
ALTER TABLE `clinica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `examen_heces_macroscopico`
--
ALTER TABLE `examen_heces_macroscopico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_heces_microscopico`
--
ALTER TABLE `examen_heces_microscopico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_heces_quimico`
--
ALTER TABLE `examen_heces_quimico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_hematologico`
--
ALTER TABLE `examen_hematologico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_orina_cristaluria`
--
ALTER TABLE `examen_orina_cristaluria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_orina_macroscopico`
--
ALTER TABLE `examen_orina_macroscopico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_orina_microscopico`
--
ALTER TABLE `examen_orina_microscopico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_orina_quimico`
--
ALTER TABLE `examen_orina_quimico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `examen_quimica_sanguinea`
--
ALTER TABLE `examen_quimica_sanguinea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_solicitado`
--
ALTER TABLE `examen_solicitado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `expediente`
--
ALTER TABLE `expediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `familiar`
--
ALTER TABLE `familiar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familiares_expediente`
--
ALTER TABLE `familiares_expediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_familiar`
--
ALTER TABLE `historial_familiar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_propio`
--
ALTER TABLE `historial_propio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historia_medica`
--
ALTER TABLE `historia_medica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresado`
--
ALTER TABLE `ingresado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de la tabla `plan_tratamiento`
--
ALTER TABLE `plan_tratamiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sala`
--
ALTER TABLE `sala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `signo_vital`
--
ALTER TABLE `signo_vital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_habitacion`
--
ALTER TABLE `tipo_habitacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anexo`
--
ALTER TABLE `anexo`
  ADD CONSTRAINT `FK_CD7EAF2C43CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `camilla`
--
ALTER TABLE `camilla`
  ADD CONSTRAINT `FK_712619ADB009290D` FOREIGN KEY (`habitacion_id`) REFERENCES `habitacion` (`id`);

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `FK_3E379A624BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`),
  ADD CONSTRAINT `FK_3E379A62DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `examen_heces_macroscopico`
--
ALTER TABLE `examen_heces_macroscopico`
  ADD CONSTRAINT `FK_B368264143CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_heces_microscopico`
--
ALTER TABLE `examen_heces_microscopico`
  ADD CONSTRAINT `FK_9ABC70443CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_heces_quimico`
--
ALTER TABLE `examen_heces_quimico`
  ADD CONSTRAINT `FK_49A805AC43CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_hematologico`
--
ALTER TABLE `examen_hematologico`
  ADD CONSTRAINT `FK_AE3CB97343CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_orina_cristaluria`
--
ALTER TABLE `examen_orina_cristaluria`
  ADD CONSTRAINT `FK_7AC4955A43CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_orina_macroscopico`
--
ALTER TABLE `examen_orina_macroscopico`
  ADD CONSTRAINT `FK_C81E2D0043CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_orina_microscopico`
--
ALTER TABLE `examen_orina_microscopico`
  ADD CONSTRAINT `FK_72DDCC4543CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_orina_quimico`
--
ALTER TABLE `examen_orina_quimico`
  ADD CONSTRAINT `FK_D422CE0343CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_quimica_sanguinea`
--
ALTER TABLE `examen_quimica_sanguinea`
  ADD CONSTRAINT `FK_D8F766BE43CA3347` FOREIGN KEY (`examen_solicitado_id`) REFERENCES `examen_solicitado` (`id`);

--
-- Filtros para la tabla `examen_solicitado`
--
ALTER TABLE `examen_solicitado`
  ADD CONSTRAINT `FK_779218FB1E011DDF` FOREIGN KEY (`cita_id`) REFERENCES `cita` (`id`);

--
-- Filtros para la tabla `expediente`
--
ALTER TABLE `expediente`
  ADD CONSTRAINT `FK_D59CA413BCE7B795` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  ADD CONSTRAINT `FK_D59CA413DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `familiares_expediente`
--
ALTER TABLE `familiares_expediente`
  ADD CONSTRAINT `FK_B24733AA10C20D71` FOREIGN KEY (`familiar_id`) REFERENCES `familiar` (`id`),
  ADD CONSTRAINT `FK_B24733AA4BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`);

--
-- Filtros para la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD CONSTRAINT `FK_F45995BAB0BA7A53` FOREIGN KEY (`tipo_habitacion_id`) REFERENCES `tipo_habitacion` (`id`),
  ADD CONSTRAINT `FK_F45995BAC51CDF3F` FOREIGN KEY (`sala_id`) REFERENCES `sala` (`id`);

--
-- Filtros para la tabla `historial_familiar`
--
ALTER TABLE `historial_familiar`
  ADD CONSTRAINT `FK_523A50F910C20D71` FOREIGN KEY (`familiar_id`) REFERENCES `familiar` (`id`);

--
-- Filtros para la tabla `historial_propio`
--
ALTER TABLE `historial_propio`
  ADD CONSTRAINT `FK_60EB986D4BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`);

--
-- Filtros para la tabla `historia_medica`
--
ALTER TABLE `historia_medica`
  ADD CONSTRAINT `FK_328E741C1E011DDF` FOREIGN KEY (`cita_id`) REFERENCES `cita` (`id`),
  ADD CONSTRAINT `FK_328E741C7A94BA1A` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`);

--
-- Filtros para la tabla `ingresado`
--
ALTER TABLE `ingresado`
  ADD CONSTRAINT `FK_6682CB4B4BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`),
  ADD CONSTRAINT `FK_6682CB4BDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6682CB4BFEEC2797` FOREIGN KEY (`camilla_id`) REFERENCES `camilla` (`id`);

--
-- Filtros para la tabla `permiso_rol`
--
ALTER TABLE `permiso_rol`
  ADD CONSTRAINT `FK_DD501D064BAB96C` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DD501D066CEFAD37` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plan_tratamiento`
--
ALTER TABLE `plan_tratamiento`
  ADD CONSTRAINT `FK_951D7817322E8DC3` FOREIGN KEY (`historia_medica_id`) REFERENCES `historia_medica` (`id`);

--
-- Filtros para la tabla `sala`
--
ALTER TABLE `sala`
  ADD CONSTRAINT `FK_E226041C9CD3F6D6` FOREIGN KEY (`clinica_id`) REFERENCES `clinica` (`id`);

--
-- Filtros para la tabla `signo_vital`
--
ALTER TABLE `signo_vital`
  ADD CONSTRAINT `FK_75668911E011DDF` FOREIGN KEY (`cita_id`) REFERENCES `cita` (`id`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6494BAB96C` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`),
  ADD CONSTRAINT `FK_8D93D6499CD3F6D6` FOREIGN KEY (`clinica_id`) REFERENCES `clinica` (`id`),
  ADD CONSTRAINT `FK_8D93D649AF3A97F` FOREIGN KEY (`usuario_especialidades_id`) REFERENCES `especialidad` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
