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
$not_found_count = 0; // Contador para los alumnos no encontrados

try {
    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    $fila_inicio = 9;
    $i = 0;

    foreach ($lines as $line) {
        if ($i < $fila_inicio) {
            $i++;
            continue;
        }

        $datos = explode(",", $line);

        $alumno = !empty($datos[1]) ? trim($datos[1]) : '';

        if ($alumno == '') {
            break;
        }

        // Extracción de asistencias, inasistencias y calificación de las columnas correspondientes
        $inasistencias_p1 = !empty($datos[39]) ? $datos[39] : ''; // Columna para inasistencias_p1 (columna 40 en Excel)
        $asistencias_p1 = !empty($datos[40]) ? $datos[40] : '';  // Columna para asistencias_p1 (columna 41 en Excel)
        $calificacion_p1 = !empty($datos[47]) ? $datos[47] : '';  // Columna para calificación_p1 (columna 48 en Excel)

        $nombrePartes = explode(" ", $alumno);

        $apellido_paterno = isset($nombrePartes[0]) ? $nombrePartes[0] : '';
        $apellido_materno = isset($nombrePartes[1]) ? $nombrePartes[1] : '';
        $nombres = isset($nombrePartes[2]) ? implode(" ", array_slice($nombrePartes, 2)) : '';

        // Buscar al alumno en la base de datos
        $sqlSelectAlumno = "
            SELECT id_alumno, id_grupo FROM alumnos
            WHERE nombre_alumno = :nombre_alumno
            AND apellido_paterno = :apellido_paterno
            AND apellido_materno = :apellido_materno
            LIMIT 1;
        ";
        $stmtSelectAlumno = $conexion->prepare($sqlSelectAlumno);
        $stmtSelectAlumno->bindParam(':nombre_alumno', $nombres);
        $stmtSelectAlumno->bindParam(':apellido_paterno', $apellido_paterno);
        $stmtSelectAlumno->bindParam(':apellido_materno', $apellido_materno);
        $stmtSelectAlumno->execute();
        $resultAlumnos = $stmtSelectAlumno->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultAlumnos) > 1) {
            throw new Exception("Error: Consulta de alumnos devolvió múltiples filas para el alumno: $alumno");
        }

        if (!$resultAlumnos) {
            $response["not_found"][] = $alumno;
            $not_found_count++;
            continue;
        }

        $id_alumno = $resultAlumnos[0]['id_alumno'];
        $id_grupo = $resultAlumnos[0]['id_grupo'];

        // Insertar o actualizar la tabla de asistencias
        $sqlSelectAsistencias = "
            SELECT asistencias_p2, inasistencias_p2 FROM asistencias
            WHERE id_alumno = :id_alumno
            LIMIT 1;
        ";
        $stmtSelectAsistencias = $conexion->prepare($sqlSelectAsistencias);
        $stmtSelectAsistencias->bindParam(':id_alumno', $id_alumno);
        $stmtSelectAsistencias->execute();
        $resultAsistencias = $stmtSelectAsistencias->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultAsistencias) > 1) {
            throw new Exception("Error: Consulta de asistencias devolvió múltiples filas para el alumno con id: $id_alumno");
        }

        if (!$resultAsistencias) {
            // Insertar nueva asistencia si no existe
            $sqlInsertAsistencias = "
                INSERT INTO asistencias (asistencias_p2, inasistencias_p2, id_alumno)
                VALUES (:asistencias_p2, :inasistencias_p2, :id_alumno);
            ";
            $stmtAsistencias = $conexion->prepare($sqlInsertAsistencias);
            $stmtAsistencias->bindParam(':asistencias_p2', $asistencias_p1);
            $stmtAsistencias->bindParam(':inasistencias_p2', $inasistencias_p1);
            $stmtAsistencias->bindParam(':id_alumno', $id_alumno);
            $stmtAsistencias->execute();
            $inserted++;
        } else {
            $current_asistencias_p1 = $resultAsistencias[0]['asistencias_p2'];
            $current_inasistencias_p1 = $resultAsistencias[0]['inasistencias_p2'];
            
            if ($current_asistencias_p1 !== $asistencias_p1 || $current_inasistencias_p1 !== $inasistencias_p1) {
                $sqlUpdateAsistencias = "
                    UPDATE asistencias 
                    SET asistencias_p2 = :asistencias_p2, inasistencias_p2 = :inasistencias_p2
                    WHERE id_alumno = :id_alumno;
                ";
                $stmtUpdateAsistencias = $conexion->prepare($sqlUpdateAsistencias);
                $stmtUpdateAsistencias->bindParam(':asistencias_p2', $asistencias_p1);
                $stmtUpdateAsistencias->bindParam(':inasistencias_p2', $inasistencias_p1);
                $stmtUpdateAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtUpdateAsistencias->execute();

                if ($stmtUpdateAsistencias->rowCount() > 0) {
                    $updated++;
                } else {
                    $unchanged++;
                }
            } else {
                $unchanged++;
            }
        }

        // Insertar o actualizar la tabla de calificaciones
        $sqlSelectCalificaciones = "
            SELECT calificacion_p2 FROM calificaciones
            WHERE id_alumno = :id_alumno AND id_asignatura = 129;
        ";
        $stmtSelectCalificaciones = $conexion->prepare($sqlSelectCalificaciones);
        $stmtSelectCalificaciones->bindParam(':id_alumno', $id_alumno);
        $stmtSelectCalificaciones->execute();
        $resultCalificaciones = $stmtSelectCalificaciones->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultCalificaciones) > 1) {
            throw new Exception("Error: Consulta de calificaciones devolvió múltiples filas para el alumno con id: $id_alumno");
        }

        if (!$resultCalificaciones) { // Cambiado de $resultCalificacion a $resultCalificaciones
            // Insertar nueva calificación si no existe
            $sqlInsertCalificaciones = "
                INSERT INTO calificaciones (calificacion_p2, id_alumno, id_asignatura, id_asistencia, id_grupo)
                VALUES (:calificacion_p2, :id_alumno, 129, :id_asistencia, :id_grupo);
            ";
            $stmtCalificaciones = $conexion->prepare($sqlInsertCalificaciones);
            $stmtCalificaciones->bindParam(':calificacion_p2', $calificacion_p1);
            $stmtCalificaciones->bindParam(':id_alumno', $id_alumno);
            // Obtener el id_asistencia adecuado
            $sqlSelectAsistenciaId = "
                SELECT id_asistencia FROM asistencias WHERE id_alumno = :id_alumno LIMIT 1;
            ";
            $stmtSelectAsistenciaId = $conexion->prepare($sqlSelectAsistenciaId);
            $stmtSelectAsistenciaId->bindParam(':id_alumno', $id_alumno);
            $stmtSelectAsistenciaId->execute();
            $id_asistencia = $stmtSelectAsistenciaId->fetchColumn();

            $stmtCalificaciones->bindParam(':id_asistencia', $id_asistencia);
            $stmtCalificaciones->bindParam(':id_grupo', $id_grupo);
            $stmtCalificaciones->execute();
            $inserted++;
        } else {
            $current_calificacion_p1 = $resultCalificaciones[0]['calificacion_p1'];
            
            if ($current_calificacion_p1 !== $calificacion_p2) { // Cambiado de $calificacion_p2 a $calificacion_p1
                // Actualizar la calificación existente
                $sqlUpdateCalificaciones = "
                    UPDATE calificaciones 
                    SET calificacion_p2 = :calificacion_p2
                    WHERE id_alumno = :id_alumno AND id_asignatura = 129;
                ";
                $stmtUpdateCalificaciones = $conexion->prepare($sqlUpdateCalificaciones);
                $stmtUpdateCalificaciones->bindParam(':calificacion_p2', $calificacion_p1);
                $stmtUpdateCalificaciones->bindParam(':id_alumno', $id_alumno);
                $stmtUpdateCalificaciones->execute();

                if ($stmtUpdateCalificaciones->rowCount() > 0) {
                    $updated++;
                } else {
                    $unchanged++;
                }
            } else {
                $unchanged++;
            }
        }
    }

    // Mensajes finales
    if ($not_found_count > 0) {
        $nombres_no_encontrados = implode(", ", $response["not_found"]);
        $response["status"] = "error";
        $response["message"] = "No se encontró al alumno: $nombres_no_encontrados. Verifique los nombres o comuníquese con servicios escolares.";
    } elseif ($inserted > 0 && $updated > 0) {
        $response["status"] = "success";
        $response["message"] = "Los datos se insertaron y actualizaron correctamente.";
    } elseif ($inserted > 0 && $updated == 0) {
        $response["status"] = "success";
        $response["message"] = "Los datos se insertaron correctamente.";
    } elseif ($updated > 0 && $inserted == 0) {
        $response["status"] = "success";
        $response["message"] = "Los datos se actualizaron correctamente.";
    } elseif ($unchanged > 0 && $inserted == 0 && $updated == 0) {
        $response["status"] = "duplicate";
        $response["message"] = "Los datos no se modificaron porque ya estaban actualizados.";
    } else {
        $response["status"] = "info";
        $response["message"] = "No se realizaron cambios.";
    }

    echo json_encode($response);
    exit;

} catch(Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Hubo un error al insertar los datos: " . $e->getMessage();
    echo json_encode($response);
    exit;
}
?>