<?php 
    require_once "../vistas/encabezado.php";
    require_once '../General_Actions/validar_sesion.php';

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Directivo', 'Admin', 'Administrativo_Docente']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Docente</title>
</head>
<body>
    <div>
        <h1>Alta de Docente</h1>
        <p>Aquí se podrá dar de alta a los docentes o personal del instituto.</p>
    </div>

    <div>
        <form action="Actions/procesar_altaDocente.php" method="POST">
            <fieldset>
                <legend>Formulario Alta DOCENTE</legend>
            </fieldset>

            <div>
                <label for="nombre_docente">Nombre(s) docente: </label>
                <input type="text" id="nombre_docente" name="nombre_docente" required>
            </div>

            <div>
                <label for="apellido_paterno">Apellido paterno: </label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" required>
            </div>

            <div>
                <label for="apellido_materno">Apellido materno: </label>
                <input type="text" id="apellido_materno" name="apellido_materno">
            </div>

            <div>
                <label for="RFC">RFC: </label>
                <input type="text" id="RFC" name="RFC" required>
            </div>

            <div>
                <label for="genero">Género: </label>
                <select name="genero" id="genero" required>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
            </div>

            <div>
                <label for="email">Email: </label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="grupo_edad">Grupo de edad: </label>
                <select name="grupo_edad" id="grupo_edad" required>
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

            <div>
                <label for="tipo_plaza">Tipo de plaza: </label>
                <select name="tipo_plaza" id="tipo_plaza" required>
                    <option value="40">Tiempo completo</option>
                    <option value="30">3/4 de tiempo</option>
                    <option value="20">1/2 tiempo</option>
                    <option value="Por horas">Por horas</option>
                </select>
            </div>

            <div>
                <label for="formacion_academica">Formación académica: </label>
                <select name="formacion_academica" id="formacion_academica" required>
                    <option value="Educación">Educación</option>
                    <option value="Artes y Humanidades">Artes y Humanidades</option>
                    <option value="Ciencias Sociales y Derecho">Ciencias Sociales y Derecho</option>
                    <option value="Aministración y Negocios">Administración y Negocios</option>
                    <option value="Ciencias Naturales, Matemáticas y estadística">Ciencias Naturales, Matemáticas y Estadística</option>
                    <option value="Tecnologías de la Información y la Comunicación">Tecnologías de la Información y la Comunicación</option>
                    <option value="Ingeniería, Manufactura y Construcción">Ingeniería, Manufactura y Construcción</option>
                    <option value="Agronomía y Veterinaria">Agronomía y Veterinaria</option>
                    <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                    <option value="Servicios">Servicios</option>
                </select>
            </div>

            <div>
                <label for="antiguedad">Antigüedad: </label>
                <select name="antiguedad" id="antiguedad" required>
                    <option value="0-4">De 0 a 4 años</option>
                    <option value="5-9">De 5 a 9 años</option>
                    <option value="10-14">De 10 a 14 años</option>
                    <option value="15-19">De 15 a 19 años</option>
                    <option value="20-24">De 20 a 24 años</option>
                    <option value="25-29">De 25 a 29 años</option>
                    <option value="De 30 años o más">De 30 años o más</option>
                </select>
            </div>

            <div>
                <label for="nivel_estudios">Nivel de estudios: </label>
                <select name="nivel_estudios" id="nivel_estudios" required>
                    <option value="Doctorado">Doctorado</option>
                    <option value="Maestría y especialidad">Maestría y especialidad</option>
                    <option value="Licenciatura Completa">Licenciatura completa</option>
                    <option value="Licenciatura incompleta o menos">Licenciatura incompleta o menos</option>
                </select>
            </div>

            <!-- Checkboxes -->
            <div>
                <label for="beca">
                    <input type="checkbox" id="beca" name="beca" value="1">
                    Cuenta con beca
                </label>
            </div>

            <div>
                <label for="discapacidad">
                    <input type="checkbox" id="discapacidad" name="discapacidad" value="1">
                    Tiene alguna discapacidad
                </label>
            </div>

            <div>
                <label for="lengua_indigena">
                    <input type="checkbox" id="lengua_indigena" name="lengua_indigena" value="1">
                    Habla alguna lengua indígena
                </label>
            </div>

            <div>
                <label for="funcion">Función del personal:</label>
                <select name="funcion" id="funcion" required>
                    <option value="Directivo sin grupo">Directivo sin grupo</option>
                    <option value="Directivo con grupo">Directivo con grupo</option>
                    <option value="Docente">Docente</option>
                    <option value="Administrativo, Auxiliar y de Servicios">Administrativo, Auxiliar y de Servicios</option>
                    <option value="Otros">Otro</option>
                </select>
            </div>

            <div>
                <label for="estudios_actuales">Estudios actuales:</label>
                <select name="estudio_actual" id="estudio_actual" required>
                    <option value="Sin estudios">   </option>
                    <option value="Doctorado">Doctorado</option>
                    <option value="Maestría">Maestría</option>
                    <option value="Especialidad">Especialidad</option>
                    <option value="Licenciatura">Licenciatura</option>
                    <option value="Técnico Superior">Técnico Superior</option>
                    <option value="Normal">Normal</option>
                </select>
            </div>

            <div>
                <label for="pais_estudio">País estudio:</label>
                <select name="pais_estudio" id="pais_estudio" required>
                    <option value="Sin estudios">    </option>
                    <option value="México">México</option>
                    <option value="Extranjero">Extranjero</option>
                </select>
            </div>

            <!-- Botón de envío -->
            <div>
                <button type="submit">Registrar Docente</button>
            </div>

        </form>
    </div>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>