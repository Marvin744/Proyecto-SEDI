<?php
    include_once "../../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $response = ["status" => "", "message" => ""];

    try {
        // Aquí va tu lógica de inserción de datos
        $type       = $_FILES['dataCliente']['type'];
        $size       = $_FILES['dataCliente']['size'];
        $archivotmp = $_FILES['dataCliente']['tmp_name'];
        $lines      = file($archivotmp);

        $fila_inicio = 6; // Cambia este valor según sea necesario

        $abreviaturas = [
            'OFI.' => 'TÉCNICO EN OFIMÁTICA',
            'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
            'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
            'FTAL.' => 'TÉCNICO FORESTAL',
            'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA',
            'ECO-ADM.' => 'ECONÓMICO - ADMINISTRATIVO',
            'QUI-BIO.' => 'QUÍMICO - BIÓLOGO',
            'FIS-MAT.' => 'FÍSICO - MATEMÁTICO',
            // Agrega más abreviaturas según sea necesario
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

            // Extracción de datos (profesor, clases, asignatura, total_horas)
            $profesor = !empty($datos[1]) ? ($datos[1]) : '';
            $clases = !empty($datos[2]) ? ($datos[2]) : '';
            $asignatura = !empty($datos[3]) ? ($datos[3]) : '';
            $total_horas = !empty($datos[7]) ? ($datos[7]) : '';

            if ($profesor == '' && $clases == '' && $asignatura == '' && $total_horas == '') {
                $i++;
                continue;
            }

            // Divide el nombre completo del profesor en partes usando espacios
            $nombrePartes = explode(" ", $profesor);

            // Asigna los apellidos y nombres
            $apellido_paterno = isset($nombrePartes[0]) ? trim($nombrePartes[0]) : '';
            $apellido_materno = isset($nombrePartes[1]) ? trim($nombrePartes[1]) : '';
            $nombres = isset($nombrePartes[2]) ? trim(implode(" ", array_slice($nombrePartes, 2))) : '';

            // Verificación e inserción de docente
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
                $sqlInsertDocente = "
                    INSERT INTO docentes (nombre_docente, apellido_paterno, apellido_materno)
                    VALUES (:nombre_docente, :apellido_paterno, :apellido_materno)
                    ON DUPLICATE KEY UPDATE id_docente=LAST_INSERT_ID(id_docente);
                ";
                $stmtDocente = $conexion->prepare($sqlInsertDocente);
                $stmtDocente->bindParam(':nombre_docente', $nombres);
                $stmtDocente->bindParam(':apellido_paterno', $apellido_paterno);
                $stmtDocente->bindParam(':apellido_materno', $apellido_materno);
                $stmtDocente->execute();
                $id_docente = $conexion->lastInsertId();
                $inserted++;
            }

            list($semestre, $grupo, $especialidad) = explode(" ", $clases);

            // Reemplazo de abreviatura
            $abreviatura_especialidad = $especialidad;
            if (array_key_exists($especialidad, $abreviaturas)) {
                $especialidad = $abreviaturas[$especialidad];
            }

            // Verificación e inserción de semestre
            $sqlSelectSemestre = "
                SELECT id_semestre FROM semestre
                WHERE nombre_semestre = :nombre_semestre;
            ";
            $stmtSelectSemestre = $conexion->prepare($sqlSelectSemestre);
            $stmtSelectSemestre->bindParam(':nombre_semestre', $semestre);
            $stmtSelectSemestre->execute();
            $id_semestre = $stmtSelectSemestre->fetchColumn();

            if (!$id_semestre) {
                $sqlInsertSemestre = "
                    INSERT INTO semestre (nombre_semestre)
                    VALUES (:nombre_semestre)
                    ON DUPLICATE KEY UPDATE id_semestre=LAST_INSERT_ID(id_semestre);
                ";
                $stmtSemestre = $conexion->prepare($sqlInsertSemestre);
                $stmtSemestre->bindParam(':nombre_semestre', $semestre);
                $stmtSemestre->execute();
                $id_semestre = $conexion->lastInsertId();
            }

            // Verificación e inserción de especialidad
            $id_tipo_programa = 1; // Nueva Escuela Mexicana
            $sqlSelectEspecialidad = "
                SELECT id_especialidad FROM especialidad
                WHERE nombre_especialidad = :nombre_especialidad
                AND id_tipo_programa = :id_tipo_programa;
            ";
            $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
            $stmtSelectEspecialidad->bindParam(':nombre_especialidad', $especialidad);
            $stmtSelectEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
            $stmtSelectEspecialidad->execute();
            $id_especialidad = $stmtSelectEspecialidad->fetchColumn();

            if (!$id_especialidad) {
                $sqlInsertEspecialidad = "
                    INSERT INTO especialidad (nombre_especialidad, id_tipo_programa)
                    VALUES (:especialidad, :id_tipo_programa)
                    ON DUPLICATE KEY UPDATE id_especialidad=LAST_INSERT_ID(id_especialidad);
                ";
                $stmtEspecialidad = $conexion->prepare($sqlInsertEspecialidad);
                $stmtEspecialidad->bindParam(':especialidad', $especialidad);
                $stmtEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
                $stmtEspecialidad->execute();
                $id_especialidad = $conexion->lastInsertId();
            }

            // Verificación e inserción de asignatura
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura = :nombre_asignatura
                AND id_tipo_programa = :id_tipo_programa
                AND id_semestre = :id_semestre;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
            $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                $sqlInsertAsignatura = "
                    INSERT INTO asignatura (nombre_asignatura, id_tipo_programa, id_semestre)
                    VALUES (:asignatura, :id_tipo_programa, :id_semestre)
                    ON DUPLICATE KEY UPDATE id_asignatura=LAST_INSERT_ID(id_asignatura);
                ";
                $stmtAsignatura = $conexion->prepare($sqlInsertAsignatura);
                $stmtAsignatura->bindParam(':asignatura', $asignatura);
                $stmtAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                $stmtAsignatura->bindParam(':id_semestre', $id_semestre);
                $stmtAsignatura->execute();
                $id_asignatura = $conexion->lastInsertId();
            }

            // Verificación e inserción de grupo
            $sqlSelectGrupo = "
                SELECT id_grupo FROM grupo
                WHERE nombre_grupo = :grupo
                AND id_especialidad = :id_especialidad
                AND id_semestre = :id_semestre;
            ";
            $stmtSelectGrupo = $conexion->prepare($sqlSelectGrupo);
            $stmtSelectGrupo->bindParam(':grupo', $grupo);
            $stmtSelectGrupo->bindParam(':id_especialidad', $id_especialidad);
            $stmtSelectGrupo->bindParam(':id_semestre', $id_semestre);
            $stmtSelectGrupo->execute();
            $id_grupo = $stmtSelectGrupo->fetchColumn();

            if (!$id_grupo) {
                // Si el grupo no existe, se inserta un nuevo registro
                $sqlInsertGrupo = "
                    INSERT INTO grupo (nombre_grupo, id_especialidad, id_semestre)
                    VALUES (:grupo, :id_especialidad, :id_semestre)
                    ON DUPLICATE KEY UPDATE id_grupo=LAST_INSERT_ID(id_grupo);
                ";
                $stmtGrupo = $conexion->prepare($sqlInsertGrupo);
                $stmtGrupo->bindParam(':grupo', $grupo);
                $stmtGrupo->bindParam(':id_especialidad', $id_especialidad);
                $stmtGrupo->bindParam(':id_semestre', $id_semestre);
                $stmtGrupo->execute();
                $id_grupo = $conexion->lastInsertId();
            }
            
            // Verificación de existencia de horario
            $sqlSelectHorario = "
                SELECT COUNT(*) FROM horarios
                WHERE id_docente = :id_docente
                AND id_asignatura = :id_asignatura
                AND id_grupo = :id_grupo
                AND horas_semanales = :horas_semanales;
            ";
            $stmtSelectHorario = $conexion->prepare($sqlSelectHorario);
            $stmtSelectHorario->bindParam(':id_docente', $id_docente);
            $stmtSelectHorario->bindParam(':id_asignatura', $id_asignatura);
            $stmtSelectHorario->bindParam(':id_grupo', $id_grupo);
            $stmtSelectHorario->bindParam(':horas_semanales', $total_horas);
            $stmtSelectHorario->execute();
            $horarioExistente = $stmtSelectHorario->fetchColumn();

            if ($horarioExistente == 0) {
                // Inserta los datos en la tabla horarios, dejando los días de la semana en blanco
                $sqlInsertHorario = "
                    INSERT INTO horarios (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
                    VALUES (:id_docente, :id_asignatura, :id_grupo, null, null, null, null, null, null, :horas_semanales);
                ";
                $stmtHorario = $conexion->prepare($sqlInsertHorario);
                $stmtHorario->bindParam(':id_docente', $id_docente);
                $stmtHorario->bindParam(':id_asignatura', $id_asignatura);
                $stmtHorario->bindParam(':id_grupo', $id_grupo);
                $stmtHorario->bindParam(':horas_semanales', $total_horas);
                $stmtHorario->execute();
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
    
    echo json_encode($response);
?>

<?php
    include_once "../../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $response = ["status" => "", "message" => ""];

    try {
        // Aquí va tu lógica de inserción de datos
        $type       = $_FILES['dataCliente']['type'];
        $size       = $_FILES['dataCliente']['size'];
        $archivotmp = $_FILES['dataCliente']['tmp_name'];
        $lines      = file($archivotmp);

        $fila_inicio = 6; // Cambia este valor según sea necesario

        $abreviaturas = [
            'OFI.' => 'TÉCNICO EN OFIMÁTICA',
            'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
            'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
            'FTAL.' => 'TÉCNICO FORESTAL',
            'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA',
            'ECO-ADM.' => 'ECONÓMICO - ADMINISTRATIVO',
            'QUI-BIO.' => 'QUÍMICO - BIÓLOGO',
            'FIS-MAT.' => 'FÍSICO - MATEMÁTICO',
            // Agrega más abreviaturas según sea necesario
        ];

        $i = 0;
        $inserted = 0;
        //$id_tipo_programa = 1; // Nueva Escuela Mexicana

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

            // Extracción de datos (profesor, clases, asignatura, total_horas)
            $profesor = !empty($datos[1]) ? ($datos[1]) : '';
            $clases = !empty($datos[2]) ? ($datos[2]) : '';
            $asignatura = !empty($datos[3]) ? ($datos[3]) : '';
            $total_horas = !empty($datos[7]) ? ($datos[7]) : '';

            // Verificación e inserción de asignatura con validación de módulos y submódulos
            $pattern = '/^([A-Z]+)\.MOD(\d+)SUB(\d+):/';
            if (preg_match($pattern, $asignatura, $matches)) {
                // $especialidadAbrev = $matches[1]; // Ejemplo: ARH
                $numeroModulo = $matches[2]; // Ejemplo: 5 para MOD5
                $numeroSubmodulo = $matches[3]; // Ejemplo: 1 para SUB1
            
                $modulo = "Módulo " . $numeroModulo; // Ejemplo: Módulo 5
                $submodulo = "Submódulo " . $numeroSubmodulo; // Ejemplo: Submódulo 1
                echo "".$modulo."   ".$submodulo;
                // Reemplazo de abreviatura
                // if (array_key_exists($especialidadAbrev, $abreviaturas)) {
                //     $especialidad = $abreviaturas[$especialidadAbrev];
                // }
            
                // Asegurando que id_tipo_programa tenga un valor por defecto
                $id_tipo_programa = 1; // Valor por defecto, por ejemplo, "Nueva Escuela Mexicana"
            
                // Consulta para encontrar coincidencias parciales en la base de datos
                $sqlSelectAsignatura = "
                    SELECT id_asignatura FROM asignatura
                    WHERE nombre_asignatura LIKE :modulo
                    AND submodulos LIKE :submodulo
                    AND id_especialidad =:id_especialidad
                    AND id_tipo_programa = 1
                    AND id_semestre = :1;
                ";
                
                
                
                // Verificación e inserción de especialidad
                $sqlSelectEspecialidad = "
                    SELECT id_especialidad FROM especialidad
                    WHERE nombre_especialidad = :nombre_especialidad
                    AND id_tipo_programa = :id_tipo_programa;
                ";
                $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
                $stmtSelectEspecialidad->bindParam(':nombre_especialidad', $especialidad);
                $stmtSelectEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
                $stmtSelectEspecialidad->execute();
                $id_especialidad = $stmtSelectEspecialidad->fetchColumn();

                // Depuración: Verifica el valor de $id_especialidad
                if (!$id_especialidad) {
                    // Inserta en la tabla especialidad si no existe
                    $sqlInsertEspecialidad = "
                        INSERT INTO especialidad (nombre_especialidad, id_tipo_programa)
                        VALUES (:nombre_especialidad, :id_tipo_programa)
                        ON DUPLICATE KEY UPDATE id_especialidad=LAST_INSERT_ID(id_especialidad);
                    ";
                    $stmtEspecialidad = $conexion->prepare($sqlInsertEspecialidad);
                    $stmtEspecialidad->bindParam(':nombre_especialidad', $especialidad);
                    $stmtEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
                    $stmtEspecialidad->execute();
                    $id_especialidad = $conexion->lastInsertId();
                }

                // Ahora puedes usar $id_especialidad para la inserción en asignatura.
                
               
               
               
                // Agregamos el porcentaje (%) al inicio y al final de los valores para permitir coincidencias parciales
                $moduloParam = "%" . $modulo . "%";
                $submoduloParam = "%" . $submodulo . "%";
                
                $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
                $stmtSelectAsignatura->bindParam(':modulo', $moduloParam);
                $stmtSelectAsignatura->bindParam(':submodulo', $submoduloParam);
                $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad);
                // $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                // $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
                $stmtSelectAsignatura->execute();
                $id_asignatura = $stmtSelectAsignatura->fetchColumn();
            
                if (!$id_asignatura) {
                    // Si no existe, inserta la nueva asignatura con el módulo y submódulo proporcionados
                    $sqlInsertAsignatura = "
                        INSERT INTO asignatura (nombre_asignatura, submodulos, id_especialidad, id_tipo_programa, id_semestre)
                        VALUES (:modulo, :submodulo, :id_especialidad, 1, 1)
                        ON DUPLICATE KEY UPDATE id_asignatura=LAST_INSERT_ID(id_asignatura);
                    ";
                    $stmtAsignatura = $conexion->prepare($sqlInsertAsignatura);
                    $stmtAsignatura->bindParam(':modulo', $modulo);
                    $stmtAsignatura->bindParam(':submodulo', $submodulo);
                    $stmtAsignatura->bindParam(':id_especialidad', $id_especialidad);
                    // $stmtAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                    // $stmtAsignatura->bindParam(':id_semestre', $id_semestre);
                    $stmtAsignatura->execute();
                    $id_asignatura = $conexion->lastInsertId();
                }
            } else {
                // Caso en que la asignatura no coincide con el patrón de módulos y submódulos
                // Aquí se sigue el flujo normal, como lo tienes en tu código
                $sqlSelectAsignatura = "
                    SELECT id_asignatura FROM asignatura
                    WHERE nombre_asignatura = :nombre_asignatura
                    AND id_tipo_programa = 1
                    AND id_semestre = 1;
                ";
                $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
                $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
                // $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                // $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
                $stmtSelectAsignatura->execute();
                $id_asignatura = $stmtSelectAsignatura->fetchColumn();

                if (!$id_asignatura) {
                    $sqlInsertAsignatura = "
                        INSERT INTO asignatura (nombre_asignatura, id_tipo_programa, id_semestre)
                        VALUES (:asignatura, 1, 1)
                        ON DUPLICATE KEY UPDATE id_asignatura=LAST_INSERT_ID(id_asignatura);
                    ";
                    $stmtAsignatura = $conexion->prepare($sqlInsertAsignatura);
                    $stmtAsignatura->bindParam(':asignatura', $asignatura);
                    // $stmtAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                    // $stmtAsignatura->bindParam(':id_semestre', $id_semestre);
                    $stmtAsignatura->execute();
                    $id_asignatura = $conexion->lastInsertId();
                }
            }

            if ($profesor == '' && $clases == '' && $asignatura == '' && $total_horas == '') {
                $i++;
                continue;
            }

            // Divide el nombre completo del profesor en partes usando espacios
            $nombrePartes = explode(" ", $profesor);

            // Asigna los apellidos y nombres
            $apellido_paterno = isset($nombrePartes[0]) ? trim($nombrePartes[0]) : '';
            $apellido_materno = isset($nombrePartes[1]) ? trim($nombrePartes[1]) : '';
            $nombres = isset($nombrePartes[2]) ? trim(implode(" ", array_slice($nombrePartes, 2))) : '';

            // Verificación e inserción de docente
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
                $sqlInsertDocente = "
                    INSERT INTO docentes (nombre_docente, apellido_paterno, apellido_materno)
                    VALUES (:nombre_docente, :apellido_paterno, :apellido_materno)
                    ON DUPLICATE KEY UPDATE id_docente=LAST_INSERT_ID(id_docente);
                ";
                $stmtDocente = $conexion->prepare($sqlInsertDocente);
                $stmtDocente->bindParam(':nombre_docente', $nombres);
                $stmtDocente->bindParam(':apellido_paterno', $apellido_paterno);
                $stmtDocente->bindParam(':apellido_materno', $apellido_materno);
                $stmtDocente->execute();
                $id_docente = $conexion->lastInsertId();
                $inserted++;
            }

            list($semestre, $grupo, $especialidad) = explode(" ", $clases);

            // Reemplazo de abreviatura
            $abreviatura_especialidad = $especialidad;
            if (array_key_exists($especialidad, $abreviaturas)) {
                $especialidad = $abreviaturas[$especialidad];
            }

            // Verificación e inserción de semestre
            $sqlSelectSemestre = "
                SELECT id_semestre FROM semestre
                WHERE nombre_semestre = :nombre_semestre;
            ";
            $stmtSelectSemestre = $conexion->prepare($sqlSelectSemestre);
            $stmtSelectSemestre->bindParam(':nombre_semestre', $semestre);
            $stmtSelectSemestre->execute();
            $id_semestre = $stmtSelectSemestre->fetchColumn();

            if (!$id_semestre) {
                $sqlInsertSemestre = "
                    INSERT INTO semestre (nombre_semestre)
                    VALUES (:nombre_semestre)
                    ON DUPLICATE KEY UPDATE id_semestre=LAST_INSERT_ID(id_semestre);
                ";
                $stmtSemestre = $conexion->prepare($sqlInsertSemestre);
                $stmtSemestre->bindParam(':nombre_semestre', $semestre);
                $stmtSemestre->execute();
                $id_semestre = $conexion->lastInsertId();
            }

            // Verificación e inserción de especialidad
            // Justo después de asignar $id_tipo_programa
            $id_tipo_programa = 1; // Nueva Escuela Mexicana
            file_put_contents('debug.log', 'Valor de $id_tipo_programa al inicio: ' . var_export($id_tipo_programa, true) . PHP_EOL, FILE_APPEND);

            // Luego, verifica el valor justo antes de cada consulta SQL
            file_put_contents('debug.log', 'Valor de $id_tipo_programa antes de SQL: ' . var_export($id_tipo_programa, true) . PHP_EOL, FILE_APPEND);

            $sqlSelectEspecialidad = "
                SELECT id_especialidad FROM especialidad
                WHERE nombre_especialidad = :nombre_especialidad
                AND id_tipo_programa = 1;
            ";
            $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
            $stmtSelectEspecialidad->bindParam(':nombre_especialidad', $especialidad);
            // $stmtSelectEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
            file_put_contents('debug.log', 'Valor de $id_tipo_programa antes de execute: ' . var_export($id_tipo_programa, true) . PHP_EOL, FILE_APPEND);

            try {
                $stmtSelectEspecialidad->execute();
            } catch (PDOException $e) {
                file_put_contents('debug.log', 'Error en la ejecución de SQL: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
                $response["status"] = "error";
                $response["message"] = "Hubo un error al ejecutar la consulta SQL: " . $e->getMessage();
            }

            $id_especialidad = $stmtSelectEspecialidad->fetchColumn();

            if (!$id_especialidad) {
                $sqlInsertEspecialidad = "
                    INSERT INTO especialidad (nombre_especialidad, id_tipo_programa)
                    VALUES (:especialidad, 1)
                    ON DUPLICATE KEY UPDATE id_especialidad=LAST_INSERT_ID(id_especialidad);
                ";
                $stmtEspecialidad = $conexion->prepare($sqlInsertEspecialidad);
                $stmtEspecialidad->bindParam(':especialidad', $especialidad);
                // $stmtEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
                $stmtEspecialidad->execute();
                $id_especialidad = $conexion->lastInsertId();
            }
            echo "Depuración iniciada"; // Esto debería aparecer antes del var_dump
            var_dump($id_especialidad); die;

            // Verificación e inserción de asignatura
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura = :nombre_asignatura
                AND id_tipo_programa = 1
                AND id_semestre = :id_semestre;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
            // $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                $sqlInsertAsignatura = "
                    INSERT INTO asignatura (nombre_asignatura, id_tipo_programa, id_semestre)
                    VALUES (:asignatura, 1, 1)
                    ON DUPLICATE KEY UPDATE id_asignatura=LAST_INSERT_ID(id_asignatura);
                ";
                $stmtAsignatura = $conexion->prepare($sqlInsertAsignatura);
                $stmtAsignatura->bindParam(':asignatura', $asignatura);
                // $stmtAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
                // $stmtAsignatura->bindParam(':id_semestre', $id_semestre);
                $stmtAsignatura->execute();
                $id_asignatura = $conexion->lastInsertId();
            }

            // Verificación e inserción de grupo
            $sqlSelectGrupo = "
                SELECT id_grupo FROM grupo
                WHERE nombre_grupo = :grupo
                AND id_especialidad = :id_especialidad
                AND id_semestre = 1;
            ";
            $stmtSelectGrupo = $conexion->prepare($sqlSelectGrupo);
            $stmtSelectGrupo->bindParam(':grupo', $grupo);
            $stmtSelectGrupo->bindParam(':id_especialidad', $id_especialidad);
            // $stmtSelectGrupo->bindParam(':id_semestre', $id_semestre);
            $stmtSelectGrupo->execute();
            $id_grupo = $stmtSelectGrupo->fetchColumn();

            if (!$id_grupo) {
                // Si el grupo no existe, se inserta un nuevo registro
                $sqlInsertGrupo = "
                    INSERT INTO grupo (nombre_grupo, id_especialidad, id_semestre)
                    VALUES (:grupo, :id_especialidad, 1)
                    ON DUPLICATE KEY UPDATE id_grupo=LAST_INSERT_ID(id_grupo);
                ";
                $stmtGrupo = $conexion->prepare($sqlInsertGrupo);
                $stmtGrupo->bindParam(':grupo', $grupo);
                $stmtGrupo->bindParam(':id_especialidad', $id_especialidad);
                $stmtGrupo->bindParam(':id_semestre', $id_semestre);
                $stmtGrupo->execute();
                $id_grupo = $conexion->lastInsertId();
            }
            
            // Verificación de existencia de horario
            $sqlSelectHorario = "
                SELECT COUNT(*) FROM horarios
                WHERE id_docente = :id_docente
                AND id_asignatura = :id_asignatura
                AND id_grupo = :id_grupo
                AND horas_semanales = :horas_semanales;
            ";
            $stmtSelectHorario = $conexion->prepare($sqlSelectHorario);
            $stmtSelectHorario->bindParam(':id_docente', $id_docente);
            $stmtSelectHorario->bindParam(':id_asignatura', $id_asignatura);
            $stmtSelectHorario->bindParam(':id_grupo', $id_grupo);
            $stmtSelectHorario->bindParam(':horas_semanales', $total_horas);
            $stmtSelectHorario->execute();
            $horarioExistente = $stmtSelectHorario->fetchColumn();

            if ($horarioExistente == 0) {
                // Inserta los datos en la tabla horarios, dejando los días de la semana en blanco
                $sqlInsertHorario = "
                    INSERT INTO horarios (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
                    VALUES (:id_docente, :id_asignatura, :id_grupo, null, null, null, null, null, null, :horas_semanales);
                ";
                $stmtHorario = $conexion->prepare($sqlInsertHorario);
                $stmtHorario->bindParam(':id_docente', $id_docente);
                $stmtHorario->bindParam(':id_asignatura', $id_asignatura);
                $stmtHorario->bindParam(':id_grupo', $id_grupo);
                $stmtHorario->bindParam(':horas_semanales', $total_horas);
                $stmtHorario->execute();
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
    
    echo json_encode($response);
?>



<!-- Copia para hacer el F19 -->
<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$response = ["status" => "", "message" => ""];

try {
    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    $fila_inicio = 6; // Cambia este valor según sea necesario

    $abreviaturas = [
        'OFI.' => 'TÉCNICO EN OFIMÁTICA',
        'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
        'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
        'FTAL.' => 'TÉCNICO FORESTAL',
        'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA',
        'ECO-ADM.' => 'ECONÓMICO - ADMINISTRATIVO',
        'QUI-BIO.' => 'QUÍMICO - BIÓLOGO',
        'FIS-MAT.' => 'FÍSICO - MATEMÁTICO',
        // Agrega más abreviaturas según sea necesario
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

        // Extracción de datos
        $profesor = !empty($datos[1]) ? trim($datos[1]) : '';
        $clases = !empty($datos[2]) ? trim($datos[2]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[7]) ? trim($datos[7]) : '';

        if (empty($profesor) && empty($clases) && empty($asignatura) && empty($total_horas)) {
            $i++;
            continue;
        }

        // Procesar 'clases' para obtener semestre, grupo y especialidad
        $clasesPartes = explode(" ", $clases);
        if (count($clasesPartes) < 3) {
            throw new Exception("Formato inválido en la columna 'clases': " . $clases);
        }
        list($semestre, $grupo, $especialidadAbrev) = $clasesPartes;

        // Reemplazo de abreviatura
        $especialidad = isset($abreviaturas[$especialidadAbrev]) ? $abreviaturas[$especialidadAbrev] : $especialidadAbrev;

        $id_tipo_programa = 1; // Nueva Escuela Mexicana

        // Verificación de existencia de 'especialidad'
        $sqlSelectEspecialidad = "
            SELECT id_especialidad FROM especialidad
            WHERE nombre_especialidad = :nombre_especialidad
            AND id_tipo_programa = :id_tipo_programa;
        ";
        $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
        $stmtSelectEspecialidad->bindParam(':nombre_especialidad', $especialidad);
        $stmtSelectEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);
        $stmtSelectEspecialidad->execute();
        $id_especialidad = $stmtSelectEspecialidad->fetchColumn();

        if (!$id_especialidad) {
            throw new Exception("La especialidad '$especialidad' no existe en la base de datos.");
        }

        // Verificación de existencia de 'semestre'
        $sqlSelectSemestre = "
            SELECT id_semestre FROM semestre
            WHERE nombre_semestre = :nombre_semestre;
        ";
        $stmtSelectSemestre = $conexion->prepare($sqlSelectSemestre);
        $stmtSelectSemestre->bindParam(':nombre_semestre', $semestre);
        $stmtSelectSemestre->execute();
        $id_semestre = $stmtSelectSemestre->fetchColumn();

        if (!$id_semestre) {
            throw new Exception("El semestre '$semestre' no existe en la base de datos.");
        }

        // Verificación de existencia de 'asignatura'
        $pattern = '/^([A-Z]+)\.MOD(\d+)SUB(\d+):/';
        if (preg_match($pattern, $asignatura, $matches)) {
            // Asignatura con patrón de módulo y submódulo
            $numeroModulo = $matches[2];
            $numeroSubmodulo = $matches[3];
            $modulo = "Módulo " . $numeroModulo;
            $submodulo = "Submódulo " . $numeroSubmodulo;

            // Verificar si la asignatura ya existe
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura LIKE :modulo
                AND submodulos LIKE :submodulo
                AND id_especialidad = :id_especialidad
                AND id_tipo_programa = :id_tipo_programa
                AND id_semestre = :id_semestre;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $moduloParam = "%" . $modulo . "%";
            $submoduloParam = "%" . $submodulo . "%";
            $stmtSelectAsignatura->bindParam(':modulo', $moduloParam);
            $stmtSelectAsignatura->bindParam(':submodulo', $submoduloParam);
            $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad);
            $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                throw new Exception("La asignatura '$modulo $submodulo' no existe en la base de datos.");
            }
        } else {
            // Asignatura sin patrón de módulo y submódulo
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE nombre_asignatura = :nombre_asignatura
                AND id_tipo_programa = :id_tipo_programa
                AND id_semestre = :id_semestre;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
            $stmtSelectAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                throw new Exception("La asignatura '$asignatura' no existe en la base de datos.");
            }
        }

        // Procesar 'profesor' para obtener nombres y apellidos
        $nombrePartes = explode(" ", $profesor);
        $apellido_paterno = isset($nombrePartes[0]) ? trim($nombrePartes[0]) : '';
        $apellido_materno = isset($nombrePartes[1]) ? trim($nombrePartes[1]) : '';
        $nombres = isset($nombrePartes[2]) ? trim(implode(" ", array_slice($nombrePartes, 2))) : '';

        // Verificación de existencia de 'docente'
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

        // Verificación de existencia de 'grupo'
        $sqlSelectGrupo = "
            SELECT id_grupo FROM grupo
            WHERE nombre_grupo = :nombre_grupo
            AND id_especialidad = :id_especialidad
            AND id_semestre = :id_semestre;
        ";
        $stmtSelectGrupo = $conexion->prepare($sqlSelectGrupo);
        $stmtSelectGrupo->bindParam(':nombre_grupo', $grupo);
        $stmtSelectGrupo->bindParam(':id_especialidad', $id_especialidad);
        $stmtSelectGrupo->bindParam(':id_semestre', $id_semestre);
        $stmtSelectGrupo->execute();
        $id_grupo = $stmtSelectGrupo->fetchColumn();

        if (!$id_grupo) {
            throw new Exception("El grupo '$grupo' no existe en la base de datos.");
        }

        // Verificación de existencia de 'horario'
        $sqlSelectHorario = "
            SELECT COUNT(*) FROM horarios
            WHERE id_docente = :id_docente
            AND id_asignatura = :id_asignatura
            AND id_grupo = :id_grupo
            AND horas_semanales = :horas_semanales;
        ";
        $stmtSelectHorario = $conexion->prepare($sqlSelectHorario);
        $stmtSelectHorario->bindParam(':id_docente', $id_docente);
        $stmtSelectHorario->bindParam(':id_asignatura', $id_asignatura);
        $stmtSelectHorario->bindParam(':id_grupo', $id_grupo);
        $stmtSelectHorario->bindParam(':horas_semanales', $total_horas);
        $stmtSelectHorario->execute();
        $horarioExistente = $stmtSelectHorario->fetchColumn();

        if ($horarioExistente == 0) {
            // Insertar nuevo 'horario'
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

echo json_encode($response);
?>





<!-- El siguiente código funciona excepto a la hora de asignar el tipo de programa a las materias -->
<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

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
        'T. DE FILOSOFIA' => 'TEMAS DE FILOSOFIA',
        'MAT. APLICADA' => 'MATEMÁTICAS APLICADAS',
    ];

    $tutorias = [
        'TUTORÍAS I', 'TUTORÍAS II', 'TUTORÍAS III', 
        'TUTORÍAS IV', 'TUTORÍAS V', 'TUTORÍAS VI'
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

        $profesor = !empty($datos[1]) ? trim($datos[1]) : '';
        $clases = !empty($datos[2]) ? trim($datos[2]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[7]) ? trim($datos[7]) : '';

        if (empty($profesor) && empty($clases) && empty($asignatura) && empty($total_horas)) {
            $i++;
            continue;
        }

        $clasesPartes = explode(" ", $clases);
        if (count($clasesPartes) < 3) {
            throw new Exception("Formato inválido en la columna 'clases': " . $clases);
        }
        list($semestre, $grupo, $especialidadAbrev) = $clasesPartes;

        // Reemplazo de abreviatura de asignatura por su nombre completo
        if (array_key_exists($asignatura, $abreviaturas_asignaturas)) {
            $asignatura = $abreviaturas_asignaturas[$asignatura];
        }

        // Obtener id_semestre
        $sqlSelectSemestre = "SELECT id_semestre FROM semestre WHERE nombre_semestre = :semestre LIMIT 1;";
        $stmtSelectSemestre = $conexion->prepare($sqlSelectSemestre);
        $stmtSelectSemestre->bindParam(':semestre', $semestre);
        $stmtSelectSemestre->execute();
        $id_semestre = $stmtSelectSemestre->fetchColumn();

        if (!$id_semestre) {
            throw new Exception("El semestre '$semestre' no existe en la base de datos.");
        }

        $especialidad = isset($abreviaturas_especialidades[$especialidadAbrev]) ? $abreviaturas_especialidades[$especialidadAbrev] : $especialidadAbrev;

        // Obtener id_especialidad y tipo de programa basado en la asignatura
        $sqlSelectEspecialidad = "
            SELECT esp.id_especialidad, tp.tipo_programa FROM especialidad esp
            JOIN tipo_programa tp ON esp.id_tipo_programa = tp.id_tipo_programa
            JOIN asignatura asig ON asig.id_especialidad = esp.id_especialidad
            WHERE asig.nombre_asignatura = :nombre_asignatura
            AND asig.id_semestre = :id_semestre
            LIMIT 1;";
        $stmtSelectEspecialidad = $conexion->prepare($sqlSelectEspecialidad);
        $stmtSelectEspecialidad->bindParam(':nombre_asignatura', $asignatura);
        $stmtSelectEspecialidad->bindParam(':id_semestre', $id_semestre);
        $stmtSelectEspecialidad->execute();
        $resultadoEspecialidad = $stmtSelectEspecialidad->fetch(PDO::FETCH_ASSOC);

        if (!$resultadoEspecialidad) {
            throw new Exception("No se pudo determinar el tipo de programa o la especialidad para la asignatura '$asignatura'.");
        }

        $id_especialidad = $resultadoEspecialidad['id_especialidad'];
        $tipo_programa = $resultadoEspecialidad['tipo_programa'];

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

        // Determinar si se inserta en horarios o actividades_complementarias
        $tabla_insercion = in_array($asignatura, $tutorias) ? 'actividades_complementarias' : 'horarios';

        // Verificación de existencia en la tabla seleccionada
        $sqlSelectHorario = "
            SELECT COUNT(*) FROM $tabla_insercion
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
            // Insertar nuevo registro en la tabla seleccionada
            $sqlInsertHorario = "
                INSERT INTO $tabla_insercion (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
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






<!-- YA FUNCIONAAAAA!!!! Checkpoint YA INSERTA datos, pero no tiene validación módulo y submódulo ni tampoco inserción a tabla actividades_complementarias -->

<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

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

        $profesor = !empty($datos[1]) ? trim($datos[1]) : '';
        $clases = !empty($datos[2]) ? trim($datos[2]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[7]) ? trim($datos[7]) : '';

        if (empty($profesor) && empty($clases) && empty($asignatura) && empty($total_horas)) {
            $i++;
            continue;
        }

        // Saltar las filas que contengan asignaturas de TUTORÍAS
        if (in_array($asignatura, $tutorias)) {
            $i++;
            continue;
        }

        $clasesPartes = explode(" ", $clases);
        if (count($clasesPartes) < 3) {
            throw new Exception("Formato inválido en la columna 'clases': " . $clases);
        }
        list($semestre, $nombre_grupo, $especialidadAbrev) = $clasesPartes;

        // Convertir el número romano al id_semestre
        if (array_key_exists($semestre, $mapa_semestres)) {
            $id_semestre = $mapa_semestres[$semestre];
        } else {
            throw new Exception("El semestre '$semestre' no es válido.");
        }

        // Reemplazo de abreviatura de asignatura por su nombre completo
        if (array_key_exists($asignatura, $abreviaturas_asignaturas)) {
            $asignatura = $abreviaturas_asignaturas[$asignatura];
        }

        $especialidad = isset($abreviaturas_especialidades[$especialidadAbrev]) ? $abreviaturas_especialidades[$especialidadAbrev] : $especialidadAbrev;

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

        // Verificación de existencia de la asignatura con id_especialidad y tronco_comun
        $sqlSelectAsignatura = "
            SELECT id_asignatura FROM asignatura
            WHERE nombre_asignatura = :nombre_asignatura
            AND (id_especialidad = :id_especialidad OR tronco_comun = 1)
            LIMIT 1;
        ";
        $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
        $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
        $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad);
        $stmtSelectAsignatura->execute();
        $id_asignatura = $stmtSelectAsignatura->fetchColumn();

        if (!$id_asignatura) {
            throw new Exception("La asignatura '$asignatura' no existe en la base de datos.");
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






<!-- Medio quiere funcionar lo de módulos y submódulos. Si inserta bien todas las asignaturas, nada mas en módulos hay pedillos leves -->

<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

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

        $profesor = !empty($datos[1]) ? trim($datos[1]) : '';
        $clases = !empty($datos[2]) ? trim($datos[2]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[7]) ? trim($datos[7]) : '';

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

        // Verificar si la asignatura comienza con la abreviatura de la especialidad y contiene módulo y submódulo
        if (strpos($asignatura, $especialidadAbrev) === 0 && preg_match('/:(.+)/', $asignatura, $matches)) {
            // Extraer la descripción después del ":"
            $asignaturaDescripcion = trim($matches[1]);

            // Búsqueda de la asignatura en la base de datos
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE (
                    LOWER(nombre_asignatura) LIKE LOWER(:asignaturaDescripcion)
                    OR LOWER(submodulos) LIKE LOWER(:asignaturaDescripcion)
                )
                OR id_semestre = :id_semestre
                OR id_especialidad = :id_especialidad
                LIMIT 1;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $asignaturaDescripcionLike = "%$asignaturaDescripcion%";
            $stmtSelectAsignatura->bindParam(':asignaturaDescripcion', $asignaturaDescripcionLike);
            $stmtSelectAsignatura->bindParam(':id_semestre', $id_semestre);
            $stmtSelectAsignatura->bindParam(':id_especialidad', $id_especialidad);
            $stmtSelectAsignatura->execute();
            $id_asignatura = $stmtSelectAsignatura->fetchColumn();

            if (!$id_asignatura) {
                throw new Exception("La asignatura relacionada con '$asignaturaDescripcion' no existe en la base de datos.");
            }
        } else {
            // Reemplazo de abreviatura de asignatura por su nombre completo si no es un módulo/submódulo
            if (array_key_exists($asignatura, $abreviaturas_asignaturas)) {
                $asignatura = $abreviaturas_asignaturas[$asignatura];
            }

            // Verificación de existencia de la asignatura
            $sqlSelectAsignatura = "
                SELECT id_asignatura FROM asignatura
                WHERE LOWER(nombre_asignatura) = LOWER(:nombre_asignatura)
                AND (id_especialidad = :id_especialidad OR tronco_comun = 1)
                LIMIT 1;
            ";
            $stmtSelectAsignatura = $conexion->prepare($sqlSelectAsignatura);
            $stmtSelectAsignatura->bindParam(':nombre_asignatura', $asignatura);
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





<!-- YA FUNCIONA ESTA WEAAAAAA, ya inserta correctamente todas las asignaturas, pero le falta insertar en la tabla de actividades complementarias -->

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

        $profesor = !empty($datos[1]) ? trim($datos[1]) : '';
        $clases = !empty($datos[2]) ? trim($datos[2]) : '';
        $asignatura = !empty($datos[3]) ? trim($datos[3]) : '';
        $total_horas = !empty($datos[7]) ? trim($datos[7]) : '';

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





























<!-- Antes de regarla con la tabla del f19 -->
<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
?>
<?php //require_once "../vistas/encabezado.php";?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 4px;
        text-align: center;
    }

    .header-row {
        background-color: #d3d3d3;
        /* Color más oscuro */
    }
    </style>
</head>

<body>
    <h1>Horario Académico</h1>

    <div class="datos_f19">
        <label for="">Número de Oficio: </label>
        <input type="number"><br>

        <label for="">Docente: </label>
        <select name="docentes" id="docentes">
            <?php

                $sql="SELECT DISTINCT id_docente FROM docentes ORDER BY apellido_paterno ASC;";
                
                        $query = $conexion->prepare($sql);
                       
                        $query->execute();
                        $result = $query->fetchAll();

                        foreach ($result as $row) {
                    
                            $nombre_docente = $row['id_docente'];
                            //$apellido_paterno = $row['apellido_paterno'];
                            //$apellido_materno = $row['apellido_materno'];
                            // Genera una opción con el nombre y apellido del docente
                            echo "<option value=\"$nombre_docente\">$nombre_docente</option>";
                        }
                
            ?>
        </select><br>

        <label for="fecha">Fecha: </label>
        <input id="fecha" class="fecha" type="date"><br>

        <label for="">Periodo: </label>
        <input type="text" name="" id="">
    </div>

    <table>
        <thead>
            <tr class="header-row">
                <th colspan="3">Carga Académica</th>
                <th colspan="8">Horario</th>
            </tr>
            <tr>
                <th>ASIGNATURA</th>
                <th>GRUPO</th>
                <th>ESPECIALIDAD</th>
                <th>LUNES</th>
                <th>MARTES</th>
                <th>MIÉRCOLES</th>
                <th>JUEVES</th>
                <th>VIERNES</th>
                <th>SÁBADO</th>
                <th class="horas-semanales">HORAS SEMANALES</th>
                <th>SEMESTRE</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sqlHorarios = "SELECT 
                                    MIN(h.id_horario) AS id_horario,
                                    a.nombre_asignatura AS asignatura, 
                                    a.submodulos AS submodulos,
                                    g.nombre_grupo AS grupo, 
                                    e.nombre_especialidad AS especialidad, 
                                    s.nombre_semestre AS nombre_semestre, -- De la tabla semestre se agarra el nombre_semestre
                                    s.numero_semestre AS numero_semestre, -- De la tabla semestre se agarra el numero_semestre
                                    GROUP_CONCAT(DISTINCT h.lunes ORDER BY h.lunes SEPARATOR ' ') AS lunes,
                                    GROUP_CONCAT(DISTINCT h.martes ORDER BY h.martes SEPARATOR ' ') AS martes,
                                    GROUP_CONCAT(DISTINCT h.miercoles ORDER BY h.miercoles SEPARATOR ' ') AS miercoles,
                                    GROUP_CONCAT(DISTINCT h.jueves ORDER BY h.jueves SEPARATOR ' ') AS jueves,
                                    GROUP_CONCAT(DISTINCT h.viernes ORDER BY h.viernes SEPARATOR ' ') AS viernes,
                                    GROUP_CONCAT(DISTINCT h.sabado ORDER BY h.sabado SEPARATOR ' ') AS sabado,
                                    SUM(h.horas_semanales) AS horas_semanales
                                    -- Quitamos la columna de docente
                                FROM horarios h
                                JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                                JOIN grupo g ON h.id_grupo = g.id_grupo
                                JOIN especialidad e ON g.id_especialidad = e.id_especialidad
                                JOIN semestre s ON g.id_semestre = s.id_semestre -- Aquí se une con la tabla semestre
                                GROUP BY 
                                    a.nombre_asignatura, 
                                    a.submodulos,
                                    g.nombre_grupo, 
                                    e.nombre_especialidad, 
                                    s.nombre_semestre, -- Agrupamos por nombre_semestre
                                    s.numero_semestre -- Agrupamos por numero_semestre
                                ORDER BY 
                                    a.nombre_asignatura, 
                                    g.nombre_grupo, 
                                    e.nombre_especialidad, 
                                    s.numero_semestre ASC;";

                $queryTableHorarios = $conexion->prepare($sqlHorarios);
                $queryTableHorarios->execute();
                $result = $queryTableHorarios->fetchAll();

                $prevAsignatura = '';
                $rowspan = 1;
                $totalHorasSemanales = 0; // Inicializar variable para el total de horas semanales

                foreach ($result as $index => $row) {
                    // Sumar las horas semanales
                    $totalHorasSemanales += $row['horas_semanales'];

                    // Checar si la asignatura es la misma que la anterior
                    if ($row['asignatura'] === $prevAsignatura) {
                        $rowspan++;
                        echo "<tr>";
                        // No imprimir la asignatura de nuevo, simplemente incrementar el rowspan
                        echo "<td style='display:none;'></td>";
                    } else {
                        // Nueva asignatura, imprimir la fila completa
                        if ($rowspan > 1) {
                            // Si hubo un rowspan anterior, cerrarlo
                            echo "<script>
                            document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                            </script>";
                        }
                        $rowspan = 1;
                        $prevAsignatura = $row['asignatura'];

                        // Concatenamos nombre_asignatura y submodulos si submodulos no está vacío
                        $asignaturaCompleta = $row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura'];

                        echo "<tr>";
                        echo "<td data-asignatura='{$row['asignatura']}'>{$row['asignatura']}</td>";
                    }
                    echo "<td>{$row['nombre_semestre']} {$row['grupo']}</td>";
                    echo "<td>{$row['especialidad']}</td>";
                    echo "<td contenteditable='true' data-day='lunes' data-id='{$row['id_horario']}'>{$row['lunes']}</td>";
                    echo "<td contenteditable='true' data-day='martes' data-id='{$row['id_horario']}'>{$row['martes']}</td>";
                    echo "<td contenteditable='true' data-day='miercoles' data-id='{$row['id_horario']}'>{$row['miercoles']}</td>";
                    echo "<td contenteditable='true' data-day='jueves' data-id='{$row['id_horario']}'>{$row['jueves']}</td>";
                    echo "<td contenteditable='true' data-day='viernes' data-id='{$row['id_horario']}'>{$row['viernes']}</td>";
                    echo "<td contenteditable='true' data-day='sabado' data-id='{$row['id_horario']}'>{$row['sabado']}</td>";
                    echo "<td>{$row['horas_semanales']}</td>";
                    echo "<td>{$row['numero_semestre']}</td>";
                    echo "</tr>";
                }

                if ($rowspan > 1) {
                    echo "<script>
                    document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                    </script>";
                }

                // Mostrar el total de horas semanales
                // echo "<tr>";
                // echo "<td colspan='9' style='text-align:right;'><strong>Total de Horas Semanales:</strong></td>";
                // echo "<td colspan='2'><strong>{$totalHorasSemanales}</strong></td>";
                // echo "</tr>";
            ?>
        </tbody>

        <tfoot>
            <tr class="header-row">
                <td colspan="3">Subtotal de Horas</td>
                <td id="subtotal-lunes">0</td>
                <td id="subtotal-martes">0</td>
                <td id="subtotal-miercoles">0</td>
                <td id="subtotal-jueves">0</td>
                <td id="subtotal-viernes">0</td>
                <td id="subtotal-sabado">0</td>
                <td id="subtotal-horas"><strong><?php echo $totalHorasSemanales; ?></strong></td>
                <td id="subtotal-docentes"></td>

            </tr>
        </tfoot>
    </table>
    <br>
    <button id="verificar-horas">Verificar Horas</button>

    <script>
        // Añadir evento al botón para verificar las horas
        document.getElementById('verificar-horas').addEventListener('click', function() {

            // Sumar las horas de los días de la semana (lunes a sábado)
            const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal-"]'))
                .slice(0, 6) // Solo considerar los días de lunes a sábado
                .reduce((total, td) => total + parseInt(td.textContent || 0, 10), 0);

            // Obtener el total de horas semanales desde el campo correspondiente
            const totalHorasSemanales = parseInt(document.getElementById('subtotal-horas').textContent || 0, 10);

            if (totalHorasDias === totalHorasSemanales) {
                alert('Las horas coinciden correctamente.');
            } else {
                alert('Las horas no coinciden. Por favor, revise los horarios.');
            }

        });

        // Script para detectar fecha actual
        window.onload = function() {
            // Obtener el input de fecha
            const fechaInput = document.getElementById('fecha');

            // Obtener la fecha actual
            const hoy = new Date();

            // Formatear la fecha en YYYY-MM-DD
            const fechaFormateada = hoy.toISOString().split('T')[0];

            // Establecer la fecha actual en el input
            fechaInput.value = fechaFormateada;
        };
    </script>

    <script>
        var subtotalHoras = 0;
        var totalHorasDias = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('td[contenteditable="true"]');

            // Función para actualizar la base de datos
            function updateDatabase(id, day, value) {
                return fetch('update_horas.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id,
                            day,
                            value
                        })
                    })
                    .then(response => response.json())
                    .catch(error => console.error('Error:', error));
            }

            // Función para verificar solapamiento de horarios
            function checkColumnOverlap(columnCells) {
                const times = [];

                for (let cell of columnCells) {
                    const value = cell.textContent.trim();
                    const ranges = value.split(' ');
                    for (let range of ranges) {
                        const [start, end] = range.split('-').map(time => {
                            const [hour, minute] = time.split(':').map(Number);
                            return hour * 60 + minute; // Convertir a minutos desde medianoche
                        });
                        times.push({
                            start,
                            end,
                            cell
                        });
                    }
                }

                for (let i = 0; i < times.length - 1; i++) {
                    for (let j = i + 1; j < times.length; j++) {
                        // Verificar si los rangos de horas se solapan
                        if (times[i].start < times[j].end && times[i].end > times[j].start) {
                            return times[j].cell; // Devolver la celda que causa el empalme
                        }
                    }
                }
                return null; // No se empalman
            }

            // Función para actualizar los subtotales
            function updateSubtotals() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                let totalHorasDias = 0;
                let subtotalHoras = 0;

                days.forEach(day => {
                    const dayCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    let subtotal = 0;

                    dayCells.forEach(cell => {
                        const ranges = cell.textContent.split(' ');
                        ranges.forEach(range => {
                            const times = range.split('-');
                            if (times.length === 2) {
                                const start = times[0].split(':').map(Number);
                                const end = times[1].split(':').map(Number);
                                const duration = (end[0] * 60 + end[1]) - (start[0] * 60 +
                                    start[1]);
                                subtotal += duration / 60; // Convertir minutos a horas
                            }
                        });
                    });

                    totalHorasDias += subtotal;
                    document.getElementById(`subtotal-${day}`).textContent = Math.floor(subtotal);
                });

                const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
                weeklyHoursCells.forEach(cell => {
                    const horas = parseInt(cell.textContent.trim(), 10);
                    subtotalHoras += isNaN(horas) ? 0 : horas;
                });

                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
            }

            // Evento para detectar cambios en celdas editables
            cells.forEach(cell => {
                cell.addEventListener('blur', function() {
                    const day = this.getAttribute('data-day');
                    const columnCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    const editedCell = this; // Guardamos la celda que fue editada

                    // Verificar empalme de horarios para todas las celdas de la misma columna (día)
                    const overlapCell = checkColumnOverlap(columnCells);
                    if (overlapCell) {
                        alert(`Los horarios se empalman en ${day}. El campo que acabas de modificar se limpiará para que puedas corregirlo.`);

                        // Limpiar el contenido de la celda editada (no el de la celda empalmada)
                        editedCell.textContent = '';

                        // Limpiar el contenido en la base de datos
                        const ids = editedCell.getAttribute('data-id').split(','); // Obtener todos los ids_horario
                        
                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {
                                
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals(); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });

                        return;
                    }

                    const ids = this.getAttribute('data-id').split(','); // Obtener todos los ids_horario
                    const value = this.textContent.trim();

                    // Validar el formato del rango de horas (ejemplo: 08:00-10:00 o múltiples rangos)
                    const timeRangePattern = /^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/;

                    if (value === "" || timeRangePattern.test(value)) {
                        // Si la validación de solapamiento y formato es exitosa, proceder con la actualización
                        ids.forEach(id => {
                            updateDatabase(id, day, value).then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals();
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:', id, data.error || data.message);
                                }
                            });
                        });
                    } else {
                        alert(
                            'Formato de rango de horas inválido. El campo se limpiará para que puedas corregirlo.'
                        );
                        this.textContent = ''; // Limpiar el contenido de la celda actual si el formato es inválido

                        // Limpiar el contenido en la base de datos
                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals(); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });
                    }

                    updateSubtotals(); // <- Llamada extra para asegurarnos de actualizar los subtotales después de cada edición
                });
            });

            // Inicializar subtotales al cargar la página
            updateSubtotals();

            function checkColumnOverlap(columnCells) {
                const times = [];

                for (let cell of columnCells) {
                    const value = cell.textContent.trim();
                    const ranges = value.split(' ');

                    for (let range of ranges) {
                        const [start, end] = range.split('-').map(time => {
                            const [hour, minute] = time.split(':').map(Number);
                            return hour * 60 + minute; // Convertir a minutos desde medianoche
                        });
                        times.push({
                            start,
                            end,
                            cell
                        });
                    }
                }

                for (let i = 0; i < times.length - 1; i++) {
                    for (let j = i + 1; j < times.length; j++) {
                        // Verificar si los rangos de horas se solapan
                        if (times[i].start < times[j].end && times[i].end > times[j].start) {
                            // Comprobamos cuál es la celda actual (la que se está editando)
                            if (times[i].cell === document.activeElement) {
                                return times[i].cell; // Retornar la celda actual si es la que causa el empalme
                            } else {
                                return times[j]
                                    .cell; // De lo contrario, retornar la otra celda que causa el empalme
                            }
                        }
                    }
                }
                return null; // No se empalman
            }

            function updateSubtotals() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                const subtotals = {
                    lunes: 0,
                    martes: 0,
                    miercoles: 0,
                    jueves: 0,
                    viernes: 0,
                    sabado: 0
                };


                let aux = 0;

                days.forEach(day => {
                    const dayCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    dayCells.forEach(cell => {
                        const ranges = cell.textContent.split(' '); // Separar por espacios
                        ranges.forEach(range => {
                            const times = range.split('-');
                            if (times.length === 2) {
                                const start = times[0].split(':');
                                const end = times[1].split(':');
                                const startHour = parseInt(start[0], 10);
                                const startMinute = parseInt(start[1], 10);
                                const endHour = parseInt(end[0], 10);
                                const endMinute = parseInt(end[1], 10);
                                const duration = (endHour * 60 + endMinute) - (startHour *
                                    60 + startMinute);
                                subtotals[day] += duration /
                                    60; // Convertir minutos a horas
                            }
                        });
                    });
                    // Mostrar el subtotal como entero
                    aux = Math.floor(subtotals[day]);
                    document.getElementById(`subtotal-${day}`).textContent = aux;
                    totalHorasDias += subtotals[day];
                });

                // Calcular el subtotal de horas semanales
                const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
                weeklyHoursCells.forEach(cell => {

                    const horas = parseInt(str, 10); // Convertir a número
                    //const horas = parseFloat(cell.textContent.trim());  Convertir a número
                    subtotalHoras += isNaN(horas) ? 0 : horas; // Sumar si es un número válido
                });

                // Mostrar el subtotal de horas semanales
                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
            }

            // Llamar a la función para calcular los subtotales al cargar la página
            updateSubtotals();

            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('tbody tr');
                const groupedData = {};

                rows.forEach(row => {
                    const asignatura = row.querySelector('td:nth-child(1)').textContent.trim();
                    const grupo = row.querySelector('td:nth-child(2)').textContent.trim();
                    const especialidad = row.querySelector('td:nth-child(3)').textContent.trim();
                    const key = `${asignatura}-${grupo}-${especialidad}`;

                    if (!groupedData[key]) {
                        groupedData[key] = {
                            firstRow: row,
                            count: 1
                        };
                    } else {
                        groupedData[key].count += 1;
                        const firstRow = groupedData[key].firstRow;

                        // Aumentar el rowspan en la primera celda de la asignatura
                        const rowspanCell = firstRow.querySelector('td:nth-child(1)');
                        rowspanCell.setAttribute('rowspan', groupedData[key].count);

                        // Ocultar las celdas duplicadas en las filas adicionales
                        row.querySelector('td:nth-child(1)').style.display = 'none'; // Asignatura
                        row.querySelector('td:nth-child(2)').style.display = 'none'; // Grupo
                        row.querySelector('td:nth-child(3)').style.display = 'none'; // Especialidad
                    }
                });
            });


        });
    </script>
</body>

</html>