<?php
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $id_Mod = (isset($_POST['id_modulo'])) ? $_POST['id_modulo'] : '';
    $nombreModulo = (isset($_POST['nombre_modulo'])) ? $_POST['nombre_modulo'] : '';

    $insertar = "INSERT INTO modulos (id_modulo, nombre_modulo) VALUES ('$id_Mod', '$nombreModulo'); ";
    $query = $conexion->prepare($insertar);
    $query->execute();
    //$query->bind_param("ss", $id_Mod, $nombreModulo);
    $result = $query->fetchAll();

    header("Location: ../alta_modulos.php"); die;


?>