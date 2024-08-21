<?php
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $response = ["status" => "", "message" => ""];
    $inserted = 0;
    $updated = 0;
    

    try {
        $type       = $_FILES['dataCliente']['type'];
        $size       = $_FILES['dataCliente']['size'];
        $archivotmp = $_FILES['dataCliente']['tmp_name'];
        $lines      = file($archivotmp);
        
        $fila_inicio = 9; // Cambiado a la fila de inicio 9
        $id_grupo_default = 111; // Valor por defecto para id_grupo
        $all_same = true; // Variable para determinar si todos los datos son iguales
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

            // Extracción de inasistencias de la columna 40 (índice 39 en PHP)
            $inasistencias = !empty($datos[39]) ? $datos[39] : '';

            $nombrePartes = explode(" ", $alumno);

            $apellido_paterno = isset($nombrePartes[0]) ? $nombrePartes[0] : '';
            $apellido_materno = isset($nombrePartes[1]) ? $nombrePartes[1] : '';
            $nombres = isset($nombrePartes[2]) ? implode(" ", array_slice($nombrePartes, 2)) : '';

            $sqlSelectAlumno = "
                SELECT id_alumno FROM alumnos
                WHERE nombre_alumno = :nombre_alumno
                AND apellido_paterno = :apellido_paterno
                AND apellido_materno = :apellido_materno;
            ";
            $stmtSelectAlumno = $conexion->prepare($sqlSelectAlumno);
            $stmtSelectAlumno->bindParam(':nombre_alumno', $nombres);
            $stmtSelectAlumno->bindParam(':apellido_paterno', $apellido_paterno);
            $stmtSelectAlumno->bindParam(':apellido_materno', $apellido_materno);
            $stmtSelectAlumno->execute();
            $id_alumno = $stmtSelectAlumno->fetchColumn();

            if (!$id_alumno) {
                $sqlInsertAlumno = "
                    INSERT INTO alumnos (nombre_alumno, apellido_paterno, apellido_materno, id_grupo)
                    VALUES (:nombre_alumno, :apellido_paterno, :apellido_materno, :id_grupo)
                    ON DUPLICATE KEY UPDATE id_alumno=LAST_INSERT_ID(id_alumno);
                ";
                $stmtAlumno = $conexion->prepare($sqlInsertAlumno);
                $stmtAlumno->bindParam(':nombre_alumno', $nombres);
                $stmtAlumno->bindParam(':apellido_paterno', $apellido_paterno);
                $stmtAlumno->bindParam(':apellido_materno', $apellido_materno);
                $stmtAlumno->bindParam(':id_grupo', $id_grupo_default); // Usar el id_grupo por defecto (111)
                $stmtAlumno->execute();
                $id_alumno = $conexion->lastInsertId();
                $inserted++;
                $all_same = false; // Se realizó una inserción, por lo tanto, no todos los datos son iguales
            }

            $sqlSelectAsistencias = "
                SELECT id_asistencia, inasistencias FROM asistencias
                WHERE id_alumno = :id_alumno
            ";

            $stmtSelectAsistencias = $conexion->prepare($sqlSelectAsistencias);
            $stmtSelectAsistencias->bindParam(':id_alumno', $id_alumno);
            $stmtSelectAsistencias->execute();
            $resultAsistencia = $stmtSelectAsistencias->fetch(PDO::FETCH_ASSOC);

            if (!$resultAsistencia) {
                $sqlInsertAsistencias = "
                    INSERT INTO asistencias (inasistencias, id_alumno)
                    VALUES (:inasistencias, :id_alumno);
                ";
                $stmtAsistencias = $conexion->prepare($sqlInsertAsistencias);
                $stmtAsistencias->bindParam(':inasistencias', $inasistencias);
                $stmtAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtAsistencias->execute();
                $inserted++;
                $all_same = false; // Se realizó una inserción, por lo tanto, no todos los datos son iguales
            } else {
                // Comparación de inasistencias
                $current_inasistencias = $resultAsistencia['inasistencias'];
                if ($current_inasistencias !== $inasistencias) {
                    $sqlUpdateAsistencias = "
                        UPDATE asistencias SET inasistencias = :inasistencias
                        WHERE id_alumno = :id_alumno;
                    ";
                    $stmtUpdateAsistencias = $conexion->prepare($sqlUpdateAsistencias);
                    $stmtUpdateAsistencias->bindParam(':inasistencias', $inasistencias);
                    $stmtUpdateAsistencias->bindParam(':id_alumno', $id_alumno);
                    $stmtUpdateAsistencias->execute();
                    $updated++;
                    $all_same = false; // Se realizó una actualización, por lo tanto, no todos los datos son iguales
                }
            }

            $i++;
        }
        
        // Mensajes finales
        if ($all_same === true) {
            $response["status"] = "duplicate";
            $response["message"] = "Los datos no se insertaron porque todos los datos son iguales.";
        } elseif ($inserted > 0 && $updated == 0) {
            $response["status"] = "success";
            $response["message"] = "Los datos se insertaron correctamente.";
        } elseif ($inserted > 0 && $updated > 0) {
            $response["status"] = "success";
            $response["message"] = "Los datos se insertaron y actualizaron correctamente.";
        } elseif ($updated > 0) {
            $response["status"] = "success";
            $response["message"] = "Los datos se actualizaron correctamente:.".$all_same;
        }

    } catch(Exception $e) {
        $response["status"] = "error";
        $response["message"] = "Hubo un error al insertar los datos: " . $e->getMessage();
    }

    echo json_encode($response);
?>