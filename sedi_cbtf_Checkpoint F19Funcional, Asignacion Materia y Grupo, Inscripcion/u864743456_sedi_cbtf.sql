-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 04-08-2024 a las 03:35:40
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
  `id_alerta` int NOT NULL AUTO_INCREMENT,
  `fecha_alerta` date NOT NULL,
  `tipo_alerta` varchar(25) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `situacion` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `observaciones` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `persona_reporta` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `alumno` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `condicionamiento` tinyint(1) DEFAULT NULL,
  `semestre` int NOT NULL,
  `nombre_grupo` varchar(2) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
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
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_alerta`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `matricula` bigint DEFAULT NULL,
  `nombre_alumno` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_semestre` int DEFAULT NULL,
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
  `ticket` tinyint(1) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `id_grupo` int NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_alumno`),
  KEY `id_grupo` (`id_grupo`,`id_especialidad`),
  KEY `id_usuario` (`id_usuario`),
  KEY `alumnos_ibfk_1` (`id_especialidad`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `alumnos`
--
DROP TRIGGER IF EXISTS `generate_username_password_after_update`;
DELIMITER $$
CREATE TRIGGER `generate_username_password_after_update` AFTER UPDATE ON `alumnos` FOR EACH ROW BEGIN
    DECLARE username VARCHAR(10);

    -- Generar el nombre de usuario basado en la matrícula
    IF NEW.matricula IS NOT NULL AND NEW.matricula <> '' THEN
        SET username = CONCAT(SUBSTRING(NEW.matricula, 1, 2), SUBSTRING(NEW.matricula, 12, 3));

        -- Actualizar el usuario correspondiente en la tabla usuarios
        UPDATE usuarios
        SET usuario = username
        WHERE id_usuario = NEW.id_usuario;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `generate_username_password_before_insert`;
DELIMITER $$
CREATE TRIGGER `generate_username_password_before_insert` BEFORE INSERT ON `alumnos` FOR EACH ROW BEGIN
    DECLARE username VARCHAR(10);
    DECLARE passwordTemp VARCHAR(8);

    -- Generar un nombre de usuario temporal
    SET username = 'temp_user';

    -- Generar una contraseña aleatoria de 8 dígitos
    SET passwordTemp = LPAD(FLOOR(RAND() * 100000000), 4, '0');

    -- Insertar el nuevo usuario y obtener el id generado
    INSERT INTO usuarios (usuario, password, perfil) 
    VALUES (username, passwordTemp, 'Alumno');

    -- Asignar el id_usuario al campo id_usuario en la tabla alumnos
    SET NEW.id_usuario = LAST_INSERT_ID();

    -- Verificar si la matrícula no está vacía
    IF NEW.matricula IS NOT NULL AND NEW.matricula <> '' THEN
        -- Generar el nombre de usuario basado en la matrícula
        SET username = CONCAT(SUBSTRING(NEW.matricula, 1, 2), SUBSTRING(NEW.matricula, 12, 3));

        -- Actualizar el usuario correspondiente en la tabla usuarios
        UPDATE usuarios
        SET usuario = username
        WHERE id_usuario = NEW.id_usuario;
    END IF;
    
    -- Establecer la fecha de creación
    SET NEW.fecha_creacion = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_subidos`
--

DROP TABLE IF EXISTS `archivos_subidos`;
CREATE TABLE IF NOT EXISTS `archivos_subidos` (
  `id_archivo` int NOT NULL AUTO_INCREMENT,
  `nombre_archivo` varchar(100) COLLATE utf8mb4_slovenian_ci NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_archivo`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `archivos_subidos`
--

INSERT INTO `archivos_subidos` (`id_archivo`, `nombre_archivo`, `fecha_subida`) VALUES
(5, 'DATOS HORARIOS_2.csv', '2024-08-03 08:57:55'),
(6, 'DATOS HORARIOS_2.csv', '2024-08-03 09:00:12'),
(7, 'DATOS HORARIOS_2.csv', '2024-08-03 09:01:54'),
(8, 'DATOS HORARIOS_2.csv', '2024-08-03 09:40:35'),
(9, 'DATOS HORARIOS_2.csv', '2024-08-03 09:46:12'),
(10, 'DATOS HORARIOS_2.csv', '2024-08-03 09:51:57'),
(11, 'DATOS HORARIOS_2.csv', '2024-08-03 10:08:42'),
(12, 'DATOS HORARIOS_2.csv', '2024-08-03 11:11:54'),
(13, 'DATOS HORARIOS_2.csv', '2024-08-03 11:29:16'),
(14, 'DATOS HORARIOS_2.csv', '2024-08-03 11:37:01'),
(15, 'DATOS HORARIOS_2.csv', '2024-08-03 11:40:28'),
(16, 'DATOS HORARIOS_2.csv', '2024-08-03 11:43:28'),
(17, 'DATOS HORARIOS_2.csv', '2024-08-03 20:29:35'),
(18, 'DATOS HORARIOS_2.csv', '2024-08-03 20:34:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` int NOT NULL AUTO_INCREMENT,
  `nombre_asignatura` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `horas` int NOT NULL,
  `id_semestre` int NOT NULL,
  `id_tipo_programa` int NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  KEY `id_tipo_programa` (`id_tipo_programa`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre_asignatura`, `horas`, `id_semestre`, `id_tipo_programa`) VALUES
(64, 'T. DE  FILOSOFÍA', 0, 1, 1),
(65, 'TUTORÍAS VI', 0, 1, 1),
(66, 'FTAL.MOD5SUB2:EJECUTA PROYECTOS', 0, 1, 1),
(67, 'INGLES II', 0, 2, 1);

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
  `id_docente` int DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=456 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre_docente` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `RFC` varchar(13) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `genero` varchar(10) COLLATE utf32_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf32_spanish_ci NOT NULL,
  `grupo_edad` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `tipo_plaza` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `formacion_academica` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `antiguedad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `nivel_estudios` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `id_usuario`, `nombre_docente`, `apellido_paterno`, `apellido_materno`, `RFC`, `genero`, `email`, `grupo_edad`, `tipo_plaza`, `formacion_academica`, `antiguedad`, `nivel_estudios`) VALUES
(14, 47, 'LIZETH', 'AVIÑA REYES', 'ANA', '', '', '', '', '', '', '', ''),
(15, 48, 'LIZETH', 'CARRASCO RUBIO', 'CYNTHIA', '', '', '', '', '', '', '', '');

--
-- Disparadores `docentes`
--
DROP TRIGGER IF EXISTS `generate_username_password_docente_after_insert`;
DELIMITER $$
CREATE TRIGGER `generate_username_password_docente_after_insert` BEFORE INSERT ON `docentes` FOR EACH ROW BEGIN
    DECLARE username VARCHAR(10);
    DECLARE default_password VARCHAR(20) DEFAULT '123456789';

    -- Generar un nombre de usuario temporal
    SET username = 'temp_user';

    -- Insertar el nuevo usuario y obtener el id generado
    INSERT INTO usuarios (usuario, password, perfil) 
    VALUES (username, default_password, 'Docente');

    -- Asignar el id_usuario al campo id_usuario en la tabla docentes
    SET NEW.id_usuario = LAST_INSERT_ID();

    -- Verificar si el email no está vacío
    IF NEW.email IS NOT NULL AND NEW.email <> '' THEN
        -- Actualizar el usuario correspondiente en la tabla usuarios con el email
        UPDATE usuarios
        SET usuario = NEW.email
        WHERE id_usuario = NEW.id_usuario;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
CREATE TABLE IF NOT EXISTS `especialidad` (
  `id_especialidad` int NOT NULL AUTO_INCREMENT,
  `nombre_especialidad` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_programa` int NOT NULL,
  PRIMARY KEY (`id_especialidad`),
  KEY `id_tipo_programa` (`id_tipo_programa`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

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
(18, 'TÉCNICO EN PROGRAMACIÓN', 2),
(57, 'TÉCNICO EN OFIMÁTICA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `id_semestre` int NOT NULL,
  `nombre_grupo` varchar(2) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `modalidad` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_especialidad` (`id_especialidad`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `id_semestre`, `nombre_grupo`, `modalidad`, `id_especialidad`) VALUES
(87, 1, 'A', '', 57),
(88, 1, 'A', '', 57),
(89, 1, 'A', '', 57),
(90, 1, 'A', '', 16),
(91, 2, 'A', '', 17),
(92, 2, 'A', '', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

DROP TABLE IF EXISTS `horarios`;
CREATE TABLE IF NOT EXISTS `horarios` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `id_docente` int DEFAULT NULL,
  `id_asignatura` int DEFAULT NULL,
  `id_grupo` int DEFAULT NULL,
  `lunes` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `martes` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `miercoles` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `jueves` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `viernes` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `sabado` varchar(50) COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `horas_semanales` int DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_asignatura` (`id_asignatura`,`id_grupo`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_docente` (`id_docente`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `id_docente`, `id_asignatura`, `id_grupo`, `lunes`, `martes`, `miercoles`, `jueves`, `viernes`, `sabado`, `horas_semanales`) VALUES
(85, 14, 64, 87, NULL, NULL, '09:00-11:00', NULL, NULL, NULL, 4),
(86, 14, 64, 88, NULL, '09:00-11:00', NULL, '09:00-12:00', NULL, NULL, 1),
(87, 14, 65, 89, '09:00-11:00 12:00-13:00', NULL, NULL, NULL, '09:00-11:00', NULL, 1),
(88, 15, 66, 90, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(89, 15, 67, 91, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(90, 15, 67, 92, '09:00-10:00', NULL, NULL, NULL, NULL, NULL, 2);

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
-- Estructura de tabla para la tabla `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `id_semestre` int NOT NULL AUTO_INCREMENT,
  `nombre_semestre` varchar(5) COLLATE utf8mb4_slovenian_ci NOT NULL,
  PRIMARY KEY (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `semestre`
--

INSERT INTO `semestre` (`id_semestre`, `nombre_semestre`) VALUES
(1, 'VI'),
(2, 'II');

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
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `perfil` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `usuario` varchar(55) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `perfil`, `usuario`, `password`) VALUES
(21, 'Directivo', 'directivo@directivo.com', '$2y$10$UkldbHkfxn.CPn9ZHK7Mre69X4SRbujvlsapWnHaY5juWfHVlA3Re'),
(23, 'Alumno', '22999', '1828'),
(26, 'Alumno', '22666', '05552689'),
(27, 'Alumno', '11444', '6320'),
(36, 'Alumno', '33222', '1611'),
(37, 'Docente', 'docente@docente.com', '$2y$10$klhL83GhPIc3aIi0kFAyGenrR8a1jvhdO.HvIDWEqgZm0gp5afsby'),
(38, 'Alumno', 'temp_user', '5916'),
(39, 'Docente', 'temp_user', '123456789'),
(40, 'Docente', 'temp_user', '123456789'),
(41, 'Docente', 'temp_user', '123456789'),
(42, 'Docente', 'temp_user', '123456789'),
(43, 'Docente', 'temp_user', '123456789'),
(44, 'Docente', 'temp_user', '123456789'),
(45, 'Docente', 'temp_user', '123456789'),
(46, 'Docente', 'temp_user', '123456789'),
(47, 'Docente', 'temp_user', '123456789'),
(48, 'Docente', 'temp_user', '123456789');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `alertas_ibfk_3` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `alertas_ibfk_4` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`),
  ADD CONSTRAINT `alumnos_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `alumnos_ibfk_4` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Filtros para la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD CONSTRAINT `asignatura_ibfk_1` FOREIGN KEY (`id_tipo_programa`) REFERENCES `tipo_programa` (`id_tipo_programa`),
  ADD CONSTRAINT `asignatura_ibfk_2` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Filtros para la tabla `asignaturas_especialidad`
--
ALTER TABLE `asignaturas_especialidad`
  ADD CONSTRAINT `asignaturas_especialidad_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
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
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD CONSTRAINT `especialidad_ibfk_1` FOREIGN KEY (`id_tipo_programa`) REFERENCES `tipo_programa` (`id_tipo_programa`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `grupo_ibfk_2` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`),
  ADD CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

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
