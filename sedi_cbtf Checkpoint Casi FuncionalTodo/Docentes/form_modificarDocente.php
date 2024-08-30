<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Consultar los registros existentes en la tabla `docentes`
    $sql = "SELECT id_docente, nombre_docente, apellido_paterno, apellido_materno, RFC, genero, email FROM docentes";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Info Personal</title>


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

        /* ALINEACIONES */

        .alinear-derecha {
            text-align: right;
        }

        .alinear-centro {
            text-align: center;
        }

        .alinear-izquierda {
            text-align: left;
        }

        .font-2 {
            font-size: 1.4rem;
        }

        /* Cambiar el tamaño de la fuente para todo el DataTable */
        .dataTable {
            font-size: 1.6rem; /* Ajusta el tamaño de la fuente aquí */
        }

        /* Cambiar el tamaño de la fuente para las celdas del encabezado */
        .dataTable thead th {
            font-size: 1.6rem; /* Ajusta el tamaño de la fuente del encabezado aquí */
        }

        /* Cambiar el tamaño de la fuente para las celdas del cuerpo */
        .dataTable tbody td {
            font-size: 1.8rem; /* Ajusta el tamaño de la fuente del cuerpo aquí */
        }

        /* Estilo del botón */
        .dataTable .edit-btn {
            background-color: #001fa8; /* Color de fondo */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            padding: .8rem 1.6rem; /* Espaciado interno */
            text-align: center; /* Alineación del texto */
            text-decoration: none; /* Sin subrayado */
            display: inline-block; /* Display en línea */
            font-size: 1.2rem; /* Tamaño de fuente */
            margin: 2px 1px; /* Margen */
            cursor: pointer; /* Cursor al pasar por encima */
            border-radius: 1rem; /* Bordes redondeados */
            font-weight: bold;
        }

        /* Estilo del botón al pasar el ratón por encima */
        .dataTable .btn-custom:hover {
            background-color: #45a049; /* Color de fondo al pasar el ratón */
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

    </style>

</head>
<body>
    <div>
        <h1>Modificar Información del Personal</h1>
        <p>Identifique el personal a modificar y dé clic en "Editar" ubicada a la derecha de la tabla.</p>
    </div>

    <div>
        <table class="display" class="table-style-1" cellpadding="10" cellspacing="0" id="tablaAlumnos"> 
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>RFC</th>
                    <th>Género</th>
                    <th>Email</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente): ?>
                    <tr>
                        <td><?php echo $docente['id_docente']; ?></td>
                        <td><?php echo $docente['nombre_docente']; ?></td>
                        <td><?php echo $docente['apellido_paterno']; ?></td>
                        <td><?php echo $docente['apellido_materno']; ?></td>
                        <td><?php echo $docente['RFC']; ?></td>
                        <td><?php echo $docente['genero']; ?></td>
                        <td><?php echo $docente['email']; ?></td>
                        <td>
                            <!-- Enlace a la página de edición con el ID del docente -->
                            <a href="form_editar_docente.php?id=<?php echo $docente['id_docente']; ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </body>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables con opciones personalizadas
            var table = $('#tablaAlumnos').DataTable({
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
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea EDITAR al alumno con nombre: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para editar el alumno
                    window.location.href = 'formularioDestino.php?id_alumno=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el alumno

                }

            });

            // Acción para el botón de eliminar
            $(document).on('click', '.info-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea ELIMINAR al alumno con nombre: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para eliminar el alumno
                    alert('Alumno de nombre ' + name + ' eliminado');
                } 
            });
        });
    </script>

</html>

<?php require_once "../vistas/pie_pagina.php"; ?>