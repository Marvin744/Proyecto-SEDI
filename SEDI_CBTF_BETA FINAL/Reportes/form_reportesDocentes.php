<?php
    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Docente', 'Directivo', 'Directivo_y_docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate PDF</title>
</head>
<body>
    <h2>REPORTES DE PERSONAL </h2>
    <form action="reportes_docentes.php" method="post">
        <select name="generate_pdf">
            <option value="pdf">REPORTE PERSONAL</option>
            <option value="pdf1">REPORTE TIPO DE PLAZA</option>
            <option value="pdf2">REPORTE FORMACIÓN ACADÉMICA</option>
            <option value="pdf3">REPORTE POR GRUPO DE EDAD</option>
            <option value="pdf4">REPORTE POR ANTIGÜEDAD</option>
            <option value="pdf5">REPORTE DE ESTUDIOS ACTUALES</option>
        </select>
        <button type="submit">Generate Reporte</button>
    </form>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>