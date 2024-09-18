<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    // Obtener el ID del usuario desde la URL
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

    // Contraseña fija en texto plano
    // $new_password = password_hash("123456789", PASSWORD_DEFAULT);
    $new_password = "123456789";

    // Preparar la consulta SQL
    $sql = "UPDATE `usuarios` SET `password` = '123456789' WHERE `usuarios`.`id_usuario` = :id_usuario;";
    $query = $conexion->prepare($sql);

    // Vincular los parámetros
    // $query->bindParam(':password', $new_password);
    $query->bindParam(':id_usuario', $id_usuario);

    // Ejecutar la consulta
    if ($query->execute()) {
        echo "<script>
                alert('¡Contraseña cambiada con éxito!\\nIndique al usuario que ingrese con la contraseña predeterminada del sistema.');
                window.location.href='form_olvidar_password.php'; // Redirige al login o a la página que prefieras
            </script>";
    } else {
        $errorInfo = $query->errorInfo();
        echo "<script>
                alert('Error al actualizar la contraseña: " . $errorInfo[2] . "');
                window.history.back();
            </script>";
    }

    $conexion = null; // Cierra la conexión
?>