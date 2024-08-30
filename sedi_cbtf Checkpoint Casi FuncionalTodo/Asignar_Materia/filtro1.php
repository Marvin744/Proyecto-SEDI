<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$filtro_semestre = $_POST['filtro_semestre'];
$filtro_especialidad = $_POST['filtro_especialidad'];
$programa = $_POST['programa'];

$sql = "SELECT a.id_asignatura, a.nombre_asignatura, a.submodulos, e.nombre_especialidad, s.numero_semestre, t.tipo_programa
        FROM asignatura a
        JOIN especialidad e ON a.id_especialidad = e.id_especialidad
        JOIN semestre s ON a.id_semestre = s.id_semestre
        JOIN tipo_programa t ON e.id_tipo_programa = t.id_tipo_programa
        WHERE a.id_semestre = :filtro_semestre 
          AND e.nombre_especialidad = :filtro_especialidad 
          AND e.id_tipo_programa = :programa
        ORDER BY s.numero_semestre ASC;";

$query = $conexion->prepare($sql);
$query->bindParam(':filtro_semestre', $filtro_semestre);
$query->bindParam(':filtro_especialidad', $filtro_especialidad);
$query->bindParam(':programa', $programa);
$query->execute();

$resultados = '';
foreach ($query as $row) {
    $resultados .= "<tr>
                        <td class='alinear-izquierda'>{$row['numero_semestre']}</td>
                        <td class='alinear-centro'>{$row['nombre_asignatura']}</td>
                        <td class='alinear-centro'>{$row['submodulos']}</td>
                        <td class='alinear-centro'>{$row['nombre_especialidad']}</td>
                        <td class='alinear-centro'>{$row['tipo_programa']}</td>
                        <td class='alinear-centro'><input type='checkbox' name='asignaturas[]' value='{$row['id_asignatura']}'></td>
                    </tr>";
}

echo $resultados;
?>