<?php
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Prepara la consulta para evitar inyecciones SQL
    $consulta = "SELECT id_usuario, perfil, password FROM usuarios WHERE usuario = :usuario";
    $query = $conexion->prepare($consulta);
    $query->bindParam(':usuario', $usuario);
    $query->execute();

    $resultado = $query->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $hashedPassword = $resultado['password'];

        // Verifica si la contraseña almacenada es la por defecto
        if (password_verify('123456789', $hashedPassword) || $hashedPassword === '123456789') {
            // Verifica si la contraseña ingresada es la por defecto
            if ($password === '123456789') {
                // Iniciar sesión y establecer variables de sesión
                session_start();
                session_regenerate_id(true); // Previene fijación de sesión
                $_SESSION['id_usuario'] = $resultado['id_usuario'];
                $_SESSION['user'] = $usuario;
                $_SESSION['perfil'] = $resultado['perfil'];

                // Redirigir al formulario de cambio de contraseña
                echo json_encode(array("status" => "change_password"));
                exit;
            }
        }

        // Verifica si la contraseña ingresada coincide con la almacenada (hasheada o en texto plano)
        if (password_verify($password, $hashedPassword) || $password === $hashedPassword) {
            // Iniciar sesión y establecer variables de sesión
            session_start();
            session_regenerate_id(true); // Previene fijación de sesión
            $_SESSION['id_usuario'] = $resultado['id_usuario'];
            $_SESSION['user'] = $usuario;
            $_SESSION['perfil'] = $resultado['perfil'];

            // Redirigir al panel de control o página principal
            echo json_encode(array("status" => "success", "perfil" => $resultado['perfil']));
        } else {
            echo json_encode(array("status" => "error", "message" => "Usuario o contraseña incorrectos."));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Usuario o contraseña incorrectos."));
    }

    $conexion = null;
?>