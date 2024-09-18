-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 18-09-2024 a las 05:48:30
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
-- Base de datos: `u864743456_sedi_cbtf2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_complementarias`
--

DROP TABLE IF EXISTS `actividades_complementarias`;
CREATE TABLE IF NOT EXISTS `actividades_complementarias` (
  `id_actividad_complementaria` int NOT NULL AUTO_INCREMENT,
  `id_docente` int DEFAULT NULL,
  `actividad` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `detalles_actividad` varchar(200) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `lunes` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `martes` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `miercoles` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `jueves` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `viernes` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `sabado` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `horas_semanales` int DEFAULT NULL,
  PRIMARY KEY (`id_actividad_complementaria`),
  KEY `id_docente` (`id_docente`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

DROP TABLE IF EXISTS `alertas`;
CREATE TABLE IF NOT EXISTS `alertas` (
  `id_alerta` int NOT NULL AUTO_INCREMENT,
  `fecha_alerta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_alerta` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `situacion` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `observaciones` varchar(150) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `persona_reporta` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `alumno` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `condicionamiento` tinyint(1) DEFAULT NULL,
  `telefono_alumno` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_tutor` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
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
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_alerta`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_usuario_2` (`id_usuario`),
  KEY `id_alumno_2` (`id_alumno`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `alertas`
--
DROP TRIGGER IF EXISTS `trigger_notificaciones_alerta_insert`;
DELIMITER $$
CREATE TRIGGER `trigger_notificaciones_alerta_insert` AFTER INSERT ON `alertas` FOR EACH ROW BEGIN
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
        INSERT INTO notificaciones (perfil, mensaje, leido, fecha_creacion, id_alerta, id_usuario)
        VALUES ('Directivo', 
                CONCAT('Inasistencias - El alumno ', NEW.alumno, ' tiene ', 
                       SUBSTRING_INDEX(SUBSTRING_INDEX(NEW.observaciones, ' ', -2), ' ', 1), ' inasistencias'), 
                0, NOW(), NEW.id_alerta, directivo_id);
    END LOOP;

    -- Cerrar el cursor
    CLOSE directivo_cursor;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `matricula` bigint DEFAULT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_alumno` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `status` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `CURP` varchar(18) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `genero` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `fecha_naci` date DEFAULT NULL,
  `lugar_nacimiento` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `ayuda_espanol` tinyint(1) DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `secundaria_egreso` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `promedio_secundaria` double(4,2) DEFAULT NULL,
  `sangre` varchar(12) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `beca_bancomer` tinyint(1) DEFAULT NULL,
  `nss` tinyint(1) DEFAULT NULL,
  `nss_numero` bigint DEFAULT NULL,
  `issste` tinyint(1) DEFAULT NULL,
  `issste_numero` bigint DEFAULT NULL,
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
  `ticket` tinyint(1) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `id_grupo` int NOT NULL,
  `id_semestre` int NOT NULL,
  `generacion` varchar(15) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_alumno`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=710 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `alumnos`
--
DROP TRIGGER IF EXISTS `before_update_generacion`;
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
DROP TRIGGER IF EXISTS `insertar_nuevo_alumno`;
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

DROP TABLE IF EXISTS `archivos_subidos`;
CREATE TABLE IF NOT EXISTS `archivos_subidos` (
  `id_archivo` int NOT NULL AUTO_INCREMENT,
  `nombre_archivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_archivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` int NOT NULL AUTO_INCREMENT,
  `nombre_asignatura` varchar(250) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `submodulos` varchar(250) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `tronco_comun` tinyint DEFAULT NULL,
  `id_semestre` int NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre_asignatura`, `submodulos`, `tronco_comun`, `id_semestre`, `id_especialidad`) VALUES
(434, 'Módulo 1 Plantea actividades del área de Recursos Humanos en función a cada área de la organización', 'Submódulo 1 Distingue los diferentes tipos de empresa por su giro, áreas funcionales, documentación administrativa y recursos', NULL, 2, 12),
(435, 'Módulo 1 Plantea actividades del área de Recursos Humanos en función a cada área de la organización', 'Submódulo 2 Elabora estrategias para realizar las actividades de su área', NULL, 2, 12),
(436, 'Módulo 2 Integra el capital humano a la organización', 'Submódulo 1 Realiza el proceso de admisión y empleo', NULL, 3, 12),
(437, 'Módulo 2 Integra el capital humano a la organización', 'Submódulo 2 Contribuye a la integración y desarrollo del personal en la organización', NULL, 3, 12),
(438, 'Módulo 3 Asiste en el control y evaluación del desempeño del capital humano de la organización', 'Submódulo 1 Asiste en las actividades de capacitación para el desarrollo del capital humano', NULL, 4, 12),
(439, 'Módulo 3 Asiste en el control y evaluación del desempeño del capital humano de la organización', 'Submódulo 2 Evalúa el desempeño de la organización utilizando herramientas de calidad', NULL, 4, 12),
(440, 'Módulo 4 Controla los procesos y servicios de higiene y seguridad del capital humano en la organización', 'Submódulo 1 Supervisa el cumplimiento de las medidas de higiene y seguridad en la organización', NULL, 5, 12),
(441, 'Módulo 4 Controla los procesos y servicios de higiene y seguridad del capital humano en la organización', 'Submódulo 2 Supervisa el cumplimiento de tareas y procesos para evaluar la productividad en la organización', NULL, 5, 12),
(442, 'Módulo 5 Determina las remuneraciones al capital humano de la organización', 'Submódulo 1 Determina la nómina del personal de la organización tomando en cuenta la normatividad laboral', NULL, 6, 12),
(443, 'Módulo 5 Determina las remuneraciones al capital humano de la organización', 'Submódulo 2 Determina remuneraciones del personal en situaciones extraordinarias', NULL, 6, 12),
(444, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 1 Elabora dibujos a mano alzada de acuerdo con las necesidades del cliente', NULL, 2, 14),
(445, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 2 Elabora bocetos a mano alzada de acuerdo con la ergonomía y las necesidades del cliente', NULL, 2, 14),
(446, 'Módulo 1 Diseña muebles de madera utilizando técnicas manuales', 'Submódulo 3 Elabora proyecciones ortogonales e isométricas de acuerdo con las necesidades del cliente', NULL, 2, 14),
(447, 'Módulo 2 Diseña muebles de madera utilizando software', 'Submódulo 1 Diseña muebles de madera asistido por computadora', NULL, 3, 14),
(448, 'Módulo 2 Diseña muebles de madera utilizando software', 'Submódulo 2 Produce modelos de muebles de madera considerando los diferentes materiales', NULL, 3, 14),
(449, 'Módulo 3 Produce piezas y da mantenimiento preventivo a máquinas para la fabricación de muebles de madera', 'Submódulo 1 Elabora piezas para la fabricación de muebles de madera', NULL, 4, 14),
(450, 'Módulo 3 Produce piezas y da mantenimiento preventivo a máquinas para la fabricación de muebles de madera', 'Submódulo 2 Realiza mantenimiento preventivo de maquinaria, herramientas y equipo para fabricación de muebles de madera', NULL, 4, 14),
(451, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 1 Fabrica piezas de acuerdo con las especificaciones del diseño', NULL, 5, 14),
(452, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 2 Ensambla, arma piezas y componentes de muebles de madera', NULL, 5, 14),
(453, 'Módulo 4 Fabrica, ensambla y aplica acabados en muebles de madera', 'Submódulo 3 Aplica acabados en muebles de madera', NULL, 5, 14),
(454, 'Módulo 6 Desarrolla un proyecto emprendedor para fabricación de muebles de madera', 'Submódulo 1 Organiza los procesos industriales en muebles de madera', NULL, 6, 14),
(455, 'Módulo 6 Desarrolla un proyecto emprendedor para fabricación de muebles de madera', 'Submódulo 2 Aplica las etapas del proceso administrativo para el desarrollo del proyecto emprendedor', NULL, 6, 14),
(456, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 1 Recolecta y almacena germoplasma', NULL, 2, 16),
(457, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 2 Establece, acondiciona y mantiene viveros para producir planta', NULL, 2, 16),
(458, 'Módulo 1 Produce y establece planta forestal', 'Submódulo 3 Participa en el establecimiento y mantenimiento de plantaciones', NULL, 2, 16),
(459, 'Módulo 2 Participa en el cultivo y en la protección de los recurso forestales', 'Submódulo 1 Realiza actividades de medición forestal para obtener el inventario forestal', NULL, 3, 16),
(460, 'Módulo 2 Participa en el cultivo y en la protección de los recurso forestales', 'Submódulo 2 Aplica tratamientos silvícolas y actividades de protección forestal', NULL, 3, 16),
(461, 'Módulo 3 Aprovecha los recurso forestales', 'Submódulo 1 Aplica actividades de aprovechamiento forestal maderable', NULL, 4, 16),
(462, 'Módulo 3 Aprovecha los recurso forestales', 'Submódulo 2 Participa en el manejo y aprovechamiento de los recursos forestales no maderables', NULL, 4, 16),
(463, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 1 Industrializa madera en rollo', NULL, 5, 16),
(464, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 2 Seca y preserva madera aserrada', NULL, 5, 16),
(465, 'Módulo 4 Procesa la transformación de madera', 'Submódulo 3 Planea proyectos productivos forestales estudiantiles', NULL, 5, 16),
(466, 'Módulo 5 Colabora en la ejecución de proyectos de servicios ambientales', 'Submódulo 1 Participa en actividades de ecoturismo, captura de carbono, servicios hidrológicos y conservación de suelos', NULL, 6, 16),
(467, 'Módulo 5 Colabora en la ejecución de proyectos de servicios ambientales', 'Submódulo 2 Ejecuta proyectos productivos forestales estudiantiles', NULL, 6, 16),
(468, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 1 Instala y configura equipo de cómputo y periféricos', NULL, 2, 18),
(469, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 2 Instala y configura sistemas operativos y aplicaciones de la ofimática', NULL, 2, 18),
(470, 'Módulo 1 Gestiona hardware y software de la ofimática', 'Submódulo 3 Gestiona archivos y dispositivos ofimáticos', NULL, 2, 18),
(471, 'Módulo 2 Gestiona información de manera local', 'Submódulo 1 Gestiona información mediante el uso de procesadores de texto', NULL, 3, 18),
(472, 'Módulo 2 Gestiona información de manera local', 'Submódulo 2 Gestiona información mediante el uso de hojas de cálculo', NULL, 3, 18),
(473, 'Módulo 2 Gestiona información de manera local', 'Submódulo 3 Gestiona información mediante el uso de software de presentaciones', NULL, 3, 18),
(474, 'Módulo 3 Gestiona información de manera remota', 'Submódulo 1 Gestiona información mediante el uso de software en línea', NULL, 4, 18),
(475, 'Módulo 3 Gestiona información de manera remota', 'Submódulo 2 Gestiona recursos mediante el uso de redes de computadoras', NULL, 4, 18),
(476, 'Módulo 4 Diseña y gestiona bases de datos ofimáticas', 'Submódulo 1 Diseña bases de datos ofimáticas', NULL, 5, 18),
(477, 'Módulo 4 Diseña y gestiona bases de datos ofimáticas', 'Submódulo 2 Gestiona información mediante el uso de sistemas manejadores de bases de datos ofimáticas', NULL, 5, 18),
(478, 'Módulo 5 Establece comunicación ofimática', 'Submódulo 1 Gestiona información a través de plataformas digitales', NULL, 6, 18),
(479, 'Módulo 5 Establece comunicación ofimática', 'Submódulo 2 Establece comunicación y gestiona información mediante el uso de dispositivos móviles', NULL, 6, 18),
(480, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 1 Construye algoritmos para la solución de problemas', NULL, 2, 20),
(481, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 2 Aplica estructuras de control con un lenguaje de programación', NULL, 2, 20),
(482, 'Módulo 1 Desarrolla software de aplicación con programación estructurada', 'Submódulo 3 Aplica estructuras de datos con un lenguaje de programación', NULL, 2, 20),
(483, 'Módulo 2 Aplica metodologías de desarrollo de software con herramientas de programación visual', 'Submódulo 1 Aplica la metodología espiral con programación orientada a objetos', NULL, 3, 20),
(484, 'Módulo 2 Aplica metodologías de desarrollo de software con herramientas de programación visual', 'Submódulo 2 Aplica la metodología de desarrollo rápido de aplicaciones con programación orientada a eventos', NULL, 3, 20),
(485, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 1 Construye páginas Web', NULL, 4, 20),
(486, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 2 Desarrolla aplicaciones que se ejecutan en el cliente', NULL, 4, 20),
(487, 'Módulo 3 Desarrolla aplicaciones Web', 'Submódulo 3 Desarrolla aplicaciones que se ejecutan en el servidor', NULL, 4, 20),
(488, 'Módulo 4 Desarrolla software de aplicación Web con almacenamiento persistente de datos', 'Submódulo 1 Construye bases de datos para aplicaciones Web', NULL, 5, 20),
(489, 'Módulo 4 Desarrolla software de aplicación Web con almacenamiento persistente de datos', 'Submódulo 2 Desarrolla aplicaciones Web con conexión a bases de datos', NULL, 5, 20),
(490, 'Módulo 5 Desarrolla aplicaciones para dispositivos móviles', 'Submódulo 1 Desarrolla aplicaciones móviles para Android', NULL, 6, 20),
(491, 'Módulo 5 Desarrolla aplicaciones para dispositivos móviles', 'Submódulo 2 Desarrolla aplicaciones móviles para IOS', NULL, 6, 20),
(492, 'ÁLGEBRA', NULL, 1, 1, 2),
(493, 'QUÍMICA I', NULL, 1, 1, 2),
(494, 'TECNOLOGÍAS DE LA INFORMACIÓN Y LA COMUNICACIÓN', NULL, 1, 1, 2),
(495, 'LÓGICA', NULL, 1, 1, 2),
(496, 'LECTURA, EXPRESIÓN ORAL Y ESCRITA I', NULL, 1, 1, 2),
(497, 'INGLÉS I', NULL, 1, 1, 2),
(498, 'GEOMETRÍA Y TRIGONOMETRÍA', NULL, 1, 2, 2),
(499, 'LECTURA, EXPRESIÓN ORAL Y ESCRITA II', NULL, 1, 2, 2),
(500, 'QUÍMICA II', NULL, 1, 2, 2),
(501, 'INGLÉS II', NULL, 1, 2, 2),
(502, 'GEOMETRÍA ANALÍTICA', NULL, 1, 3, 2),
(503, 'BIOLOGÍA', NULL, 1, 3, 2),
(504, 'ÉTICA', NULL, 1, 3, 2),
(505, 'INGLÉS III', NULL, 1, 3, 2),
(506, 'CÁLCULO DIFERENCIAL', NULL, 1, 4, 2),
(507, 'FÍSICA I', NULL, 1, 4, 2),
(508, 'ECOLOGÍA', NULL, 1, 4, 2),
(509, 'INGLÉS IV', NULL, 1, 4, 2),
(510, 'CÁLCULO INTEGRAL', NULL, 1, 5, 2),
(511, 'FÍSICA II', NULL, 1, 5, 2),
(512, 'CIENCIA, TECNOLOGÍA, SOCIEDAD Y VALORES', NULL, 1, 5, 2),
(513, 'INGLÉS V', NULL, 1, 5, 2),
(514, 'PROBABILIDAD Y ESTADÍSTICA', NULL, 1, 6, 2),
(515, 'TEMAS DE FILOSOFIA', NULL, 1, 6, 2),
(516, 'Módulo 1 Gestiona trámites administrativos del área de recursos humanos', 'Submódulo 1 Ejecuta procedimientos administrativos del área de recursos humanos', NULL, 2, 11),
(517, 'Módulo 1 Gestiona trámites administrativos del área de recursos humanos', 'Submódulo 2 Gestiona documentación del área de recursos humanos', NULL, 2, 11),
(518, 'Módulo 2 Integra el talento humano en la organización', 'Submódulo 1 Gestiona el proceso de reclutamiento, selección y admisión del talento humano', NULL, 3, 11),
(519, 'Módulo 2 Integra el talento humano en la organización', 'Submódulo 2 Gestiona los procesos de inducción y permanencia del talento humano', NULL, 3, 11),
(520, 'Módulo 3 Implementa plan de desarrollo del talento humano', 'Submódulo 1 Gestiona los procesos de capacitación para el desarrollo del talento humano', NULL, 4, 11),
(521, 'Módulo 3 Implementa plan de desarrollo del talento humano', 'Submódulo 2 Promueve condiciones de trabajo saludables en la organización', NULL, 4, 11),
(522, 'Módulo 4 Evalúa el desempeño del talento humano', 'Submódulo 1 Gestiona la aplicación de la evaluación del desempeño humano', NULL, 5, 11),
(523, 'Módulo 4 Evalúa el desempeño del talento humano', 'Submódulo 2 Mide el desempeño del talento humano', NULL, 5, 11),
(524, 'Módulo 5 Maneja nómina y compensaciones del talento humano', 'Submódulo 1 - Auxilia en el cálculo de la nómina ordinaria', NULL, 6, 11),
(525, 'Módulo 5 Maneja nómina y compensaciones del talento humano', 'Submódulo 2 - Auxilia en el cálculo de la nómina extraordinaria', NULL, 6, 11),
(526, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 1 Diseña software de sistemas informáticos', NULL, 2, 19),
(527, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 2 Codifica software de sistemas informáticos', NULL, 2, 19),
(528, 'Módulo 1 Desarrolla software de sistemas informáticos', 'Submódulo 3 Implementa software de sistemas informáticos', NULL, 2, 19),
(529, 'Módulo 2 Desarrolla software con herramientas orientadas a la productividad', 'Submódulo 1 Emplea frameworks para el desarrollo de software', NULL, 3, 19),
(530, 'Módulo 2 Desarrolla software con herramientas orientadas a la productividad', 'Submódulo 2 Aplica metodologías ágiles para el desarrollo de software', NULL, 3, 19),
(531, 'Módulo 3 Administra bases de datos en un sistema de información', 'Submódulo 1 Implementa Base de Datos Relacionales en un sistema de información', NULL, 4, 19),
(532, 'Módulo 3 Administra bases de datos en un sistema de información', 'Submódulo 2 Implementa Base de Datos no Relacionales en un sistema de información', NULL, 4, 19),
(533, 'Módulo 4 Desarrolla aplicaciones web en un sistema de información', 'Submódulo 1 Construye aplicaciones web', NULL, 5, 19),
(534, 'Módulo 4 Desarrolla aplicaciones web en un sistema de información', 'Submódulo 2 Implementa aplicaciones web', NULL, 5, 19),
(535, 'Módulo 5 Desarrolla aplicaciones móviles multiplataforma', 'Submódulo 1 Diseña aplicaciones móviles multiplataforma', NULL, 6, 19),
(536, 'Módulo 5 Desarrolla aplicaciones móviles multiplataforma', 'Submódulo 2 Implementa aplicaciones móviles multiplataforma', NULL, 6, 19),
(537, 'Lengua y comunicación I', NULL, 1, 1, 1),
(538, 'Inglés I', NULL, 1, 1, 1),
(539, 'Pensamiento matemático I', NULL, 1, 1, 1),
(540, 'Cultura digital I', NULL, 1, 1, 1),
(541, 'La materia y sus interacciones', NULL, 1, 1, 1),
(542, 'Humanidades I', NULL, 1, 1, 1),
(543, 'Ciencias Sociales I', NULL, 1, 1, 1),
(544, 'Formación socioemocional I', NULL, 1, 1, 1),
(545, 'Lengua y comunicación II', NULL, 1, 2, 1),
(546, 'Inglés II', NULL, 1, 2, 1),
(547, 'Pensamiento matemático II', NULL, 1, 2, 1),
(548, 'Cultura digital II', NULL, 1, 2, 1),
(549, 'Conservación de la energía y sus interacciones con la materia', NULL, 1, 2, 1),
(550, 'Ciencias Sociales II', NULL, 1, 2, 1),
(551, 'Formación socioemocional II', NULL, 1, 2, 1),
(552, 'Lengua y comunicación III', NULL, 1, 3, 1),
(553, 'Inglés III', NULL, 1, 3, 1),
(554, 'Pensamiento matemático III', NULL, 1, 3, 1),
(555, 'Ecosistemas: interacciones, energía y dinámica', NULL, 1, 3, 1),
(556, 'Humanidades II', NULL, 1, 3, 1),
(557, 'Formación socioemocional III', NULL, 1, 3, 1),
(558, 'Inglés IV', NULL, 1, 4, 1),
(559, 'Temas selectos de matemáticas I', NULL, 1, 4, 1),
(560, 'Conciencia histórica I. Perspectivas del México antiguo en los contextos globales', NULL, 1, 4, 1),
(561, 'Reacciones químicas: conservación de la materia en la formación de nuevas sustancias', NULL, 1, 4, 1),
(562, 'Ciencias sociales III', NULL, 1, 4, 1),
(563, 'Formación socioemocional IV', NULL, 1, 4, 1),
(564, 'Inglés V', NULL, 1, 5, 1),
(565, 'Temas selectos de matemáticas II', NULL, 1, 5, 1),
(566, 'Conciencia histórica II. México durante el expansionismo capitalista', NULL, 1, 5, 1),
(567, 'La energía en los procesos de la vida diaria', NULL, 1, 5, 1),
(568, 'UAC fundamental extendida I', NULL, 1, 5, 1),
(569, 'Formación socioemocional V', NULL, 1, 5, 1),
(570, 'Temas selectos de matemáticas III', NULL, 1, 6, 1),
(571, 'Conciencia histórica III. La realidad actual en perspectiva histórica', NULL, 1, 6, 1),
(572, 'Organismos: estructuras y procesos. Herencia y evolución biológica', NULL, 1, 6, 1),
(573, 'Humanidades III', NULL, 1, 6, 1),
(574, 'UAC fundamental extendida II', NULL, 1, 6, 1),
(575, 'Formación socioemocional VI', NULL, 1, 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id_asistencia` bigint NOT NULL AUTO_INCREMENT,
  `asistencias_p1` int DEFAULT NULL,
  `asistencias_p2` int DEFAULT NULL,
  `asistencias_p3` int DEFAULT NULL,
  `inasistencias_p1` int DEFAULT NULL,
  `inasistencias_p2` int DEFAULT NULL,
  `inasistencias_p3` int DEFAULT NULL,
  `asistencia_total` double DEFAULT NULL,
  `id_alumno` bigint NOT NULL,
  `id_asignatura` int NOT NULL,
  `id_grupo` int NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  UNIQUE KEY `unique_asistencia` (`id_alumno`,`id_asignatura`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_asignatura` (`id_asignatura`),
  KEY `id_grupo` (`id_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=404 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Disparadores `asistencias`
--
DROP TRIGGER IF EXISTS `calculate_asistencia_total_before_insert`;
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
DROP TRIGGER IF EXISTS `calculate_asistencia_total_before_update`;
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
DROP TRIGGER IF EXISTS `trigger_inasistencias_alerta_insert`;
DELIMITER $$
CREATE TRIGGER `trigger_inasistencias_alerta_insert` AFTER INSERT ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_inasistencias INT;
    DECLARE asistencia_total_value DOUBLE;
    DECLARE full_name VARCHAR(255);
    DECLARE telefono_alumno_value VARCHAR(20);
    DECLARE tutor_nombre VARCHAR(255);
    DECLARE tutor_telefono VARCHAR(20);
    DECLARE alerta_existente INT;

    -- Obtener el número total de inasistencias
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);

    -- Obtener el valor de asistencia_total
    SET asistencia_total_value = NEW.asistencia_total;

    -- Obtener el nombre completo del alumno y otros datos
    SELECT CONCAT(a.nombre_alumno, ' ', a.apellido_paterno, ' ', a.apellido_materno), a.telefono, a.nombre_tutor, a.telefono_tutor
    INTO full_name, telefono_alumno_value, tutor_nombre, tutor_telefono
    FROM alumnos a
    WHERE a.id_alumno = NEW.id_alumno;

    -- Verificar si ya existe una alerta similar para evitar duplicados
    SELECT COUNT(*) INTO alerta_existente
    FROM alertas
    WHERE id_alumno = NEW.id_alumno AND tipo_alerta IN ('INASISTENCIAS', 'Asistencia Baja');

    -- Insertar alerta solo si no existe ninguna previa similar
    IF alerta_existente = 0 THEN
        IF total_inasistencias >= 3 OR asistencia_total_value <= 79 THEN
            INSERT INTO alertas (id_alerta, tipo_alerta, situacion, observaciones, alumno, id_alumno, id_usuario, telefono_alumno, nombre_tutor, telefono_tutor)
            VALUES (NULL, 'INASISTENCIAS', 'NO ATENDIDA', CONCAT('INASISTENCIAS: El alumno ', full_name, ' ha acumulado ', total_inasistencias, ' inasistencias'), full_name, NEW.id_alumno, 1, telefono_alumno_value, tutor_nombre, tutor_telefono);
        END IF;
    END IF;

END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_inasistencias_alerta_update`;
DELIMITER $$
CREATE TRIGGER `trigger_inasistencias_alerta_update` AFTER UPDATE ON `asistencias` FOR EACH ROW BEGIN
    DECLARE total_inasistencias INT;
    DECLARE asistencia_total_value DOUBLE;
    DECLARE full_name VARCHAR(255);
    DECLARE telefono_alumno_value VARCHAR(20);
    DECLARE tutor_nombre VARCHAR(255);
    DECLARE tutor_telefono VARCHAR(20);
    DECLARE alerta_existente INT;

    -- Obtener el número total de inasistencias
    SET total_inasistencias = IFNULL(NEW.inasistencias_p1, 0) + IFNULL(NEW.inasistencias_p2, 0) + IFNULL(NEW.inasistencias_p3, 0);

    -- Obtener el valor de asistencia_total
    SET asistencia_total_value = NEW.asistencia_total;

    -- Obtener el nombre completo del alumno y otros datos
    SELECT CONCAT(nombre_alumno, ' ', apellido_paterno, ' ', apellido_materno), telefono, nombre_tutor, telefono_tutor
    INTO full_name, telefono_alumno_value, tutor_nombre, tutor_telefono
    FROM alumnos
    WHERE id_alumno = NEW.id_alumno;

    -- Verificar si ya existe una alerta similar para evitar duplicados
    SELECT COUNT(*) INTO alerta_existente
    FROM alertas
    WHERE id_alumno = NEW.id_alumno AND tipo_alerta IN ('INASISTENCIAS', 'Asistencia Baja');

    -- Insertar alerta solo si no existe ninguna previa similar
    IF alerta_existente = 0 THEN
        IF total_inasistencias >= 3 AND asistencia_total_value <= 79 THEN
            INSERT INTO alertas (id_alerta, tipo_alerta, situacion, observaciones, alumno, id_alumno, id_usuario, telefono_alumno, nombre_tutor, telefono_tutor)
            VALUES (NULL, 'INASISTENCIAS', 'NO ATENDIDA', CONCAT('INASISTENCIAS: El alumno ', full_name, ' ha acumulado ', total_inasistencias, ' inasistencias'), full_name, NEW.id_alumno, 1, telefono_alumno_value, tutor_nombre, tutor_telefono);
        END IF;
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

DROP TABLE IF EXISTS `calificaciones`;
CREATE TABLE IF NOT EXISTS `calificaciones` (
  `id_calificacion` int NOT NULL AUTO_INCREMENT,
  `calificacion_p1` double DEFAULT NULL,
  `calificacion_p2` double DEFAULT NULL,
  `calificacion_p3` double DEFAULT NULL,
  `calificacion_final` double DEFAULT NULL,
  `acreditacion` varchar(5) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `id_alumno` bigint NOT NULL,
  `id_asignatura` int NOT NULL,
  `id_asistencia` bigint NOT NULL,
  `id_grupo` int NOT NULL,
  PRIMARY KEY (`id_calificacion`),
  KEY `id_asignatura` (`id_asignatura`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_asistencia` (`id_asistencia`),
  KEY `id_grupo` (`id_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `calificaciones`
--
DROP TRIGGER IF EXISTS `calculate_acreditacion_before_insert`;
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
DROP TRIGGER IF EXISTS `calculate_acreditacion_before_update`;
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
DROP TRIGGER IF EXISTS `calculate_calificacion_final_before_insert`;
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
DROP TRIGGER IF EXISTS `calculate_calificacion_final_before_update`;
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

DROP TABLE IF EXISTS `config_botones`;
CREATE TABLE IF NOT EXISTS `config_botones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `boton` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Volcado de datos para la tabla `config_botones`
--

INSERT INTO `config_botones` (`id`, `boton`, `estado`) VALUES
(7, 'p3', 1),
(6, 'p2', 1),
(5, 'p1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `titulo_estudios` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nombre_docente` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `apellido_paterno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `apellido_materno` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `RFC` varchar(13) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `genero` varchar(10) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `grupo_edad` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `tipo_plaza` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `formacion_academica` varchar(50) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `antiguedad` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `nivel_estudios` varchar(30) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `beca` tinyint DEFAULT NULL,
  `discapacidad` tinyint NOT NULL,
  `lengua_indigena` tinyint NOT NULL,
  `funcion` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `estudio_actual` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `pais_estudio` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Disparadores `docentes`
--
DROP TRIGGER IF EXISTS `actualizar_usuario_email_docente`;
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
  `nombre_especialidad` varchar(250) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_programa` int NOT NULL,
  PRIMARY KEY (`id_especialidad`),
  KEY `id_tipo_programa` (`id_tipo_programa`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

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

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `id_semestre` int NOT NULL,
  `nombre_grupo` varchar(2) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_especialidad` int NOT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_especialidad` (`id_especialidad`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

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
(207, 1, 'X', 2),
(208, 1, 'A', 2),
(209, 1, 'B', 2),
(210, 1, 'C', 2),
(211, 1, 'D', 2),
(212, 1, 'E', 2),
(213, 1, 'F', 2),
(214, 1, 'G', 2),
(215, 1, 'H', 2),
(216, 1, 'I', 2),
(217, 1, 'J', 2),
(218, 2, 'A', 12),
(219, 2, 'B', 12),
(220, 2, 'C', 12),
(221, 2, 'A', 14),
(222, 2, 'B', 14),
(223, 2, 'C', 14),
(224, 2, 'A', 16),
(225, 2, 'B', 16),
(226, 2, 'C', 16),
(227, 2, 'A', 18),
(228, 2, 'B', 18),
(229, 2, 'C', 18),
(230, 2, 'A', 20),
(231, 2, 'B', 20),
(232, 2, 'C', 20),
(233, 3, 'A', 12),
(234, 3, 'B', 12),
(235, 3, 'C', 12),
(236, 3, 'A', 14),
(237, 3, 'B', 14),
(238, 3, 'C', 14),
(239, 3, 'A', 16),
(240, 3, 'B', 16),
(241, 3, 'C', 16),
(242, 3, 'A', 18),
(243, 3, 'B', 18),
(244, 3, 'C', 18),
(245, 3, 'A', 20),
(246, 3, 'B', 20),
(247, 3, 'C', 20);

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
  `lunes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `martes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `miercoles` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `jueves` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `viernes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `sabado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  `horas_semanales` int DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_asignatura` (`id_asignatura`,`id_grupo`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_docente` (`id_docente`)
) ENGINE=InnoDB AUTO_INCREMENT=578 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `perfil` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci NOT NULL,
  `leido` tinyint(1) DEFAULT '0',
  `id_alerta` int NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_alerta` (`id_alerta`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `id_semestre` int NOT NULL AUTO_INCREMENT,
  `nombre_semestre` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci NOT NULL,
  `numero_semestre` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

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
(1, 'MCCEMS'),
(2, 'Acuerdo 653'),
(3, 'SAETAM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `perfil` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `usuario` varchar(55) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=675 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

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
