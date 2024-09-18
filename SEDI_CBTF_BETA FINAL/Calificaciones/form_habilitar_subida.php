<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Jefe']);

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

    <style>
        .custom-control-panel {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            text-align: center; /* Centramos el contenido del cuerpo */
        }

        .custom-header {
            color: #333;
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .custom-form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 60rem;
            margin-left: 20rem;
            display: inline-block; /* Para que se centre dentro del contenedor */
            text-align: center; /* Para que el contenido dentro de la forma no se centre */
        }

        fieldset {
            border: none;
            margin: 10px;
        }

        .custom-div {
            margin-bottom: 20px;
            text-align: center; /* Centramos los divs dentro del formulario */
        }

        .custom-label {
            display: block;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .custom-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-habilitar {
            background-color: #28a745;
            color: #fff;
        }

        .btn-deshabilitar {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-cierre {
            background-color: #007bff;
            color: #fff;
        }

        .custom-button:hover {
            opacity: 0.8;
        }

        .custom-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .btn-cierre-importante {
            background-color: white;
            color: black;
            font-size: 18px;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(255, 87, 51, 0.4);
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-cierre-importante:hover {
            background-color: #e40000;
            color: white;
            box-shadow: 0 8px 15px rgba(255, 69, 0, 0.6);
            transform: scale(1.05);
        }

        .btn-cierre-importante:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.5);
        }

        .custom-h2 {
            font-weight: bold;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>

    <h2 class="custom-header">ACCIONES DEL SISTEMA</h2>

    <div class="custom-form">
        <form id="controlBotones" method="POST">
            <fieldset>
                <h2 class="custom-h2">SUBIDA DE CALIFICACIONES</h2>
                <div class="custom-div">
                    <label class="custom-label" for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 1</label>
                    <div>
                        <button type="button" class="custom-button btn-habilitar" id="habilitar_p1">Habilitar Parcial 1</button>
                        <button type="button" class="custom-button btn-deshabilitar" id="deshabilitar_p1">Deshabilitar Parcial 1</button>
                    </div>
                </div>

                <div class="custom-div">
                    <label class="custom-label" for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 2</label>
                    <div>
                        <button type="button" class="custom-button btn-habilitar" id="habilitar_p2">Habilitar Parcial 2</button>
                        <button type="button" class="custom-button btn-deshabilitar" id="deshabilitar_p2">Deshabilitar Parcial 2</button>
                    </div>
                </div>

                <div class="custom-div">
                    <label class="custom-label" for="habilitar">Habilitar/Deshabilitar Subir Calificaciones Parcial 3</label>
                    <div>
                        <button type="button" class="custom-button btn-habilitar" id="habilitar_p3">Habilitar Parcial 3</button>
                        <button type="button" class="custom-button btn-deshabilitar" id="deshabilitar_p3">Deshabilitar Parcial 3</button>
                    </div>
                </div>
            </fieldset>
           
        </form>
    </div>
    
    <div class="custom-form">
        <form method="POST" action="exportar_csv.php">
            <fieldset>
                <h2 class="custom-h2">ESCOLARIDAD</h2>
                <div class="custom-div">
                    <!-- <label class="custom-label" for="">Cerrar Ciclo Escolar</label> -->
                    <div>
                        <button type="button" class="custom-button btn-cierre-importante" id="cierre_ciclo_escolar" name="cierre_ciclo_escolar">CIERRE SEMESTRE</button>
                    </div>
                </div>
                <br>
                <label class="custom-label" for="">Exportar CSV de todos los alumnos</label>
                <div>
                    <button type="submit" class="custom-button btn-cierre" name="exportar_csv">Exportar CSV</button>
                </div>
            </fieldset>
        </form>
    </div>
    

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
                if (confirm("¿Está seguro de que desea DESHABILITAR las calificaciones del PARCIAL 3?")) {
                    $("<input>").attr("type", "hidden").attr("name", "action").attr("value", "deshabilitar_p3").appendTo("#controlBotones");
                    $("#controlBotones").submit();
                }
            });

            // Cierre Ciclo Escolar
            $("#cierre_ciclo_escolar").click(function() {
                if (confirm("¿Está seguro de que desea CERRAR EL SEMESTRE? Todos los alumnos inscritos pasarán a status PENDIENTE para la reinscripción. ¿Desea continuar?")) {
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