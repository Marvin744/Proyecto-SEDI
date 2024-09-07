<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 


    $id_modulo = (isset($_POST['id_modulo'])) ? $_POST['id_modulo'] : '';
    $especialidad = (isset($_POST['especialidad'])) ? $_POST['especialidad'] : '';
    $nombre_modulo = (isset($_POST['nombre_modulo'])) ? $_POST['nombre_modulo'] : '';


    $update = ("UPDATE `modulos` SET `nombre_modulo` = '$nombre_modulo', `especialidad` = '$especialidad' WHERE `id_modulo` = '$id_modulo';");
    $query = $conexion->prepare($update);
    $query->execute();
    $mostrar = $query->fetch();

    header('Location: alta_modulos.php'); die;
?>