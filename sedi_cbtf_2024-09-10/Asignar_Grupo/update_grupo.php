<?php
// Conectar a la base de datos
include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Obtener el nuevo grupo y los IDs de los alumnos seleccionados
$nuevo_grupo = isset($_POST['nuevo_grupo']) ? $_POST['nuevo_grupo'] : null;
$alumnos_ids = isset($_POST['alumnos']) ? $_POST['alumnos'] : []; // Cambiar 'id_alumno' a 'alumnos'
$formulario = isset($_POST['formulario']) ? $_POST['formulario'] : '';

// Mostrar los datos enviados para depuración
echo "<pre>";
print_r($_POST);
echo "</pre>";

print_r($alumnos_ids);

// Asegurarse de que hay al menos un alumno seleccionado
if (!empty($alumnos_ids)) {

    // Escapar los valores para evitar SQL Injection
    $alumnos_ids = array_map('intval', $alumnos_ids); // Asegura que todos los IDs sean enteros
    $alumnos_ids_str = implode(',', $alumnos_ids);

    // Preparar la consulta de actualización
    $update_query = "UPDATE alumnos SET id_grupo = ? WHERE id_alumno IN ($alumnos_ids_str)";
    $stmt = $conexion->prepare($update_query);
    
    // Ejecutar la consulta con el nuevo grupo
    if ($stmt->execute([$nuevo_grupo])) {
        switch ($formulario) {
            case 'formulario1':
                $url_redireccion = "form_asignar_grupo1.php";
                break;
            case 'formulario2':
                $url_redireccion = "form_asignar_grupo2.php";
                break;
            case 'formulario3':
                $url_redireccion = "form_asignar_grupo3.php";
                break;
        default:
            header("Location: index.php"); // O redirige a una página por defecto
            break;
    }
    echo "<script>
    alert('Grupo actualizado con Exito!');
    window.location.href = '$url_redireccion';
</script>";
    } else {
        echo "Error actualizando grupo: " . implode(", ", $stmt->errorInfo());
    switch ($formulario) {
        case 'formulario1':
            $url_redireccion = "form_asignar_grupo1.php";
            break;
        case 'formulario2':
            $url_redireccion = "form_asignar_grupo2.php";
            break;
        case 'formulario3':
            $url_redireccion = "form_asignar_grupo3.php";
            break;
        default:
            header("Location: index.php"); // O redirige a una página por defecto
            break;
        }
            echo "<script>
    alert('Error actualizando grupo:'. implode(', ', $stmt->errorInfo()));
    window.location.href = '$url_redireccion';
</script>";
    }
} else {
        switch ($formulario) {
        case 'formulario1':
            $url_redireccion = "form_asignar_grupo1.php";
            break;
        case 'formulario2':
            $url_redireccion = "form_asignar_grupo2.php";
            break;
        case 'formulario3':
            $url_redireccion = "form_asignar_grupo3.php";
            break;
        default:
            header("Location: index.php"); // O redirige a una página por defecto
            break;
    }
    echo "<script>
    alert('No se selecciono ningun alumno');
    window.location.href = '$url_redireccion';
</script>";
}



$conexion = null; // Cerrar la conexión
?>