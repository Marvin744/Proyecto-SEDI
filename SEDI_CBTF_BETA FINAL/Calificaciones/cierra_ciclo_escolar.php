<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$sql = "UPDATE alumnos SET status = 'PENDIENTE' WHERE status='INSCRITO'";
$query = $conexion->prepare($sql);
$query->execute();

echo "Ciclo escolar cerrado y estado de alumnos actualizado.";
?>
