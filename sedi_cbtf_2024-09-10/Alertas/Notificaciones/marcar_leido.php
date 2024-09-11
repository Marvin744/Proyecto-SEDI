<?php
    include_once '../../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Decodificar los datos JSON recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si 'mensaje' está presente en los datos recibidos
    if (isset($data['mensaje'])) {
        $mensaje = $data['mensaje'];

        session_start();
        $id_usuario = $_SESSION['id_usuario'];

        $sql = "UPDATE notificaciones SET leido = 1 WHERE id_usuario = :id_usuario AND mensaje = :mensaje";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió el mensaje']);
    }
?>