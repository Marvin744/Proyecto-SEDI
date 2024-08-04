<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $day = $data['day'];
    $value = $data['value'];

    $sqlUpdate = "UPDATE horarios SET $day = :value WHERE id_horario = :id";
    $queryUpdate = $conexion->prepare($sqlUpdate);
    $queryUpdate->bindParam(':value', $value);
    $queryUpdate->bindParam(':id', $id);

    if ($queryUpdate->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
?>