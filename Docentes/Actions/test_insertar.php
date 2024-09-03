<?php
include_once "../../bd/config.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$especialidad = 'TÉCNICO EN PROGRAMACIÓN'; // Puedes cambiar esto según tu necesidad
$id_tipo_programa = 1; // Este es el valor que debería insertarse

$sqlInsertEspecialidad = "
    INSERT INTO especialidad (nombre_especialidad, id_tipo_programa)
    VALUES (:especialidad, :id_tipo_programa);
";

$stmtEspecialidad = $conexion->prepare($sqlInsertEspecialidad);
$stmtEspecialidad->bindParam(':especialidad', $especialidad);
$stmtEspecialidad->bindParam(':id_tipo_programa', $id_tipo_programa);

if ($stmtEspecialidad->execute()) {
    echo "Inserción exitosa";
} else {
    var_dump($stmtEspecialidad->errorInfo());
}
?>