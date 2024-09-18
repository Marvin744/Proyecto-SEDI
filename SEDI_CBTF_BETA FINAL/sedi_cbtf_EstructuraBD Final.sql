-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-09-2024 a las 05:52:35
-- Versión del servidor: 10.11.8-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

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

CREATE TABLE `actividades_complementarias` (
  `id_actividad_complementaria` int(11) NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `actividad` varchar(150) NOT NULL,
  `detalles_actividad` varchar(200) NOT NULL,
  `lunes` varchar(50) DEFAULT NULL,
  `martes` varchar(50) DEFAULT NULL,
  `miercoles` varchar(50) DEFAULT NULL,
  `jueves` varchar(50) DEFAULT NULL,
  `viernes` varchar(50) DEFAULT NULL,
  `sabado` varchar(50) DEFAULT NULL,
  `horas_semanales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(11) NOT NULL,
  `fecha_alerta` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_alerta` varchar(50) NOT NULL,
  `situacion` varchar(150) NOT NULL,
  `observaciones` varchar(150) NOT NULL,
  `persona_reporta` varchar(100) DEFAULT NULL,
  `alumno` varchar(100) NOT NULL,
  `condicionamiento` tinyint(1) DEFAULT NULL,
  `telefono_alumno` varchar(15) DEFAULT NULL,
  `nombre_tutor` varchar(100) DEFAULT NULL,
  `telefono_tutor` varchar(15) DEFAULT NULL,
  `cita` varchar(100) DEFAULT NULL,
  `asistencia_padre_tutor` tinyint(1) DEFAULT NULL,
  `canalizacion` varchar(50) DEFAULT NULL,
  `quien_atiende` varchar(100) DEFAULT NULL,
  `tratamiento` varchar(100) DEFAULT NULL,
  `sancion` varchar(100) DEFAULT NULL,
  `fecha_cumplimiento` date DEFAULT NULL,
  `evidencias` varchar(150) DEFAULT NULL,
  `quien_atiende_suspencion_hss` varchar(100) DEFAULT NULL,
  `seguimiento` varchar(400) DEFAULT NULL,
  `id_alumno` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `alertas`
--
DELIMITER $$
CREATE TRIGGER `trigger_notificaciones_alerta_insert_admins` AFTER INSERT ON `alertas` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    DECLARE admin_id INT;
    DECLARE done INT DEFAULT 0;
    DECLARE admin_cursor CURSOR FOR
        SELECT id_usuario FROM usuarios WHERE perfil = 'Admin';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Abrir cursor para iterar sobre cada directivo
    OPEN admin_cursor;

    fetch_loop: LOOP
        FETCH admin_cursor INTO admin_id;
        IF done THEN
            LEAVE fetch_loop;
        END IF;

        -- Insertar notificación para cada directivo
    -- Asigna el mensaje basado en el tipo de alerta
    IF NEW.tipo_alerta = 'INDISCIPLINA' THEN
        SET mensaje = CONCAT('Alerta: INDISCIPLINA, Se ha generado una Alerta de indisciplina del alumno: ', NEW.alumno);
    ELSEIF NEW.tipo_alerta = 'ACADEMICO: REPROBACION, FALTAS' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS ACADEMICOS, el alumno: ', NEW.alumno, ' Necesita apoyo para mejorar su rendimiento.');
    ELSEIF NEW.tipo_alerta = 'ADICCIONES' THEN
        SET mensaje = CONCAT('Alerta: Posible ADICCION, el alumno: ', NEW.alumno, ' Necesita apoyo.');
    ELSEIF NEW.tipo_alerta = 'PROBLEMAS EMOCIONALES' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS EMOCIONALES, el alumno: ', NEW.alumno, ' Requiere un seguimiento adecuado.');
    ELSEIF NEW.tipo_alerta = 'DISCAPACIDAD' THEN
        SET mensaje = CONCAT('Alerta: DISCAPACIDAD, el alumno: ', NEW.alumno, ' Requiere coordinación necesaria.');
    ELSEIF NEW.tipo_alerta = 'INASISTENCIAS' THEN
        SET mensaje = CONCAT('Alerta: INASISTENCIAS: El alumno ', NEW.alumno, ' ha acumulado ', SUBSTRING_INDEX(SUBSTRING_INDEX(NEW.observaciones, ' ', -2), ' ', 1), ' inasistencias');
    ELSE
        SET mensaje = 'Tipo de alerta desconocido';
    END IF;

        INSERT INTO notificaciones (perfil, mensaje, leido, fecha_creacion, id_alerta, id_usuario)
        VALUES ('Directivo', mensaje, 0, NOW(), NEW.id_alerta, admin_id);
    END LOOP;

    -- Cerrar el cursor
    CLOSE admin_cursor;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_notificaciones_alerta_insert_directivos` AFTER INSERT ON `alertas` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    DECLARE directivo_id INT;
    DECLARE done INT DEFAULT 0;
    DECLARE directivo_cursor CURSOR FOR
        SELECT id_usuario FROM usuarios WHERE perfil = 'Directivo';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Abrir cursor para iterar sobre cada directivo
    OPEN directivo_cursor;

    fetch_loop: LOOP
        FETCH directivo_cursor INTO directivo_id;
        IF done THEN
            LEAVE fetch_loop;
        END IF;

        -- Insertar notificación para cada directivo
    -- Asigna el mensaje basado en el tipo de alerta
    IF NEW.tipo_alerta = 'INDISCIPLINA' THEN
        SET mensaje = CONCAT('Alerta: INDISCIPLINA, Se ha generado una Alerta de indisciplina del alumno: ', NEW.alumno);
    ELSEIF NEW.tipo_alerta = 'ACADEMICO: REPROBACION, FALTAS' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS ACADEMICOS, el alumno: ', NEW.alumno, ' Necesita apoyo para mejorar su rendimiento.');
    ELSEIF NEW.tipo_alerta = 'ADICCIONES' THEN
        SET mensaje = CONCAT('Alerta: Posible ADICCION, el alumno: ', NEW.alumno, ' Necesita apoyo.');
    ELSEIF NEW.tipo_alerta = 'PROBLEMAS EMOCIONALES' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS EMOCIONALES, el alumno: ', NEW.alumno, ' Requiere un seguimiento adecuado.');
    ELSEIF NEW.tipo_alerta = 'DISCAPACIDAD' THEN
        SET mensaje = CONCAT('Alerta: DISCAPACIDAD, el alumno: ', NEW.alumno, ' Requiere coordinación necesaria.');
    ELSEIF NEW.tipo_alerta = 'INASISTENCIAS' THEN
        SET mensaje = CONCAT('Alerta: INASISTENCIAS: El alumno ', NEW.alumno, ' ha acumulado ', SUBSTRING_INDEX(SUBSTRING_INDEX(NEW.observaciones, ' ', -2), ' ', 1), ' inasistencias');
    ELSE
        SET mensaje = 'Tipo de alerta desconocido';
    END IF;

        INSERT INTO notificaciones (perfil, mensaje, leido, fecha_creacion, id_alerta, id_usuario)
        VALUES ('Directivo', mensaje, 0, NOW(), NEW.id_alerta, directivo_id);
    END LOOP;

    -- Cerrar el cursor
    CLOSE directivo_cursor;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_notificaciones_alerta_insert_directivos_docentes` AFTER INSERT ON `alertas` FOR EACH ROW BEGIN
    DECLARE mensaje VARCHAR(255);
    DECLARE directivo_docente_id INT;
    DECLARE done INT DEFAULT 0;
    DECLARE directivo_docente_cursor CURSOR FOR
        SELECT id_usuario FROM usuarios WHERE perfil = 'Directivo_y_docente';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Abrir cursor para iterar sobre cada directivo
    OPEN directivo_docente_cursor;

    fetch_loop: LOOP
        FETCH directivo_docente_cursor INTO directivo_docente_id;
        IF done THEN
            LEAVE fetch_loop;
        END IF;

        -- Insertar notificación para cada directivo
    -- Asigna el mensaje basado en el tipo de alerta
    IF NEW.tipo_alerta = 'INDISCIPLINA' THEN
        SET mensaje = CONCAT('Alerta: INDISCIPLINA, Se ha generado una Alerta de indisciplina del alumno: ', NEW.alumno);
    ELSEIF NEW.tipo_alerta = 'ACADEMICO: REPROBACION, FALTAS' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS ACADEMICOS, el alumno: ', NEW.alumno, ' Necesita apoyo para mejorar su rendimiento.');
    ELSEIF NEW.tipo_alerta = 'ADICCIONES' THEN
        SET mensaje = CONCAT('Alerta: Posible ADICCION, el alumno: ', NEW.alumno, ' Necesita apoyo.');
    ELSEIF NEW.tipo_alerta = 'PROBLEMAS EMOCIONALES' THEN
        SET mensaje = CONCAT('Alerta: PROBLEMAS EMOCIONALES, el alumno: ', NEW.alumno, ' Requiere un seguimiento adecuado.');
    ELSEIF NEW.tipo_alerta = 'DISCAPACIDAD' THEN
        SET mensaje = CONCAT('Alerta: DISCAPACIDAD, el alumno: ', NEW.alumno, ' Requiere coordinación necesaria.');
    ELSEIF NEW.tipo_alerta = 'INASISTENCIAS' THEN
        SET mensaje = CONCAT('Alerta: INASISTENCIAS: El alumno ', NEW.alumno, ' ha acumulado ', SUBSTRING_INDEX(SUBSTRING_INDEX(NEW.observaciones, ' ', -2), ' ', 1), ' inasistencias');
    ELSE
        SET mensaje = 'Tipo de alerta desconocido';
    END IF;

        INSERT INTO notificaciones (perfil, mensaje, leido, fecha_creacion, id_alerta, id_usuario)
        VALUES ('Directivo', mensaje, 0, NOW(), NEW.id_alerta, directivo_docente_id);
    END LOOP;

    -- Cerrar el cursor
    CLOSE directivo_docente_cursor;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `matricula` bigint(20) DEFAULT NULL,
  `nombre_alumno` varchar(30) NOT NULL,
  `apellido_paterno` varchar(20) NOT NULL,
  `apellido_materno` varchar(20) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `CURP` varchar(18) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha_naci` date DEFAULT NULL,
  `lugar_nacimiento` varchar(30) DEFAULT NULL,
  `nacionalidad` varchar(30) DEFAULT NULL,
  `ayuda_espanol` tinyint(1) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `secundaria_egreso` varchar(100) DEFAULT NULL,
  `promedio_secundaria` double(4,2) DEFAULT NULL,
  `sangre` varchar(12) DEFAULT NULL,
  `beca_bancomer` tinyint(1) DEFAULT NULL,
  `nss` tinyint(1) DEFAULT NULL,
  `nss_numero` bigint(20) DEFAULT NULL,
  `issste` tinyint(1) DEFAULT NULL,
  `issste_numero` varchar(30) DEFAULT NULL,
  `discapacidad_mo_psi` tinyint(1) DEFAULT NULL,
  `detalles_discapacidad` varchar(100) DEFAULT NULL,
  `documento_validacion_discapacidad` tinyint(1) DEFAULT NULL,
  `alergia` tinyint(1) DEFAULT NULL,
  `detalles_alergias` varchar(100) DEFAULT NULL,
  `requiere_medicacion` tinyint(1) DEFAULT NULL,
  `medicacion_necesaria` varchar(100) DEFAULT NULL,
  `lentes_graduados` tinyint(1) DEFAULT NULL,
  `aparatos_asistencia` tinyint(1) DEFAULT NULL,
  `detalles_aparatos_asistencia` varchar(100) DEFAULT NULL,
  `calle_numero` varchar(100) DEFAULT NULL,
  `colonia` varchar(50) DEFAULT NULL,
  `codigo_postal` int(11) DEFAULT NULL,
  `dispositivo_internet` tinyint(1) DEFAULT NULL,
  `numero_dispositivos` int(11) DEFAULT NULL,
  `nombre_tutor` varchar(100) DEFAULT NULL,
  `telefono_tutor` varchar(50) DEFAULT NULL,
  `nombre_madre` varchar(100) DEFAULT NULL,
  `telefono_madre` varchar(50) DEFAULT NULL,
  `nombre_padre` varchar(100) DEFAULT NULL,
  `telefono_padre` varchar(50) DEFAULT NULL,
  `EP_acta_nacimiento` tinyint(1) DEFAULT NULL,
  `EP_CURP` tinyint(1) DEFAULT NULL,
  `EP_comprobante_domicilio` tinyint(1) DEFAULT NULL,
  `EP_nss_issste` tinyint(1) DEFAULT NULL,
  `EP_certificado_secundaria` tinyint(1) DEFAULT NULL,
  `EP_ficha_psicopedagogica` tinyint(1) DEFAULT NULL,
  `EP_ficha_buena_conducta` tinyint(1) DEFAULT NULL,
  `EP_fotografias` tinyint(1) DEFAULT NULL,
  `EP_autenticacion_secundaria` tinyint(1) DEFAULT NULL,
  `observaciones` varchar(200) DEFAULT NULL,
  `ticket` tinyint(1) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `generacion` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `alumnos`
--
DELIMITER $$
CREATE TRIGGER `before_update_generacion` BEFORE UPDATE ON `alumnos` FOR EACH ROW BEGIN
    DECLARE matricula_prefix VARCHAR(2);
    DECLARE anio_inicio VARCHAR(4);
    DECLARE anio_fin VARCHAR(4);

    -- Verificar si la matricula es NULL o ha cambiado
    IF OLD.matricula IS NULL OR NEW.matricula != OLD.matricula THEN
        -- Extraer los primeros dos dígitos de la nueva matrícula
        SET matricula_prefix = LEFT(NEW.matricula, 2);
        
        -- Construir los años de generación
        SET anio_inicio = CONCAT('20', matricula_prefix);
        SET anio_fin = CONCAT('20', LPAD(CAST(matricula_prefix AS UNSIGNED) + 3, 2, '0'));

        -- Asignar el valor al campo generacion
        SET NEW.generacion = CONCAT(anio_inicio, '-', anio_fin);
    END IF;
END
$$
DELIMITER ;
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
DELIMITER $$
CREATE TRIGGER `insertar_nuevo_alumno` BEFORE INSERT ON `alumnos` FOR EACH ROW BEGIN
    IF NEW.ticket = 1 THEN
        UPDATE alumnos
        SET id_semestre = 1
        WHERE id_alumno = NEW.id_alumno;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_subidos`
--

CREATE TABLE `archivos_subidos` (
  `id_archivo` int(11) NOT NULL,
  `nombre_archivo` varchar(100) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `id_asignatura` int(11) NOT NULL,
  `nombre_asignatura` varchar(250) DEFAULT NULL,
  `submodulos` varchar(250) DEFAULT NULL,
  `tronco_comun` tinyint(4) DEFAULT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre_asignatura`, `submodulos`, `tronco_comun`, `id_semestre`, `id_especialidad`) VALUES
(143, 'Módulo 1 Plantea actividades del área de Recursos Humanos en función a cada área de la organización', 'Submódulo 1 Distingue los diferentes tipos de empresa por su giro, áreas funcionales, documentación administrativa y recursos', NULL, 2, 12),
(144, 'Módulo 1 Plantea actividades del área de Recursos Humanos en función a cada área de la organización', 'Submódulo 2 Elabora estrategias para realizar las actividades de su área', NULL, 2, 12),
(145, 'Módulo 2 Integra el capital humano a la organización', 'Submódulo 1 Realiza el proceso de admisión y empleo', NULL, 3, 12),
(146, 'Módulo 2 Integra el capital humano a la organización', 'Submódulo 2 Contribuye a la integración y desarrollo del personal en la organización', NULL, 3, 12),
(147, 'Módulo 3 Asiste en el control y evaluación del desempeño del capital humano de la organización', 'Submódulo 1 Asiste en las actividades de capacitación para el desarrollo del capital humano', NULL, 4, 12),
(148, 'Módulo 3 Asiste en el control y evaluación del desempeño del capital humano de la organización', 'Submódulo 2 Evalúa el desempeño de la organización utilizando herramientas de calidad', NULL, 4, 12),
(149, 'Módulo 4 Controla los procesos y servicios de higiene y seguridad del capital humano en la organización', 'Submódulo 1 Supervisa el cumplimiento de las medidas de higiene y seguridad en la organización', NULL, 5, 12),
(150, 'Módulo 4 Controla los procesos y servicios de higiene y seguridad del capital humano en la organización', 'Submódulo 2 Supervisa el cumplimiento de tareas y procesos para evaluar la productividad en la organización', NULL, 5, 12),
(151, 'Módulo 5 Determina las remuneraciones al capital humano de la organización', 'Submódulo 1 Determina la nómina del personal de la organización tomando en cuenta la normatividad laboral', NULL, 6, 12),
(152, 'Módulo 5 Determina las remuneraciones al capital humano de la organización', 'Submódulo 2 Determina remuneraciones del personal en situaciones extraordinarias', NULL, 6, 12),
(153, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 1 Elabora dibujos a mano alzada de acuerdo con las necesidades del cliente', NULL, 2, 14),
(154, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 2 Elabora bocetos a mano alzada de acuerdo con la ergonomía y las necesidades del cliente', NULL, 2, 14),
(155, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 3 Elabora proyecciones ortogonales e isométricas de acuerdo con las necesidades del cliente', NULL, 2, 14),
(156, 'Módulo 2 Diseña muebles de madera utilizando software', 'Submódulo 1 Diseña muebles de madera asistido por computadora', NULL, 3, 14),
(157, 'Módulo 2 Diseña muebles de madera utilizando software', 'Submódulo 2 Produce modelos de muebles de madera considerando los diferentes materiales', NULL, 3, 14),
(158, 'Módulo 3 Produce piezas y da mantenimiento preventivo a máquinas para la fabricación de muebles de madera', 'Submódulo 1 Elabora piezas para la fabricación de muebles de madera', NULL, 4, 14),
(159, 'Módulo 3 Produce piezas y da mantenimiento preventivo a máquinas para la fabricación de muebles de madera', 'Submódulo 2 Realiza mantenimiento preventivo de maquinaria, herramientas y equipo para fabricación de muebles de madera', NULL, 4, 14),
(160, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 1 Fabrica piezas de acuerdo con las especificaciones del diseño', NULL, 5, 14),
(161, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 2 Ensambla, arma piezas y componentes de muebles de madera', NULL, 5, 14),
(162, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 3 Aplica acabados en muebles de madera', NULL, 5, 14),
(163, 'Módulo 6 Desarrolla un proyecto emprendedor para fabricación de muebles de madera', 'Submódulo 1 Organiza los procesos industriales en muebles de madera', NULL, 6, 14),
(164, 'Módulo 6 Desarrolla un proyecto emprendedor para fabricación de muebles de madera', 'Submódulo 2 Aplica las etapas del proceso administrativo para el desarrollo del proyecto emprendedor', NULL, 6, 14),
(165, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 1 Recolecta y almacena germoplasma', NULL, 2, 16),
(166, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 2 Establece, acondiciona y mantiene viveros para producir planta', NULL, 2, 16),
(167, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 3 Participa en el establecimiento y mantenimiento de plantaciones', NULL, 2, 16),
(168, 'Módulo 2 Participa en el cultivo y en la protección de los recurso forestales', 'Submódulo 1 Realiza actividades de medición forestal para obtener el inventario forestal', NULL, 3, 16),
(169, 'Módulo 2 Participa en el cultivo y en la protección de los recurso forestales', 'Submódulo 2 Aplica tratamientos silvícolas y actividades de protección forestal', NULL, 3, 16),
(170, 'Módulo 3 Aprovecha los recurso forestales', 'Submódulo 1 Aplica actividades de aprovechamiento forestal maderable', NULL, 4, 16),
(171, 'Módulo 3 Aprovecha los recurso forestales', 'Submódulo 2 Participa en el manejo y aprovechamiento de los recursos forestales no maderables', NULL, 4, 16),
(172, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 1 Industrializa madera en rollo', NULL, 5, 16),
(173, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 2 Seca y preserva madera aserrada', NULL, 5, 16),
(174, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 3 Planea proyectos productivos forestales estudiantiles', NULL, 5, 16),
(175, 'Módulo 5 Colabora en la ejecución de proyectos de servicios ambientales', 'Submódulo 1 Participa en actividades de ecoturismo, captura de carbono, servicios hidrológicos y conservación de suelos', NULL, 6, 16),
(176, 'Módulo 5 Colabora en la ejecución de proyectos de servicios ambientales', 'Submódulo 2 Ejecuta proyectos productivos forestales estudiantiles', NULL, 6, 16),
(177, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 1 Instala y configura equipo de cómputo y periféricos', NULL, 2, 18),
(178, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 2 Instala y configura sistemas operativos y aplicaciones de la ofimática', NULL, 2, 18),
(179, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 3 Gestiona archivos y dispositivos ofimáticos', NULL, 2, 18),
(180, 'Módulo 2 Gestiona información de manera local', 'Submódulo 1 Gestiona información mediante el uso de procesadores de texto', NULL, 3, 18),
(181, 'Módulo 2 Gestiona información de manera local', 'Submódulo 2 Gestiona información mediante el uso de hojas de cálculo', NULL, 3, 18),
(182, 'Módulo 2 Gestiona información de manera local', 'Submódulo 3 Gestiona información mediante el uso de software de presentaciones', NULL, 3, 18),
(183, 'Módulo 3 Gestiona información de manera remota', 'Submódulo 1 Gestiona información mediante el uso de software en línea', NULL, 4, 18),
(184, 'Módulo 3 Gestiona información de manera remota', 'Submódulo 2 Gestiona recursos mediante el uso de redes de computadoras', NULL, 4, 18),
(185, 'Módulo 4 Diseña y gestiona bases de datos ofimáticas', 'Submódulo 1 Diseña bases de datos ofimáticas', NULL, 5, 18),
(186, 'Módulo 4 Diseña y gestiona bases de datos ofimáticas', 'Submódulo 2 Gestiona información mediante el uso de sistemas manejadores de bases de datos ofimáticas', NULL, 5, 18),
(187, 'Módulo 5 Establece comunicación ofimática', 'Submódulo 1 Gestiona información a través de plataformas digitales', NULL, 6, 18),
(188, 'Módulo 5 Establece comunicación ofimática', 'Submódulo 2 Establece comunicación y gestiona información mediante el uso de dispositivos móviles', NULL, 6, 18),
(189, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 1 Construye algoritmos para la solución de problemas', NULL, 2, 20),
(190, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 2 Aplica estructuras de control con un lenguaje de programación', NULL, 2, 20),
(191, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 3 Aplica estructuras de datos con un lenguaje de programación', NULL, 2, 20),
(192, 'Módulo 2 Aplica metodologías de desarrollo de software con herramientas de programación visual', 'Submódulo 1 Aplica la metodología espiral con programación orientada a objetos', NULL, 3, 20),
(193, 'Módulo 2 Aplica metodologías de desarrollo de software con herramientas de programación visual', 'Submódulo 2 Aplica la metodología de desarrollo rápido de aplicaciones con programación orientada a eventos', NULL, 3, 20),
(194, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 1 Construye páginas Web', NULL, 4, 20),
(195, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 2 Desarrolla aplicaciones que se ejecutan en el cliente', NULL, 4, 20),
(196, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 3 Desarrolla aplicaciones que se ejecutan en el servidor', NULL, 4, 20),
(197, 'Módulo 4 Desarrolla software de aplicación Web con almacenamiento persistente de datos', 'Submódulo 1 Construye bases de datos para aplicaciones Web', NULL, 5, 20),
(198, 'Módulo 4 Desarrolla software de aplicación Web con almacenamiento persistente de datos', 'Submódulo 2 Desarrolla aplicaciones Web con conexión a bases de datos', NULL, 5, 20),
(199, 'Módulo 5 Desarrolla aplicaciones para dispositivos móviles', 'Submódulo 1 Desarrolla aplicaciones móviles para Android', NULL, 6, 20),
(200, 'Módulo 5 Desarrolla aplicaciones para dispositivos móviles', 'Submódulo 2 Desarrolla aplicaciones móviles para IOS', NULL, 6, 20),
(201, 'ÁLGEBRA', NULL, 1, 1, 2),
(202, 'QUÍMICA I', NULL, 1, 1, 2),
(203, 'TECNOLOGÍAS DE LA INFORMACIÓN Y LA COMUNICACIÓN', NULL, 1, 1, 2),
(204, 'LÓGICA', NULL, 1, 1, 2),
(205, 'LECTURA, EXPRESIÓN ORAL Y ESCRITA I', NULL, 1, 1, 2),
(206, 'INGLÉS I', NULL, 1, 1, 2),
(207, 'GEOMETRÍA Y TRIGONOMETRÍA', NULL, 1, 2, 2),
(208, 'LECTURA, EXPRESIÓN ORAL Y ESCRITA II', NULL, 1, 2, 2),
(209, 'QUÍMICA II', NULL, 1, 2, 2),
(210, 'INGLÉS II', NULL, 1, 2, 2),
(211, 'GEOMETRÍA ANALÍTICA', NULL, 1, 3, 2),
(212, 'BIOLOGÍA', NULL, 1, 3, 2),
(213, 'ÉTICA', NULL, 1, 3, 2),
(214, 'INGLÉS III', NULL, 1, 3, 2),
(215, 'CÁLCULO DIFERENCIAL', NULL, 1, 4, 2),
(216, 'FÍSICA I', NULL, 1, 4, 2),
(217, 'ECOLOGÍA', NULL, 1, 4, 2),
(218, 'INGLÉS IV', NULL, 1, 4, 2),
(219, 'CÁLCULO INTEGRAL', NULL, 1, 5, 2),
(220, 'FÍSICA II', NULL, 1, 5, 2),
(221, 'CIENCIA, TECNOLOGÍA, SOCIEDAD Y VALORES', NULL, 1, 5, 2),
(222, 'INGLÉS V', NULL, 1, 5, 2),
(223, 'PROBABILIDAD Y ESTADÍSTICA', NULL, 1, 6, 2),
(224, 'TEMAS DE FILOSOFIA', NULL, 1, 6, 2),
(225, 'Módulo 1 Gestiona trámites administrativos del área de recursos humanos', 'Submódulo 1 Ejecuta procedimientos administrativos del área de recursos humanos', NULL, 2, 11),
(226, 'Módulo 1 Gestiona trámites administrativos del área de recursos humanos', 'Submódulo 2 Gestiona documentación del área de recursos humanos', NULL, 2, 11),
(227, 'Módulo 2 Integra el talento humano en la organización', 'Submódulo 1 Gestiona el proceso de reclutamiento, selección y admisión del talento humano', NULL, 3, 11),
(228, 'Módulo 2 Integra el talento humano en la organización', 'Submódulo 2 Gestiona los procesos de inducción y permanencia del talento humano', NULL, 3, 11),
(229, 'Módulo 3 Implementa plan de desarrollo del talento humano', 'Submódulo 1 Gestiona los procesos de capacitación para el desarrollo del talento humano', NULL, 4, 11),
(230, 'Módulo 3 Implementa plan de desarrollo del talento humano', 'Submódulo 2 Promueve condiciones de trabajo saludables en la organización', NULL, 4, 11),
(231, 'Módulo 4 Evalúa el desempeño del talento humano', 'Submódulo 1 Gestiona la aplicación de la evaluación del desempeño humano', NULL, 5, 11),
(232, 'Módulo 4 Evalúa el desempeño del talento humano', 'Submódulo 2 Mide el desempeño del talento humano', NULL, 5, 11),
(233, 'Módulo 5 Maneja nómina y compensaciones del talento humano', 'Submódulo 1 - Auxilia en el cálculo de la nómina ordinaria', NULL, 6, 11),
(234, 'Módulo 5 Maneja nómina y compensaciones del talento humano', 'Submódulo 2 - Auxilia en el cálculo de la nómina extraordinaria', NULL, 6, 11),
(235, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 1 Diseña software de sistemas informáticos', NULL, 2, 19),
(236, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 2 Codifica software de sistemas informáticos', NULL, 2, 19),
(237, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 3 Implementa software de sistemas informáticos', NULL, 2, 19),
(238, 'Módulo 2 Desarrolla software con herramientas orientadas a la productividad', 'Submódulo 1 Emplea frameworks para el desarrollo de software', NULL, 3, 19),
(239, 'Módulo 2 Desarrolla software con herramientas orientadas a la productividad', 'Submódulo 2 Aplica metodologías ágiles para el desarrollo de software', NULL, 3, 19),
(240, 'Módulo 3 Administra bases de datos en un sistema de información', 'Submódulo 1 Implementa Base de Datos Relacionales en un sistema de información', NULL, 4, 19),
(241, 'Módulo 3 Administra bases de datos en un sistema de información', 'Submódulo 2 Implementa Base de Datos no Relacionales en un sistema de información', NULL, 4, 19),
(242, 'Módulo 4 Desarrolla aplicaciones web en un sistema de información', 'Submódulo 1 Construye aplicaciones web', NULL, 5, 19),
(243, 'Módulo 4 Desarrolla aplicaciones web en un sistema de información', 'Submódulo 2 Implementa aplicaciones web', NULL, 5, 19),
(244, 'Módulo 5 Desarrolla aplicaciones móviles multiplataforma', 'Submódulo 1 Diseña aplicaciones móviles multiplataforma', NULL, 6, 19),
(245, 'Módulo 5 Desarrolla aplicaciones móviles multiplataforma', 'Submódulo 2 Implementa aplicaciones móviles multiplataforma', NULL, 6, 19),
(246, 'Lengua y comunicación I', NULL, 1, 1, 1),
(247, 'Inglés I', NULL, 1, 1, 1),
(248, 'Pensamiento matemático I', NULL, 1, 1, 1),
(249, 'Cultura digital I', NULL, 1, 1, 1),
(250, 'La materia y sus interacciones', NULL, 1, 1, 1),
(251, 'Humanidades I', NULL, 1, 1, 1),
(252, 'Ciencias Sociales I', NULL, 1, 1, 1),
(253, 'Formación socioemocional I', NULL, 1, 1, 1),
(254, 'Lengua y comunicación II', NULL, 1, 2, 1),
(255, 'Inglés II', NULL, 1, 2, 1),
(256, 'Pensamiento matemático II', NULL, 1, 2, 1),
(257, 'Cultura digital II', NULL, 1, 2, 1),
(258, 'Conservación de la energía y sus interacciones con la materia', NULL, 1, 2, 1),
(259, 'Ciencias Sociales II', NULL, 1, 2, 1),
(260, 'Formación socioemocional II', NULL, 1, 2, 1),
(261, 'Lengua y comunicación III', NULL, 1, 3, 1),
(262, 'Inglés III', NULL, 1, 3, 1),
(263, 'Pensamiento matemático III', NULL, 1, 3, 1),
(264, 'Ecosistemas: interacciones, energía y dinámica', NULL, 1, 3, 1),
(265, 'Humanidades II', NULL, 1, 3, 1),
(266, 'Formación socioemocional III', NULL, 1, 3, 1),
(267, 'Inglés IV', NULL, 1, 4, 1),
(268, 'Temas selectos de matemáticas I', NULL, 1, 4, 1),
(269, 'Conciencia histórica I. Perspectivas del México antiguo en los contextos globales', NULL, 1, 4, 1),
(270, 'Reacciones químicas: conservación de la materia en la formación de nuevas sustancias', NULL, 1, 4, 1),
(271, 'Ciencias sociales III', NULL, 1, 4, 1),
(272, 'Formación socioemocional IV', NULL, 1, 4, 1),
(273, 'Inglés V', NULL, 1, 5, 1),
(274, 'Temas selectos de matemáticas II', NULL, 1, 5, 1),
(275, 'Conciencia histórica II. México durante el expansionismo capitalista', NULL, 1, 5, 1),
(276, 'La energía en los procesos de la vida diaria', NULL, 1, 5, 1),
(277, 'UAC fundamental extendida I', NULL, 1, 5, 1),
(278, 'Formación socioemocional V', NULL, 1, 5, 1),
(279, 'Temas selectos de matemáticas III', NULL, 1, 6, 1),
(280, 'Conciencia histórica III. La realidad actual en perspectiva histórica', NULL, 1, 6, 1),
(281, 'Organismos: estructuras y procesos. Herencia y evolución biológica', NULL, 1, 6, 1),
(282, 'Humanidades III', NULL, 1, 6, 1),
(283, 'UAC fundamental extendida II', NULL, 1, 6, 1),
(284, 'Formación socioemocional VI', NULL, 1, 6, 1),
(285, 'EDUCACION FISICA', NULL, 1, 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` bigint(20) NOT NULL,
  `asistencias_p1` int(11) DEFAULT NULL,
  `asistencias_p2` int(11) DEFAULT NULL,
  `asistencias_p3` int(11) DEFAULT NULL,
  `inasistencias_p1` int(11) DEFAULT NULL,
  `inasistencias_p2` int(11) DEFAULT NULL,
  `inasistencias_p3` int(11) DEFAULT NULL,
  `asistencia_total` double DEFAULT NULL,
  `id_alumno` bigint(20) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Disparadores `asistencias`
--
DELIMITER $$
CREATE TRIGGER `calculate_asistencia_total_before_insert` BEFORE INSERT ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_asistencias DOUBLE;
    DECLARE total_inasistencias DOUBLE;
    DECLARE asistencias_totales DOUBLE;

    SET total_asistencias = IFNULL(NEW.asistencias_p1, 0) + IFNULL(NEW.asistencias_p2, 0) + IFNULL(NEW.asistencias_p3, 0);
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);
    SET asistencias_totales = total_asistencias + total_inasistencias;

    IF asistencias_totales > 0 THEN
        SET NEW.asistencia_total = ROUND((total_asistencias * 100) / asistencias_totales, 2);
    ELSE
        SET NEW.asistencia_total = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_asistencia_total_before_update` BEFORE UPDATE ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_asistencias DOUBLE;
    DECLARE total_inasistencias DOUBLE;
    DECLARE asistencias_totales DOUBLE;

    SET total_asistencias = IFNULL(NEW.asistencias_p1, 0) + IFNULL(NEW.asistencias_p2, 0) + IFNULL(NEW.asistencias_p3, 0);
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);
    SET asistencias_totales = total_asistencias + total_inasistencias;

    IF asistencias_totales > 0 THEN
        SET NEW.asistencia_total = ROUND((total_asistencias * 100) / asistencias_totales, 2);
    ELSE
        SET NEW.asistencia_total = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_inasistencias_alerta_insert` AFTER INSERT ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_inasistencias INT;
    DECLARE asistencia_total_value DOUBLE;
    DECLARE full_name VARCHAR(255);
    DECLARE telefono_alumno_value VARCHAR(20);
    DECLARE tutor_nombre VARCHAR(255);
    DECLARE tutor_telefono VARCHAR(20);
    DECLARE alerta_existente INT;
    DECLARE nombre_asignaturas VARCHAR(250);
    DECLARE submodulo VARCHAR(250);
    DECLARE observaciones_alerta VARCHAR(400);

    -- Obtener el número total de inasistencias
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);

    -- Obtener el valor de asistencia_total
    SET asistencia_total_value = NEW.asistencia_total;

    -- Obtener el nombre completo del alumno y otros datos
    SELECT CONCAT(nombre_alumno, ' ', apellido_paterno, ' ', apellido_materno), telefono, nombre_tutor, telefono_tutor
    INTO full_name, telefono_alumno_value, tutor_nombre, tutor_telefono
    FROM alumnos
    WHERE id_alumno = NEW.id_alumno;

    -- Inicializar `observaciones_alerta` con un valor predeterminado
    SET observaciones_alerta = 'No se pudo obtener la información de la asignatura.';

    -- Obtener el nombre de la asignatura y los submódulos basándose en el `id_asignatura` de la fila actualizada
    SELECT IFNULL(nombre_asignatura, 'Desconocida') AS nombre_asignaturas, IFNULL(submodulos, '') AS submodulo
    INTO nombre_asignaturas, submodulo
    FROM asignatura
    WHERE id_asignatura = NEW.id_asignatura;

    -- Construir las observaciones de la alerta
    SET observaciones_alerta = CONCAT('Inasistencias: El alumno ', full_name, ' ha acumulado ', total_inasistencias, ' inasistencias en la asignatura ', nombre_asignaturas, ' (', submodulo, ').');

    -- Verificar si ya existe una alerta similar para evitar duplicados
    SELECT COUNT(*) INTO alerta_existente
    FROM alertas
    WHERE id_alumno = NEW.id_alumno AND tipo_alerta IN ('Inasistencias', 'Asistencia Baja');

    -- Insertar alerta solo si no existe ninguna previa similar
    IF alerta_existente = 0 THEN
        IF total_inasistencias >= 3 AND asistencia_total_value <= 79 THEN
            INSERT INTO alertas (id_alerta, tipo_alerta, situacion, observaciones, alumno, id_alumno, id_usuario, telefono_alumno, nombre_tutor, telefono_tutor)
            VALUES (NULL, 'ACADEMICO: REPROBACION, FALTAS', 'NO ATENDIDA', observaciones_alerta, full_name, NEW.id_alumno, 1, telefono_alumno_value, tutor_nombre, tutor_telefono);
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_inasistencias_alerta_update` AFTER UPDATE ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_inasistencias INT;
    DECLARE asistencia_total_value DOUBLE;
    DECLARE full_name VARCHAR(255);
    DECLARE telefono_alumno_value VARCHAR(20);
    DECLARE tutor_nombre VARCHAR(255);
    DECLARE tutor_telefono VARCHAR(20);
    DECLARE alerta_existente INT;
    DECLARE nombre_asignaturas VARCHAR(250);
    DECLARE submodulo VARCHAR(250);
    DECLARE observaciones_alerta VARCHAR(400);

    -- Obtener el número total de inasistencias
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);

    -- Obtener el valor de asistencia_total
    SET asistencia_total_value = NEW.asistencia_total;

    -- Obtener el nombre completo del alumno y otros datos
    SELECT CONCAT(nombre_alumno, ' ', apellido_paterno, ' ', apellido_materno), telefono, nombre_tutor, telefono_tutor
    INTO full_name, telefono_alumno_value, tutor_nombre, tutor_telefono
    FROM alumnos
    WHERE id_alumno = NEW.id_alumno;

    -- Inicializar `observaciones_alerta` con un valor predeterminado
    SET observaciones_alerta = 'No se pudo obtener la información de la asignatura.';

    -- Obtener el nombre de la asignatura y los submódulos basándose en el `id_asignatura` de la fila actualizada
    SELECT IFNULL(nombre_asignatura, 'Desconocida') AS nombre_asignaturas, IFNULL(submodulos, '') AS submodulo
    INTO nombre_asignaturas, submodulo
    FROM asignatura
    WHERE id_asignatura = NEW.id_asignatura;

    -- Construir las observaciones de la alerta
    SET observaciones_alerta = CONCAT('Inasistencias: El alumno ', full_name, ' ha acumulado ', total_inasistencias, ' inasistencias en la asignatura ', nombre_asignaturas, ' (', submodulo, ').');

    -- Verificar si ya existe una alerta similar para evitar duplicados
    SELECT COUNT(*) INTO alerta_existente
    FROM alertas
    WHERE id_alumno = NEW.id_alumno AND tipo_alerta IN ('Inasistencias', 'Asistencia Baja');

    -- Insertar alerta solo si no existe ninguna previa similar
    IF alerta_existente = 0 THEN
        IF total_inasistencias >= 3 AND asistencia_total_value <= 79 THEN
            INSERT INTO alertas (id_alerta, tipo_alerta, situacion, observaciones, alumno, id_alumno, id_usuario, telefono_alumno, nombre_tutor, telefono_tutor)
            VALUES (NULL, 'ACADEMICO: REPROBACION, FALTAS', 'NO ATENDIDA', observaciones_alerta, full_name, NEW.id_alumno, 1, telefono_alumno_value, tutor_nombre, tutor_telefono);
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL,
  `calificacion_p1` double DEFAULT NULL,
  `calificacion_p2` double DEFAULT NULL,
  `calificacion_p3` double DEFAULT NULL,
  `calificacion_final` double DEFAULT NULL,
  `acreditacion` varchar(5) DEFAULT NULL,
  `id_alumno` bigint(20) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `id_asistencia` bigint(20) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `calificaciones`
--
DELIMITER $$
CREATE TRIGGER `calculate_acreditacion_before_insert` BEFORE INSERT ON `calificaciones` FOR EACH ROW BEGIN
    DECLARE asistencias_total DOUBLE;

    -- Obtener asistencia_total de la tabla asistencias
    SELECT asistencia_total INTO asistencias_total
    FROM asistencias
    WHERE id_alumno = NEW.id_alumno AND id_asignatura = NEW.id_asignatura;

    -- Validar y asignar el valor de acreditacion
    IF asistencias_total IS NOT NULL THEN
        IF NEW.calificacion_final >= 6 AND asistencias_total >= 80 THEN
            SET NEW.acreditacion = 'A';
        ELSEIF NEW.calificacion_final >= 6 AND asistencias_total < 80 THEN
            SET NEW.acreditacion = 'NP';
        ELSEIF NEW.calificacion_final < 6 AND asistencias_total >= 80 THEN
            SET NEW.acreditacion = 'NA';
        ELSE
            SET NEW.acreditacion = 'NA'; -- Si ninguna condición se cumple, asignar NA por defecto
        END IF;
    ELSE
        SET NEW.acreditacion = 'NA'; -- Manejo si no se encuentra el valor de asistencia_total
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_acreditacion_before_update` BEFORE UPDATE ON `calificaciones` FOR EACH ROW BEGIN
    DECLARE asistencias_total DOUBLE;

    -- Obtener asistencia_total de la tabla asistencias
    SELECT asistencia_total INTO asistencias_total
    FROM asistencias
    WHERE id_alumno = NEW.id_alumno AND id_asignatura = NEW.id_asignatura;

    -- Validar y asignar el valor de acreditacion
    IF asistencias_total IS NOT NULL THEN
        IF NEW.calificacion_final >= 6 AND asistencias_total >= 80 THEN
            SET NEW.acreditacion = 'A';
        ELSEIF NEW.calificacion_final >= 6 AND asistencias_total < 80 THEN
            SET NEW.acreditacion = 'NP';
        ELSEIF NEW.calificacion_final < 6 AND asistencias_total >= 80 THEN
            SET NEW.acreditacion = 'NA';
        ELSE
            SET NEW.acreditacion = 'NA'; -- Si ninguna condición se cumple, asignar NA por defecto
        END IF;
    ELSE
        SET NEW.acreditacion = 'NA'; -- Manejo si no se encuentra el valor de asistencia_total
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_calificacion_final_before_insert` BEFORE INSERT ON `calificaciones` FOR EACH ROW BEGIN
    DECLARE total_calificaciones DOUBLE;
    DECLARE calificaciones_count INT;
    DECLARE calificacion_final DOUBLE;

    -- Cuenta cuántas calificaciones no son NULL
    SET calificaciones_count = (IF(NEW.calificacion_p1 IS NOT NULL, 1, 0) +
                                IF(NEW.calificacion_p2 IS NOT NULL, 1, 0) +
                                IF(NEW.calificacion_p3 IS NOT NULL, 1, 0));

    -- Suma las calificaciones, considerando que NULL es 0
    SET total_calificaciones = IFNULL(NEW.calificacion_p1, 0) + 
                               IFNULL(NEW.calificacion_p2, 0) + 
                               IFNULL(NEW.calificacion_p3, 0);

    -- Calcula la calificación final solo si hay calificaciones válidas
    IF calificaciones_count > 0 THEN
        SET calificacion_final = ROUND(total_calificaciones / calificaciones_count, 2);
    ELSE
        SET calificacion_final = 0;
    END IF;

    -- Asigna el valor calculado a la nueva fila
    SET NEW.calificacion_final = calificacion_final;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_calificacion_final_before_update` BEFORE UPDATE ON `calificaciones` FOR EACH ROW BEGIN
    DECLARE total_calificaciones DOUBLE;
    DECLARE calificaciones_count INT;
    DECLARE calificacion_final DOUBLE;

    -- Cuenta cuántas calificaciones no son NULL
    SET calificaciones_count = (IF(NEW.calificacion_p1 IS NOT NULL, 1, 0) +
                                IF(NEW.calificacion_p2 IS NOT NULL, 1, 0) +
                                IF(NEW.calificacion_p3 IS NOT NULL, 1, 0));

    -- Suma las calificaciones, considerando que NULL es 0
    SET total_calificaciones = IFNULL(NEW.calificacion_p1, 0) + 
                               IFNULL(NEW.calificacion_p2, 0) + 
                               IFNULL(NEW.calificacion_p3, 0);

    -- Calcula la calificación final solo si hay calificaciones válidas
    IF calificaciones_count > 0 THEN
        SET calificacion_final = ROUND(total_calificaciones / calificaciones_count, 2);
    ELSE
        SET calificacion_final = 0;
    END IF;

    -- Asigna el valor calculado a la nueva fila
    SET NEW.calificacion_final = calificacion_final;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_botones`
--

CREATE TABLE `config_botones` (
  `id` int(11) NOT NULL,
  `boton` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `config_botones`
--

INSERT INTO `config_botones` (`id`, `boton`, `estado`) VALUES
(7, 'p3', 0),
(6, 'p2', 0),
(5, 'p1', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo_estudios` varchar(50) DEFAULT NULL,
  `nombre_docente` varchar(30) DEFAULT NULL,
  `apellido_paterno` varchar(20) DEFAULT NULL,
  `apellido_materno` varchar(20) DEFAULT NULL,
  `RFC` varchar(13) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `grupo_edad` varchar(20) DEFAULT NULL,
  `tipo_plaza` varchar(20) DEFAULT NULL,
  `formacion_academica` varchar(50) DEFAULT NULL,
  `antiguedad` varchar(30) DEFAULT NULL,
  `nivel_estudios` varchar(30) DEFAULT NULL,
  `beca` tinyint(4) DEFAULT NULL,
  `discapacidad` tinyint(4) DEFAULT NULL,
  `lengua_indigena` tinyint(4) DEFAULT NULL,
  `funcion` varchar(100) DEFAULT NULL,
  `estudio_actual` varchar(100) DEFAULT NULL,
  `pais_estudio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `docentes`
--
DELIMITER $$
CREATE TRIGGER `actualizar_usuario_email_docente` BEFORE UPDATE ON `docentes` FOR EACH ROW BEGIN
    -- Verificar si el email ha cambiado
    IF NEW.email <> OLD.email THEN
        -- Actualizar el usuario correspondiente en la tabla usuarios con el nuevo email
        UPDATE usuarios
        SET usuario = NEW.email
        WHERE id_usuario = NEW.id_usuario;
    END IF;
END
$$
DELIMITER ;
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

CREATE TABLE `especialidad` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(100) NOT NULL,
  `id_tipo_programa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre_especialidad`, `id_tipo_programa`) VALUES
(1, 'BACHILLERATO TECNOLÓGICO', 1),
(2, 'BACHILLERATO TECNOLÓGICO', 2),
(11, 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 1),
(12, 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 2),
(13, 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA', 1),
(14, 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA', 2),
(15, 'TÉCNICO FORESTAL', 1),
(16, 'TÉCNICO FORESTAL', 2),
(17, 'TÉCNICO EN OFIMÁTICA', 1),
(18, 'TÉCNICO EN OFIMÁTICA', 2),
(19, 'TÉCNICO EN PROGRAMACIÓN', 1),
(20, 'TÉCNICO EN PROGRAMACIÓN', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `nombre_grupo` varchar(2) NOT NULL,
  `id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `id_semestre`, `nombre_grupo`, `id_especialidad`) VALUES
(1, 1, 'X', 1),
(2, 1, 'A', 1),
(3, 1, 'B', 1),
(4, 1, 'C', 1),
(5, 1, 'D', 1),
(6, 1, 'E', 1),
(7, 1, 'F', 1),
(8, 1, 'G', 1),
(9, 1, 'H', 1),
(10, 1, 'I', 1),
(11, 1, 'J', 1),
(12, 2, 'A', 11),
(13, 2, 'B', 11),
(14, 2, 'C', 11),
(15, 2, 'A', 13),
(16, 2, 'B', 13),
(17, 2, 'C', 13),
(18, 2, 'A', 15),
(19, 2, 'B', 15),
(20, 2, 'C', 15),
(21, 2, 'A', 17),
(22, 2, 'B', 17),
(23, 2, 'C', 17),
(24, 2, 'A', 19),
(25, 2, 'B', 19),
(26, 2, 'C', 19),
(27, 3, 'A', 11),
(28, 3, 'B', 11),
(29, 3, 'C', 11),
(30, 3, 'A', 13),
(31, 3, 'B', 13),
(32, 3, 'C', 13),
(33, 3, 'A', 15),
(34, 3, 'B', 15),
(35, 3, 'C', 15),
(36, 3, 'A', 17),
(37, 3, 'B', 17),
(38, 3, 'C', 17),
(39, 3, 'A', 19),
(40, 3, 'B', 19),
(41, 3, 'C', 19),
(42, 4, 'A', 11),
(43, 4, 'B', 11),
(44, 4, 'C', 11),
(45, 4, 'A', 13),
(46, 4, 'B', 13),
(47, 4, 'C', 13),
(48, 4, 'A', 15),
(49, 4, 'B', 15),
(50, 4, 'C', 15),
(51, 4, 'A', 17),
(52, 4, 'B', 17),
(53, 4, 'C', 17),
(54, 4, 'A', 19),
(55, 4, 'B', 19),
(56, 4, 'C', 19),
(57, 4, 'A', 12),
(58, 4, 'B', 12),
(59, 4, 'C', 12),
(60, 4, 'A', 14),
(61, 4, 'B', 14),
(62, 4, 'C', 14),
(63, 4, 'A', 16),
(64, 4, 'B', 16),
(65, 4, 'C', 16),
(66, 4, 'A', 18),
(67, 4, 'B', 18),
(68, 4, 'C', 18),
(69, 4, 'A', 20),
(70, 4, 'B', 20),
(71, 4, 'C', 20),
(72, 5, 'A', 11),
(73, 5, 'B', 11),
(74, 5, 'C', 11),
(75, 5, 'A', 13),
(76, 5, 'B', 13),
(77, 5, 'C', 13),
(78, 5, 'A', 15),
(79, 5, 'B', 15),
(80, 5, 'C', 15),
(81, 5, 'A', 17),
(82, 5, 'B', 17),
(83, 5, 'C', 17),
(84, 5, 'A', 19),
(85, 5, 'B', 19),
(86, 5, 'C', 19),
(87, 5, 'A', 12),
(88, 5, 'B', 12),
(89, 5, 'C', 12),
(90, 5, 'A', 14),
(91, 5, 'B', 14),
(92, 5, 'C', 14),
(93, 5, 'A', 16),
(94, 5, 'B', 16),
(95, 5, 'C', 16),
(96, 5, 'A', 18),
(97, 5, 'B', 18),
(98, 5, 'C', 18),
(99, 5, 'A', 20),
(100, 5, 'B', 20),
(101, 5, 'C', 20),
(102, 6, 'A', 11),
(103, 6, 'B', 11),
(104, 6, 'C', 11),
(105, 6, 'A', 13),
(106, 6, 'B', 13),
(107, 6, 'C', 13),
(108, 6, 'A', 15),
(109, 6, 'B', 15),
(110, 6, 'C', 15),
(111, 6, 'A', 17),
(112, 6, 'B', 17),
(113, 6, 'C', 17),
(114, 6, 'A', 19),
(115, 6, 'B', 19),
(116, 6, 'C', 19),
(117, 6, 'A', 12),
(118, 6, 'B', 12),
(119, 6, 'C', 12),
(120, 6, 'A', 14),
(121, 6, 'B', 14),
(122, 6, 'C', 14),
(123, 6, 'A', 16),
(124, 6, 'B', 16),
(125, 6, 'C', 16),
(126, 6, 'A', 18),
(127, 6, 'B', 18),
(128, 6, 'C', 18),
(129, 6, 'A', 20),
(130, 6, 'B', 20),
(131, 6, 'C', 20),
(208, 1, 'X', 2),
(209, 1, 'A', 2),
(210, 1, 'B', 2),
(211, 1, 'C', 2),
(212, 1, 'D', 2),
(213, 1, 'E', 2),
(214, 1, 'F', 2),
(215, 1, 'G', 2),
(216, 1, 'H', 2),
(217, 1, 'I', 2),
(218, 1, 'J', 2),
(219, 2, 'A', 12),
(220, 2, 'B', 12),
(221, 2, 'C', 12),
(222, 2, 'A', 14),
(223, 2, 'B', 14),
(224, 2, 'C', 14),
(225, 2, 'A', 16),
(226, 2, 'B', 16),
(227, 2, 'C', 16),
(228, 2, 'A', 18),
(229, 2, 'B', 18),
(230, 2, 'C', 18),
(231, 2, 'A', 20),
(232, 2, 'B', 20),
(233, 2, 'C', 20),
(234, 3, 'A', 12),
(235, 3, 'B', 12),
(236, 3, 'C', 12),
(237, 3, 'A', 14),
(238, 3, 'B', 14),
(239, 3, 'C', 14),
(240, 3, 'A', 16),
(241, 3, 'B', 16),
(242, 3, 'C', 16),
(243, 3, 'A', 18),
(244, 3, 'B', 18),
(245, 3, 'C', 18),
(246, 3, 'A', 20),
(247, 3, 'B', 20),
(248, 3, 'C', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `id_asignatura` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `lunes` varchar(50) DEFAULT NULL,
  `martes` varchar(50) DEFAULT NULL,
  `miercoles` varchar(50) DEFAULT NULL,
  `jueves` varchar(50) DEFAULT NULL,
  `viernes` varchar(50) DEFAULT NULL,
  `sabado` varchar(50) DEFAULT NULL,
  `horas_semanales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` bigint(20) NOT NULL,
  `perfil` varchar(20) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `id_alerta` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semestre`
--

CREATE TABLE `semestre` (
  `id_semestre` int(11) NOT NULL,
  `nombre_semestre` varchar(5) NOT NULL,
  `numero_semestre` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `semestre`
--

INSERT INTO `semestre` (`id_semestre`, `nombre_semestre`, `numero_semestre`) VALUES
(1, 'I', '1ro'),
(2, 'II', '2do'),
(3, 'III', '3ro'),
(4, 'IV', '4to'),
(5, 'V', '5to'),
(6, 'VI', '6to');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_programa`
--

CREATE TABLE `tipo_programa` (
  `id_tipo_programa` int(11) NOT NULL,
  `tipo_programa` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_programa`
--

INSERT INTO `tipo_programa` (`id_tipo_programa`, `tipo_programa`) VALUES
(1, 'MCCEMS'),
(2, 'Acuerdo 653');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `perfil` varchar(100) NOT NULL,
  `usuario` varchar(55) DEFAULT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades_complementarias`
--
ALTER TABLE `actividades_complementarias`
  ADD PRIMARY KEY (`id_actividad_complementaria`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_usuario_2` (`id_usuario`),
  ADD KEY `id_alumno_2` (`id_alumno`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_semestre` (`id_semestre`);

--
-- Indices de la tabla `archivos_subidos`
--
ALTER TABLE `archivos_subidos`
  ADD PRIMARY KEY (`id_archivo`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`id_asignatura`),
  ADD KEY `id_semestre` (`id_semestre`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_asignatura` (`id_asignatura`,`id_grupo`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_asignatura` (`id_asignatura`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_asistencia` (`id_asistencia`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `config_botones`
--
ALTER TABLE `config_botones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD KEY `id_tipo_programa` (`id_tipo_programa`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `id_especialidad` (`id_especialidad`),
  ADD KEY `id_semestre` (`id_semestre`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_asignatura` (`id_asignatura`,`id_grupo`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_alerta` (`id_alerta`);

--
-- Indices de la tabla `semestre`
--
ALTER TABLE `semestre`
  ADD PRIMARY KEY (`id_semestre`);

--
-- Indices de la tabla `tipo_programa`
--
ALTER TABLE `tipo_programa`
  ADD PRIMARY KEY (`id_tipo_programa`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades_complementarias`
--
ALTER TABLE `actividades_complementarias`
  MODIFY `id_actividad_complementaria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `archivos_subidos`
--
ALTER TABLE `archivos_subidos`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `id_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `config_botones`
--
ALTER TABLE `config_botones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `semestre`
--
ALTER TABLE `semestre`
  MODIFY `id_semestre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades_complementarias`
--
ALTER TABLE `actividades_complementarias`
  ADD CONSTRAINT `actividades_complementarias_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_3` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `alertas_ibfk_4` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`),
  ADD CONSTRAINT `alumnos_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `alumnos_ibfk_4` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Filtros para la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD CONSTRAINT `asignatura_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `asignatura_ibfk_2` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `calificaciones_ibfk_5` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `calificaciones_ibfk_6` FOREIGN KEY (`id_asistencia`) REFERENCES `asistencias` (`id_asistencia`),
  ADD CONSTRAINT `calificaciones_ibfk_7` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

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
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`id_alerta`) REFERENCES `alertas` (`id_alerta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
