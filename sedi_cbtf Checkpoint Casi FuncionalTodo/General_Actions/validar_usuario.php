<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    try {
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error", "message" => "Error de conexión a la base de datos: " . $e->getMessage()));
        exit();
    }

    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($usuario) || empty($password)) {
        echo json_encode(array("status" => "error", "message" => "Usuario o contraseña no pueden estar vacíos."));
        exit();
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $consulta = "SELECT id_usuario, perfil, password FROM usuarios WHERE usuario = :usuario;";
        $query = $conexion->prepare($consulta);
        $query->bindParam(':usuario', $usuario);
        $query->execute();

        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $hashedPassword = $resultado['password'];
            $perfil = $resultado['perfil'];
            $idUsuario = $resultado['id_usuario'];

            // Obtener el nombre completo según el perfil antes de verificar la contraseña
            if ($perfil === 'Alumno') {
                $consultaNombre = "SELECT nombre_alumno, apellido_paterno, apellido_materno FROM alumnos WHERE id_usuario = :idUsuario;";
            } elseif ($perfil === 'Docente') {
                $consultaNombre = "SELECT nombre_docente, apellido_paterno, apellido_materno FROM docentes WHERE id_usuario = :idUsuario;";
            } elseif ($perfil === 'Administrativo') {
                $consultaNombre = "SELECT nombre_docente, apellido_paterno, apellido_materno FROM docentes WHERE id_usuario = :idUsuario;";
            } elseif ($perfil === 'Directivo') {
                $consultaNombre = "SELECT nombre_docente, apellido_paterno, apellido_materno FROM docentes WHERE id_usuario = :idUsuario;";
            } elseif ($perfil === 'Admin') {
                $consultaNombre = "SELECT nombre_docente, apellido_paterno, apellido_materno FROM docentes WHERE id_usuario = :idUsuario;";
            } else {
                $consultaNombre = ""; // En caso de que no se reconozca el perfil
            }

            if (!empty($consultaNombre)) {
                $queryNombre = $conexion->prepare($consultaNombre);
                $queryNombre->bindParam(':idUsuario', $idUsuario);
                $queryNombre->execute();
                $nombreResultado = $queryNombre->fetch(PDO::FETCH_ASSOC);

                if ($nombreResultado) {
                    $nombreCompleto = $nombreResultado['nombre_alumno'] ?? $nombreResultado['nombre_docente'] ?? $nombreResultado['nombre_administrativo'] ?? $nombreResultado['nombre_directivo'] ?? $nombreResultado['nombre_admin'];
                    $nombreCompleto .= " " . $nombreResultado['apellido_paterno'] . " " . $nombreResultado['apellido_materno'];
                } else {
                    // Asignar un nombre por defecto según el perfil
                    if ($perfil === 'Alumno') {
                        $nombreCompleto = "Alumno de prueba";
                    } elseif ($perfil === 'Docente') {
                        $nombreCompleto = "Docente de prueba";
                    } elseif ($perfil === 'Administrativo') {
                        $nombreCompleto = "Área Administrativa";
                    } elseif ($perfil === 'Directivo') {
                        $nombreCompleto = "Directivo de prueba";
                    } elseif ($perfil === 'Admin') {
                        $nombreCompleto = "Admin Todopoderoso";
                    } else {
                        $nombreCompleto = "Usuario de prueba";
                    }
                }

                // Verificar la contraseña
                if (password_verify($password, $hashedPassword) || $password === $hashedPassword) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['id_usuario'] = $idUsuario;
                    $_SESSION['user'] = $usuario;
                    $_SESSION['perfil'] = $perfil;
                    $_SESSION['nombre_completo'] = $nombreCompleto;

                    // Verificar si la contraseña es la por defecto
                    if ($password === '123456789') {
                        // Redirigir al formulario de cambio de contraseña
                        echo json_encode(array("status" => "change_password"));
                        exit;
                    }

                    // Redirigir al panel de control o página principal
                    echo json_encode(array("status" => "success", "perfil" => $perfil));
                } else {
                    echo json_encode(array("status" => "error", "message" => "Usuario o contraseña incorrectos."));
                }
            } else {
                echo json_encode(array("status" => "error", "message" => "Error al determinar el nombre del usuario."));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Usuario o contraseña incorrectos."));
        }
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error", "message" => "Error al realizar la consulta: " . $e->getMessage()));
    }

    $conexion = null;
?>