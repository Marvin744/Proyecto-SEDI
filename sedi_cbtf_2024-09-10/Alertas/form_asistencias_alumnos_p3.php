<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_Jefe', 'Docente']);
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

    <style>
        body.excel-upload {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin-bottom: 10rem;
            padding: 0;
        }

        .excel-upload-section {
            text-align: center;
            margin: 20px 0;
        }

        .excel-upload-section h1 {
            font-size: 2.5em;
            color: #007BFF;
        }

        .excel-upload-form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: 40px auto; /* Ajuste de margen superior */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .excel-upload-fieldset {
            border: none;
            padding: 0;
        }

        .excel-upload-legend {
            font-size: 1.5em;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .excel-upload-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .excel-upload-input-wrapper {
            position: relative;
            width: 100%;
            text-align: center;
        }

        .excel-upload-input {
            opacity: 0;
            position: absolute;
            z-index: 2;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        .excel-upload-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1em;
            width: 100%;
            text-align: center;
            display: inline-block;
        }

        .excel-upload-button:hover {
            background-color: #0056b3;
        }

        .excel-upload-file-name {
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
            display: block;
        }

        .excel-upload-submit {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1em;
            margin: 20px auto 0; /* Ajusta los márgenes */
            display: block; /* Cambia a block para que el margen automático funcione */
        }

        .excel-upload-submit:hover {
            background-color: #0056b3;
        }

        .excel-upload-message {
            margin: 20px 0;
            text-align: center;
        }

        .excel-upload-loading {
            display: none;
            margin-top: 10px;
        }
    </style>
    
</head>
<body class="excel-upload">
    <section class="excel-upload-section">
        <h1>Asistencias Alumnos Parcial 3</h1>
    </section>

    <div>
        <!-- Contenedor para mensajes -->
        <div id="mensaje" class="excel-upload-message"></div>
        
        <form id="subir_csv" class="excel-upload-form" action="Actions/recibir_excelInasistencias_p3.php" method="POST" enctype="multipart/form-data">
            <fieldset class="excel-upload-fieldset">
                <legend class="excel-upload-legend">Carga el archivo de los alumnos</legend>

                <div class="excel-upload-input-wrapper">
                    <button type="button" class="excel-upload-button">Seleccionar archivo</button>
                    <input type="file" name="dataCliente" id="btn_subirAsistencias" class="excel-upload-input" required>
                    <span id="file-name" class="excel-upload-file-name">Ningún archivo seleccionado</span>
                </div>

                <div>
                    <input type="submit" value="Cargar" class="excel-upload-submit">
                </div>

                <div class="excel-upload-loading" id="loading">Cargando...</div>
            </fieldset>
        </form>
    </div>

    <!-- JavaScript para manejar la respuesta del servidor -->
    <script>
        document.getElementById("btn_subirAsistencias").addEventListener("change", function() {
            var fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
            document.getElementById("file-name").textContent = fileName;
        });

        document.getElementById("subir_csv").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita el envío del formulario para manejarlo con JS

            var formData = new FormData(this);

            document.getElementById("loading").style.display = "block";

            fetch("Actions/recibir_excelInasistencias_p3.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading").style.display = "none";
                if (data.status === "success") {
                    alert(data.message);
                } else if (data.status === "duplicate") {
                    alert(data.message);
                } else if (data.status === "error") {
                    alert(data.message);
                }
            })
            .catch(error => {
                document.getElementById("loading").style.display = "none";
                alert("Hubo un error al procesar la solicitud: " + error.message);
            });
        });
    </script>

</body>
</html>

<?php
    require_once "../vistas/pie_pagina.php";
?>