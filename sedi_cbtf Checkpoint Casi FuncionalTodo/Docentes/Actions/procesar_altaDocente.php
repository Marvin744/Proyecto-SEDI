<?php
// Incluir archivo de configuración para la conexión a la base de datos
include_once "../../bd/config.php";

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre_docente = $_POST['nombre_docente'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $RFC = $_POST['RFC'];
    $genero = $_POST['genero'];
    $email = $_POST['email'];
    $grupo_edad = $_POST['grupo_edad'];
    $tipo_plaza = $_POST['tipo_plaza'];
    $formacion_academica = $_POST['formacion_academica'];
    $antiguedad = $_POST['antiguedad'];
    $nivel_estudios = $_POST['nivel_estudios'];
    $beca = isset($_POST['beca']) ? 1 : 0;
    $discapacidad = isset($_POST['discapacidad']) ? 1 : 0;
    $lengua_indigena = isset($_POST['lengua_indigena']) ? 1 : 0;
    $funcion = $_POST['funcion'];
    $estudio_actual = isset($_POST['estudio_actual']);
    $pais_estudio = $_POST['pais_estudio'];

    try {
        // Crear una nueva conexión a la base de datos con modo de depuración activado
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta SQL para insertar los datos
        $sql = "INSERT INTO docentes (id_usuario, nombre_docente, apellido_paterno, apellido_materno, RFC, genero, email, grupo_edad, tipo_plaza, 
                                      formacion_academica, antiguedad, nivel_estudios, beca, discapacidad, lengua_indigena, funcion, 
                                      estudio_actual, pais_estudio) 
                VALUES (:id_usuario, :nombre_docente, :apellido_paterno, :apellido_materno, :RFC, :genero, :email, :grupo_edad, :tipo_plaza, 
                        :formacion_academica, :antiguedad, :nivel_estudios, :beca, :discapacidad, :lengua_indigena, :funcion, 
                        :estudio_actual, :pais_estudio)";

        // Preparar la declaración para ejecutar
        $stmt = $conexion->prepare($sql);

        // Ejecutar la consulta con los datos del formulario, id_usuario será NULL
        $stmt->execute([
            ':id_usuario' => NULL,
            ':nombre_docente' => $nombre_docente,
            ':apellido_paterno' => $apellido_paterno,
            ':apellido_materno' => $apellido_materno,
            ':RFC' => $RFC,
            ':genero' => $genero,
            ':email' => $email,
            ':grupo_edad' => $grupo_edad,
            ':tipo_plaza' => $tipo_plaza,
            ':formacion_academica' => $formacion_academica,
            ':antiguedad' => $antiguedad,
            ':nivel_estudios' => $nivel_estudios,
            ':beca' => $beca,
            ':discapacidad' => $discapacidad,
            ':lengua_indigena' => $lengua_indigena,
            ':funcion' => $funcion,
            ':estudio_actual' => $estudio_actual,
            ':pais_estudio' => $pais_estudio
        ]);

        // Redirigir al usuario a una página de éxito
        header("Location: ../alta_docente.php");
        exit();

    } catch (PDOException $e) {
        // Mostrar un mensaje de error si algo falla
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // Si el formulario no fue enviado correctamente, redirigir al formulario de nuevo
    header("Location: procesar_alta_docente.php");
    exit();
}
?>

