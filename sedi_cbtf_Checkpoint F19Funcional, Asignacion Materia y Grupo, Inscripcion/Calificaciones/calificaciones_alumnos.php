<?php
session_start();

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

// Aquí puedes generar el formulario para las calificaciones, y los botones estarán habilitados/deshabilitados
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificaciones Alumnos</title>
</head>
<body>

    <h2>Registro de Calificaciones</h2>

    <form>
        <button type="button" id="calificaciones_parcial_1" <?php if(empty($_SESSION['habilitar_p1'])) echo 'disabled'; ?>>Parcial 1</button>
        <button type="button" id="calificaciones_parcial_2" <?php if(empty($_SESSION['habilitar_p2'])) echo 'disabled'; ?>>Parcial 2</button>
        <button type="button" id="calificaciones_parcial_3" <?php if(empty($_SESSION['habilitar_p3'])) echo 'disabled'; ?>>Parcial 3</button>
    </form>

</body>
</html>
