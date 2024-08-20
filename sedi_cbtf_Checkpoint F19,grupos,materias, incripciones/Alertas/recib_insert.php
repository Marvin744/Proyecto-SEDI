<?php
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Recibir y sanitizar los datos del formulario
$id_alumno = isset($_POST['id_alumno']) ? $_POST['id_alumno'] : NULL;
$tipo_alerta = isset($_POST['tipo_alerta']) ? $_POST['tipo_alerta'] : NULL;
$situacion = isset($_POST['situacion']) ? $_POST['situacion'] : NULL;
$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : NULL;
$alumno = isset($_POST['alumno']) ? $_POST['alumno'] : NULL;
$persona_reporta = isset($_POST['persona_reporta']) ? $_POST['persona_reporta'] : NULL;
$condicionamiento = isset($_POST['condicionamiento']) ? $_POST['condicionamiento'] : NULL;
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : NULL;
$nombre_tutor = isset($_POST['nombre_tutor']) ? $_POST['nombre_tutor'] : NULL;
$telefono_tutor = isset($_POST['telefono_tutor']) ? $_POST['telefono_tutor'] : NULL;
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : NULL;

echo "<pre>";
print_r($_POST);
echo "</pre>";


// Preparar la sentencia SQL de inserción
$insert = "INSERT INTO `alertas` (
`id_alerta`,
`fecha_alerta`,
`tipo_alerta`,
`situacion`,
`observaciones`,
`persona_reporta`,
`alumno`,
`condicionamiento`,
`telefono_alumno`,
`nombre_tutor`,
`parentesco_tutor`,
`telefono_tutor`,
`cita`,
`asistencia_padre_tutor`,
`canalizacion`,
`quien_atiende`,
`tratamiento`,
`sancion`,
`fecha_cumplimiento`,
`evidencias`,
`quien_atiende_suspencion_hss`,
`seguimiento`,
`id_alumno`,
`id_usuario`
) VALUES (
NULL,
NULL,
:tipo_alerta,
:situacion,
:observaciones,
:persona_reporta,
:alumno,
:condicionamiento,
:telefono,
:nombre_tutor,
NULL,
:telefono_tutor,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
:id_alumno,
:id_usuario);";

// Preparar la consulta
$query = $conexion->prepare($insert);

// Vincular los parámetros con sus respectivos valores
$query->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
$query->bindParam(':tipo_alerta', $tipo_alerta, PDO::PARAM_STR);
$query->bindParam(':situacion', $situacion, PDO::PARAM_STR);
$query->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
$query->bindParam(':alumno', $alumno, PDO::PARAM_STR);
$query->bindParam(':persona_reporta', $persona_reporta, PDO::PARAM_STR);
$query->bindParam(':condicionamiento', $condicionamiento, PDO::PARAM_STR);
$query->bindParam(':telefono', $telefono, PDO::PARAM_STR);
$query->bindParam(':nombre_tutor', $nombre_tutor, PDO::PARAM_STR);
$query->bindParam(':telefono_tutor', $telefono_tutor, PDO::PARAM_STR);
$query->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);

// Ejecutar la consulta
$query->execute();

// Verificar errores
$error = $query->errorInfo();
if ($error[0] != '00000') {
    echo "Error en la consulta SQL: " . $error[2];
} else {
    if($query->rowCount() > 0){
        echo "Registro actualizado correctamente.";
    } else {
        echo "No se pudo actualizar el registro.";
    }
}
?>
