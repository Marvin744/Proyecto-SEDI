<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ["status" => "", "message" => ""];

try {
    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    $fila_inicio = 6;

    $abreviaturas_especialidades = [
        'OFI.' => 'TÉCNICO EN OFIMÁTICA',
        'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
        'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
        'FTAL.' => 'TÉCNICO FORESTAL',
        'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA',
        'ECO-ADM.' => 'ECONÓMICO - ADMINISTRATIVO',
        'QUI-BIO.' => 'QUÍMICO - BIÓLOGO',
        'FIS-MAT.' => 'FÍSICO - MATEMÁTICO',
    ];

    $abreviaturas_asignaturas = [
        'T. DE FILOSOFIA' => 'TEMAS DE FILOSOFÍA',
        'T. DE FILOSOFÍA' => 'TEMAS DE FILOSOFÍA',
        'MAT. APLICADA' => 'MATEMÁTICAS APLICADAS',
    ];

    $tutorias = [
        'TUTORÍAS I', 'TUTORÍAS II', 'TUTORÍAS III', 
        'TUTORÍAS IV', 'TUTORÍAS V', 'TUTORÍAS VI'
    ];

    // Mapa de números romanos a id_semestre
    $mapa_semestres = [
        'I' => 1,
        'II' => 2,
        'III' => 3,
        'IV' => 4,
        'V' => 5,
        'VI' => 6
    ];

    $i = 0;
    $inserted = 0;

    foreach ($lines as $line) {
        if ($i < $fila_inicio) {
            $i++;
            continue;
        }

        if (trim($line) == '') {
            $i++;
            continue;
        }

        $datos = explode(",", $line);

        $profesor = !empty($datos[0]) ? trim($datos[0]) : '';
        $clases = !empty($datos[1]) ? trim($datos[1]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[6]) ? trim($datos[6]) : '';

        if (empty($profesor) && empty($clases) && empty($asignatura) && empty($total_horas)) {
            $i++;
            continue;
        }

        // Saltar las filas que contengan asignaturas de TUTORÍAS
        if (in_array($asignatura, $tutorias)) {
            $i++;
            continue;
        }

        // Extraer los componentes de la columna "clases"
        $clasesPartes = explode(" ", $clases);
        if (count($clasesPartes) < 3) {
            throw new Exception("Formato inválido en la columna 'clases': " . $clases);
        }
        list($semestre, $nombre_grupo, $especialidadAbrev) = $clasesPartes;
        $especialidad = isset($abreviaturas_especialidades[$especialidadAbrev]) ? $abreviaturas_especialidades[$especialidadAbrev] : $especialidadAbrev;

        // Convertir el número romano al id_semestre
        if (array_key_exists($semestre, $mapa_semestres)) {
            $id_semestre = $mapa_semestres[$semestre];
        } else {
            throw new Exception("El semestre '$semestre' no es válido.");
        }

        // Validación para determinar qué consulta SQL utilizar
        if (strpos($asignatura, $especialidadAbrev) === 0 && preg_match('/:(.+)/', $asignatura, $matches)) {
            // Si la asignatura empieza con la abreviatura de la especialidad y contiene módulo/submódulo

            // Extraer la descripción después del ":"
            $asignaturaDescripcion = trim($matches[1]);

            // Búsqueda de la asignatura en la base de datos (Consulta avanzada)
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura LIKE :asignaturaDescripcion
                OR submodulos LIKE :asignaturaDescripcion
                AND id_semestre = :id_semestre
                AND id_especialidad = id_especialidad; 
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $asignaturaDescripcionLike = "%$asignaturaDescripcion%";
            $stmtSelectAsignatura->bindParam(':asignaturaDescripcion', $asignaturaDescripcionLike);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre, PDO::PARAM_INT);
            $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad, PDO::PARAM_INT);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                throw new Exception("La asignatura relacionada con '$asignaturaDescripcion' no existe en la base de datos.");
            }
        } else {
            // Si la asignatura no empieza con la abreviatura de la especialidad

            // Reemplazo de abreviatura de asignatura por su nombre completo si no es un módulo/submódulo
            if (array_key_exists($asignatura, $abreviaturas_asignaturas)) {
                $asignatura = $abreviaturas_asignaturas[$asignatura];
            }

            // Verificación de existencia de la asignatura (Consulta original revisada)
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura LIKE :nombre_asignatura
                OR submodulos LIKE :nombre_asignatura
                AND id_semestre = :id_semestre
                AND id_especialidad = :id_especialidad LIMIT 1; 
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                throw new Exception("La asignatura '$asignatura' no existe en la base de datos.");
            }
        }


        // Determinar el tipo de programa basado en la especialidad
        if (in_array($especialidad, ['TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS', 'TÉCNICO EN PROGRAMACIÓN'])) {
            $tipo_programa = 'MCCEMS';
        } elseif (in_array($especialidad, ['TÉCNICO EN OFIMÁTICA', 'TÉCNICO FORESTAL', 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA'])) {
            $tipo_programa = 'Acuerdo 653';
        } else {
            throw new Exception("No se pudo determinar el tipo de programa para la especialidad '$especialidad'.");
        }

        // Obtener id_especialidad filtrando por especialidad y tipo_programa
        $sqlSelectEspecialidad = "
            SELECT id_especialidad FROM especialidad
            WHERE nombre_especialidad = :especialidad
            AND id_tipo_programa = (SELECT id_tipo_programa FROM tipo_programa WHERE tipo_programa = :tipo_programa LIMIT 1)
            LIMIT 1;";
        $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
        $stmtSelectEspecialidad->bindParam(':especialidad', $especialidad);
        $stmtSelectEspecialidad->bindParam(':tipo_programa', $tipo_programa);
        $stmtSelectEspecialidad->execute();
        $id_especialidad = $stmtSelectEspecialidad->fetchColumn();

        if (!$id_especialidad) {
            throw new Exception("La especialidad '$especialidad' con tipo de programa '$tipo_programa' no existe en la base de datos.");
        }

        // Obtener id_grupo basado en el semestre, nombre del grupo y especialidad
        $sqlSelectGrupo = "
            SELECT id_grupo FROM grupo
            WHERE nombre_grupo = :nombre_grupo
            AND id_semestre = :id_semestre
            AND id_especialidad = :id_especialidad
            LIMIT 1;";
        $stmtSelectGrupo = $conexion->prepare($sqlSelectGrupo);
        $stmtSelectGrupo->bindParam(':nombre_grupo', $nombre_grupo);
        $stmtSelectGrupo->bindParam(':id_semestre', $id_semestre);
        $stmtSelectGrupo->bindParam(':id_especialidad', $id_especialidad);
        $stmtSelectGrupo->execute();
        $id_grupo = $stmtSelectGrupo->fetchColumn();

        if (!$id_grupo) {
            throw new Exception("El grupo '$nombre_grupo' con semestre '$semestre' y especialidad '$especialidad' no existe en la base de datos.");
        }

        // Verificación de existencia de 'docente'
        $nombrePartes = explode(" ", $profesor);
        $apellido_paterno = isset($nombrePartes[0]) ? trim($nombrePartes[0]) : '';
        $apellido_materno = isset($nombrePartes[1]) ? trim($nombrePartes[1]) : '';
        $nombres = isset($nombrePartes[2]) ? trim(implode(" ", array_slice($nombrePartes, 2))) : '';

        $sqlSelectDocente = "
            SELECT id_docente FROM docentes
            WHERE nombre_docente = :nombre_docente
            AND apellido_paterno = :apellido_paterno
            AND apellido_materno = :apellido_materno;
        ";
        $stmtSelectDocente = $conexion->prepare($sqlSelectDocente);
        $stmtSelectDocente->bindParam(':nombre_docente', $nombres);
        $stmtSelectDocente->bindParam(':apellido_paterno', $apellido_paterno);
        $stmtSelectDocente->bindParam(':apellido_materno', $apellido_materno);
        $stmtSelectDocente->execute();
        $id_docente = $stmtSelectDocente->fetchColumn();

        if (!$id_docente) {
            throw new Exception("El docente '$nombres $apellido_paterno $apellido_materno' no existe en la base de datos.");
        }

        // Verificación de existencia en la tabla 'horarios'
        $sqlSelectHorario = "
            SELECT COUNT(*) FROM horarios
            WHERE id_docente = :id_docente
            AND id_grupo = :id_grupo
            AND horas_semanales = :horas_semanales
            AND id_asignatura = :id_asignatura;
        ";
        $stmtSelectHorario = $conexion->prepare($sqlSelectHorario);
        $stmtSelectHorario->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $stmtSelectHorario->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmtSelectHorario->bindParam(':horas_semanales', $total_horas, PDO::PARAM_INT);
        $stmtSelectHorario->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $stmtSelectHorario->execute();
        $horarioExistente = $stmtSelectHorario->fetchColumn();

        if ($horarioExistente == 0) {
            // Insertar nuevo registro en la tabla 'horarios'
            $sqlInsertHorario = "
                INSERT INTO horarios (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
                VALUES (:id_docente, :id_asignatura, :id_grupo, NULL, NULL, NULL, NULL, NULL, NULL, :horas_semanales);
            ";
            $stmtInsertHorario = $conexion->prepare($sqlInsertHorario);
            $stmtInsertHorario->bindParam(':id_docente', $id_docente);
            $stmtInsertHorario->bindParam(':id_asignatura', $id_asignatura);
            $stmtInsertHorario->bindParam(':id_grupo', $id_grupo);
            $stmtInsertHorario->bindParam(':horas_semanales', $total_horas);
            $stmtInsertHorario->execute();
            $inserted++;
        }

        $i++;
    }

    if ($inserted > 0) {
        $response["status"] = "success";
        $response["message"] = "Los datos se insertaron correctamente.";
    } else {
        $response["status"] = "duplicate";
        $response["message"] = "Los datos no se insertaron porque todos los datos son iguales.";
    }

} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Hubo un error al insertar los datos: " . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>