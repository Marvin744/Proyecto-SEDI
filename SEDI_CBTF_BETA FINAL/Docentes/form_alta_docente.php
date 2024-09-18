<?php 
    require_once "../vistas/encabezado.php";
    require_once '../General_Actions/validar_sesion.php';

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Docente', 'Administrativo_Jefe', 'Directivo', 'Directivo_y_docente']);

    if (isset($_SESSION['mensaje'])) {
        echo "<script>alert('".$_SESSION['mensaje']."');</script>";
        // Limpiar el mensaje de la sesión para que no se repita en la recarga
        unset($_SESSION['mensaje']);
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Docente</title>

    <style>
    /* Estilos generales 
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Estilos del formulario */
    .unique-form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 70rem;
        margin: auto;
        /* Para asegurar que esté centrado */
    }

    h1 {
        text-align: center;
        color: #333;
    }

    fieldset {
        border: none;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Estilos para los checkbox */
    .unique-checkbox-group {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .unique-checkbox-group label {
        margin-left: 8px;
        font-weight: normal;
    }

    /* Botón de envío */
    .unique-submit-button {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        text-align: center;
    }

    .unique-submit-button:hover {
        background-color: #0056b3;
    }

    /* Alinear correctamente las etiquetas y los campos */
    .unique-form-group {
        display: flex;
        flex-direction: column;
    }

    .unique-form-group label {
        margin-bottom: 5px;
    }

    .unique-form-group input,
    .unique-form-group select {
        margin-bottom: 15px;
    }

    /* Responsivo */
    @media (max-width: 600px) {
        .unique-form-container {
            padding: 15px;
        }

        .unique-submit-button {
            padding: 12px;
        }
    }
    </style>
</head>

<body>
    <div class="unique-form-container">
        <h1 style="font-weight: bold;">ALTA DEL PERSONAL DEL PLANTEL</h1>
        <p style="font-size: 2rem;">Ingrese los datos del personal como se indican a continuación.</p><br>
        <p style="font-size: 1.6rem;">Nota: En el título del personal finalícelo con un ".", por ejemplo "ING." o "M.T.I."</p>
        <br>

        <form action="Actions/procesar_altaDocente.php" method="POST">
            <fieldset>
                <!-- <legend>Formulario Alta DOCENTE</legend> -->

                <!-- Titulo del docente -->
                <div class="unique-form-group">
                    <label for="titulo_docente">Título del personal:</label>
                    <input type="text" id="titulo_docente" name="titulo_docente" required>
                </div>

                <!-- Nombre del docente -->
                <div class="unique-form-group">
                    <label for="nombre_docente">Nombre(s) personal:</label>
                    <input type="text" id="nombre_docente" name="nombre_docente" required>
                </div>

                <!-- Apellidos -->
                <div class="unique-form-group">
                    <label for="apellido_paterno">Apellido paterno:</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" required>
                </div>

                <div class="unique-form-group">
                    <label for="apellido_materno">Apellido materno:</label>
                    <input type="text" id="apellido_materno" name="apellido_materno">
                </div>

                <!-- RFC -->
                <div class="unique-form-group">
                    <label for="RFC">RFC:</label>
                    <input type="text" id="RFC" name="RFC" required>
                </div>

                <!-- Género -->
                <div class="unique-form-group">
                    <label for="genero">Género:</label>
                    <select name="genero" id="genero" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                    </select>
                </div>

                <!-- Email -->
                <div class="unique-form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <!-- Grupo de edad -->
                <div class="unique-form-group">
                    <label for="grupo_edad">Grupo de edad:</label>
                    <select name="grupo_edad" id="grupo_edad" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="24 años o menos">Menos de 24</option>
                        <option value="25-29">De 25 a 29</option>
                        <option value="30-34">De 30 a 34</option>
                        <option value="35-39">De 35 a 39</option>
                        <option value="40-44">De 40 a 44</option>
                        <option value="45-49">De 45 a 49</option>
                        <option value="50-54">De 50 a 54</option>
                        <option value="55-59">De 55 a 59</option>
                        <option value="60-64">De 60 a 64</option>
                        <option value="65 años o más">De 65 o más</option>
                    </select>
                </div>

                <!-- Tipo de plaza -->
                <div class="unique-form-group">
                    <label for="tipo_plaza">Tipo de plaza:</label>
                    <select name="tipo_plaza" id="tipo_plaza" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="40">Tiempo completo</option>
                        <option value="30">3/4 de tiempo</option>
                        <option value="20">1/2 tiempo</option>
                        <option value="Por horas">Por horas</option>
                    </select>
                </div>

                <!-- Formación académica -->
                <div class="unique-form-group">
                    <label for="formacion_academica">Formación académica:</label>
                    <select name="formacion_academica" id="formacion_academica" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Educación">Educación</option>
                        <option value="Artes y Humanidades">Artes y Humanidades</option>
                        <option value="Ciencias Sociales y Derecho">Ciencias Sociales y Derecho</option>
                        <option value="Administración y Negocios">Administración y Negocios</option>
                        <option value="Ciencias Naturales, Matemáticas y Estadística">Ciencias Naturales, Matemáticas y
                            Estadística</option>
                        <option value="Tecnologías de la Información y la Comunicación">Tecnologías de la Información y
                            la Comunicación</option>
                        <option value="Ingeniería, Manufactura y Construcción">Ingeniería, Manufactura y Construcción
                        </option>
                        <option value="Agronomía y Veterinaria">Agronomía y Veterinaria</option>
                        <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                        <option value="Servicios">Servicios</option>
                    </select>
                </div>

                <!-- Antigüedad -->
                <div class="unique-form-group">
                    <label for="antiguedad">Antigüedad:</label>
                    <select name="antiguedad" id="antiguedad" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="0-4">De 0 a 4 años</option>
                        <option value="5-9">De 5 a 9 años</option>
                        <option value="10-14">De 10 a 14 años</option>
                        <option value="15-19">De 15 a 19 años</option>
                        <option value="20-24">De 20 a 24 años</option>
                        <option value="25-29">De 25 a 29 años</option>
                        <option value="30 o más">De 30 años o más</option>
                    </select>
                </div>

                <!-- Nivel de estudios -->
                <div class="unique-form-group">
                    <label for="nivel_estudios">Nivel de estudios:</label>
                    <select name="nivel_estudios" id="nivel_estudios" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Doctorado">Doctorado</option>
                        <option value="Maestría y especialidad">Maestría y especialidad</option>
                        <option value="Licenciatura Completa">Licenciatura completa</option>
                        <option value="Licenciatura incompleta o menos">Licenciatura incompleta o menos</option>
                    </select>
                </div>

                <!-- Checkboxes -->
                <div class="unique-checkbox-group">
                    <input type="checkbox" id="beca" name="beca" value="1">
                    <label for="beca">Cuenta con beca</label>
                </div>

                <div class="unique-checkbox-group">
                    <input type="checkbox" id="discapacidad" name="discapacidad" value="1">
                    <label for="discapacidad">Tiene alguna discapacidad</label>
                </div>

                <div class="unique-checkbox-group">
                    <input type="checkbox" id="lengua_indigena" name="lengua_indigena" value="1">
                    <label for="lengua_indigena">Habla alguna lengua indígena</label>
                </div>

                <!-- Función del personal -->
                <div class="unique-form-group">
                    <label for="funcion">Función del personal:</label>
                    <select name="funcion" id="funcion" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Directivo sin grupo">Directivo sin grupo</option>
                        <option value="Directivo con grupo">Directivo con grupo</option>
                        <option value="Docente">Docente</option>
                        <option value="Administrativo, Auxiliar y de Servicios">Administrativo, Auxiliar y de Servicios
                        </option>
                        <option value="Otros">Otro</option>
                    </select>
                </div>

                <!-- Estudios actuales -->
                <div class="unique-form-group">
                    <label for="estudio_actual">Estudios actuales:</label>
                    <select name="estudio_actual" id="estudio_actual" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Doctorado">Doctorado</option>
                        <option value="Maestría">Maestría</option>
                        <option value="Especialidad">Especialidad</option>
                        <option value="Licenciatura">Licenciatura</option>
                        <option value="Técnico Superior">Técnico Superior</option>
                        <option value="Normal">Normal</option>
                    </select>
                </div>

                <!-- País estudio -->
                <div class="unique-form-group">
                    <label for="pais_estudio">País estudio:</label>
                    <select name="pais_estudio" id="pais_estudio" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="México">México</option>
                        <option value="Extranjero">Extranjero</option>
                    </select>
                </div>
                <br>

                <!-- Botón de envío -->
                <button type="submit" class="unique-submit-button">Registrar Docente</button>

            </fieldset>
        </form>
    </div>
</body>

</html>

<?php require_once "../vistas/pie_pagina.php"; ?>