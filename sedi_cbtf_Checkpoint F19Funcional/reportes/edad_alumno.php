<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Consulta de Edades de Alumnos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Edades de los Alumnos</h2>

    <?php 
	include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    // Consulta para obtener todos los alumnos
    $sql = $conexion->query("SELECT nombre_alumno, apellido_paterno, apellido_materno, fecha_naci FROM alumnos");

    if ($sql->rowCount() > 0) {
        echo "<table class='table'>";
        echo "<thead><tr><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Fecha de Nacimiento</th><th>Edad</th></tr></thead><tbody>";

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $nombre_alumno = $row['nombre_alumno'];
            $apellido_paterno = $row['apellido_paterno'];
            $apellido_materno = $row['apellido_materno'];
            $fecha_naci = $row['fecha_naci'];

            // Calcular la edad
            $fecha_nacimiento = new DateTime($fecha_naci);
            $fecha_actual = new DateTime();
            $edad = $fecha_actual->diff($fecha_nacimiento)->y;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($nombre_alumno) . "</td>";
            echo "<td>" . htmlspecialchars($apellido_paterno) . "</td>";
            echo "<td>" . htmlspecialchars($apellido_materno) . "</td>";
            echo "<td>" . htmlspecialchars($fecha_naci) . "</td>";
            echo "<td>" . htmlspecialchars($edad) . " años</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron alumnos.</p>";
    }
    ?>

    <!-- Botón para proceder al reporte general -->
    <form action="reporte_edad.php" method="GET">
        <button type="submit" class="btn btn-primary">Ver Reporte Completo</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
