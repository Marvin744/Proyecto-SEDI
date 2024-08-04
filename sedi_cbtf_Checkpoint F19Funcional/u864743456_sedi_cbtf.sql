-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 09-07-2024 a las 06:10:01
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u864743456_sedi_cbtf`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_complementarias`
--

DROP TABLE IF EXISTS `actividades_complementarias`;
CREATE TABLE IF NOT EXISTS `actividades_complementarias` (
  `id_actividad_complementaria` int NOT NULL,
  `tipo_actividad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `nombre_actividad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_actividad_complementaria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `actividades_complementarias`
--

INSERT INTO `actividades_complementarias` (`id_actividad_complementaria`, `tipo_actividad`, `nombre_actividad`) VALUES
(457, 'Programa', 'Tutorias'),
(481, 'Paraescolar', 'Basquetball'),
(511, 'Paraescolar', 'Voleiball');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

DROP TABLE IF EXISTS `alertas`;
CREATE TABLE IF NOT EXISTS `alertas` (
  `id_alerta` int NOT NULL,
  `fecha_alerta` date NOT NULL,
  `tipo_alerta` varchar(25) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `situacion` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `observaciones` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `persona_reporta` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `alumno` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `condicionamiento` tinyint(1) DEFAULT NULL,
  `semestre` int NOT NULL,
  `grupo` varchar(2) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `especialidad` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `telefono_alumno` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_tutor` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `parentesco_tutor` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_tutor` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `cita` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `asistencia_padre_tutor` tinyint(1) DEFAULT NULL,
  `canalizacion` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `quien_atiende` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `tratamiento` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `sancion` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `fecha_cumplimiento` date DEFAULT NULL,
  `evidencias` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `quien_atiende_suspencion_hss` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `seguimiento` varchar(400) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_alumno` bigint NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_alerta`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `alertas`
--

INSERT INTO `alertas` (`id_alerta`, `fecha_alerta`, `tipo_alerta`, `situacion`, `observaciones`, `persona_reporta`, `alumno`, `condicionamiento`, `semestre`, `grupo`, `especialidad`, `telefono_alumno`, `nombre_tutor`, `parentesco_tutor`, `telefono_tutor`, `cita`, `asistencia_padre_tutor`, `canalizacion`, `quien_atiende`, `tratamiento`, `sancion`, `fecha_cumplimiento`, `evidencias`, `quien_atiende_suspencion_hss`, `seguimiento`, `id_alumno`, `id_especialidad`) VALUES
(1, '2024-05-01', 'Adicciónes', 'CONSUMIENDO ESTUPEFACIENTES\r\n', 'FECHA DE INGRESO 15 DE MARZO Y SALIDA 15 DE JUNIO\r\n', 'Karla Fisher', 'Rodolfo Alemán Martínez', 0, 6, 'A', 'Programación', '6741108880', 'Alfredo ALemán Avila', 'Padre', '6741108890', '8:00 A.M. /17 de abril del 2023\r\n', 1, 'UNIDAD DE ATENCIÓN SOCIOEMOCIONAL', '', '', 'TRATAMIENTO ALTERNATIVIO (ANEXO)\r\n', '2008-05-23', '', 'CHRISTIAN IRIGOYEN\r\n', 'SE DIO UN PRIMER ACERCAMIENTO CON ÉL EL DIA 29/11/23 Y SE OBSERVA AL JOVEN CONCIENTE DE LO OCURRIDO EN EL REPORTE, ASI COMO DURANTE SU ESTADIA EN EL ANEXO. SIN EMBARGO NO SE OBSERVA MEJORIA EN SU TRATAMIENTO YA QUE EL COMENTA QUE NO LE SIRVIO DE MUCHO. SE LE PEDIRA ANTIDOPING NUEVAMENTE SIN PREVIO AVISO PARA REVISAR QUE NO ESTE CONSUMIENDO SUSTANCIAS NUEVAMENTE.\r\n', 1, 12),
(2, '2024-05-02', 'Académico', 'SE CONSIDERA UN ALUMNO EN RIESGO DE ABANDONO POR EL NUMERO CONSIDERADO DE FALTAS EN TODAS SUS MATERIAS Y PRESENTAR LA MAYORIA DE ESTAS REPROBADAS\r\n', 'EL ALUMNO FUE ATENDIDO DE MANERA INDIVIDUAL PARA VALORAR SU SITUACIÓN ANTES DE SER REINTEGRADO A SUS CLASES.\r\n\r\n\r\n', 'Javier Varela Guardado', 'Hendrik Alberto Villarreal Sarmiento ', NULL, 6, 'C', 'Ofimática ', '6741009578', 'María de la Luz Sarmiento Nevarez', 'Madre', '6741108991', NULL, NULL, 'UNIDAD DE ATENCION S.', '', NULL, NULL, NULL, '', NULL, NULL, 2, 11),
(3, '2024-05-11', 'Adicción', 'Pendiente', 'No hay', 'Karla Fisher', 'Hendrik Villarreal', NULL, 6, 'C', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 12),
(4, '2024-05-11', 'Adicción', 'Un Adicto', 'Se Droga', NULL, 'Rodolfo Alemán Martínez', NULL, 6, 'A', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 12),
(78, '2024-06-28', 'Adicciónes', 'Un Adicto', 'Se Droga', NULL, 'Rodolfo Alemán Martínez', NULL, 6, 'B', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` bigint NOT NULL AUTO_INCREMENT,
  `matricula` bigint DEFAULT NULL,
  `nombre_alumno` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `semestre` int DEFAULT NULL,
  `status` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `CURP` varchar(18) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `genero` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `fecha_naci` date DEFAULT NULL,
  `lugar_nacimiento` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `ayuda_español` tinyint(1) DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `secundaria_egreso` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `promedio_secundaria` double(4,2) DEFAULT NULL,
  `sangre` varchar(12) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `beca_bancomer` tinyint(1) DEFAULT NULL,
  `nss` tinyint(1) DEFAULT NULL,
  `nss_numero` bigint NOT NULL,
  `issste` tinyint(1) NOT NULL,
  `issste_numero` bigint NOT NULL,
  `discapacidad_mo_psi` tinyint(1) DEFAULT NULL,
  `detalles_discapacidad` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `documento_validacion_discapacidad` tinyint(1) DEFAULT NULL,
  `alergia` tinyint(1) DEFAULT NULL,
  `detalles_alergias` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `requiere_medicacion` tinyint(1) DEFAULT NULL,
  `medicacion_necesaria` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `lentes_graduados` tinyint(1) DEFAULT NULL,
  `aparatos_asistencia` tinyint(1) DEFAULT NULL,
  `detalles_aparatos_asistencia` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `calle_numero` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `colonia` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `codigo_postal` int DEFAULT NULL,
  `dispositivo_internet` tinyint(1) DEFAULT NULL,
  `numero_dispositivos` int DEFAULT NULL,
  `nombre_tutor` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_tutor` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_madre` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_madre` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_padre` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_padre` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `EP_acta_nacimiento` tinyint(1) DEFAULT NULL,
  `EP_CURP` tinyint(1) DEFAULT NULL,
  `EP_comprobante_domicilio` tinyint(1) DEFAULT NULL,
  `EP_nss_issste` tinyint(1) DEFAULT NULL,
  `EP_certificado_secundaria` tinyint(1) DEFAULT NULL,
  `EP_ficha_psicopedagogica` tinyint(1) DEFAULT NULL,
  `EP_ficha_buena_conducta` tinyint(1) DEFAULT NULL,
  `EP_fotografias` tinyint(1) DEFAULT NULL,
  `EP_autenticacion_secundaria` tinyint(1) DEFAULT NULL,
  `observaciones` varchar(200) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_grupo` int NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_alumno`),
  KEY `id_grupo` (`id_grupo`,`id_especialidad`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `matricula`, `nombre_alumno`, `apellido_paterno`, `apellido_materno`, `semestre`, `status`, `CURP`, `genero`, `edad`, `fecha_naci`, `lugar_nacimiento`, `nacionalidad`, `ayuda_español`, `telefono`, `correo`, `secundaria_egreso`, `promedio_secundaria`, `sangre`, `beca_bancomer`, `nss`, `nss_numero`, `issste`, `issste_numero`, `discapacidad_mo_psi`, `detalles_discapacidad`, `documento_validacion_discapacidad`, `alergia`, `detalles_alergias`, `requiere_medicacion`, `medicacion_necesaria`, `lentes_graduados`, `aparatos_asistencia`, `detalles_aparatos_asistencia`, `calle_numero`, `colonia`, `codigo_postal`, `dispositivo_internet`, `numero_dispositivos`, `nombre_tutor`, `telefono_tutor`, `nombre_madre`, `telefono_madre`, `nombre_padre`, `telefono_padre`, `EP_acta_nacimiento`, `EP_CURP`, `EP_comprobante_domicilio`, `EP_nss_issste`, `EP_certificado_secundaria`, `EP_ficha_psicopedagogica`, `EP_ficha_buena_conducta`, `EP_fotografias`, `EP_autenticacion_secundaria`, `observaciones`, `id_grupo`, `id_especialidad`) VALUES
(12, 0, 'FAP', 'CUM', '', 1, 'INSCRITO', 'VISHI', 'HOMBRE', 10, '2014-07-08', 'SANTIAGO PAPASQUIARO', 'MEXICO', 0, '67410359865', 'mario@gmail.cum', 'EST #32', 7.80, 'A+', 1, 1, 0, 0, 0, 0, '', 0, 0, '', 0, '', 1, 0, '', 'LAGOS DE ARROYO', 'MIPITO', 54453, 0, 0, 'MARIO', '6741035986', 'MARIA', '7468953', 'MARJON', '67413689', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'LLEG\'O CON CUM', 3, 13),
(13, NULL, 'Sorino', 'Solano', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 11),
(14, NULL, 'Fap', 'Cum', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14),
(15, NULL, 'Sorino', 'Solano', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 11),
(16, NULL, 'XCFB', 'BXB', '', 1, 'INSCRITO', '', 'HOMBRE', 0, '0000-00-00', '', 'MEXICANO(A)', 0, '', '', '', 0.00, 'A+', 0, 0, 0, 0, 0, 0, '', 0, 0, '', 0, '', 0, 0, '', '', '', 0, 0, 1, '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 3, 14),
(17, 0, 'DANIEL', 'ROBLES', 'MUNTURIN', 1, 'INSCRITO', 'MARHD010702', 'MUJER', 19, '2004-07-14', 'LA BEGAS', 'MEXICANO(A)', 1, '6741172431', 'iamyuri69@outlook.cum', 'LUIS MORA', 6.20, 'AB-', 0, 1, 1201120121, 1, 101201201, 1, 'TELEKINESIS', 0, 0, '', 1, 'CLONASEPAN', 1, 0, '', 'FLOR DE DURAZNO NO. 204', 'CNOP', 34636, 0, 0, 'ARTURO VELASQUEZ', '6741002438', 'ROSA DURA', '6741108947', 'GERARDO VALLEZ', '6182662', 1, 0, 1, 0, 1, 0, 0, 1, 1, 'DEMACIADO MORENO', 3, 13),
(18, 0, 'HENDRIK ALBERTO', 'VILLARREAL', 'SARMIENTO', 1, 'INSCRITO', 'VISH010721HDGLRNA1', 'HOMBRE', 22, '2001-07-21', 'SANTIAGO PAPASQUIARO, DGO', 'MEXICANO(A)', 1, '6741009578', 'hendrik.vs2101@gmail.com', 'EST #32', 7.80, 'B+', 1, 1, 12345678901, 0, 0, 0, '', 0, 0, '', 0, '', 1, 0, 'LENTES', 'ARROYO SECO #301', 'EL PUEBLO', 34649, 1, 1, 'MARÍA DE LA LUZ SARMIENTO NEVÁREZ', '6741078730', 'MARÍA DE LA LUZ SARMIENTO NEVÁREZ', '6741078730', 'MARÍA DE LA LUZ SARMIENTO NEVÁREZ', '6741078730', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'QUIERO DORMIR', 3, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` int NOT NULL,
  `nombre_asignatura` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `horas` int NOT NULL,
  `semestre` int NOT NULL,
  `id_tipo_programa` int NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  KEY `id_tipo_programa` (`id_tipo_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre_asignatura`, `horas`, `semestre`, `id_tipo_programa`) VALUES
(4, 'Conservación de la energía y su interacción con la materia', 15, 9, 2),
(8, 'Pensamiento matemático ll', 20, 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas_especialidad`
--

DROP TABLE IF EXISTS `asignaturas_especialidad`;
CREATE TABLE IF NOT EXISTS `asignaturas_especialidad` (
  `id_especialidad` int NOT NULL,
  `id_asignatura` int NOT NULL,
  KEY `id_especialidad` (`id_especialidad`),
  KEY `id_asignatura` (`id_asignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `asignaturas_especialidad`
--

INSERT INTO `asignaturas_especialidad` (`id_especialidad`, `id_asignatura`) VALUES
(12, 4),
(14, 4),
(11, 4),
(12, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

DROP TABLE IF EXISTS `calificaciones`;
CREATE TABLE IF NOT EXISTS `calificaciones` (
  `calificacion_parcial1` double(4,2) NOT NULL,
  `calificacion_parcial2` double DEFAULT NULL,
  `calificacion_parcial3` double(2,2) DEFAULT NULL,
  `calificacion_final` double(2,2) DEFAULT NULL,
  `asistencia_parcial1` int NOT NULL,
  `asitencia_parcial2` int DEFAULT NULL,
  `asistencia_parcial3` int DEFAULT NULL,
  `asistencia_total` int DEFAULT NULL,
  `acreditacion` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_docente` int NOT NULL,
  `id_alumno` bigint NOT NULL,
  `id_asignatura` int NOT NULL,
  `id_especialidad` int NOT NULL,
  KEY `id_especialidad` (`id_especialidad`),
  KEY `id_asignatura` (`id_asignatura`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_docente` (`id_docente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

DROP TABLE IF EXISTS `clases`;
CREATE TABLE IF NOT EXISTS `clases` (
  `id_docente` int NOT NULL,
  `id_asignatura` int NOT NULL,
  `id_grupo` int NOT NULL,
  KEY `id_grupo` (`id_grupo`),
  KEY `id_docente` (`id_docente`),
  KEY `id_asignatura` (`id_asignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_horarios`
--

DROP TABLE IF EXISTS `datos_horarios`;
CREATE TABLE IF NOT EXISTS `datos_horarios` (
  `id_datos_horarios` int NOT NULL AUTO_INCREMENT,
  `profesor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `clases` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `asignatura` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `total_horas` int NOT NULL,
  PRIMARY KEY (`id_datos_horarios`)
) ENGINE=MyISAM AUTO_INCREMENT=396 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `datos_horarios`
--

INSERT INTO `datos_horarios` (`id_datos_horarios`, `profesor`, `clases`, `asignatura`, `total_horas`) VALUES
(394, 'CARRASCO RUBIO CYNTHIA LIZETH', 'II A PRO.', 'INGLES II', 1),
(395, 'CARRASCO RUBIO CYNTHIA LIZETH', 'II A PRO.', 'INGLES II', 2),
(391, 'AVIÑA REYES ANA LIZETH', 'VI A OFI.', 'T. DE  FILOSOFÍA', 1),
(393, 'CARRASCO RUBIO CYNTHIA LIZETH', 'VI A ARH.', 'FTAL.MOD5SUB2:EJECUTA PROYECTOS', 4),
(392, 'AVIÑA REYES ANA LIZETH', 'VI A OFI.', 'TUTORÍAS VI', 1),
(390, 'AVIÑA REYES ANA LIZETH', 'VI A OFI.', 'T. DE  FILOSOFÍA', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL,
  `nombre_docente` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `RFC` varchar(13) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `grupo_edad` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `tipo_plaza` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `formacion_academica` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `antiguedad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `nivel_estudios` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_docente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `nombre_docente`, `apellido_paterno`, `apellido_materno`, `RFC`, `grupo_edad`, `tipo_plaza`, `formacion_academica`, `antiguedad`, `nivel_estudios`) VALUES
(1012, 'Alfonso Arturo ', 'Villarreal', 'Sarmiento', 'AAVS1021HSR5R', '25 - 29', 'Por horas', 'Ingienería en Sistemas Computacionales', '1 - 4 ', 'Licenciatura completa'),
(1082, 'Javier ', 'Varela', 'Guardado', 'JAVG1021HG67X', '40 - 44', 'Tiempo completo', 'Ingienería en Sistemas Computacionales', '5 - 9', 'Maestría'),
(1083, 'Osvaldo', 'Almodovar', 'Varela', 'OSVA10S1BC012', '40 - 44', 'Tiempo completo', 'Licenciado en matemáticas aplicadas', '5 - 9', 'Maestría');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
CREATE TABLE IF NOT EXISTS `especialidad` (
  `id_especialidad` int NOT NULL,
  `nombre_especialidad` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_programa` int NOT NULL,
  PRIMARY KEY (`id_especialidad`),
  KEY `id_tipo_programa` (`id_tipo_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre_especialidad`, `id_tipo_programa`) VALUES
(1, 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 3),
(11, 'TÉCNICO EN OFIMÁTICA', 2),
(12, 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA', 2),
(13, 'BACHILLERATO GENERAL', 1),
(14, 'TÉCNICO FORESTAL', 2),
(15, 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 2),
(16, 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 1),
(17, 'TÉCNICO EN PROGRAMACIÓN', 1),
(18, 'TÉCNICO EN PROGRAMACIÓN', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL,
  `semestre` int NOT NULL,
  `nombre_grupo` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `modalidad` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `semestre`, `nombre_grupo`, `modalidad`) VALUES
(1, 1, 'A', 'Matutina'),
(2, 3, 'C', 'Vespertino'),
(3, 1, 'X', 'MATUTINO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

DROP TABLE IF EXISTS `modulos`;
CREATE TABLE IF NOT EXISTS `modulos` (
  `id_modulo` int NOT NULL,
  `nombre_modulo` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_modulo`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `id_especialidad`) VALUES
(1, 'MODULO PROFESIONAL I PRODUCE Y ESTABLECE PLANTA FORESTAL', 14),
(11, 'MÓDULO I: GESTIONA HARDWARE Y SOFTWARE DE LA OFIMÁTICA. ', 11),
(20, 'Modulo1.- Desarrolla software de sistemas informáticos', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submodulo`
--

DROP TABLE IF EXISTS `submodulo`;
CREATE TABLE IF NOT EXISTS `submodulo` (
  `id_submodulo` int NOT NULL,
  `nombre_submodulo` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_modulo` int NOT NULL,
  PRIMARY KEY (`id_submodulo`),
  KEY `id_modulo` (`id_modulo`),
  KEY `id_modulo_2` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `submodulo`
--

INSERT INTO `submodulo` (`id_submodulo`, `nombre_submodulo`, `id_modulo`) VALUES
(1, 'Submódulo 1 Elabora dibujos a mano alzada de acuerdo a las necesidades del cliente.', 1),
(3, 'Submódulo 3 Elabora proyecciones ortogonales e isométricas de acuerdo a las necesidades del cliente.', 1),
(11, 'Submodulo 1 Instala y configura el equipo de cómputo periféricos.', 11),
(12, 'Submodulo 2 Instala y configura sistemas operativos y aplicaciones de la ofimática.', 11),
(21, 'Submódulo 1 - Diseña software de sistemas informáticos', 20),
(22, 'Submódulo 2 - Codifica software de sistemas informáticos', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_programa`
--

DROP TABLE IF EXISTS `tipo_programa`;
CREATE TABLE IF NOT EXISTS `tipo_programa` (
  `id_tipo_programa` int NOT NULL,
  `tipo_programa` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_programa`
--

INSERT INTO `tipo_programa` (`id_tipo_programa`, `tipo_programa`) VALUES
(1, 'Nueva Escuela Mexicana'),
(2, 'Acuerdo 029'),
(3, 'SAETAM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL,
  `perfil` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `usuario` varchar(55) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `password` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `perfil`, `usuario`, `password`) VALUES
(1, 'Admin', 'admin@admin.com', 'hola1234!'),
(10, 'Servicios', 'servicios_Escolares@cbtf2.edu.mx', 'serEsc_2024!'),
(11, 'Servicios', 'departamento_Academico@cbtf2.edu.mx', 'depAca_2024!'),
(12, 'Servicios', 'secretaria_Uno@cbtf2.edu.mx', 'secUno_2024!'),
(13, 'Servicios', 'secretaria_Dos@cbtf2.edu.mx', 'secDos_2024!'),
(14, 'Servicios', 'secretaria_Tres@cbtf2.edu.mx', 'secTres_2024!');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD CONSTRAINT `asignatura_ibfk_1` FOREIGN KEY (`id_tipo_programa`) REFERENCES `tipo_programa` (`id_tipo_programa`);

--
-- Filtros para la tabla `asignaturas_especialidad`
--
ALTER TABLE `asignaturas_especialidad`
  ADD CONSTRAINT `asignaturas_especialidad_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `asignaturas_especialidad_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `calificaciones_ibfk_4` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `calificaciones_ibfk_5` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`);

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `clases_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD CONSTRAINT `especialidad_ibfk_1` FOREIGN KEY (`id_tipo_programa`) REFERENCES `tipo_programa` (`id_tipo_programa`);

--
-- Filtros para la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

--
-- Filtros para la tabla `submodulo`
--
ALTER TABLE `submodulo`
  ADD CONSTRAINT `submodulo_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
