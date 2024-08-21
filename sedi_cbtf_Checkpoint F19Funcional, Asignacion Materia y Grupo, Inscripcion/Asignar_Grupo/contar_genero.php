<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$id_grupo = $_POST['id_grupo'];

// Consulta para contar mujeres en el grupo
$sqlMujer = "SELECT COUNT(*) as total_mujer FROM alumnos WHERE genero='Mujer' AND id_grupo=:id_grupo;";
$queryTableMujer = $conexion->prepare($sqlMujer);
$queryTableMujer->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
$queryTableMujer->execute();
$totalMujer = $queryTableMujer->fetchColumn();

// Consulta para contar hombres en el grupo
$sqlHombre = "SELECT COUNT(*) as total_hombre FROM alumnos WHERE genero='Hombre' AND id_grupo=:id_grupo;";
$queryTableHombre = $conexion->prepare($sqlHombre);
$queryTableHombre->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
$queryTableHombre->execute();
$totalHombre = $queryTableHombre->fetchColumn();

echo "Este grupo tiene $totalMujer Mujeres y $totalHombre Hombres.";
?>
