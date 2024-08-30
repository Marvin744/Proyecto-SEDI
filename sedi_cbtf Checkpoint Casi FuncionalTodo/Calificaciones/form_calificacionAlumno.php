<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";
?>
<!Doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de calificaciones</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Calificaciones del Alumno</h2>

        <?php
          // Captura el valor seleccionado en el select
          // $id_alumno = isset($_POST['id_alumno']) ? $_POST['id_alumno'] : ''; // Cambiar 'id_alumno' a 'alumnos'

          $id_usuario = $_SESSION['id_usuario'];
          $id_alumno = $_REQUEST['id_alumno'];

          // Consulta para obtener las calificaciones del alumno seleccionado
          $sql = "SELECT calificaciones.*, alumnos.nombre_alumno, alumnos.apellido_paterno, alumnos.apellido_materno, asignatura.nombre_asignatura, asistencias.asistencias_p1, asistencias.asistencias_p2, asistencias.asistencias_p3
                  FROM calificaciones 
                  INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
                  INNER JOIN usuarios ON alumnos.id_usuario = usuarios.id_usuario
                  INNER JOIN asignatura ON calificaciones.id_asignatura = asignatura.id_asignatura
                  INNER JOIN asistencias ON calificaciones.id_asistencia = asistencias.id_asistencia
                  WHERE alumnos.id_usuario = :id_usuario OR calificaciones.id_alumno = :id_alumno";
          
          $querycali = $conexion->prepare($sql);
          $querycali->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
          $querycali->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
          $querycali->execute();
          $resultado = $querycali->fetchAll(PDO::FETCH_ASSOC);

          if (!empty($resultado)) {
              $nombreCompleto = htmlspecialchars($resultado[0]['nombre_alumno'] . ' ' . $resultado[0]['apellido_paterno'] . ' ' . $resultado[0]['apellido_materno'], ENT_QUOTES, 'UTF-8');
              echo "<h3>$nombreCompleto</h3>";
              echo "Nombre del alumno = " . $nombreCompleto;

              // Mostrar los resultados en una tabla
              echo "<table class='table'>";
              echo "<thead><tr><th>Asignatura</th><th>Calificación Parcial 1</th><th>Asistencia Parcial 1</th><th>Calificación Parcial 2</th><th>Asistencia Parcial 2</th><th>Calificación Parcial 3</th><th>Asistencia Parcial 3</th></tr></thead><tbody>";

              foreach ($resultado as $sqlcali) { 
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($sqlcali['nombre_asignatura'], ENT_QUOTES, 'UTF-8') . "</td>";
                  echo "<td>" . htmlspecialchars($sqlcali['calificacion_p1'], ENT_QUOTES, 'UTF-8') . "</td>";
                  echo "<td>" . ($sqlcali['asistencias_p1']) . "</td>";
                  echo "<td>" . htmlspecialchars($sqlcali['calificacion_p2'], ENT_QUOTES, 'UTF-8') . "</td>";
                  echo "<td>" . ($sqlcali['asistencias_p2']) . "</td>";
                  echo "<td>" . htmlspecialchars($sqlcali['calificacion_p3'], ENT_QUOTES, 'UTF-8') . "</td>";
                  echo "<td>" . ($sqlcali['asistencias_p3']) . "</td>";
                  echo "</tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p>No se encontraron calificaciones para este alumno.</p>";
          }
      ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php require_once "../vistas/pie_pagina.php"; ?>