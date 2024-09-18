<?php

include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["status" => "", "message" => "", "not_found" => []];
$inserted = 0;
$updated = 0;
$unchanged = 0;
$not_found_count = 0;

// Definir las abreviaturas de especialidades
$abreviaturas_especialidades = [
    'OFI.' => 'TÉCNICO EN OFIMÁTICA',
    'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
    'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
    'FTAL.' => 'TÉCNICO FORESTAL',
    'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA'
];

try {
    // Validación del tipo de archivo
    $fileType = pathinfo($_FILES['dataCliente']['name'], PATHINFO_EXTENSION);
    if ($fileType !== 'csv') {
        throw new Exception("El formato del archivo no es válido. Convierta el archivo a una extensión '.csv'.");
    }

    // Verificación de carga correcta del archivo
    // if (!isset($_FILES['dataCliente']) || $_FILES['dataCliente']['tmp_name'] == '') {
    //     throw new Exception("No se cargó correctamente el archivo CSV.");
    // }

    // Verificación de carga correcta del archivo (Aquí agregas la validación)
    if (!isset($_FILES['dataCliente']) || $_FILES['dataCliente']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al cargar el archivo. Detalles: " . json_encode($_FILES)
        ]);
        exit;
    }

    // Cargar el archivo CSV
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    
    // Abrir el archivo para leerlo
    if (($handle = fopen($archivotmp, "r")) !== FALSE) {
        $row = 1;
        $asignatura = '';
        $semestre = '';
        $especialidad = '';
        $alumnos = [];
        $faltas = [];
        $asistencias = [];
        $calificaciones = [];
        
        // Leer el archivo línea por línea
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Leer la asignatura desde la celda C4 (columna 3, fila 4)
            if ($row == 4) {
                $asignatura = !empty($data[2]) ? trim($data[2]) : '';
                
                // Verificar si la asignatura tiene abreviatura y módulo/submódulo
                foreach ($abreviaturas_especialidades as $abreviatura => $nombre_especialidad) {
                    if (strpos($asignatura, $abreviatura) === 0) {
                        // Reemplazar la abreviatura por el nombre completo
                        $asignatura = str_replace($abreviatura, '', $asignatura);
                        $especialidad = $nombre_especialidad;
                        break;
                    }
                }

                // Si tiene la estructura MODxSUBy: descripción (módulo/submódulo)
                if (preg_match('/^MOD\d+SUB\d+:\s*(.+)$/', $asignatura, $matches)) {
                    $descripcion = trim($matches[1]);

                    // Buscar el submódulo en la base de datos
                    $descripcion = "%$descripcion%";
                    $sqlSelectAsignatura = "
                        SELECT id_asignatura FROM asignatura
                        WHERE submodulos LIKE :descripcion
                        LIMIT 1;
                    ";
                    $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
                    $stmtSelectAsignatura->bindParam(':descripcion', $descripcion);
                    $stmtSelectAsignatura->execute();
                    $resultAsignatura = $stmtSelectAsignatura->fetch(PDO::FETCH_ASSOC);

                    if (!$resultAsignatura) {
                        throw new Exception("No se encontró el submódulo con la descripción: " . $matches[1]);
                    }

                    $id_asignatura = $resultAsignatura['id_asignatura'];
                } else {
                    // Caso normal: asignatura sin módulo y submódulo
                    // Buscar el id_asignatura en la base de datos por nombre normal
                    $sqlSelectAsignatura = "
                        SELECT id_asignatura 
                        FROM asignatura
                        WHERE nombre_asignatura = :nombre_asignatura
                        AND id_especialidad = (
                            SELECT id_especialidad FROM especialidad WHERE nombre_especialidad = :especialidad
                        )
                        LIMIT 1;
                    ";
                    $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
                    $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
                    $stmtSelectAsignatura->bindParam(':especialidad', $especialidad);
                    $stmtSelectAsignatura->execute();
                    $resultAsignatura = $stmtSelectAsignatura->fetch(PDO::FETCH_ASSOC);

                    if (!$resultAsignatura) {
                        throw new Exception("No se encontró la asignatura: " . $asignatura);
                    }

                    $id_asignatura = $resultAsignatura['id_asignatura'];
                }
            }

            // Leer el semestre desde la celda C5 (columna 3, fila 5)
            if ($row == 5) {
                $semestre = !empty($data[2]) ? trim($data[2]) : '';
            }

            // A partir de la fila 10, leer alumnos, faltas, asistencias y calificaciones
            if ($row >= 10) {
                $alumno = !empty($data[1]) ? trim($data[1]) : '';
                $falta = !empty($data[39]) ? trim($data[39]) : ''; // AN10 (columna 40)
                $asistencia = !empty($data[40]) ? trim($data[40]) : ''; // AO10 (columna 41)
                $calificacion = !empty($data[47]) ? trim($data[47]) : ''; // AV10 (columna 48)

                // Guardar los datos solo si el alumno tiene nombre
                if ($alumno != '') {
                    $alumnos[] = $alumno;
                    $faltas[] = $falta;
                    $asistencias[] = $asistencia;
                    $calificaciones[] = $calificacion;
                }
            }

            $row++;
        }

        fclose($handle);

        // Verificar si se encontró la asignatura
        if ($asignatura == '') {
            throw new Exception("No se encontró el nombre de la asignatura en la celda C4.");
        }

        // Aquí puedes implementar la lógica para insertar los datos de los alumnos en la base de datos
        foreach ($alumnos as $index => $alumno) {
            $inasistencia = $faltas[$index];
            $asistencia = $asistencias[$index];
            $calificacion = $calificaciones[$index];

            // Obtener id del alumno
            $sqlSelectAlumno = "
                SELECT id_alumno, id_grupo FROM alumnos
                WHERE CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre_alumno) = :nombre_completo
                LIMIT 1;
            ";
            $stmtSelectAlumno = $conexion->prepare($sqlSelectAlumno);
            $stmtSelectAlumno->bindParam(':nombre_completo', $alumno);
            $stmtSelectAlumno->execute();
            $resultAlumno = $stmtSelectAlumno->fetch(PDO::FETCH_ASSOC);

            if (!$resultAlumno) {
                $response["not_found"][] = $alumno;
                $not_found_count++;
                continue;
            }

            $id_alumno = $resultAlumno['id_alumno'];
            $id_grupo = $resultAlumno['id_grupo'];

            // Insertar o actualizar asistencias
            $sqlSelectAsistencias = "
                SELECT id_asistencia FROM asistencias
                WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura
                LIMIT 1;
            ";
            $stmtSelectAsistencias = $conexion->prepare($sqlSelectAsistencias);
            $stmtSelectAsistencias->bindParam(':id_alumno', $id_alumno);
            $stmtSelectAsistencias->bindParam(':id_asignatura', $id_asignatura);
            $stmtSelectAsistencias->execute();
            $resultAsistencia = $stmtSelectAsistencias->fetch(PDO::FETCH_ASSOC);

            if (!$resultAsistencia) {
                // Insertar nueva asistencia
                $sqlInsertAsistencias = "
                    INSERT INTO asistencias (id_alumno, id_asignatura, inasistencias_p1, asistencias_p1)
                    VALUES (:id_alumno, :id_asignatura, :inasistencias_p1, :asistencias_p1);
                ";
                $stmtInsertAsistencias = $conexion->prepare($sqlInsertAsistencias);
                $stmtInsertAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtInsertAsistencias->bindParam(':id_asignatura', $id_asignatura);
                $stmtInsertAsistencias->bindParam(':inasistencias_p1', $inasistencia);
                $stmtInsertAsistencias->bindParam(':asistencias_p1', $asistencia);
                $stmtInsertAsistencias->execute();
            } else {
                // Actualizar asistencia existente
                $sqlUpdateAsistencias = "
                    UPDATE asistencias 
                    SET inasistencias_p1 = :inasistencias_p1, asistencias_p1 = :asistencias_p1
                    WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura;
                ";
                $stmtUpdateAsistencias = $conexion->prepare($sqlUpdateAsistencias);
                $stmtUpdateAsistencias->bindParam(':inasistencias_p1', $inasistencia);
                $stmtUpdateAsistencias->bindParam(':asistencias_p1', $asistencia);
                $stmtUpdateAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtUpdateAsistencias->bindParam(':id_asignatura', $id_asignatura);
                $stmtUpdateAsistencias->execute();
            }

            // Insertar o actualizar calificaciones
            $sqlSelectCalificaciones = "
                SELECT id_calificacion FROM calificaciones
                WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura
                LIMIT 1;
            ";
            $stmtSelectCalificaciones = $conexion->prepare($sqlSelectCalificaciones);
            $stmtSelectCalificaciones->bindParam(':id_alumno', $id_alumno);
            $stmtSelectCalificaciones->bindParam(':id_asignatura', $id_asignatura);
            $stmtSelectCalificaciones->execute();
            $resultCalificacion = $stmtSelectCalificaciones->fetch(PDO::FETCH_ASSOC);

            if (!$resultCalificacion) {
                // Insertar nueva calificación
                $sqlInsertCalificaciones = "
                    INSERT INTO calificaciones (id_alumno, id_asignatura, calificacion_p1, id_grupo)
                    VALUES (:id_alumno, :id_asignatura, :calificacion_p1, :id_grupo);
                ";
                $stmtInsertCalificaciones = $conexion->prepare($sqlInsertCalificaciones);
                $stmtInsertCalificaciones->bindParam(':id_alumno', $id_alumno);
                $stmtInsertCalificaciones->bindParam(':id_asignatura', $id_asignatura);
                $stmtInsertCalificaciones->bindParam(':calificacion_p1', $calificacion);
                $stmtInsertCalificaciones->bindParam(':id_grupo', $id_grupo);
                $stmtInsertCalificaciones->execute();
            } else {
                // Actualizar calificación existente
                $sqlUpdateCalificaciones = "
                    UPDATE calificaciones 
                    SET calificacion_p1 = :calificacion_p1
                    WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura;
                ";
                $stmtUpdateCalificaciones = $conexion->prepare($sqlUpdateCalificaciones);
                $stmtUpdateCalificaciones->bindParam(':calificacion_p1', $calificacion);
                $stmtUpdateCalificaciones->bindParam(':id_alumno', $id_alumno);
                $stmtUpdateCalificaciones->bindParam(':id_asignatura', $id_asignatura);
                $stmtUpdateCalificaciones->execute();
            }
        }

        // Mensajes finales sobre el procesamiento
        if ($not_found_count > 0) {
            $nombres_no_encontrados = implode(", ", $response["not_found"]);
            $response["status"] = "error";
            $response["message"] = "No se encontró al alumno: $nombres_no_encontrados.";
        } else {
            $response["status"] = "success";
            $response["message"] = "Datos procesados correctamente.";
        }

    } else {
        throw new Exception("No se pudo abrir el archivo CSV.");
    }

} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Ups, ha ocurrido un error: " . $e->getMessage();
}

echo json_encode($response);
exit;

?>