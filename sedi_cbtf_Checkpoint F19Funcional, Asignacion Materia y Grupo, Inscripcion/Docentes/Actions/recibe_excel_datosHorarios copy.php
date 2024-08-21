<?php
    include_once "../../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    // Define la fila de inicio
    $fila_inicio = 6; // Cambia este valor según sea necesario

    $i = 0;

    foreach ($lines as $line) {
        // Salta las primeras filas
        if ($i < $fila_inicio) {
            $i++;
            continue;
        }

        // Verifica si la línea está vacía
        if (trim($line) == '') {
            $i++;
            continue;
        }

        $datos = explode(",", $line);

        $profesor       = !empty($datos[1]) ? ($datos[1]) : '';
        $clases         = !empty($datos[2]) ? ($datos[2]) : '';
        $asignatura     = !empty($datos[3]) ? ($datos[3]) : '';
        $total_horas    = !empty($datos[7]) ? ($datos[7]) : '';

        // Verifica si los campos importantes están vacíos
        if ($profesor == '' && $clases == '' && $asignatura == '' && $total_horas == '') {
            $i++;
            continue;
        }

        $insertar = "INSERT INTO `datos_horarios` (`profesor`, `clases`, `asignatura`, `total_horas`) VALUES ('$profesor', '$clases', '$asignatura', '$total_horas');";
        $query = $conexion->prepare($insertar);
        $query->execute();

        echo "<div>". $i. "). " .$line."</div>";
        $i++;
    }

    // Calcula la cantidad de registros agregados
    $cantidad_registros = count($lines);
    $cantidad_regist_agregados = ($cantidad_registros - $fila_inicio);

    echo '<p style="text-align:center; color:#333;">Total de Registros: '. $cantidad_regist_agregados .'</p>';

    // Guardar el nombre del archivo y la fecha de subida
    $nombre_archivo = basename($_FILES["dataCliente"]["name"]);
    $sql = "INSERT INTO archivos_subidos (nombre_archivo) VALUES ('$nombre_archivo')";

    if ($conexion->query($sql) !== FALSE) {
        echo "Información del archivo guardada correctamente.";
    } else {
        $errorInfo = $conexion->errorInfo();
        echo "Error: " . $sql . "<br>" . $errorInfo[2];
    }

    // Cerrar la conexión
    $conexion = null;
?>

<a href="../docente_f19.php">Atras</a>



<!-- YA FUNCIONANDOOOOO -->

<?php
    include_once "../../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    // Define la fila de inicio
    $fila_inicio = 6; // Cambia este valor según sea necesario

    $i = 0;

    foreach ($lines as $line) {
        // Salta las primeras filas
        if ($i < $fila_inicio) {
            $i++;
            continue;
        }

        // Verifica si la línea está vacía
        if (trim($line) == '') {
            $i++;
            continue;
        }

        $datos = explode(",", $line);

        $profesor       = !empty($datos[1]) ? ($datos[1]) : '';
        $clases         = !empty($datos[2]) ? ($datos[2]) : '';
        $asignatura     = !empty($datos[3]) ? ($datos[3]) : '';
        $total_horas    = !empty($datos[7]) ? ($datos[7]) : '';

        // Verifica si los campos importantes están vacíos
        if ($profesor == '' && $clases == '' && $asignatura == '' && $total_horas == '') {
            $i++;
            continue;
        }

        // Divide el nombre del profesor en partes
        $nombreCompleto = explode(" ", $profesor);
        $nombre_docente = array_pop($nombreCompleto);
        $apellido_materno = array_pop($nombreCompleto);
        $apellido_paterno = implode(" ", $nombreCompleto);

        // Verifica si el docente ya existe
        $sqlSelectDocente = "
            SELECT id_docente FROM docentes
            WHERE nombre_docente = :nombre_docente
            AND apellido_paterno = :apellido_paterno
            AND apellido_materno = :apellido_materno;
        ";
        $stmtSelectDocente = $conexion->prepare($sqlSelectDocente);
        $stmtSelectDocente->bindParam(':nombre_docente', $nombre_docente);
        $stmtSelectDocente->bindParam(':apellido_paterno', $apellido_paterno);
        $stmtSelectDocente->bindParam(':apellido_materno', $apellido_materno);
        $stmtSelectDocente->execute();
        $id_docente = $stmtSelectDocente->fetchColumn();

        if (!$id_docente) {
            // Inserta el docente si no existe
            $sqlInsertDocente = "
                INSERT INTO docentes (nombre_docente, apellido_paterno, apellido_materno)
                VALUES (:nombre_docente, :apellido_paterno, :apellido_materno)
                ON DUPLICATE KEY UPDATE id_docente=LAST_INSERT_ID(id_docente);
            ";
            $stmtDocente = $conexion->prepare($sqlInsertDocente);
            $stmtDocente->bindParam(':nombre_docente', $nombre_docente);
            $stmtDocente->bindParam(':apellido_paterno', $apellido_paterno);
            $stmtDocente->bindParam(':apellido_materno', $apellido_materno);
            $stmtDocente->execute();
            $id_docente = $conexion->lastInsertId();
        }

        // Inserta o actualiza la asignatura con id_tipo_programa = 1
        $id_tipo_programa = 1; // Nueva Escuela Mexicana
        $sqlInsertAsignatura = "
            INSERT INTO asignatura (nombre_asignatura, id_tipo_programa)
            VALUES (:asignatura, :id_tipo_programa)
            ON DUPLICATE KEY UPDATE id_asignatura=LAST_INSERT_ID(id_asignatura);
        ";
        $stmtAsignatura = $conexion->prepare($sqlInsertAsignatura);
        $stmtAsignatura->bindParam(':asignatura', $asignatura);
        $stmtAsignatura->bindParam(':id_tipo_programa', $id_tipo_programa);
        $stmtAsignatura->execute();
        $id_asignatura = $conexion->lastInsertId();

        // Divide las clases en partes
        list($semestre, $grupo, $especialidad) = explode(" ", $clases);

        // Inserta o actualiza la especialidad con id_tipo_programa = 1
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

        // Inserta o actualiza el grupo
        $sqlInsertGrupo = "
            INSERT INTO grupo (nombre_grupo, id_especialidad)
            VALUES (:grupo, :id_especialidad)
            ON DUPLICATE KEY UPDATE id_grupo=LAST_INSERT_ID(id_grupo);
        ";
        $stmtGrupo = $conexion->prepare($sqlInsertGrupo);
        $stmtGrupo->bindParam(':grupo', $grupo);
        $stmtGrupo->bindParam(':id_especialidad', $id_especialidad);
        $stmtGrupo->execute();
        $id_grupo = $conexion->lastInsertId();

        // Inserta los datos en la tabla horarios
        $sqlInsertHorario = "
            INSERT INTO horarios (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
            VALUES (:id_docente, :id_asignatura, :id_grupo, :lunes, :martes, :miercoles, :jueves, :viernes, :sabado, :horas_semanales);
        ";
        $stmtHorario = $conexion->prepare($sqlInsertHorario);
        $stmtHorario->bindParam(':id_docente', $id_docente);
        $stmtHorario->bindParam(':id_asignatura', $id_asignatura);
        $stmtHorario->bindParam(':id_grupo', $id_grupo);
        $stmtHorario->bindParam(':lunes', $datos[4]);
        $stmtHorario->bindParam(':martes', $datos[5]);
        $stmtHorario->bindParam(':miercoles', $datos[6]);
        $stmtHorario->bindParam(':jueves', $datos[7]);
        $stmtHorario->bindParam(':viernes', $datos[8]);
        $stmtHorario->bindParam(':sabado', $datos[9]);
        $stmtHorario->bindParam(':horas_semanales', $total_horas);
        $stmtHorario->execute();

        echo "<div>". $i. "). " .$line."</div>";
        $i++;
    }

    // Calcula la cantidad de registros agregados
    $cantidad_registros = count($lines);
    $cantidad_regist_agregados = ($cantidad_registros - $fila_inicio);

    echo '<p style="text-align:center; color:#333;">Total de Registros: '. $cantidad_regist_agregados .'</p>';

    // Guardar el nombre del archivo y la fecha de subida
    $nombre_archivo = basename($_FILES["dataCliente"]["name"]);
    $sql = "INSERT INTO archivos_subidos (nombre_archivo) VALUES ('$nombre_archivo')";

    if ($conexion->query($sql) !== FALSE) {
        echo "Información del archivo guardada correctamente.";
    } else {
        $errorInfo = $conexion->errorInfo();
        echo "Error: " . $sql . "<br>" . $errorInfo[2];
    }

    // Cerrar la conexión
    $conexion = null;
?>

<a href="../docente_f19.php">Atras</a>



<!-- Trabajo total -->

<?php
    include_once "../../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $type       = $_FILES['dataCliente']['type'];
    $size       = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lines      = file($archivotmp);

    // Define la fila de inicio
    $fila_inicio = 6; // Cambia este valor según sea necesario

    // Define el mapeo de abreviaturas a nombres completos
    $abreviaturas = [
        'OFI.' => 'TÉCNICO EN OFIMÁTICA',
        'ARH.' => 'TÉCNICO EN ADMINISTRACIÓN DE RECURSOS HUMANOS',
        'PRO.' => 'TÉCNICO EN PROGRAMACIÓN',
        'FTAL.' => 'TÉCNICO FORESTAL',
        'DFMM.' => 'TÉCNICO EN DISEÑO Y FABRICACIÓN DE MUEBLES DE MADERA',
        'ECO-ADM.' => '',
        // Agrega más abreviaturas según sea necesario
    ];

    $i = 0;

    foreach ($lines as $line) {
        // Salta las primeras filas
        if ($i < $fila_inicio) {
            $i++;
            continue;
        }

        // Verifica si la línea está vacía
        if (trim($line) == '') {
            $i++;
            continue;
        }

        $datos = explode(",", $line);

        $profesor       = !empty($datos[1]) ? ($datos[1]) : '';
        $clases         = !empty($datos[2]) ? ($datos[2]) : '';
        $asignatura     = !empty($datos[3]) ? ($datos[3]) : '';
        $total_horas    = !empty($datos[7]) ? ($datos[7]) : '';

        // Verifica si los campos importantes están vacíos
        if ($profesor == '' && $clases == '' && $asignatura == '' && $total_horas == '') {
            $i++;
            continue;
        }

        // Divide el nombre del profesor en partes
        $nombreCompleto = explode(" ", $profesor);
        $nombre_docente = array_pop($nombreCompleto);
        $apellido_materno = array_pop($nombreCompleto);
        $apellido_paterno = implode(" ", $nombreCompleto);

        // Verifica si el docente ya existe
        $sqlSelectDocente = "
            SELECT id_docente FROM docentes
            WHERE nombre_docente = :nombre_docente
            AND apellido_paterno = :apellido_paterno
            AND apellido_materno = :apellido_materno;
        ";
        $stmtSelectDocente = $conexion->prepare($sqlSelectDocente);
        $stmtSelectDocente->bindParam(':nombre_docente', $nombre_docente);
        $stmtSelectDocente->bindParam(':apellido_paterno', $apellido_paterno);
        $stmtSelectDocente->bindParam(':apellido_materno', $apellido_materno);
        $stmtSelectDocente->execute();
        $id_docente = $stmtSelectDocente->fetchColumn();

        if (!$id_docente) {
            // Inserta el docente si no existe
            $sqlInsertDocente = "
                INSERT INTO docentes (nombre_docente, apellido_paterno, apellido_materno)
                VALUES (:nombre_docente, :apellido_paterno, :apellido_materno)
                ON DUPLICATE KEY UPDATE id_docente=LAST_INSERT_ID(id_docente);
            ";
            $stmtDocente = $conexion->prepare($sqlInsertDocente);
            $stmtDocente->bindParam(':nombre_docente', $nombre_docente);
            $stmtDocente->bindParam(':apellido_paterno', $apellido_paterno);
            $stmtDocente->bindParam(':apellido_materno', $apellido_materno);
            $stmtDocente->execute();
            $id_docente = $conexion->lastInsertId();
        }

        // Divide las clases en partes
        list($semestre, $grupo, $especialidad) = explode(" ", $clases);

        // Reemplaza la abreviatura con el nombre completo
        $abreviatura_especialidad = $especialidad;
        if (array_key_exists($especialidad, $abreviaturas)) {
            $especialidad = $abreviaturas[$especialidad];
        }

        // Verifica si el semestre ya existe
        $sqlSelectSemestre = "
            SELECT id_semestre FROM semestre
            WHERE nombre_semestre = :nombre_semestre;
        ";
        $stmtSelectSemestre = $conexion->prepare($sqlSelectSemestre);
        $stmtSelectSemestre->bindParam(':nombre_semestre', $semestre);
        $stmtSelectSemestre->execute();
        $id_semestre = $stmtSelectSemestre->fetchColumn();

        if (!$id_semestre) {
            // Inserta el semestre si no existe
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

        // Define el id_tipo_programa
        $id_tipo_programa = 1; // Nueva Escuela Mexicana

        // Verifica si la especialidad ya existe
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
            // Inserta la especialidad si no existe
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

        // Verifica si la asignatura ya existe
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
            // Inserta la asignatura si no existe
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

        // Inserta o actualiza el grupo
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

        // Inserta los datos en la tabla horarios
        $sqlInsertHorario = "
            INSERT INTO horarios (id_docente, id_asignatura, id_grupo, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
            VALUES (:id_docente, :id_asignatura, :id_grupo, :lunes, :martes, :miercoles, :jueves, :viernes, :sabado, :horas_semanales);
        ";
        $stmtHorario = $conexion->prepare($sqlInsertHorario);
        $stmtHorario->bindParam(':id_docente', $id_docente);
        $stmtHorario->bindParam(':id_asignatura', $id_asignatura);
        $stmtHorario->bindParam(':id_grupo', $id_grupo);
        $stmtHorario->bindParam(':lunes', $datos[4]);
        $stmtHorario->bindParam(':martes', $datos[5]);
        $stmtHorario->bindParam(':miercoles', $datos[6]);
        $stmtHorario->bindParam(':jueves', $datos[7]);
        $stmtHorario->bindParam(':viernes', $datos[8]);
        $stmtHorario->bindParam(':sabado', $datos[9]);
        $stmtHorario->bindParam(':horas_semanales', $total_horas);
        $stmtHorario->execute();

        // Muestra la línea con la abreviatura
        echo "<div>". $i. "). " . str_replace($especialidad, $abreviatura_especialidad, $line) ."</div>";
        $i++;
    }

    // Calcula la cantidad de registros agregados
    $cantidad_registros = count($lines);
    $cantidad_regist_agregados = ($cantidad_registros - $fila_inicio);

    echo '<p style="text-align:center; color:#333;">Total de Registros: '. $cantidad_regist_agregados .'</p>';

    // Guardar el nombre del archivo y la fecha de subida
    $nombre_archivo = basename($_FILES["dataCliente"]["name"]);
    $sql = "INSERT INTO archivos_subidos (nombre_archivo) VALUES ('$nombre_archivo')";

    if ($conexion->query($sql) !== FALSE) {
        echo "Información del archivo guardada correctamente.";
    } else {
        $errorInfo = $conexion->errorInfo();
        echo "Error: " . $sql . "<br>" . $errorInfo[2];
    }

    // Cerrar la conexión
    $conexion = null;
?>

<a href="../docente_f19.php">Atras</a>