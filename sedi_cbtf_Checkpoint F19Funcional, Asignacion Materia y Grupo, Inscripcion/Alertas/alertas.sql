-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-06-2024 a las 03:53:23
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
(1, '2024-05-01', 'Adicciónes', 'CONSUMIENDO ESTUPEFACIENTES\r\n', 'FECHA DE INGRESO 15 DE MARZO Y SALIDA 15 DE JUNIO\r\n', 'Karla Fisher', 'Rodolfo Alemán Martínez', 0, 6, 'A', 'Programación', '6741108880', 'Alfredo ALemán Avila', 'Padre', '6741108890', '8:00 A.M. /17 de abril del 2023\r\n', 1, 'UNIDAD DE ATENCIÓN SOCIOEMOCIONAL', '', '', 'TRATAMIENTO ALTERNATIVIO (ANEXO)\r\n', '2008-05-23', '', 'CHRISTIAN IRIGOYEN\r\n', 'SE DIO UN PRIMER ACERCAMIENTO CON ÉL EL DIA 29/11/23 Y SE OBSERVA AL JOVEN CONCIENTE DE LO OCURRIDO EN EL REPORTE, ASI COMO DURANTE SU ESTADIA EN EL ANEXO. SIN EMBARGO NO SE OBSERVA MEJORIA EN SU TRATAMIENTO YA QUE EL COMENTA QUE NO LE SIRVIO DE MUCHO. SE LE PEDIRA ANTIDOPING NUEVAMENTE SIN PREVIO AVISO PARA REVISAR QUE NO ESTE CONSUMIENDO SUSTANCIAS NUEVAMENTE.\r\n', 19010090, 12),
(2, '2024-05-02', 'Académico', 'SE CONSIDERA UN ALUMNO EN RIESGO DE ABANDONO POR EL NUMERO CONSIDERADO DE FALTAS EN TODAS SUS MATERIAS Y PRESENTAR LA MAYORIA DE ESTAS REPROBADAS\r\n', 'EL ALUMNO FUE ATENDIDO DE MANERA INDIVIDUAL PARA VALORAR SU SITUACIÓN ANTES DE SER REINTEGRADO A SUS CLASES.\r\n\r\n\r\n', 'Javier Varela Guardado', 'Hendrik Alberto Villarreal Sarmiento ', NULL, 6, 'C', 'Ofimática ', '6741009578', 'María de la Luz Sarmiento Nevarez', 'Madre', '6741108991', NULL, NULL, 'UNIDAD DE ATENCION S.', '', NULL, NULL, NULL, '', NULL, NULL, 19010015, 11),
(3, '2024-05-11', 'Adicción', 'Pendiente', 'No hay', 'Karla Fisher', 'Hendrik Villarreal', NULL, 6, 'C', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19010015, 12),
(4, '2024-05-11', 'Adicción', 'Un Adicto', 'Se Droga', NULL, 'Rodolfo Alemán Martínez', NULL, 6, 'A', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19010090, 12),
(78, '2024-06-28', 'Adicciónes', 'Un Adicto', 'Se Droga', NULL, 'Rodolfo Alemán Martínez', NULL, 6, 'B', 'Programación', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19010090, 12);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `alertas_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
