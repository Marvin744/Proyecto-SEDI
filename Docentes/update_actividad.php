<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$dato = $data['dato'];
$value = $data['value'];

echo $dato;

// Validar que el campo es uno de los permitidos
$allowedColumns = ['actividad', 'detalles_actividad', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'horas_semanales'];
if (!in_array($dato, $allowedColumns)) {
    echo json_encode(['success' => false, 'message' => 'Campo no permitido']);
    exit();
}

// Determinar si el campo debe ser validado por formato de horas
if (in_array($dato, ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'])) {
    // Validar el formato del rango de horas o múltiples rangos
    if (!preg_match('/^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/', $value) && $value !== '') {
        echo json_encode(['success' => false, 'message' => 'Formato de rango de horas inválido']);
        exit();
    }
}

// Preparar la consulta SQL
if ($value === '') {
    // Limpiar el campo en la base de datos si el valor es vacío
    $sqlUpdate = "UPDATE actividades_complementarias SET $dato = NULL WHERE id_actividad_complementaria = :id";
} else {
    $sqlUpdate = "UPDATE actividades_complementarias SET $dato = :value WHERE id_actividad_complementaria = :id";
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

$conexion = null; // Cerrar la conexión
?>
