<?php
include_once 'bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
$id_alerta = $_REQUEST['id_alerta'];

$DeleteRegistro = ("DELETE FROM 'alertas' WHERE 'alertas'.'id_alerta'= $id_alerta");
$query = $conexion->prepare($DeleteRegistro);
$query->execute();
$mostrar = $query->fetch();
?>