<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Administrativo_Docente', 'Administrativo_Jefe', 'Administrativo_y_docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Docente</title>
</head>
<body>
    <h1>Perfil de DOCENTE</h1>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>