<?php
// Checkpoint Asistencias, Calificaciones y Validaciones funcionales
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

            // Extracción de asistencias, inasistencias y calificaciones de las columnas correspondientes
            $inasistencias_p3 = !empty($datos[39]) ? $datos[39] : ''; // Columna para inasistencias_p3 (columna 40 en Excel)
            $asistencias_p3 = !empty($datos[40]) ? $datos[40] : '';  // Columna para asistencias_p3 (columna 41 en Excel)
            $calificacion_p3 = !empty($datos[47]) ? $datos[47] : '';  // Columna para calificacion_p3 (columna 48 en Excel)

            $nombrePartes = explode(" ", $alumno);

            $apellido_paterno = isset($nombrePartes[0]) ? $nombrePartes[0] : '';
            $apellido_materno = isset($nombrePartes[1]) ? $nombrePartes[1] : '';
            $nombres = isset($nombrePartes[2]) ? implode(" ", array_slice($nombrePartes, 2)) : '';

            // Buscar al alumno en la base de datos
            $sqlSelectAlumno = "
                SELECT id_alumno, id_grupo FROM alumnos
                WHERE nombre_alumno = :nombre_alumno
                AND apellido_paterno = :apellido_paterno
                AND apellido_materno = :apellido_materno;
            ";
            $stmtSelectAlumno = $conexion->prepare($sqlSelectAlumno);
            $stmtSelectAlumno->bindParam(':nombre_alumno', $nombres);
            $stmtSelectAlumno->bindParam(':apellido_paterno', $apellido_paterno);
            $stmtSelectAlumno->bindParam(':apellido_materno', $apellido_materno);
            $stmtSelectAlumno->execute();
            $resultAlumno = $stmtSelectAlumno->fetch(PDO::FETCH_ASSOC);

            if (!$resultAlumno) {
                // Si no encuentra el alumno, agrega el nombre a la lista de no encontrados
                $response["not_found"][] = $alumno;
                $not_found_count++; // Incrementar el contador de alumnos no encontrados
                continue; // No continuar con la inserción para este alumno
            }

            $id_alumno = $resultAlumno['id_alumno'];
            $id_grupo = $resultAlumno['id_grupo'];

            // Verificar si ya existe una entrada en la tabla de asistencias
            $sqlSelectAsistencias = "
                SELECT asistencias_p3, inasistencias_p3 FROM asistencias
                WHERE id_alumno = :id_alumno
            ";

            $stmtSelectAsistencias = $conexion->prepare($sqlSelectAsistencias);
            $stmtSelectAsistencias->bindParam(':id_alumno', $id_alumno);
            $stmtSelectAsistencias->execute();
            $resultAsistencia = $stmtSelectAsistencias->fetch(PDO::FETCH_ASSOC);

            if (!$resultAsistencia) {
                // Insertar nueva asistencia si no existe
                $sqlInsertAsistencias = "
                    INSERT INTO asistencias (asistencias_p3, inasistencias_p3, id_alumno)
                    VALUES (:asistencias_p3, :inasistencias_p3, :id_alumno);
                ";
                $stmtAsistencias = $conexion->prepare($sqlInsertAsistencias);
                $stmtAsistencias->bindParam(':asistencias_p3', $asistencias_p3);
                $stmtAsistencias->bindParam(':inasistencias_p3', $inasistencias_p3);
                $stmtAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtAsistencias->execute();
                $inserted++;
            } else {
                // Comparación de asistencias e inasistencias
                $current_asistencias_p3 = $resultAsistencia['asistencias_p3'];
                $current_inasistencias_p3 = $resultAsistencia['inasistencias_p3'];
                
                // Si los datos son diferentes, actualiza; si son iguales, no hacer nada
                if ($current_asistencias_p3 !== $asistencias_p3 || $current_inasistencias_p3 !== $inasistencias_p3) {
                    // Actualizar la asistencia existente
                    $sqlUpdateAsistencias = "
                        UPDATE asistencias 
                        SET asistencias_p3 = :asistencias_p3, inasistencias_p3 = :inasistencias_p3
                        WHERE id_alumno = :id_alumno;
                    ";
                    $stmtUpdateAsistencias = $conexion->prepare($sqlUpdateAsistencias);
                    $stmtUpdateAsistencias->bindParam(':asistencias_p3', $asistencias_p3);
                    $stmtUpdateAsistencias->bindParam(':inasistencias_p3', $inasistencias_p3);
                    $stmtUpdateAsistencias->bindParam(':id_alumno', $id_alumno);
                    $stmtUpdateAsistencias->execute();

                    if ($stmtUpdateAsistencias->rowCount() > 0) {
                        // Solo aumentar el contador de updated si realmente se cambió algo
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                } else {
                    // No hacer nada si los datos son iguales
                    $unchanged++;
                }
            }

            // Verificar si ya existe una entrada en la tabla de calificaciones
            $sqlSelectCalificaciones = "
                SELECT calificacion_p3 FROM calificaciones
                WHERE id_alumno = :id_alumno
            ";

            $stmtSelectCalificaciones = $conexion->prepare($sqlSelectCalificaciones);
            $stmtSelectCalificaciones->bindParam(':id_alumno', $id_alumno);
            $stmtSelectCalificaciones->execute();
            $resultCalificacion = $stmtSelectCalificaciones->fetch(PDO::FETCH_ASSOC);

            if (!$resultCalificacion) {
                // Insertar nueva calificación si no existe
                $sqlInsertCalificaciones = "
                    INSERT INTO calificaciones (calificacion_p3, id_alumno, id_asignatura, id_asistencia, id_grupo)
                    VALUES (:calificacion_p3, :id_alumno, 129, :id_asistencia, :id_grupo);
                ";
                $stmtCalificaciones = $conexion->prepare($sqlInsertCalificaciones);
                $stmtCalificaciones->bindParam(':calificacion_p3', $calificacion_p3);
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
                // Comparación de calificaciones
                $current_calificacion_p3 = $resultCalificacion['calificacion_p3'];
                
                // Si los datos son diferentes, actualiza; si son iguales, no hacer nada
                if ($current_calificacion_p3 !== $calificacion_p3) {
                    // Actualizar la calificación existente
                    $sqlUpdateCalificaciones = "
                        UPDATE calificaciones 
                        SET calificacion_p3 = :calificacion_p3
                        WHERE id_alumno = :id_alumno;
                    ";
                    $stmtUpdateCalificaciones = $conexion->prepare($sqlUpdateCalificaciones);
                    $stmtUpdateCalificaciones->bindParam(':calificacion_p3', $calificacion_p3);
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

            $i++;
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