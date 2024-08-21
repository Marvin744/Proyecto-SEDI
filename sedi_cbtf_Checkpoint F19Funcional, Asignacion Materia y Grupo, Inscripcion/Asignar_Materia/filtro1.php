<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$filtro_semestre = $_POST['filtro_semestre'];
$filtro_especialidad = $_POST['filtro_especialidad'];

$sql = "SELECT a.id_asignatura, a.nombre_asignatura, a.submodulos, e.nombre_especialidad, s.nombre_semestre, t.tipo_programa
        FROM asignatura a
        JOIN especialidad e ON a.id_especialidad = e.id_especialidad
        JOIN semestre s ON a.id_semestre = s.id_semestre
        JOIN tipo_programa t ON a.id_tipo_programa = t.id_tipo_programa
        WHERE a.id_semestre = :filtro_semestre OR a.id_especialidad = :filtro_especialidad AND a.id_semestre <= '2'
        ORDER BY nombre_semestre ASC;";

$query = $conexion->prepare($sql);
$query->bindParam(':filtro_semestre', $filtro_semestre);
$query->bindParam(':filtro_especialidad', $filtro_especialidad);
$query->execute();

$resultados = '';
foreach ($query as $row) {
    $resultados .= "<tr>
                        <td class='alinear-izquierda'>{$row['nombre_semestre']}</td>
                        <td class='alinear-izquierda'>{$row['nombre_asignatura']}</td>
                        <td class='alinear-centro'>{$row['submodulos']}</td>
                        <td class='alinear-derecha'>{$row['nombre_especialidad']}</td>
                        <td class='alinear-derecha'>{$row['tipo_programa']}</td>
                        <td><input type='checkbox' name='asignaturas[]' value='{$row['id_asignatura']}'></td>
                    </tr>";
}

echo $resultados;
?>