<?php require_once '../General_Actions/validar_sesion.php';?>
<?php require_once "../vistas/encabezado.php"?>

<!DOCTYPE html>
<html lang="en">
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
        <form action="">
            <fieldset>
                <legend>Formulario Alta DOCENTE</legend>
            </fieldset>

            <div>
                <label for="">Nombre(s) docente: </label>
                <input type="text">
            </div>

            <div>
                <label for="">Apellido paterno: </label>
                <input type="text">
            </div>

            <div>
                <label for="">Apellido materno: </label>
                <input type="text">
            </div>

            <div>
                <label for="">RFC: </label>
                <input type="text">
            </div>

            <div>
                <label for="">Email: </label>
                <input type="text">
            </div>

            <div>
                <label for="">Grupo de edad: </label>
                <select name="grupo-edad" id="grupo-edad">
                    <option value="-24">Menos de 24</option>
                    <option value="25-29">De 25 a 29</option>
                    <option value="30-34">De 30 a 34</option>
                    <option value="35-39">De 35 a 39</option>
                    <option value="40-44">De 40 a 44</option>
                    <option value="45-49">De 45 a 49</option>
                    <option value="50-54">De 50 a 54</option>
                    <option value="55-59">De 55 a 59</option>
                    <option value="60-64">De 60 a 64</option>
                    <option value="+65">De 65 o más</option>
                </select>
            </div>

            <div>
                <label for="">Tipo de plaza: </label>
                <select name="tipo-plaza" id="tipo-plaza">
                    <option value="40">Tiempo completo</option>
                    <option value="30">3/4 de tiempo</option>
                    <option value="20">1/2 tiempo</option>
                    <option value="">Por horas</option>
                </select>
            </div>

            <div>
                <label for="">Campo de formación académica: </label>
                <select name="formacion-academica" id="formacion-academica">
                    <option value="">Educación</option>
                    <option value="">Artes y Humanidades</option>
                    <option value="">Ciencias Sociales y Derecho</option>
                    <option value="">Adminsitración y Negocios</option>
                    <option value="">Ciencias Naturales, Matemáticas y Estadística</option>
                    <option value="">Tecnologías de la Información y la Comunicación</option>
                    <option value="">Ingeniería, Manufactura y Construcción</option>
                    <option value="">Agronomía y Veterinaria</option>
                    <option value="">Ciencias de la Salud</option>
                    <option value="">Servicios</option>
                </select>
            </div>

            <div>
                <label for="">Antigüedad: </label>
                <select name="antiguedad" id="antiguedad">
                    <option value="0-4">De 0 a 4 años</option>
                    <option value="5-9">De 5 a 9 años</option>
                    <option value="10-14">De 10 a 14 años</option>
                    <option value="15-19">De 15 a 19 años</option>
                    <option value="20-24">De 20 a 24 años</option>
                    <option value="25-29">De 25 a 29 años</option>
                    <option value="+30">De 30 años o más</option>
                </select>
            </div>

            <div>
                <label for="">Nivel de estudios:</label>
                <select name="nivel-estudios" id="nivel-estudios">
                    <option value="">Doctorado</option>
                    <option value="">Maestría</option>
                    <option value="">Especialidad</option>
                    <option value="">Licenciatura</option>
                    <option value="">Técnico Superior</option>
                    <option value="">Normal</option>
                </select>
            </div>

        </form>
    </div>
</body>
</html>