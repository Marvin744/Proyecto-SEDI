-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 02-07-2024 a las 16:15:42
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sedi_cbtf`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_complementarias`
--

DROP TABLE IF EXISTS `actividades_complementarias`;
CREATE TABLE IF NOT EXISTS `actividades_complementarias` (
  `id_actividad_complementaria` int(4) NOT NULL,
  `tipo_actividad` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `nombre_actividad` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
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
  `id_alerta` int(20) NOT NULL,
  `fecha_alerta` date NOT NULL,
  `tipo_alerta` varchar(25) COLLATE utf32_spanish_ci NOT NULL,
  `situacion` varchar(150) COLLATE utf32_spanish_ci NOT NULL,
  `observaciones` varchar(150) COLLATE utf32_spanish_ci NOT NULL,
  `persona_reporta` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `alumno` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `condicionamiento` tinyint(1) DEFAULT NULL,
  `semestre` int(1) NOT NULL,
  `grupo` varchar(2) COLLATE utf32_spanish_ci NOT NULL,
  `especialidad` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `telefono_alumno` varchar(15) COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_tutor` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `parentesco_tutor` varchar(20) COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_tutor` varchar(15) COLLATE utf32_spanish_ci DEFAULT NULL,
  `cita` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `asistencia_padre_tutor` tinyint(1) DEFAULT NULL,
  `canalizacion` varchar(50) COLLATE utf32_spanish_ci DEFAULT NULL,
  `quien_atiende` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `tratamiento` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `sancion` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `fecha_cumplimiento` date DEFAULT NULL,
  `evidencias` varchar(150) COLLATE utf32_spanish_ci DEFAULT NULL,
  `quien_atiende_suspencion_hss` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `seguimiento` varchar(400) COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  PRIMARY KEY (`id_alerta`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `alertas`
--

INSERT INTO `alertas` (`id_alerta`, `fecha_alerta`, `tipo_alerta`, `situacion`, `observaciones`, `persona_reporta`, `alumno`, `condicionamiento`, `semestre`, `grupo`, `especialidad`, `telefono_alumno`, `nombre_tutor`, `parentesco_tutor`, `telefono_tutor`, `cita`, `asistencia_padre_tutor`, `canalizacion`, `quien_atiende`, `tratamiento`, `sancion`, `fecha_cumplimiento`, `evidencias`, `quien_atiende_suspencion_hss`, `seguimiento`, `id_alumno`, `id_especialidad`) VALUES
(1, '2024-05-01', 'Adicción', 'CONSUMIENDO ESTUPEFACIENTES\r\n', 'FECHA DE INGRESO 15 DE MARZO Y SALIDA 15 DE JUNIO\r\n', 'Karla Fisher', 'Rodolfo Alemán Martínez', 0, 6, 'A', 'Programación', '6741108880', 'Alfredo Alemán Avila', 'Padre', '6741108890', '8:00 A.M. /17 de abril del 2023\r\n', 1, 'UNIDAD DE ATENCIÓN SOCIOEMOCIONAL', '', '', 'TRATAMIENTO ALTERNATIVIO (ANEXO)\r\n', '2008-05-23', '', 'CHRISTIAN IRIGOYEN\r\n', 'SE DIO UN PRIMER ACERCAMIENTO CON ÉL EL DIA 29/11/23 Y SE OBSERVA AL JOVEN CONCIENTE DE LO OCURRIDO EN EL REPORTE, ASI COMO DURANTE SU ESTADIA EN EL ANEXO. SIN EMBARGO NO SE OBSERVA MEJORIA EN SU TRATAMIENTO YA QUE EL COMENTA QUE NO LE SIRVIO DE MUCHO. SE LE PEDIRA ANTIDOPING NUEVAMENTE SIN PREVIO AVISO PARA REVISAR QUE NO ESTE CONSUMIENDO SUSTANCIAS NUEVAMENTE.\r\n', 19010090, 12),
(2, '2024-05-02', 'Académico', 'SE CONSIDERA UN ALUMNO EN RIESGO DE ABANDONO POR EL NUMERO CONSIDERADO DE FALTAS EN TODAS SUS MATERIAS Y PRESENTAR LA MAYORIA DE ESTAS REPROBADAS\r\n', 'EL ALUMNO FUE ATENDIDO DE MANERA INDIVIDUAL PARA VALORAR SU SITUACIÓN ANTES DE SER REINTEGRADO A SUS CLASES.\r\n\r\n\r\n', 'Javier Varela Guardado', 'Hendrik Alberto Villarreal Sarmiento ', NULL, 6, 'C', 'Ofimática ', '6741009578', 'María de la Luz Sarmiento Nevarez', 'Madre', '6741108991', NULL, NULL, 'UNIDAD DE ATENCION S.', '', NULL, NULL, NULL, '', NULL, NULL, 19010015, 11),
(3, '2024-05-11', 'Adicción', 'Pendientes', 'No hay observaciones', 'Karla Fisher', 'Hendrik Villarreal', NULL, 6, 'C', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19010015, 12),
(4, '2024-05-11', 'Adicción', 'Un Adictor', 'Se Droga', NULL, 'Rodolfo Alemán Martínez', NULL, 6, 'A', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19010090, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `nombre_alumno` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) COLLATE utf32_spanish_ci DEFAULT NULL,
  `semestre` int(1) NOT NULL,
  `grupo` varchar(1) COLLATE utf32_spanish_ci NOT NULL,
  `CURP` varchar(18) COLLATE utf32_spanish_ci DEFAULT NULL,
  `genero` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `edad` int(3) NOT NULL,
  `fecha_naci` date NOT NULL,
  `lugar_nacimiento` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `nacionalidad` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `ayuda_español` tinyint(1) NOT NULL,
  `telefono` varchar(15) COLLATE utf32_spanish_ci NOT NULL,
  `correo` varchar(50) COLLATE utf32_spanish_ci NOT NULL,
  `secundaria_egreso` varchar(50) COLLATE utf32_spanish_ci NOT NULL,
  `promedio_secundaria` double(4,2) NOT NULL,
  `beca_bancomer` tinyint(1) NOT NULL,
  `nss_issste` tinyint(1) NOT NULL,
  `discapacidad_mo_psi` tinyint(1) NOT NULL,
  `detalles_discapacidad` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `documento_validacion_discapacidad` tinyint(1) DEFAULT NULL,
  `alergia` tinyint(1) NOT NULL,
  `detalles_alergias` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `requiere_medicacion` tinyint(1) NOT NULL,
  `medicacion_necesaria` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `lentes_graduados` tinyint(1) NOT NULL,
  `aparatos_asistencia` tinyint(1) NOT NULL,
  `detalles_aparatos_asistencia` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `calle_numero` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `colonia` varchar(50) COLLATE utf32_spanish_ci NOT NULL,
  `codigo_postal` int(5) NOT NULL,
  `dispositivo_internet` tinyint(1) NOT NULL,
  `numero_dispositivos` int(3) NOT NULL,
  `nombre_tutor` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `telefono_tutor` varchar(15) COLLATE utf32_spanish_ci NOT NULL,
  `nombre_madre` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_madre` varchar(15) COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_padre` varchar(100) COLLATE utf32_spanish_ci DEFAULT NULL,
  `telefono_padre` varchar(15) COLLATE utf32_spanish_ci DEFAULT NULL,
  `EP_acta_nacimiento` tinyint(1) NOT NULL,
  `EP_CURP` tinyint(1) NOT NULL,
  `EP_comprobante_domicilio` tinyint(1) NOT NULL,
  `EP_nss_issste` tinyint(1) NOT NULL,
  `EP_certificado_secundaria` tinyint(1) NOT NULL,
  `EP_ficha_psicopedagogica` tinyint(1) NOT NULL,
  `EP_ficha_buena_conducta` tinyint(1) NOT NULL,
  `EP_fotografias` tinyint(1) NOT NULL,
  `EP_autenticacion_secundaria` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_alumno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `nombre_alumno`, `apellido_paterno`, `apellido_materno`, `semestre`, `grupo`, `CURP`, `genero`, `edad`, `fecha_naci`, `lugar_nacimiento`, `nacionalidad`, `ayuda_español`, `telefono`, `correo`, `secundaria_egreso`, `promedio_secundaria`, `beca_bancomer`, `nss_issste`, `discapacidad_mo_psi`, `detalles_discapacidad`, `documento_validacion_discapacidad`, `alergia`, `detalles_alergias`, `requiere_medicacion`, `medicacion_necesaria`, `lentes_graduados`, `aparatos_asistencia`, `detalles_aparatos_asistencia`, `calle_numero`, `colonia`, `codigo_postal`, `dispositivo_internet`, `numero_dispositivos`, `nombre_tutor`, `telefono_tutor`, `nombre_madre`, `telefono_madre`, `nombre_padre`, `telefono_padre`, `EP_acta_nacimiento`, `EP_CURP`, `EP_comprobante_domicilio`, `EP_nss_issste`, `EP_certificado_secundaria`, `EP_ficha_psicopedagogica`, `EP_ficha_buena_conducta`, `EP_fotografias`, `EP_autenticacion_secundaria`) VALUES
(19010015, 'Hendrik Alberto', 'Villarreal', 'Sarmiento', 6, 'C', 'VISH010721HDGLRNA1', 'Masculino', 22, '2001-07-21', 'Santiago Papasquiaro, Durango', 'Mexicana', 0, '6741009578', 'hendrik.vs2101@gmail.com', 'EST 32', 8.70, 0, 1, 1, 'Discapacidad visual de grado inicial', 1, 0, NULL, 0, NULL, 1, 0, NULL, 'Arroyo seco # 301', 'El pueblo', 34649, 1, 4, 'María de la Luz Sarmiento Nevarez', '6741108991', 'María de la Luz Sarmiento Nevarez', '6741108991', 'Alfonso Villarreal Espinoza', '6741112301', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(19010090, 'Jesús Rodolfo', 'Alemán', 'Martínez', 9, 'C', 'AEMJ010808HDGLRSA5', 'Masculino', 17, '2009-05-22', 'Santiago Papasquiaro', 'Mexicana', 0, '6741108880', 'j.rodolfoalemanmtz@gmail.com', 'Venustiano Carranza', 9.70, 0, 0, 0, NULL, NULL, 1, 'Penicilina', 0, NULL, 0, 0, NULL, 'Av. Insurgentes #104', 'Lomas de San Juan', 34635, 1, 3, 'Alfredo ALemán Avila', '6741108890', 'Ma. Victoria MArtínez Favela', '6741108891', 'Alfredo Alemán Avila', '674110890', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(19010104, 'Mravin Gonzalo', 'Meza', 'Hurtado', 6, 'D', 'MEHM010207HNEZRRA9', 'Masculino', 23, '2001-02-07', 'Chicago', 'Mexicana, Americana', 1, '6741012543', 'marvinmeza1920@gmail.com', 'EST 32', 9.30, 1, 1, 0, NULL, NULL, 0, NULL, 0, NULL, 0, 0, NULL, '21 de Marzo #807', 'La haciendita', 34636, 1, 5, 'Gilberto Meza Cariaga', '6741102145', 'María del Rosario Hurtado Aguirre', '6741112990', 'Gilberto Meza Cariaga', '6741102145', 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` int(4) NOT NULL,
  `nombre_asignatura` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `horas` int(3) NOT NULL,
  `semestre` int(2) NOT NULL,
  `id_tipo_programa` int(11) NOT NULL,
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
  `id_especialidad` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
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
  `calificacion_parcial1` double(2,2) NOT NULL,
  `calificacion_parcial2` double(2,2) DEFAULT NULL,
  `calificacion_parcial3` double(2,2) DEFAULT NULL,
  `calificacion_final` double(2,2) DEFAULT NULL,
  `asistencia_parcial1` int(2) NOT NULL,
  `asitencia_parcial2` int(2) DEFAULT NULL,
  `asistencia_parcial3` int(2) DEFAULT NULL,
  `asistencia_total` int(3) DEFAULT NULL,
  `acreditacion` varchar(20) COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_docente` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
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
  `id_docente` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  KEY `id_grupo` (`id_grupo`),
  KEY `id_docente` (`id_docente`),
  KEY `id_asignatura` (`id_asignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id_docente`, `id_asignatura`, `id_grupo`) VALUES
(1012, 8, 2),
(1082, 8, 9),
(1082, 4, 9),
(1083, 8, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int(11) NOT NULL,
  `nombre_docente` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) COLLATE utf32_spanish_ci DEFAULT NULL,
  `RFC` varchar(13) COLLATE utf32_spanish_ci NOT NULL,
  `grupo_edad` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `tipo_plaza` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `formacion_academica` varchar(50) COLLATE utf32_spanish_ci NOT NULL,
  `antiguedad` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `nivel_estudios` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
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
  `id_especialidad` int(4) NOT NULL,
  `nombre_especialidad` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_programa` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id_especialidad`),
  KEY `id_tipo_programa` (`id_tipo_programa`),
  KEY `id_grupo` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre_especialidad`, `id_tipo_programa`, `id_grupo`) VALUES
(11, 'Ofimatica', 2, 9),
(12, 'Programación', 1, 10),
(14, 'Forestal', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int(11) NOT NULL,
  `semestre` int(2) NOT NULL,
  `nombre_grupo` varchar(1) COLLATE utf32_spanish_ci NOT NULL,
  `modalidad` varchar(15) COLLATE utf32_spanish_ci NOT NULL,
  `id_alumno` int(11) NOT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_alumno` (`id_alumno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `semestre`, `nombre_grupo`, `modalidad`, `id_alumno`) VALUES
(2, 6, 'D', 'Matutina', 19010104),
(9, 6, 'C', 'Matutina', 19010015),
(10, 9, 'C', 'Vespertina', 19010090);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

DROP TABLE IF EXISTS `modulos`;
CREATE TABLE IF NOT EXISTS `modulos` (
  `id_modulo` int(4) NOT NULL,
  `nombre_modulo` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `id_especialidad` int(11) NOT NULL,
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
  `id_submodulo` int(4) NOT NULL,
  `nombre_submodulo` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `id_modulo` int(11) NOT NULL,
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
  `id_tipo_programa` int(4) NOT NULL,
  `tipo_programa` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_programa`
--

INSERT INTO `tipo_programa` (`id_tipo_programa`, `tipo_programa`) VALUES
(1, 'Nueva escuela mexicana'),
(2, 'Acuerdo 029');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(4) NOT NULL,
  `perfil` varchar(20) COLLATE utf32_spanish_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  `password` varchar(30) COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `alertas_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

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
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `calificaciones_ibfk_4` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

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
  ADD CONSTRAINT `especialidad_ibfk_1` FOREIGN KEY (`id_tipo_programa`) REFERENCES `tipo_programa` (`id_tipo_programa`),
  ADD CONSTRAINT `especialidad_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`);

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
