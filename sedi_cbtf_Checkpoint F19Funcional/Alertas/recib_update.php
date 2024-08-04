<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 


    $id_alerta = (isset($_POST['id_alerta'])) ? $_POST['id_alerta'] : '';

    $tipo_alerta = (isset($_POST['tipo_alerta'])) ? $_POST['tipo_alerta'] : '';


    $update = ("UPDATE alertas SET tipo_alerta = '$tipo_alerta' WHERE id_alerta = $id_alerta;");
    $query = $conexion->prepare($update);
    $query->execute();
    $mostrar = $query->fetch();

header('Location: alerta.php'); die;
?>