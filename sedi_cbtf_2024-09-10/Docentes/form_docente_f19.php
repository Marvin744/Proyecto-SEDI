<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F19 Docentes</title>

    <link rel="stylesheet" href="styles/normalize.css">
    <link rel="preload" href="styles/style1.css?ver=1.00" as="style">
    <link rel="stylesheet" href="styles/style1.css?ver=1.00">

    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin: 0 4px;
        }
        
        .pagination button:hover {
            background-color: #45a049;
        }

        /* .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 15px;
            margin-left: 5px;
            background: #4CAF50;
            border: 1px solid transparent;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #45a049;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 5px;
            margin-left: 5px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }

        .dataTables_wrapper .dataTables_length select {
            padding: 5px;
            margin-left: 5px;
            border-radius: 3px;
            border: 1px solid #ddd;
        } */

        .boton_cargar input[type="file"] {
            display: none;
        }
        .boton_cargar label {
            cursor: pointer;
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-align: center;
            margin-right: 10px; /* Espacio entre los botones */
        }
        .boton_subir input[type="submit"] {
            display: none;
        }
        .boton_subir svg {
            cursor: pointer;
            vertical-align: middle; /* Alineación vertical */
        }
        .boton_subir div {
            display: inline-block; /* Alineación en línea */
        }

    </style>
</head>
<body>

    <section>
        <h1 style="font-size: 2.4rem; font-weight: bold; text-align: center;">Generación del Formato 19 de los Docentes</h1>
        <p style="font-size: 2rem;">Seleccione el archivo con los datos de los horarios en formato ".csv" y presione el botón de Subir.</p>
    </section>

    <?php
        $sql = "SELECT nombre_archivo, fecha_subida FROM archivos_subidos ORDER BY fecha_subida DESC LIMIT 1";
        $query = $conexion->prepare($sql);
        $query->execute();

        $ultimo_archivo = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    
    <form id="subir_csv" class="boton_subir" action="Actions/recibe_excel_datosHorarios.php" method="POST" enctype="multipart/form-data">
        <div class="boton_cargar">
            <input type="file" name="dataCliente" id="btn_subirF19">
            <label for="btn_subirF19">Seleccionar archivo</label>
        </div>
        <div>
            <button type="submit" id="submit-svg">
                <svg xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 512 512">
                    <path fill="#3cc62a" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM385 231c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-71-71V376c0 13.3-10.7 24-24 24s-24-10.7-24-24V193.9l-71 71c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 119c9.4-9.4 24.6-9.4 33.9 0L385 231z"/>
                </svg>
            </button>
        </div>

        <?php if ($ultimo_archivo): ?>

        <div class="ultimo-archivo">
            <p>Último archivo subido: <?php echo htmlspecialchars($ultimo_archivo['nombre_archivo']); ?></p>
            <p>Fecha de subida: <?php echo htmlspecialchars($ultimo_archivo['fecha_subida']); ?></p>
        </div>

        <?php endif; ?>

    </form>

    <div>
        <br>
        <table class="display" id="tablaHorarios" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">ID Docente</th>
                    <th scope="col">Nombre Docente</th>
                    <th scope="col">RFC</th>
                    <th scope="col">Plaza (horas)</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    $sqlDocentes = "SELECT * FROM docentes ORDER BY apellido_paterno ASC;";
                    $queryTableDocentes = $conexion->prepare($sqlDocentes);
                    $queryTableDocentes->execute();
                    
                    foreach ($queryTableDocentes as $row) {
                        $nombreCompleto = htmlspecialchars($row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ' ' . $row['nombre_docente'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>

                                <td class='alinear-centro'>{$row['id_docente']}</td>
                                <td class='alinear-izquierda'>{$nombreCompleto}</td>
                                <td class='alinear-centro'>{$row['RFC']}</td>
                                <td class='alinear-centro'>{$row['tipo_plaza']}</td>
                                <td class='alinear-centro'>
                                    <button class='f19-btn' data-id='{$row['id_docente']}' data-name='{$nombreCompleto}'>F19</button>
                                    <button class='edit-btn' data-id='{$row['id_docente']}' data-name='{$nombreCompleto}'>Editar</button>
                                    <!-- <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id_docente']}'  data-name='{$nombreCompleto}'>Info</button> -->
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   





    <script> // Script para validar si se ha subido el archivo
        document.getElementById('submit-svg').addEventListener('click', function(event) {
            const fileInput = document.getElementById('btn_subirF19');
            if (!fileInput.files.length) {
                alert('Por favor, selecciona un archivo antes de subir.');
                event.preventDefault();
            } else {
                //document.querySelector('.boton_subir').submit();
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables con opciones personalizadas
            var table = $('#tablaHorarios').DataTable({
                "pageLength": 10, // Número de filas por página
                "lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
                "pagingType": "full_numbers", // Estilo de paginación: "simple", "simple_numbers", "full", "full_numbers"
                "language": {   // 
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }

                

            });

            // Configuración para que la búsqueda se aplique en tiempo real
            $('#campo').on("keyup", function() {
                table.search($(this).val()).draw();
            });

            // Acción para el botón de f19
            $(document).on('click', '.f19-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea EDITAR el F19 del docente: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para editar el alumno
                    window.location.href = 'form_tablaF19.php?id_docente=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el alumno

                }

            });

            // Acción para el botón de editar
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea EDITAR al Docente: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para editar el docente
                    window.location.href = 'form_editar_docente.php?id_docente=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el docente
                } 
            });
        });
    </script>

    <script>
        document.getElementById("subir_csv").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita el envío del formulario para manejarlo con JS

            var formData = new FormData(this);

            fetch("Actions/recibe_excel_datosHorarios.php", {
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

<?php require_once "../vistas/pie_pagina.php"; ?>