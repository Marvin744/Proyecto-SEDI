<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso('Administrativo_Jefe');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrativo Jefe</title>
</head>
<body>
    <h1>Perfil de ADMINISTRATIVO JEFE</h1>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>