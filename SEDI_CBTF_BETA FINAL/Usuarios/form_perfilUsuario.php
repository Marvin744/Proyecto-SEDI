<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Docente', 'Administrativo_Jefe', 'Directivo', 'Directivo_y_docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios del Instituto</title>

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

    </style>
</head>
<body>
    
    <div class="container">
        <h1>Usuarios del Instituto</h1>
    </div>
    <div>
        <table class="display" class="table-style-1" id="tablaUsuarios" style="width:100%" >
            <thead class="alinear-centro">
                <tr>
                    <th>ID Usuario</th>
                    <th>Perfil</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Consulta SQL para obtener los datos de los usuarios
                    $sqlUsuarios = "SELECT d.id_docente,
                                           d.apellido_paterno,
                                           d.apellido_materno,
                                           d.nombre_docente,
                                           d.email,
                                           u.id_usuario,
                                           u.perfil
                                    FROM docentes d
                                    JOIN usuarios u ON d.id_usuario = u.id_usuario
                                    WHERE u.perfil NOT IN ('Alumno', 'Admin')
                                    ORDER BY d.apellido_paterno ASC;";
                    $queryTableUsuarios = $conexion->prepare($sqlUsuarios);
                    $queryTableUsuarios->execute();
                    
                    foreach ($queryTableUsuarios as $row) {
                        $nombreCompleto = htmlspecialchars($row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ' ' . $row['nombre_docente'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>
                                <td class='alinear-centro'>{$row['id_usuario']}</td>
                                <td class='alinear-centro'>{$row['perfil']}</td>
                                <td class='alinear-centro'>{$nombreCompleto}</td>
                                <td class='alinear-centro'>{$row['email']}</td>
                                <td class='alinear-centro'>
                                    <button class='edit-btn' data-id='{$row['id_usuario']}' data-name='{$nombreCompleto}'>Cambiar perfil</button>
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   

    <div id="divmodal"></div>

<script>
function mostrarUsuario(id){
    var ruta = 'modal_usuario.php?persona='+id;
    $.get(ruta,function(data){
        $('#divmodal').html(data);
        $('#ModalUsuario').modal('show');
    });
}


</script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables con opciones personalizadas
            var table = $('#tablaUsuarios').DataTable({
                "pageLength": 10, // Número de filas por página
                "lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
                "pagingType": "full_numbers", // Estilo de paginación: "simple", "simple_numbers", "full", "full_numbers"
                "language": {   
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

                if (confirm('¿Está seguro de que desea EDITAR al usuario con nombre: ' + name + '?')) {
                    window.location.href = 'form_modificar_perfil.php?id_usuario=' + id; // Redirigir a la página de edición
                }
            });

        });
    </script>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>