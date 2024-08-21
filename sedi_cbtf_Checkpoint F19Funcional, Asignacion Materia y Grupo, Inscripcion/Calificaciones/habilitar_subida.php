<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Botones</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

    <h2>Control de Botones de Calificaciones</h2>
    
    <form id="controlBotones">
        <!-- Botones para Calificaciones Parcial 1 -->
        <button type="button" id="habilitar_p1">Habilitar Parcial 1</button>
        <button type="button" id="deshabilitar_p1">Deshabilitar Parcial 1</button>

        <!-- Botones para Calificaciones Parcial 2 -->
        <button type="button" id="habilitar_p2">Habilitar Parcial 2</button>
        <button type="button" id="deshabilitar_p2">Deshabilitar Parcial 2</button>

        <!-- Botones para Calificaciones Parcial 3 -->
        <button type="button" id="habilitar_p3">Habilitar Parcial 3</button>
        <button type="button" id="deshabilitar_p3">Deshabilitar Parcial 3</button>

        <!-- Botón para cierre del ciclo escolar -->
        <button type="button" id="cierre_ciclo_escolar">Cierre Ciclo Escolar</button>
    </form>

    <script>
        $(document).ready(function() {
            // Habilitar/Deshabilitar botón Parcial 1
            $("#habilitar_p1").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 1?")) {
                    $.post("calificaciones_alumnos.php", { action: "habilitar_p1" });
                }
            });

            $("#deshabilitar_p1").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 1?")) {
                    $.post("calificaciones_alumnos.php", { action: "deshabilitar_p1" });
                }
            });

            // Habilitar/Deshabilitar botón Parcial 2
            $("#habilitar_p2").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 2?")) {
                    $.post("calificaciones_alumnos.php", { action: "habilitar_p2" });
                }
            });

            $("#deshabilitar_p2").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 2?")) {
                    $.post("calificaciones_alumnos.php", { action: "deshabilitar_p2" });
                }
            });

            // Habilitar/Deshabilitar botón Parcial 3
            $("#habilitar_p3").click(function() {
                if (confirm("¿Estás seguro de que deseas habilitar el botón Parcial 3?")) {
                    $.post("calificaciones_alumnos.php", { action: "habilitar_p3" });
                }
            });

            $("#deshabilitar_p3").click(function() {
                if (confirm("¿Estás seguro de que deseas deshabilitar el botón Parcial 3?")) {
                    $.post("calificaciones_alumnos.php", { action: "deshabilitar_p3" });
                }
            });

            // Cierre Ciclo Escolar
            $("#cierre_ciclo_escolar").click(function() {
                if (confirm("¿Estás seguro de que deseas realizar el cierre del ciclo escolar?")) {
                    $.post("cierre_ciclo_escolar.php", function(response) {
                        alert("El ciclo escolar se ha cerrado y el estado de los alumnos ha sido actualizado a 'PENDIENTE'.");
                    });
                }
            });
        });
    </script>

</body>
</html>
