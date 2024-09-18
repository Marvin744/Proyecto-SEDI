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

try {
    // Validación del tipo de archivo
    $fileType = pathinfo($_FILES['dataCliente']['name'], PATHINFO_EXTENSION);
    if ($fileType !== 'csv') {
        // Detener el procesamiento si el archivo no es un CSV
        $response["status"] = "error";
        $response["message"] = "El archivo cargado no es de tipo CSV UTF-8. Por favor, guarde el archivo con formato .csv y vuelva a intentarlo.";
        echo json_encode($response);
        exit;  // Detener ejecución aquí
    }

    // Cargar el archivo CSV
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    
    // Abrir el archivo para leerlo
    if (($handle = fopen($archivotmp, "r")) !== FALSE) {
        $row = 1;
        $asignatura = '';

        // Leer el archivo línea por línea
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 4) {
                // Celda C4 en formato CSV sería la tercera columna en la fila 4
                $asignatura = !empty($data[2]) ? trim($data[2]) : ''; // C4 sería la columna 3 (índice 2)
                
                // Extraer el ID de la asignatura del formato "(ID) NOMBRE_ASIGNATURA"
                if (preg_match('/\((\d+)\)/', $asignatura, $matches)) {
                    $id_asignatura = $matches[1]; // El ID está en $matches[1]
                } else {
                    throw new Exception("No se encontró el ID de la asignatura en el formato esperado.");
                }
                break;
            }
            $row++;
        }
        fclose($handle);
        
        // Verificar si se extrajo el id_asignatura
        if (!isset($id_asignatura)) {
            throw new Exception("No se encontró el ID de la asignatura en la celda C4.");
        }

        // Verificar si el id_asignatura existe en la tabla asignatura
        $sqlCheckAsignatura = "
        SELECT COUNT(*) FROM asignatura WHERE id_asignatura = :id_asignatura;
        ";
        $stmtCheckAsignatura = $conexion->prepare($sqlCheckAsignatura);
        $stmtCheckAsignatura->bindParam(':id_asignatura', $id_asignatura);
        $stmtCheckAsignatura->execute();
        $asignaturaExists = $stmtCheckAsignatura->fetchColumn();

        if (!$asignaturaExists) {
        throw new Exception("El id_asignatura $id_asignatura no existe en la tabla de asignaturas.");
        }

        // Reabrir el archivo para procesar las filas a partir de la fila 9
        if (($handle = fopen($archivotmp, "r")) !== FALSE) {
            $fila_inicio = 9;
            $i = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($i < $fila_inicio) {
                    $i++;
                    continue;
                }

                // Procesar cada fila
                $alumno = !empty($data[1]) ? trim($data[1]) : '';

                if ($alumno == '') {
                    break;
                }

                // Extracción de asistencias, inasistencias y calificación de las columnas correspondientes
                $inasistencias_p2 = !empty($data[39]) ? $data[39] : ''; // Columna 40 en Excel
                $asistencias_p2 = !empty($data[40]) ? $data[40] : '';   // Columna 41 en Excel
                $calificacion_p2 = !empty($data[47]) ? $data[47] : '';  // Columna 48 en Excel

                // $nombrePartes = explode(" ", $alumno);
                // $apellido_paterno = isset($nombrePartes[0]) ? $nombrePartes[0] : '';
                // $apellido_materno = isset($nombrePartes[1]) ? $nombrePartes[1] : '';
                // $nombres = isset($nombrePartes[2]) ? implode(" ", array_slice($nombrePartes, 2)) : '';
                // echo "".$alumno;
                // Nueva consulta SQL que permite que apellido_materno sea NULL o esté vacío
                $sqlSelectAlumno = "
                    SELECT id_alumno, id_grupo FROM alumnos
                    WHERE CONCAT(apellido_paterno, IF(apellido_materno IS NOT NULL AND apellido_materno != '', CONCAT(' ', apellido_materno), ''), ' ', nombre_alumno ) = :nombre_completo
                    LIMIT 1;
                ";
                $stmtSelectAlumno = $conexion->prepare($sqlSelectAlumno);
                $stmtSelectAlumno->bindParam(':nombre_completo', $alumno);
                $stmtSelectAlumno->execute();
                $resultAlumnos = $stmtSelectAlumno->fetch(PDO::FETCH_ASSOC);
                // var_dump($resultAlumnos);
                $id_alumno = 0;
                $id_grupo =0;
                // Verifica si hay resultados antes de acceder al índice 0
                if (!empty($resultAlumnos) && isset($resultAlumnos)) {
                     //Si hay resultados, accede a los valores
                    $id_alumno = $resultAlumnos['id_alumno'];
                    $id_grupo = $resultAlumnos['id_grupo'];
                    // echo "".$id_alumno;
                    // echo "".$id_grupo;
                } else {
                    //Manejar el caso donde no se encuentra el alumno
                   $response["not_found"][] = $alumno;
                    $not_found_count++;
                    continue;
                }

                // Insertar o actualizar la tabla de asistencias
                $sqlSelectAsistencias = "
                    SELECT asistencias_p2, inasistencias_p2 FROM asistencias
                    WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura LIMIT 1;
                ";
                $stmtSelectAsistencias = $conexion->prepare($sqlSelectAsistencias);
                $stmtSelectAsistencias->bindParam(':id_alumno', $id_alumno);
                $stmtSelectAsistencias->bindParam(':id_asignatura', $id_asignatura);
                $stmtSelectAsistencias->execute();
                $resultAsistencias = $stmtSelectAsistencias->fetch(PDO::FETCH_ASSOC);
                $errorInfo = $stmtSelectAsistencias->errorInfo();
    // echo "Error en la inserción de calificaciones:<br>";
    // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
    // echo "Código de error: " . $errorInfo[1] . "<br>";
    // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
    //             var_dump($resultAsistencias);
                if (!$resultAsistencias) {
                    // Insertar nueva asistencia si no existe
                    $sqlInsertAsistencias = "
                    INSERT INTO asistencias (asistencias_p2, inasistencias_p2, id_alumno, id_asignatura, id_grupo)
                    VALUES (:asistencias_p2, :inasistencias_p2, :id_alumno, :id_asignatura, :id_grupo);
                    ";
                    $stmtAsistencias = $conexion->prepare($sqlInsertAsistencias);
                    $stmtAsistencias->bindParam(':asistencias_p2', $asistencias_p2);
                    $stmtAsistencias->bindParam(':inasistencias_p2', $inasistencias_p2);
                    $stmtAsistencias->bindParam(':id_alumno', $id_alumno);
                    $stmtAsistencias->bindParam(':id_asignatura', $id_asignatura); // Asegúrate de incluir id_asignatura
                    $stmtAsistencias->bindParam(':id_grupo', $id_grupo);
                    $stmtAsistencias->execute();
                    $errorInfo = $stmtAsistencias->errorInfo();
    // echo "Error en la inserción de calificaciones:<br>";
    // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
    // echo "Código de error: " . $errorInfo[1] . "<br>";
    // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
                    $inserted++;
                } else {
                    $current_asistencias_p2 = $resultAsistencias['asistencias_p2'];
                    $current_inasistencias_p2 = $resultAsistencias['inasistencias_p2'];
                    
                    // Actualizar la asistencia existente si los valores han cambiado
                    if ($current_asistencias_p2 !== $asistencias_p2 || $current_inasistencias_p2 !== $inasistencias_p2) {
                        $sqlUpdateAsistencias = "
                            UPDATE asistencias 
                            SET asistencias_p2 = :asistencias_p2, inasistencias_p2 = :inasistencias_p2
                            WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura;
                        ";
                        $stmtUpdateAsistencias = $conexion->prepare($sqlUpdateAsistencias);
                        $stmtUpdateAsistencias->bindParam(':asistencias_p2', $asistencias_p2);
                        $stmtUpdateAsistencias->bindParam(':inasistencias_p2', $inasistencias_p2);
                        $stmtUpdateAsistencias->bindParam(':id_alumno', $id_alumno);
                        $stmtUpdateAsistencias->bindParam(':id_asignatura', $id_asignatura); // Asegúrate de incluir id_asignatura en la condición
                        $stmtUpdateAsistencias->execute();
                        
                        $errorInfo = $stmtUpdateAsistencias->errorInfo();
                        // echo "Error en la inserción de calificaciones:<br>";
                        // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
                        // echo "Código de error: " . $errorInfo[1] . "<br>";
                        // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
                        if ($stmtUpdateAsistencias->rowCount() > 0) {
                            $updated++;
                        } else {
                            $unchanged++;
                        }
                    } 
                }

                // Insertar o actualizar la tabla de calificaciones
                $sqlSelectCalificaciones = "
                    SELECT calificacion_p2 FROM calificaciones
                    WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura LIMIT 1;
                ";
                $stmtSelectCalificaciones = $conexion->prepare($sqlSelectCalificaciones);
                $stmtSelectCalificaciones->bindParam(':id_alumno', $id_alumno);
                $stmtSelectCalificaciones->bindParam(':id_asignatura', $id_asignatura);
                $stmtSelectCalificaciones->execute();
                $resultCalificaciones = $stmtSelectCalificaciones->fetch(PDO::FETCH_ASSOC);
                // var_dump($resultCalificaciones);
                
                // Asegúrate de que el id_asistencia se obtiene correctamente
                $sqlSelectAsistenciaId = "
                SELECT id_asistencia FROM asistencias WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura LIMIT 1;
                ";
                $stmtSelectAsistenciaId = $conexion->prepare($sqlSelectAsistenciaId);
                $stmtSelectAsistenciaId->bindParam(':id_alumno', $id_alumno);
                $stmtSelectAsistenciaId->bindParam(':id_asignatura', $id_asignatura);
                $stmtSelectAsistenciaId->execute();
                $errorInfo = $stmtSelectAsistenciaId->errorInfo();
    // echo "Error en la inserción de calificaciones:<br>";
    // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
    // echo "Código de error: " . $errorInfo[1] . "<br>";
    // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
                $asistencias = $stmtSelectAsistenciaId->fetchColumn();
                // var_dump($asistencias);
                if ($asistencias) {
                    $id_asistencia = $asistencias;
                }

                // echo "".$calificacion_p2;
                // echo "".$id_asistencia;
                // echo "".$id_grupo;
                if (!$id_asistencia && !isset($id_asistencia['id_asistencia'])) {
                    // Error: no se encontró la asistencia asociada
                  throw new Exception("No se encontró la asistencia asociada para el alumno con ID $id_alumno y la asignatura $id_asignatura.");
                } else {
                    if ($resultCalificaciones === false) {
                        // echo "Esta adentro";
                        // Insertar nueva calificación si no existe
                        $sqlInsertCalificaciones = "
                            INSERT INTO calificaciones (calificacion_p2, id_alumno, id_asignatura, id_asistencia, id_grupo)
                            VALUES (:calificacion_p2, :id_alumno, :id_asignatura, :id_asistencia, :id_grupo);
                        ";
                        // Preparar la declaración
                        $stmtCalificaciones = $conexion->prepare($sqlInsertCalificaciones);
                        // echo "Asignatura:".$id_asignatura;
                        // echo "Alumno:".$id_alumno;
                        // Asegúrate de que todas las variables están correctamente vinculadas
                        $stmtCalificaciones->bindParam(':calificacion_p2', $calificacion_p2);
                        $stmtCalificaciones->bindParam(':id_alumno', $id_alumno);
                        $stmtCalificaciones->bindParam(':id_asignatura', $id_asignatura);
                        $stmtCalificaciones->bindParam(':id_asistencia', $id_asistencia);
                        $stmtCalificaciones->bindParam(':id_grupo', $id_grupo);
                       
                        $errorInfo = $stmtCalificaciones->errorInfo();
                        // echo "Error en la inserción de calificaciones:<br>";
                        // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
                        // echo "Código de error: " . $errorInfo[1] . "<br>";
                        // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
                        // Ejecutar la inserción
                        if ($stmtCalificaciones->execute()) {
                            // echo "Si inserto la calificacion";
                            $inserted++;
                        } else {
                            // echo "No inserto calificacion";
                           
                            throw new Exception("Error al insertar en la tabla de calificaciones para el alumno con ID $id_alumno y asignatura $id_asignatura.");
                        }
                        
                    } else {
                        $current_calificacion_p2 = $resultCalificaciones['calificacion_p2'];
                        
                        if ($current_calificacion_p2 !== $calificacion_p2) {
                            // Actualizar la calificación existente
                            $sqlUpdateCalificaciones = "
                                UPDATE calificaciones 
                                SET calificacion_p2 = :calificacion_p2
                                WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura;
                            ";
                            $stmtUpdateCalificaciones = $conexion->prepare($sqlUpdateCalificaciones);
                            $stmtUpdateCalificaciones->bindParam(':calificacion_p2', $calificacion_p2);
                            $stmtUpdateCalificaciones->bindParam(':id_alumno', $id_alumno);
                            $stmtUpdateCalificaciones->bindParam(':id_asignatura', $id_asignatura);
                            $stmtUpdateCalificaciones->execute();
                            $errorInfo = $stmtUpdateCalificaciones->errorInfo();
                            // echo "Error en la inserción de calificaciones:<br>";
                            // echo "SQLSTATE: " . $errorInfo[0] . "<br>";
                            // echo "Código de error: " . $errorInfo[1] . "<br>";
                            // echo "Mensaje de error: " . $errorInfo[2] . "<br>";
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
                
            }
            fclose($handle);
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

    } else {
        throw new Exception("No se pudo abrir el archivo CSV.");
    }

} catch(Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Ups, ha ocurrido un error inesperado: " . $e->getMessage() . " Alumno: " . $id_alumno . " Asignatura: " . $id_asignatura . "Grupo: " . $id_grupo . " ID Asistencia " . $id_asistencia;
    echo json_encode($response);
    exit;
}
?>