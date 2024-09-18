<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Alumno', 'Administrativo', 'Administrativo_y_docente', 'Administrativo_Docente', 'Administrativo_Jefe', 'Directivo_y_docente']);
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
          $id_alumno = isset($_REQUEST['id_alumno']) ? $_REQUEST['id_alumno'] : NULL;

          // Consulta para obtener las calificaciones del alumno seleccionado
          $sql = "SELECT calificaciones.*, alumnos.nombre_alumno, alumnos.apellido_paterno, alumnos.apellido_materno, asignatura.nombre_asignatura, asistencias.asistencias_p1, asistencias.asistencias_p2, asistencias.asistencias_p3, grupo.id_semestre, grupo.nombre_grupo, especialidad.nombre_especialidad
                  FROM calificaciones 
                  INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
                  INNER JOIN usuarios ON alumnos.id_usuario = usuarios.id_usuario
                  INNER JOIN asignatura ON calificaciones.id_asignatura = asignatura.id_asignatura
                  INNER JOIN asistencias ON calificaciones.id_asistencia = asistencias.id_asistencia
                  INNER JOIN grupo ON calificaciones.id_grupo = grupo.id_grupo
                  INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
                  WHERE alumnos.id_usuario = :id_usuario OR calificaciones.id_alumno = :id_alumno";
          
          $querycali = $conexion->prepare($sql);
          $querycali->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
          $querycali->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
          $querycali->execute();
          $resultado = $querycali->fetchAll(PDO::FETCH_ASSOC);

          if (!empty($resultado)) {
              $nombreCompleto = htmlspecialchars($resultado[0]['nombre_alumno'] . ' ' . $resultado[0]['apellido_paterno'] . ' ' . $resultado[0]['apellido_materno'], ENT_QUOTES, 'UTF-8');
              echo "Nombre del alumno = " . $nombreCompleto;

              
              $grado_grupo = htmlspecialchars($resultado[0]['id_semestre'] . '° ' . $resultado[0]['nombre_grupo'], ENT_QUOTES, 'UTF-8');
              echo "<br>";
              echo "Grado y Grupo = " . $grado_grupo;

              $especialidad = htmlspecialchars($resultado[0]['nombre_especialidad'], ENT_QUOTES, 'UTF-8');
              echo "<br>";
              echo "Especialidad = " . $especialidad;

// Mostrar los resultados en una tabla
echo "<table class='table'>";
echo "<thead><tr>
        <th>Asignatura</th>
        <th>Calificación Parcial 1</th>
        <th>Asistencia Parcial 1</th>
        <th>Calificación Parcial 2</th>
        <th>Asistencia Parcial 2</th>
        <th>Calificación Parcial 3</th>
        <th>Asistencia Parcial 3</th>
        <th>Calificación Final</th> <!-- Añadimos esta columna -->
        <th>Asistencia Total</th> <!-- Añadimos esta columna -->
    </tr></thead><tbody>";

    $suma_calificaciones_finales = 0;
    $total_asignaturas = 0;

foreach ($resultado as $sqlcali) { 
    // Calcular el promedio de las calificaciones parciales
    $calificacion_final = ($sqlcali['calificacion_p1'] + $sqlcali['calificacion_p2'] + $sqlcali['calificacion_p3']) / 3;
    $asistencia_final = ($sqlcali['asistencias_p1'] + $sqlcali['asistencias_p2'] + $sqlcali['asistencias_p3']);


    // Verifica que las calificaciones no sean null antes de aplicar number_format
    $calificacion_p1 = isset($sqlcali['calificacion_p1']) ? number_format($sqlcali['calificacion_p1'], 2) : 'N/A';
    $calificacion_p2 = isset($sqlcali['calificacion_p2']) ? number_format($sqlcali['calificacion_p2'], 2) : 'N/A';
    $calificacion_p3 = isset($sqlcali['calificacion_p3']) ? number_format($sqlcali['calificacion_p3'], 2) : 'N/A';
    $calificacion_final_formatted = isset($calificacion_final) ? number_format($calificacion_final, 2) : 'N/A';
    $asistencia_final_formatted = isset($asistencia_final) ? number_format($asistencia_final) : 'N/A';

    // Sumar las calificaciones finales y contar el número de asignaturas
    $suma_calificaciones_finales += $calificacion_final;
    $total_asignaturas++;

    echo "<tr>";
    echo "<td>" . htmlspecialchars($sqlcali['nombre_asignatura'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . $calificacion_p1 . "</td>";
    echo "<td>" . ($sqlcali['asistencias_p1']) . "</td>";
    echo "<td>" . $calificacion_p2 . "</td>";
    echo "<td>" . ($sqlcali['asistencias_p2']) . "</td>";
    echo "<td>" . $calificacion_p3 . "</td>";
    echo "<td>" . ($sqlcali['asistencias_p3']) . "</td>";
    echo "<td>" . $calificacion_final_formatted . "</td>"; // Mostrar la calificación final con 2 decimales
    echo "<td>" . $asistencia_final_formatted . "</td>"; // Mostrar la asistencia final
    echo "</tr>";
}

// Calcular la calificación total (promedio de todas las calificaciones finales)
$calificacion_total = $total_asignaturas > 0 ? $suma_calificaciones_finales / $total_asignaturas : 0;



// Agregar la fila de calificación total al final de la tabla
echo "<tr>";
echo "<td colspan='7' style='text-align: right;'><strong>Calificación Total:</strong></td>";
echo "<td><strong>" . number_format($calificacion_total, 2) . "</strong></td>";
echo "</tr>";


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
