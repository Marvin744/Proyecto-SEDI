<?php
    include_once '../../bd/config.php';
    include_once '../../General_Actions/validar_sesion.php';

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $id_usuario = $_SESSION['id_usuario']; // Obtener el id del usuario actual

    // Consultar las notificaciones para el usuario actual
    $sql = "SELECT mensaje, fecha_creacion FROM notificaciones WHERE id_usuario = :id_usuario AND leido = 0 ORDER BY fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notificaciones);
?>