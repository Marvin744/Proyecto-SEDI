<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $boton = $_POST['action'];

            switch ($boton) {
                case 'habilitar_p1':
                case 'deshabilitar_p1':
                    $sql = "UPDATE config_botones SET estado = :estado WHERE boton = 'p1'";
                    $estado = ($boton === 'habilitar_p1') ? 1 : 0;
                    break;
                case 'habilitar_p2':
                case 'deshabilitar_p2':
                    $sql = "UPDATE config_botones SET estado = :estado WHERE boton = 'p2'";
                    $estado = ($boton === 'habilitar_p2') ? 1 : 0;
                    break;
                case 'habilitar_p3':
                case 'deshabilitar_p3':
                    $sql = "UPDATE config_botones SET estado = :estado WHERE boton = 'p3'";
                    $estado = ($boton === 'habilitar_p3') ? 1 : 0;
                    break;
                default:
                    $estado = 0;
                    break;
            }

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_BOOL);
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo());
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Control de Botones</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <h2>Control de Botones de Calificaciones</h2>

    <form id="controlBotones" method="POST">
        <div>
            <label for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 1</label>
            <div>
                <button type="button" id="habilitar_p1">Habilitar Parcial 1</button>
                <button type="button" id="deshabilitar_p1">Deshabilitar Parcial 1</button>
            </div>
        </div>

        <div>
            <label for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 2</label>
            <div>
                <button type="button" id="habilitar_p2">Habilitar Parcial 2</button>
                <button type="button" id="deshabilitar_p2">Deshabilitar Parcial 2</button>
            </div>
        </div>

        <div>
            <label for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 3</label>
            <div>
                <button type="button" id="habilitar_p3">Habilitar Parcial 3</button>
                <button type="button" id="deshabilitar_p3">Deshabilitar Parcial 3</button>
            </div>
        </div>
    </form>

    <div>
        <label for="">Cerrar Ciclo Escolar</label>
        <div>
            <button type="button" id="cierre_ciclo_escolar" name="cierre_ciclo_escolar">Cierre Ciclo Escolar</button>
        </div>
    </div>

    <form method="POST" action="exportar_csv.php">
        <label for="">Exportar CSV de todos los alumnos</label>
        <div>
            <button type="submit" name="exportar_csv">Exportar CSV</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            // Habilitar/Deshabilitar botón Parcial 1
            $("#habilitar_p1").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 1?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "habilitar_p1").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            $("#deshabilitar_p1").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 1?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "deshabilitar_p1").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            // Habilitar/Deshabilitar botón Parcial 2
            $("#habilitar_p2").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 2?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "habilitar_p2").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            $("#deshabilitar_p2").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 2?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "deshabilitar_p2").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            // Habilitar/Deshabilitar botón Parcial 3
            $("#habilitar_p3").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 3?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "habilitar_p3").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            $("#deshabilitar_p3").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 3?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "deshabilitar_p3").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            // Cierre Ciclo Escolar
            $("#cierre_ciclo_escolar").click(function() {
                if (confirm("¿Estás seguro de que deseas realizar el cierre del ciclo escolar?")) {
                    $.post("cierra_ciclo_escolar.php", function(response) {
                        alert("El ciclo escolar se ha cerrado y el estado de los alumnos ha sido actualizado a 'PENDIENTE'.");
                    });
                }
            });
        });
    </script>

</body>

</html>

<?php
    require_once "../vistas/pie_pagina.php";
?>