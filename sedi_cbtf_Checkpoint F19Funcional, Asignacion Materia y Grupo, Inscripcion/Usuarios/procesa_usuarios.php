<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($perfil) || empty($usuario) || empty($password)) {
        echo "<script>
                alert('Todos los campos son obligatorios.');
                window.history.back(); // Redirige al usuario de vuelta al formulario
            </script>";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Verifica si el usuario ya existe
    $consultaExiste = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
    $queryExiste = $conexion->prepare($consultaExiste);
    $queryExiste->bindParam(':usuario', $usuario);
    $queryExiste->execute();
    $usuarioExiste = $queryExiste->fetchColumn();

    if ($usuarioExiste) {
        echo "<script>
                alert('El nombre de usuario ya está registrado.');
                window.history.back();
            </script>";
        exit();
    }

    // Inserta el nuevo usuario
    $insertarUsuario = "INSERT INTO usuarios (perfil, usuario, password) VALUES (:perfil, :usuario, :password)";
    $query = $conexion->prepare($insertarUsuario);

    $query->bindValue(':perfil', $perfil);
    $query->bindValue(':usuario', $usuario);
    $query->bindValue(':password', $passwordHash);

    $resultado = $query->execute();

    if ($resultado) {
        echo "<script>
                alert('Usuario registrado exitosamente.');
                window.location.href='../login.html'; die;
            </script>";
    } else {
        echo "Error en la inserción: " . $query->errorInfo()[2];
    }

    $conexion = null; // Cierra la conexión
?>