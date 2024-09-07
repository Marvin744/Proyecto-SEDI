<?php
    session_start(); // Asegúrate de que la sesión esté iniciada
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    $usuario = isset($_SESSION['user']) ? $_SESSION['user'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($usuario) || empty($password)) {
        echo "<script>
                alert('Todos los campos son obligatorios.');
                window.history.back(); // Redirige al usuario de vuelta al formulario
            </script>";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Verifica si el usuario existe
    $consultaExiste = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
    $queryExiste = $conexion->prepare($consultaExiste);
    $queryExiste->bindParam(':usuario', $usuario);
    $queryExiste->execute();
    $usuarioExiste = $queryExiste->fetchColumn();

    if ($usuarioExiste) {
        // Actualiza la contraseña del usuario
        $actualizarUsuario = "UPDATE usuarios SET password = :password WHERE usuario = :usuario";
        $query = $conexion->prepare($actualizarUsuario);

        $query->bindValue(':password', $passwordHash);
        $query->bindValue(':usuario', $usuario);

        $resultado = $query->execute();

        if ($resultado) {
            echo "<script>
                    alert('Contraseña actualizada exitosamente. Será redirigido al inicio de sesión.');
                    window.location.href='../login.html'; die;
                </script>";
        } else {
            echo "Error en la actualización: " . $query->errorInfo()[2];
        }
    } else {
        echo "<script>
                alert('El usuario no existe.');
                window.history.back();
            </script>";
    }

    $conexion = null; // Cierra la conexión
?>