<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_y_docente', 'Administrativo_Docente', 'Administrativo_Jefe']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'habilitar_p1':
                    $_SESSION['habilitar_p1'] = true;
                    break;
                case 'deshabilitar_p1':
                    $_SESSION['habilitar_p1'] = false;
                    break;
                case 'habilitar_p2':
                    $_SESSION['habilitar_p2'] = true;
                    break;
                case 'deshabilitar_p2':
                    $_SESSION['habilitar_p2'] = false;
                    break;
                case 'habilitar_p3':
                    $_SESSION['habilitar_p3'] = true;
                    break;
                case 'deshabilitar_p3':
                    $_SESSION['habilitar_p3'] = false;
                    break;
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias y Calificaciones</title>
    <style>
        body.paginaexcel-page {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding-top: 8rem;
            margin: 0;
        }

        .paginaexcel-header {
            font-size: 3rem;
            color: #007BFF;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .paginaexcel-button-container {
            display: block;
            width: 100%;
            max-width: 130rem;
            margin: 0 auto;
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 0 1.5rem rgba(0, 0, 0, 0.1);
        }

        .paginaexcel-button-group {
            display: block;
            margin-bottom: 3rem;
        }

        .paginaexcel-button-group h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1.2rem;
        }

        .paginaexcel-button-group a {
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            margin: 1rem;
            display: inline-block;
            font-size: 1.8rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .paginaexcel-button-group a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .paginaexcel-button-group a:active {
            background-color: #003f7f;
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="paginaexcel-page">
    <div>
        <h1 class="paginaexcel-header">Asignación de Grupos</h1>
    </div>

    <div class="paginaexcel-button-container">
        <div class="paginaexcel-button-group">
            <h2>Seleccione el semestre del alumno al que desea asignar el grupo</h2>
            <a href="form_asignar_grupo1.php">Semestre 1 y 2</a>
            <a href="form_asignar_grupo2.php">Semestre 3 y 4</a>
            <a href="form_asignar_grupo3.php">Semestre 5 y 6</a>
        </div>
        <div class="paginaexcel-button-group">
            <h2>Calificaciones y asistencias de los Alumnos por parciales</h2>
            <a href="../Asignar_Materia/form_asignarMateria.php">Asignación de Materias</a>
        </div>
    </div>

</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>