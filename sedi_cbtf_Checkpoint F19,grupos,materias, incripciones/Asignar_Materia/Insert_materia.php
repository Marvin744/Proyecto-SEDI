<?php
// Conectar a la base de datos
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$grupo = isset($_POST['grupo']) ? $_POST['grupo'] : null;
$asignatura = isset($_POST['asignaturas']) ? $_POST['asignaturas'] : [];

echo "<pre>";
print_r($_POST);
echo "</pre>";

$sqlSelect = "SELECT id_alumno from alumnos where id_grupo = :grupo;";
$querySelect = $conexion->prepare($sqlSelect);
$querySelect->bindParam(':grupo', $grupo, PDO::PARAM_STR);
$querySelect->execute();

// Guarda los resultados en un array
$resultados = $querySelect->fetchAll(PDO::FETCH_ASSOC); // Esto devuelve un array asociativo

// Si quieres un array con solo los valores (sin las claves), puedes hacer:
$idsAlumnos = array_column($resultados, 'id_alumno');

// Opcionalmente, puedes imprimir el array para ver los resultados
print_r($resultados);

$sqlInsert = "INSERT INTO `calificaciones` (`id_calificaciones`, `calificacion_parcial1`, `calificacion_parcial2`, `calificacion_parcial3`, `calificacion_final`, `asistencia_parcial1`, `asitencia_parcial2`, `asistencia_parcial3`, `asistencia_total`, `acreditacion`, `id_docente`, `id_alumno`, `id_asignatura`, `id_grupo`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, :id_alumno, :id_asignatura, :id_grupo);";
$queryInsert = $conexion->prepare($sqlInsert);

// Recorrer el array de alumnos y hacer el insert para cada uno
foreach ($resultados as $fila) {
    $id_alumno = $fila['id_alumno'];
    foreach ($asignatura as $id_asignatura) {
        // Vincular los parámetros
        $queryInsert->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
        $queryInsert->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $queryInsert->bindParam(':id_grupo', $grupo, PDO::PARAM_STR);
        
        // Ejecutar la consulta
        $queryInsert->execute();
    }
}

echo "Los datos se han insertado correctamente en la tabla calificaciones.";

?>