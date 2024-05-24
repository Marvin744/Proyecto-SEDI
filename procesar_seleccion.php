<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de calificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
  </head>
  <body>
    <div class="container mt-5">
      <h2>Calificaciones del Alumno</h2>

      
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Captura el valor seleccionado en el select
          $id_alumno = $_POST['id_alumno'];

          // Conexión a la base de datos
          include('config.php');

          // Consulta para obtener las calificaciones del alumno seleccionado
          $sql = $con->query("SELECT calificaciones.*, alumnos.nombre_alumno, alumnos.apellido_paterno, alumnos.apellido_materno, asignatura.nombre_asignatura, docentes.nombre_docente
                              FROM calificaciones 
                              INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
                              INNER JOIN asignatura ON calificaciones.id_asignatura = asignatura.id_asignatura
                              INNER JOIN docentes ON calificaciones.id_docente = docentes.id_docente
                              WHERE alumnos.id_alumno = '$id_alumno'");

          // Mostrar los datos del alumno seleccionado
          if ($row = $sql->fetch_assoc()) {
              echo "<p><strong>Alumno:</strong> " . $row['nombre_alumno'] . " " . $row['apellido_paterno'] . " " . $row['apellido_materno'] . "</p>";
              echo "<p><strong>Número de control:</strong> " . $id_alumno . "</p>";
            }

          // Reiniciar el puntero del resultado para reutilizarlo en la tabla
          $sql->data_seek(0);

          // Mostrar los resultados en una tabla
          echo "<table class='table'>";
          echo "<thead><tr><th>Asignatura</th><th>Docente</th><th>Calificación Parcial 1</th><th>Calificación Parcial 2</th><th>Calificación Parcial 3</th><th>Asistencia Parcial 1</th><th>Asistencia Parcial 2</th><th>Asistencia Parcial 3</th></tr></thead><tbody>";

          while ($resultado = $sql->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $resultado['nombre_asignatura'] . "</td>";
              echo "<td>" . $resultado['nombre_docente'] . "</td>";
              echo "<td>" . $resultado['calificacion_parcial1'] . "</td>";
              echo "<td>" . $resultado['calificacion_parcial2'] . "</td>";
              echo "<td>" . $resultado['calificacion_parcial3'] . "</td>";
              echo "<td>" . $resultado['asistencia_parcial1'] . "</td>";
              echo "<td>" . $resultado['asistencia_parcial2'] . "</td>";
              echo "<td>" . $resultado['asistencia_parcial3'] . "</td>";
              echo "</tr>";
          }

          echo "</tbody></table>";
      }
      ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
