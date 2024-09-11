<?php
require_once '../../General_Actions/validar_sesion.php';
include_once '../../bd/config.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$id_usuario = $_POST['id_usuario'];
$nuevo_perfil = $_POST['nuevo_perfil'];

// Actualizar el perfil del usuario
$sql = "UPDATE usuarios SET perfil = :nuevo_perfil WHERE id_usuario = :id_usuario";
$query = $conexion->prepare($sql);
$query->bindParam(':nuevo_perfil', $nuevo_perfil, PDO::PARAM_STR);
$query->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($query->execute()) {
    header("Location: ../form_modificar_perfil.php?id_usuario=$id_usuario&success=1"); die;
    exit();
} else {
    header("Location: ../form_modificar_perfil.php?id_usuario=$id_usuario&success=0"); die;
    exit();
}
?>