<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
?>
<?php require_once '../General_Actions/validar_sesion.php';?>
<?php require_once "../vistas/encabezado.php"?>

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
        <h1>Generación del Formato 19 de los Docentes</h1>
        <p>Primero cargue el archivo de los horarios y luego modifique los datos en las tablas</p>
    </section>

    <?php
        $sql = "SELECT nombre_archivo, fecha_subida FROM archivos_subidos ORDER BY fecha_subida DESC LIMIT 1";
        $query = $conexion->prepare($sql);
        $query->execute();

        $ultimo_archivo = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    
    <form class="boton_subir" action="Actions/recibe_excel_datosHorarios.php" method="POST" enctype="multipart/form-data">
        <div class="boton_cargar">
            <input type="file" name="dataCliente" id="btn_subirF19">
            <label for="btn_subirF19">Seleccionar archivo</label>
        </div>

        <div>
            <svg id="submit-svg" xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 512 512">
                <path fill="#3cc62a" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM385 231c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-71-71V376c0 13.3-10.7 24-24 24s-24-10.7-24-24V193.9l-71 71c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 119c9.4-9.4 24.6-9.4 33.9 0L385 231z"/>
            </svg>
            <input type="submit" name="subir" value="Subir Excel">
        </div>

        <?php if ($ultimo_archivo): ?>
        <div class="ultimo-archivo">
            <p>Último archivo subido: <?php echo htmlspecialchars($ultimo_archivo['nombre_archivo']); ?></p>
            <p>Fecha de subida: <?php echo htmlspecialchars($ultimo_archivo['fecha_subida']); ?></p>
        </div>
        <?php endif; ?>
    </form>

    <div class="datos_f19">
        <label for="">Número de Oficio: </label>
        <input type="number"><br>

        <label for="">Docente: </label>
        <select name="docentes" id="docentes">
            <?php

                $sql="SELECT DISTINCT profesor FROM datos_horarios ORDER BY profesor ASC;";
                
                        $query = $conexion->prepare($sql);
                       
                        $query->execute();
                        $result = $query->fetchAll();

                        foreach ($result as $row) {
                    
                            $nombre_docente = $row['profesor'];
                            //$apellido_paterno = $row['apellido_paterno'];
                            //$apellido_materno = $row['apellido_materno'];
                            // Genera una opción con el nombre y apellido del docente
                            echo "<option value=\"$nombre_docente\">$nombre_docente</option>";
                        }
                
            ?>
        </select><br>

        <label for="fecha">Fecha: </label>
        <input id="fecha" class="fecha" type="date"><br>

        <label for="">Periodo: </label>
        <input type="text" name="" id="">
    </div>

    <div>
        <input class="boton_enviar" type="submit" value="Enviar"></input>
    </div>


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
                    $sqlDocentes = ("SELECT * FROM docentes ORDER BY apellido_paterno ASC;");
                    $queryTableDocentes = $conexion->prepare($sqlDocentes);
                    $queryTableDocentes->execute();
                    
                    foreach ($queryTableDocentes as $row) {
                        $nombreCompleto = htmlspecialchars($row['nombre_docente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>
                                <td>{$row['id_docente']}</td>
                                <td>{$nombreCompleto}</td>
                                <td>{$row['RFC']}</td>
                                <td>{$row['tipo_plaza']}</td>
                                <td>
                                    <button class='f19-btn' data-id='{$row['id_docente']}' data-name='{$nombreCompleto}'>F19</button>
                                    <button class='pdf-btn' data-id='{$row['id_docente']}' data-name='{$nombreCompleto}'>PDF</button>
                                    <!-- <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id_docente']}'  data-name='{$nombreCompleto}'>Info</button> -->
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   


    <!-- <div>
        <table class="asignar_horarios">
            <thead>
                <tr>
                    <th scope="col">ID Registro</th>
                    <th scope="col">Nombre Docente</th>
                    <th scope="col">Clases</th>
                    <th scope="col">Asignatura</th>
                    <th scope="col">Horas Totales</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $sqlHorarios = ("SELECT * FROM datos_horarios ORDER BY profesor ASC;");
                    $queryTable = $conexion->prepare($sqlHorarios);
                    $queryTable->execute();
                    foreach ($queryTable as $row) {
                ?>
                    <tr>
                        <td><?php echo $row['id_datos_horarios']; ?></td>
                        <td><?php echo $row['profesor']; ?></td>
                        <td><?php echo $row['clases']; ?></td>
                        <td><?php echo $row['asignatura']; ?></td>
                        <td><?php echo $row['total_horas']; ?></td>
                    </tr>
            </tbody>
                <?php
                    }   
                ?>
            

        </table>
    </div> -->
    <script> // Script para detectar fecha actual
        window.onload = function() {
            // Obtener el input de fecha
            const fechaInput = document.getElementById('fecha');

            // Obtener la fecha actual
            const hoy = new Date();

            // Formatear la fecha en YYYY-MM-DD
            const fechaFormateada = hoy.toISOString().split('T')[0];

            // Establecer la fecha actual en el input
            fechaInput.value = fechaFormateada;
        };
    </script> 

    <script> // Script para validar si se ha subido el archivo
        document.getElementById('submit-svg').addEventListener('click', function(event) {
            const fileInput = document.getElementById('btn_subirF19');
            if (!fileInput.files.length) {
                alert('Por favor, selecciona un archivo antes de subir.');
                event.preventDefault();
            } else {
                document.querySelector('.boton_subir').submit();
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

            // Acción para el botón de editar
            $(document).on('click', '.f19-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea EDITAR el F19 del docente: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para editar el alumno
                    window.location.href = 'formularioDestino.php?id_datos_horarios=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el alumno

                }

            });

            // Acción para el botón de eliminar
            $(document).on('click', '.pdf-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea ELIMINAR al alumno con nombre: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para eliminar el alumno
                    alert('Alumno con ID ' + name + ' eliminado');
                } 
            });
        });
    </script>
    
</body>
</html>