<?php
    ob_start(); // Inicia el almacenamiento en búfer de salida

    function verificarPermiso($perfilesRequeridos) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Asegurarse de que $perfilesRequeridos sea un array
        if (!is_array($perfilesRequeridos)) {
            $perfilesRequeridos = [$perfilesRequeridos];
        }

        if (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], $perfilesRequeridos)) {
            // Redirigir a una página de error o de acceso denegado
            header("Location: acceso_denegado.php"); die;
            exit;
        }
    }

    ob_end_flush(); // Envía el contenido del búfer de salida y desactiva el almacenamiento en búfer
?>