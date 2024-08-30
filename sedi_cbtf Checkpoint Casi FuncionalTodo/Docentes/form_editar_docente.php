<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    // Verificar si se ha proporcionado un ID de docente en la URL
    if (isset($_GET['id'])) {
        $id_docente = $_GET['id'];

        // Consultar los datos del docente con el ID proporcionado
        $sql = "SELECT * FROM docentes WHERE id_docente = :id_docente";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id_docente' => $id_docente]);
        $docente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontraron los datos
        if (!$docente) {
            echo "Docente no encontrado.";
            exit;
        }
    } else {
        echo "ID de docente no proporcionado.";
        exit;
    }

    // Procesar el formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos actualizados del formulario
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
        $estudio_actual = $_POST['estudio_actual'];
        $pais_estudio = $_POST['pais_estudio'];

        // Actualizar los datos en la base de datos
        $sql = "UPDATE docentes SET 
                nombre_docente = :nombre_docente,
                apellido_paterno = :apellido_paterno,
                apellido_materno = :apellido_materno,
                RFC = :RFC,
                genero = :genero,
                email = :email,
                grupo_edad = :grupo_edad,
                tipo_plaza = :tipo_plaza,
                formacion_academica = :formacion_academica,
                antiguedad = :antiguedad,
                nivel_estudios = :nivel_estudios,
                beca = :beca,
                discapacidad = :discapacidad,
                lengua_indigena = :lengua_indigena,
                funcion = :funcion,
                estudio_actual = :estudio_actual,
                pais_estudio = :pais_estudio
                WHERE id_docente = :id_docente";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
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
            ':pais_estudio' => $pais_estudio,
            ':id_docente' => $id_docente
        ]);


        // Redirigir al usuario a la página de modificación con un mensaje de éxito
        $url_redireccion = 'modificar_docente.php';
        echo "<script>
        alert('Modificacion realizada con exito');
        window.location.href = '$url_redireccion';
        </script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Docente</title>
</head>
<body>
    <div>
        <h1>Editar Docente</h1>
        <p>Modifica los datos del docente.</p>
    </div>

    <div>
        <form action="" method="POST">
            <div>
                <label for="nombre_docente">Nombre(s) docente: </label>
                <input type="text" id="nombre_docente" name="nombre_docente" value="<?php echo $docente['nombre_docente']; ?>" required>
            </div>

            <div>
                <label for="apellido_paterno">Apellido paterno: </label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo $docente['apellido_paterno']; ?>" required>
            </div>

            <div>
                <label for="apellido_materno">Apellido materno: </label>
                <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo $docente['apellido_materno']; ?>">
            </div>

            <div>
                <label for="RFC">RFC: </label>
                <input type="text" id="RFC" name="RFC" value="<?php echo $docente['RFC']; ?>">
            </div>

            <div>
                <label for="genero">Género: </label>
                <select name="genero" id="genero" required>
                    <option value="Hombre" <?php if ($docente['genero'] == 'Hombre') echo 'selected'; ?>>Hombre</option>
                    <option value="Mujer" <?php if ($docente['genero'] == 'Mujer') echo 'selected'; ?>>Mujer</option>
                </select>
            </div>

            <div>
                <label for="email">Email: </label>
                <input type="email" id="email" name="email" value="<?php echo $docente['email']; ?>" required>
            </div>

            <div>
                <label for="grupo_edad">Grupo de edad: </label>
                <select name="grupo_edad" id="grupo_edad" required>
                    <option value="24 años o menos" <?php if ($docente['grupo_edad'] == '24 años o menos') echo 'selected'; ?>>Menos de 24</option>
                    <option value="25-29" <?php if ($docente['grupo_edad'] == '25-29') echo 'selected'; ?>>De 25 a 29</option>
                    <option value="30-34" <?php if ($docente['grupo_edad'] == '30-34') echo 'selected'; ?>>De 30 a 34</option>
                    <option value="35-39" <?php if ($docente['grupo_edad'] == '35-39') echo 'selected'; ?>>De 35 a 39</option>
                    <option value="40-44" <?php if ($docente['grupo_edad'] == '40-44') echo 'selected'; ?>>De 40 a 44</option>
                    <option value="45-49" <?php if ($docente['grupo_edad'] == '45-49') echo 'selected'; ?>>De 45 a 49</option>
                    <option value="50-54" <?php if ($docente['grupo_edad'] == '50-54') echo 'selected'; ?>>De 50 a 54</option>
                    <option value="55-59" <?php if ($docente['grupo_edad'] == '55-59') echo 'selected'; ?>>De 55 a 59</option>
                    <option value="60-64" <?php if ($docente['grupo_edad'] == '60-64') echo 'selected'; ?>>De 60 a 64</option>
                    <option value="65 años o más" <?php if ($docente['grupo_edad'] == '65 años o más') echo 'selected'; ?>>De 65 o más</option>
                </select>
            </div>

            <div>
                <label for="tipo_plaza">Tipo de plaza: </label>
                <select name="tipo_plaza" id="tipo_plaza" required>
                    <option value="40" <?php if ($docente['tipo_plaza'] == '40') echo 'selected'; ?>>Tiempo completo</option>
                    <option value="30" <?php if ($docente['tipo_plaza'] == '30') echo 'selected'; ?>>3/4 de tiempo</option>
                    <option value="20" <?php if ($docente['tipo_plaza'] == '20') echo 'selected'; ?>>1/2 tiempo</option>
                    <option value="Por horas" <?php if ($docente['tipo_plaza'] == 'Por horas') echo 'selected'; ?>>Por horas</option>
                </select>
            </div>

            <div>
                <label for="formacion_academica">Formación académica: </label>
                <select name="formacion_academica" id="formacion_academica" required>
                    <option value="Educación" <?php if ($docente['formacion_academica'] == 'Educación') echo 'selected'; ?>>Educación</option>
                    <option value="Artes y Humanidades" <?php if ($docente['formacion_academica'] == 'Artes y Humanidades') echo 'selected'; ?>>Artes y Humanidades</option>
                    <option value="Ciencias Sociales y Derecho" <?php if ($docente['formacion_academica'] == 'Ciencias Sociales y Derecho') echo 'selected'; ?>>Ciencias Sociales y Derecho</option>
                    <option value="Aministración y Negocios" <?php if ($docente['formacion_academica'] == 'Aministración y Negocios') echo 'selected'; ?>>Administración y Negocios</option>
                    <option value="Ciencias Naturales, Matemáticas y estadística" <?php if ($docente['formacion_academica'] == 'Ciencias Naturales, Matemáticas y estadística') echo 'selected'; ?>>Ciencias Naturales, Matemáticas y estadística</option>
                    <option value="Tecnologías de la Información y la Comunicación" <?php if ($docente['formacion_academica'] == 'Tecnologías de la Información y la Comunicación') echo 'selected'; ?>>Tecnologías de la Información y la Comunicación</option>
                    <option value="Ingeniería, Manufactura y Construcción" <?php if ($docente['formacion_academica'] == 'Ingeniería, Manufactura y Construcción') echo 'selected'; ?>>Ingeniería, Manufactura y Construcción</option>
                    <option value="Agronomía y Veterinaria" <?php if ($docente['formacion_academica'] == 'Agronomía y Veterinaria') echo 'selected'; ?>>Agronomía y Veterinaria</option>
                    <option value="Ciencias de la Salud" <?php if ($docente['formacion_academica'] == 'Ciencias de la Salud') echo 'selected'; ?>>Ciencias de la Salud</option>
                    <option value="Servicios" <?php if ($docente['formacion_academica'] == 'Servicios') echo 'selected'; ?>>Servicios</option>
                </select>
            </div>

            <div>
                <label for="antiguedad">Antigüedad: </label>
                <select name="antiguedad" id="antiguedad" required>
                    <option value="0-4" <?php if ($docente['antiguedad'] == '0-4') echo 'selected'; ?>>De 0 a 4 años</option>
                    <option value="5-9" <?php if ($docente['antiguedad'] == '5-9') echo 'selected'; ?>>De 5 a 9 años</option>
                    <option value="10-14" <?php if ($docente['antiguedad'] == '10-14') echo 'selected'; ?>>De 10 a 14 años</option>
                    <option value="15-19" <?php if ($docente['antiguedad'] == '15-19') echo 'selected'; ?>>De 15 a 19 años</option>
                    <option value="20-24" <?php if ($docente['antiguedad'] == '20-24') echo 'selected'; ?>>De 20 a 24 años</option>
                    <option value="25-29" <?php if ($docente['antiguedad'] == '25-29') echo 'selected'; ?>>De 25 a 29 años</option>
                    <option value="De 30 años o más" <?php if ($docente['antiguedad'] == 'De 30 años o más') echo 'selected'; ?>>De 30 años o más</option>
                </select>
            </div>

            <div>
                <label for="nivel_estudios">Nivel de estudios: </label>
                <select name="nivel_estudios" id="nivel_estudios" required>
                    <option value="Doctorado" <?php if ($docente['nivel_estudios'] == 'Doctorado') echo 'selected'; ?>>Doctorado</option>
                    <option value="Maestría y especialidad" <?php if ($docente['nivel_estudios'] == 'Maestría y especialidad') echo 'selected'; ?>>Maestría y especialidad</option>
                    <option value="Licenciatura Completa" <?php if ($docente['nivel_estudios'] == 'Licenciatura Completa') echo 'selected'; ?>>Licenciatura completa</option>
                    <option value="Licenciatura incompleta o menos" <?php if ($docente['nivel_estudios'] == 'Licenciatura incompleta o menos') echo 'selected'; ?>>Licenciatura incompleta o menos</option>
                </select>
            </div>

            <!-- Checkboxes -->
            <div>
                <label for="beca">
                    <input type="checkbox" id="beca" name="beca" value="1" <?php if ($docente['beca'] == 1) echo 'checked'; ?>>
                    Cuenta con beca
                </label>
            </div>

            <div>
                <label for="discapacidad">
                    <input type="checkbox" id="discapacidad" name="discapacidad" value="1" <?php if ($docente['discapacidad'] == 1) echo 'checked'; ?>>
                    Tiene alguna discapacidad
                </label>
            </div>

            <div>
                <label for="lengua_indigena">
                    <input type="checkbox" id="lengua_indigena" name="lengua_indigena" value="1" <?php if ($docente['lengua_indigena'] == 1) echo 'checked'; ?>>
                    Habla alguna lengua indígena
                </label>
            </div>

            <div>
                <label for="funcion">Función del personal:</label>
                <select name="funcion" id="funcion" required>
                    <option value="Directivo sin grupo" <?php if ($docente['funcion'] == 'Directivo sin grupo') echo 'selected'; ?>>Directivo sin grupo</option>
                    <option value="Directivo con grupo" <?php if ($docente['funcion'] == 'Directivo con grupo') echo 'selected'; ?>>Directivo con grupo</option>
                    <option value="Docente" <?php if ($docente['funcion'] == 'Docente') echo 'selected'; ?>>Docente</option>
                    <option value="Administrativo, Auxiliar y de Servicios" <?php if ($docente['funcion'] == 'Administrativo, Auxiliar y de Servicios') echo 'selected'; ?>>Administrativo, Auxiliar y de Servicios</option>
                    <option value="Otros" <?php if ($docente['funcion'] == 'Otros') echo 'selected'; ?>>Otro</option>
                </select>
            </div>

            <div>
                <label for="estudio_actual">Estudios actuales:</label>
                <select name="estudio_actual" id="estudio_actual" required>
                    <option value="Sin estudios" <?php if ($docente['estudio_actual'] == 'Sin estudios') echo 'selected'; ?>></option>
                    <option value="Doctorado" <?php if ($docente['estudio_actual'] == 'Doctorado') echo 'selected'; ?>>Doctorado</option>
                    <option value="Maestría" <?php if ($docente['estudio_actual'] == 'Maestría') echo 'selected'; ?>>Maestría</option>
                    <option value="Especialidad" <?php if ($docente['estudio_actual'] == 'Especialidad') echo 'selected'; ?>>Especialidad</option>
                    <option value="Licenciatura" <?php if ($docente['estudio_actual'] == 'Licenciatura') echo 'selected'; ?>>Licenciatura</option>
                    <option value="Técnico Superior" <?php if ($docente['estudio_actual'] == 'Técnico Superior') echo 'selected'; ?>>Técnico Superior</option>
                    <option value="Normal" <?php if ($docente['estudio_actual'] == 'Normal') echo 'selected'; ?>>Normal</option>
                </select>
            </div>

            <div>
                <label for="pais_estudio">País estudio:</label>
                <select name="pais_estudio" id="pais_estudio" required>
                    <option value="Sin estudios" <?php if ($docente['pais_estudio'] == 'Sin estudios') echo 'selected'; ?>></option>
                    <option value="México" <?php if ($docente['pais_estudio'] == 'México') echo 'selected'; ?>>México</option>
                    <option value="Extranjero" <?php if ($docente['pais_estudio'] == 'Extranjero') echo 'selected'; ?>>Extranjero</option>
                </select>
            </div>

            <!-- Botón de envío -->
            <div>
                <button type="submit">Actualizar Docente</button>
            </div>

        </form>
    </div>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>