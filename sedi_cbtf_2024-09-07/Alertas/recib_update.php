<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Recibir y sanitizar los datos del formulario
$id_alerta = isset($_POST['id_alerta']) ? $_POST['id_alerta'] : NULL;
$tipo_alerta = isset($_POST['tipo_alerta']) ? $_POST['tipo_alerta'] : NULL;
$situacion = isset($_POST['situacion']) ? $_POST['situacion'] : NULL;
$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : NULL;
$condicionamiento = isset($_POST['condicionamiento']) ? $_POST['condicionamiento'] : NULL;
$cita = isset($_POST['cita']) ? $_POST['cita'] : NULL;
$asistencia_padre_tutor = isset($_POST['asistencia_padre_tutor']) ? $_POST['asistencia_padre_tutor'] : NULL;
$canalizacion = isset($_POST['canalizacion']) ? $_POST['canalizacion'] : NULL;
$quien_atiende = isset($_POST['quien_atiende']) ? $_POST['quien_atiende'] : NULL;
$tratamiento = isset($_POST['tratamiento']) ? $_POST['tratamiento'] : NULL;
$sancion = isset($_POST['sancion']) ? $_POST['sancion'] : NULL;
$fecha_cumplimiento = isset($_POST['fecha_cumplimiento']) ? $_POST['fecha_cumplimiento'] : NULL;
$evidencias = isset($_POST['evidencias']) ? $_POST['evidencias'] : NULL;
$quien_atiende_suspencion_hss = isset($_POST['quien_atiende_suspencion_hss']) ? $_POST['quien_atiende_suspencion_hss'] : NULL;
$seguimiento = isset($_POST['seguimiento']) ? $_POST['seguimiento'] : NULL;

$asistencia_padre_tutor = !empty($asistencia_padre_tutor) ? $asistencia_padre_tutor : NULL;
$fecha_cumplimiento = !empty($fecha_cumplimiento) ? $fecha_cumplimiento : NULL;


// Construir la consulta de actualización
$update = "UPDATE alertas SET 
    tipo_alerta = :tipo_alerta,
    situacion = :situacion,
    observaciones = :observaciones,
    condicionamiento = :condicionamiento,
    cita = :cita,
    asistencia_padre_tutor = :asistencia_padre_tutor,
    canalizacion = :canalizacion,
    quien_atiende = :quien_atiende,
    tratamiento = :tratamiento,
    sancion = :sancion,
    fecha_cumplimiento = :fecha_cumplimiento,
    evidencias = :evidencias,
    quien_atiende_suspencion_hss = :quien_atiende_suspencion_hss,
    seguimiento = :seguimiento
    WHERE id_alerta = :id_alerta";

$query = $conexion->prepare($update);

// Vincular los parámetros
$query->bindParam(':id_alerta', $id_alerta, PDO::PARAM_INT);
$query->bindParam(':tipo_alerta', $tipo_alerta, PDO::PARAM_STR);
$query->bindParam(':situacion', $situacion, PDO::PARAM_STR);
$query->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
$query->bindParam(':condicionamiento', $condicionamiento, PDO::PARAM_STR);
$query->bindParam(':cita', $cita, PDO::PARAM_STR);
$query->bindParam(':asistencia_padre_tutor', $asistencia_padre_tutor, PDO::PARAM_STR);
$query->bindParam(':canalizacion', $canalizacion, PDO::PARAM_STR);
$query->bindParam(':quien_atiende', $quien_atiende, PDO::PARAM_STR);
$query->bindParam(':tratamiento', $tratamiento, PDO::PARAM_STR);
$query->bindParam(':sancion', $sancion, PDO::PARAM_STR);
$query->bindParam(':fecha_cumplimiento', $fecha_cumplimiento, PDO::PARAM_STR);
$query->bindParam(':evidencias', $evidencias, PDO::PARAM_STR);
$query->bindParam(':quien_atiende_suspencion_hss', $quien_atiende_suspencion_hss, PDO::PARAM_STR);
$query->bindParam(':seguimiento', $seguimiento, PDO::PARAM_STR);

// Ejecutar la consulta
$query->execute();

// Verificar errores
$error = $query->errorInfo();
if ($error[0] != '00000') {
    echo "Error en la consulta SQL: " . $error[2];
} else {
    if($query->rowCount() > 0){
        $url_redireccion = "form_alerta.php";
        echo "<script>
        alert('Alerta Actualizada con Exito!');
        window.location.href = '$url_redireccion';
    </script>";
    } else {
        echo "No se pudo actualizar el registro.";
    }
}
?>
