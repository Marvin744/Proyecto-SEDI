<?php
// Conectar a la base de datos
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$filtro_semestre = $_POST['filtro_semestre'];
$filtro_especialidad = $_POST['filtro_especialidad'];
$programa = $_POST['programa'];


$grupo = isset($_POST['grupo']) ? $_POST['grupo'] : null;
$asignatura = isset($_POST['asignaturas']) ? $_POST['asignaturas'] : [];

echo "<pre>";
print_r($_POST);
echo "</pre>";

$espeSel = "SELECT id_especialidad FROM especialidad WHERE nombre_especialidad = :filtro_especialidad AND id_tipo_programa = :programa";
$especSelect = $conexion->prepare($espeSel);
$especSelect->bindParam(':filtro_especialidad', $filtro_especialidad, PDO::PARAM_STR);
$especSelect->bindParam(':programa', $programa, PDO::PARAM_STR);
$especSelect->execute();

$result = $especSelect->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $id_especialidad = $result['id_especialidad'];
} else {
    // Manejar el caso donde no se encontró la especialidad
    echo "No se encontró la especialidad con los filtros proporcionados.";
    exit;   
}
$grupoSel = "SELECT id_grupo FROM grupo WHERE nombre_grupo= :grupo AND id_semestre = :filtro_semestre AND id_especialidad = $id_especialidad";
$grupoSelect = $conexion->prepare($grupoSel);
$grupoSelect->bindParam(':grupo', $grupo, PDO::PARAM_STR);
$grupoSelect->bindParam(':filtro_semestre', $filtro_semestre, PDO::PARAM_STR);
$grupoSelect->execute();

$resultG = $grupoSelect->fetch(PDO::FETCH_ASSOC);

if ($resultG) {
    $id_grupo = $resultG['id_grupo'];
} else {
    // Manejar el caso donde no se encontró la especialidad
    echo "No se encontró un grupo con los filtros proporcionados.";
    exit;
}

$sqlSelect = "SELECT id_alumno from alumnos where id_grupo = :id_grupo;";
$querySelect = $conexion->prepare($sqlSelect);
$querySelect->bindParam(':id_grupo', $id_grupo, PDO::PARAM_STR);
$querySelect->execute();

// Guarda los resultados en un array
$resultados = $querySelect->fetchAll(PDO::FETCH_ASSOC); // Esto devuelve un array asociativo

// Si quieres un array con solo los valores (sin las claves), puedes hacer:
$idsAlumnos = array_column($resultados, 'id_alumno');

// Opcionalmente, puedes imprimir el array para ver los resultados
print_r($resultados);

$sqlInsert = "INSERT INTO `calificaciones` (`id_calificacion`, `calificacion_p1`, `calificacion_p2`, `calificacion_p3`, `calificacion_final`, `acreditacion`, `id_alumno`, `id_asignatura`, `id_asistencia`, `id_grupo`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, :id_alumno, :id_asignatura, 1, :id_grupo);";
$queryInsert = $conexion->prepare($sqlInsert);

// Recorrer el array de alumnos y hacer el insert para cada uno
foreach ($resultados as $fila) {
    $id_alumno = $fila['id_alumno'];
    foreach ($asignatura as $id_asignatura) {
        // Vincular los parámetros
        $queryInsert->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
        $queryInsert->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $queryInsert->bindParam(':id_grupo', $id_grupo, PDO::PARAM_STR);
        
        // Ejecutar la consulta
        $queryInsert->execute();
    }
}

 $url_redireccion = "form_asignarMateria.php";

 echo "<script>
 alert('Materias Asignadas correctamemnte');
 window.location.href = '$url_redireccion';
 </script>";
?>