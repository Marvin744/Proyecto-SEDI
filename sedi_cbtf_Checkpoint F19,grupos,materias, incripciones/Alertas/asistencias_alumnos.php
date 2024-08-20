<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias</title>

    <link rel="stylesheet" href="styles/normalize.css">
    <link rel="preload" href="styles/style1.css?ver=1.00" as="style">
    <link rel="stylesheet" href="styles/style1.css?ver=1.00">

    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
</head>
<body>
    <section>
        <h1>Asistencias Alumnos</h1>
    </section>

    <div>
        <!-- Contenedor para mensajes -->
        <div id="mensaje"></div>
        
        <form id="subir_csv" class="" action="recibir_excelInasistencias.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Carga el archivo de los alumnos</legend>

                <div class="">
                    <label for="">Cargar archivo</label><br>
                    <input type="file" name="dataCliente" id="btn_subirAsistencias"> <!-- Asegúrate que el name coincida -->
                </div>

                <div>
                    <input type="submit" value="Cargar">
                </div>
            </fieldset>
        </form>
    </div>

    <!-- JavaScript para manejar la respuesta del servidor -->
    <script>
        document.getElementById("subir_csv").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita el envío del formulario para manejarlo con JS

            var formData = new FormData(this);

            fetch("recibir_excelInasistencias.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                } else if (data.status === "duplicate") {
                    alert(data.message);
                } else if (data.status === "error") {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert("Hubo un error al procesar la solicitud: " + error.message);
            });
        });
    </script>

</body>
</html>