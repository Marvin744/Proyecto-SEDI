<?php

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Obtener y limpiar los valores de los filtros
$buscar = isset($_POST['buscar']) ? trim($_POST['buscar']) : '';
$grupos = isset($_POST['grupos']) ? $_POST['grupos'] : '';

// Construir la consulta SQL
$sql = "SELECT  a.id_alumno,
                a.matricula,
                a.status,
                a.apellido_paterno,
                a.apellido_materno,
                a.nombre_alumno,
                g.nombre_grupo,
                s.numero_semestre,
                a.genero,
                e.nombre_especialidad
        FROM
                alumnos a
        JOIN
                grupo g ON a.id_grupo = g.id_grupo
        JOIN
                semestre s ON g.id_semestre = s.id_semestre
        JOIN
                especialidad e ON g.id_especialidad = e.id_especialidad
        WHERE
                (g.id_semestre = '5' OR g.id_semestre = '6') AND a.status = 'INSCRITO' ";


// Añadir condición para el grupo si está seleccionado
if ($grupos !== '') {
    $sql .= " AND g.nombre_grupo = :grupos";
}

// Añadir condición para el filtro de búsqueda si está presente
if ($buscar !== '') {
    $sql .= " AND (a.matricula LIKE :buscar OR 
                   a.status LIKE :buscar OR 
                   s.numero_semestre LIKE :buscar OR 
                   g.nombre_grupo LIKE :buscar OR 
                   a.apellido_paterno LIKE :buscar OR 
                   e.nombre_especialidad LIKE :buscar OR 
                   a.apellido_materno LIKE :buscar OR 
                   a.nombre_alumno LIKE :buscar OR 
                   a.genero LIKE :buscar)";
}

// Añadir el orden
$sql .= " ORDER BY a.apellido_paterno ASC";

$query = $conexion->prepare($sql);

// Vincular parámetros si se aplican filtros
if ($grupos !== '') {
    $query->bindParam(':grupos', $grupos, PDO::PARAM_STR);
}
if ($buscar !== '') {
    $buscar_param = "%$buscar%";
    $query->bindParam(':buscar', $buscar_param, PDO::PARAM_STR);
}

$query->execute();

$resultados = '';
foreach ($query as $row) {
    $resultados .= "<tr>
                        <td class='alinear-izquierda'>{$row['matricula']}</td>
                        <td class='alinear-centro'>{$row['status']}</td>
                        <td class='alinear-derecha'>{$row['numero_semestre']}</td>
                        <td class='alinear-derecha'>{$row['nombre_grupo']}</td>
                        <td class='alinear-derecha'>{$row['nombre_especialidad']}</td>
                        <td class='alinear-derecha'>{$row['apellido_paterno']}</td>
                        <td class='alinear-derecha'>{$row['apellido_materno']}</td>
                        <td class='alinear-derecha'>{$row['nombre_alumno']}</td>
                        <td class='alinear-derecha'>{$row['genero']}</td>
                        <td><input type='checkbox' name='alumnos[]' value='{$row['id_alumno']}'></td>
                    </tr>";
}

echo $resultados;
?>
