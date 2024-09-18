<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_y_docente', 'Administrativo_Jefe']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>

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

        /* Estilo del botón */
        .dataTable .cali-btn {
            background-color: #AF2800; /* Color de fondo */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            padding: .8rem 0.8rem; /* Espaciado interno */
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

        .enlace-verde-grande {
            font-size: 12px; /* Aumenta el tamaño de la fuente */
            color: #ffffff; /* Cambia el color del texto a blanco */
            background-color: #28a745; /* Cambia el fondo a verde */
            padding: 5px 12px; /* Añade un poco de relleno para hacerlo más grande */
            border-radius: 10px; /* Añade bordes redondeados */
            text-decoration: none; /* Quita el subrayado del enlace */
            display: inline-block; /* Asegura que los márgenes y el relleno afecten al tamaño del enlace */
            font-weight: bold; /* Hace que el texto esté en negritas */
            transition: background-color 0.3s ease; /* Añade una transición suave al cambiar el fondo */
        }

        .enlace-verde-grande:hover {
            background-color: #218838; /* Cambia el color del fondo al pasar el cursor */
        }

        .enlace-azul-grande {
            font-size: 12px; /* Aumenta el tamaño de la fuente */
            color: #ffffff; /* Cambia el color del texto a blanco */
            background-color: #0015d3; /* Cambia el fondo a verde */
            padding: 5px 12px; /* Añade un poco de relleno para hacerlo más grande */
            border-radius: 10px; /* Añade bordes redondeados */
            text-decoration: none; /* Quita el subrayado del enlace */
            display: inline-block; /* Asegura que los márgenes y el relleno afecten al tamaño del enlace */
            font-weight: bold; /* Hace que el texto esté en negritas */
            transition: background-color 0.3s ease; /* Añade una transición suave al cambiar el fondo */
        }

        .enlace-azul-grande:hover {
            background-color: #218838; /* Cambia el color del fondo al pasar el cursor */
        }

        .enlace-verde-grande2 {
            font-size: 20px; /* Aumenta el tamaño de la fuente */
            color: #ffffff; /* Cambia el color del texto a blanco */
            background-color: #28a745; /* Cambia el fondo a verde */
            padding: 10px 20px; /* Añade un poco de relleno para hacerlo más grande */
            border-radius: 10px; /* Añade bordes redondeados */
            text-decoration: none; /* Quita el subrayado del enlace */
            display: inline-block; /* Asegura que los márgenes y el relleno afecten al tamaño del enlace */
            font-weight: bold; /* Hace que el texto esté en negritas */
            transition: background-color 0.3s ease; /* Añade una transición suave al cambiar el fondo */
        }

        .enlace-verde-grande:hover {
            background-color: #218838; /* Cambia el color del fondo al pasar el cursor */
            cursor: pointer;
        }

        .enlace-azul-grande2 {
            font-size: 20px; /* Aumenta el tamaño de la fuente */
            color: #ffffff; /* Cambia el color del texto a blanco */
            background-color: #0015d3; /* Cambia el fondo a verde */
            padding: 10px 20px; /* Añade un poco de relleno para hacerlo más grande */
            border-radius: 10px; /* Añade bordes redondeados */
            text-decoration: none; /* Quita el subrayado del enlace */
            display: inline-block; /* Asegura que los márgenes y el relleno afecten al tamaño del enlace */
            font-weight: bold; /* Hace que el texto esté en negritas */
            transition: background-color 0.3s ease; /* Añade una transición suave al cambiar el fondo */
        }

        .enlace-azul-grande:hover {
            background-color: #218838; /* Cambia el color del fondo al pasar el cursor */
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
    
    <div class="container">
        <h1>Alumnos del Instituto</h1>
    </div>
    <div>
        <table class="display" class="table-style-1" id="tablaAlumnos" style="width:100%" >
            <thead class="alinear-centro">
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Nombre/s</th>
                    <th scope="col">Matrícula</th>
                    <th scope="col">CURP</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    $sqlAlumnos = "SELECT a.id_alumno,
                                        a.matricula,
                                        a.status,
                                        a.apellido_paterno,
                                        a.apellido_materno,
                                        a.nombre_alumno,
                                        a.CURP,
                                        a.telefono,
                                        g.nombre_grupo,
                                        s.numero_semestre,
                                        a.genero,
                                        e.nombre_especialidad
                                    FROM
                                        alumnos a
                                    JOIN
                                        grupo g ON a.id_grupo = g.id_grupo
                                    JOIN
                                        semestre s ON a.id_semestre = s.id_semestre
                                    JOIN
                                        especialidad e ON g.id_especialidad = e.id_especialidad
                                    WHERE a.nombre_alumno != '1'
                                    ORDER BY apellido_paterno ASC;";
                    $queryTableAlumnos = $conexion->prepare($sqlAlumnos);
                    $queryTableAlumnos->execute();
                    
                    foreach ($queryTableAlumnos as $row) {
                        $nombreCompleto = htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>

                                <td class='alinear-centro'>{$row['status']}</td>
                                <td class='alinear-derecha'>{$row['numero_semestre']}</td>
                                <td class='alinear-izquierda'>{$row['nombre_grupo']}</td>
                                <td class='alinear-centro'>{$row['nombre_especialidad']}</td>
                                <td class='alinear-centro'>{$row['apellido_paterno']}</td>
                                <td class='alinear-centro'>{$row['apellido_materno']}</td>
                                <td class='alinear-centro'>{$row['nombre_alumno']}</td>
                                <td class='alinear-izquierda'>{$row['matricula']}</td>
                                <td class='alinear-centro'>{$row['CURP']}</td>
                                <td class='alinear-izquierda'>{$row['telefono']}</td>
                                <td class='alinear-centro'>
                                    <button class='edit-btn' data-id='{$row['id_alumno']}' data-name='{$nombreCompleto}'>Editar</button>
                                    <button class='cali-btn' data-id='{$row['id_alumno']}' data-name='{$nombreCompleto}'>Calificación</button>
                                    <a class='enlace-verde-grande' id='modal' onclick='mostrarUsuario({$row['id_alumno']})' data-toggle='modal'>Usuario</a>

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
                    window.location.href = 'form_modificar_alumno.php?id_alumno=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el alumno

                }

            });

            // Acción para el botón de editar
            $(document).on('click', '.cali-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (confirm('¿Está seguro de que desea ver las CALIFICACIONES del alumno con nombre: ' + name + '?')) {
                    // Aquí puedes agregar la lógica para editar el alumno
                    window.location.href = '../Calificaciones/form_calificacionAlumno.php?id_alumno=' + id; // Aquí puedes redirigir a otra página o abrir un modal para editar el alumno

                }
            });
        });
    </script>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>