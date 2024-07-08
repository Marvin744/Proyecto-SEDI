<?php
    require_once 'General_Actions/validar_sesion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Bienvenido</h1>
    </header>

    <form action="Docentes/docente_f19.php">
        <fieldset>
            <div>
                <input type="submit" value="F19 Docentes">
            </div>
        </fieldset>
    </form>

    <form action="Docentes/alta_modulos.php">
        <fieldset>
            <div>
                <input type="submit" value="Modulos y submodulos">
            </div>
        </fieldset>
    </form>

    <form action="docente_f19.php">
        <fieldset>
            <div>
                <input type="submit" value="F19 Docentes">
            </div>
        </fieldset>
    </form>

    <form action="General_Actions/logout.php">
        <fieldset>
            <div>
                <input type="submit" value="Salir">
            </div>
        </fieldset>
    </form>
</body>
</html>