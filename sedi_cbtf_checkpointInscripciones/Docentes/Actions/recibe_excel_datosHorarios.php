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

?>

<a href="../docente_f19.php">Atras</a>