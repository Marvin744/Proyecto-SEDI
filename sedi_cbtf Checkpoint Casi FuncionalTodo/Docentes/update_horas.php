<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $day = $data['day'];
    $value = $data['value'];

    if ($value === '') {
        // Limpiar el campo en la base de datos si el valor es vacío
        $sqlUpdate = "UPDATE horarios SET $day = NULL WHERE id_horario = :id";
    } else {
        // Validar el formato del rango de horas o múltiples rangos
        if (preg_match('/^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/', $value)) {
            $sqlUpdate = "UPDATE horarios SET $day = :value WHERE id_horario = :id";
        } else {
            echo json_encode(['success' => false, 'message' => 'Formato de rango de horas inválido']);
            exit();
        }
    }

    $queryUpdate = $conexion->prepare($sqlUpdate);
    if ($value !== '') {
        $queryUpdate->bindParam(':value', $value);
    }
    $queryUpdate->bindParam(':id', $id);

    if ($queryUpdate->execute()) {
        echo json_encode(['success' => true]);

    } else {
        $errorInfo = $queryUpdate->errorInfo();
        echo json_encode(['success' => false, 'error' => $errorInfo]);
    }
?>



