
<?php 
	include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    require_once "../vistas/encabezado.php";
    require_once '../General_Actions/validar_sesion.php';

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Psicologo_y_docente', 'Administrativo', 'Administrativo_y_docente','Administrativo_Docente', 'Administrativo_Jefe', 'Directivo', 'Directivo_y_docente']);
 ?>

<!DOCTYPE html>
<html>
    <form method="POST">
    <head>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <title>Alertas</title>

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

    .estado-rojo {
        background-color: #ff4c4c; /* Rojo */
        color: white;
    }

    .estado-amarillo {
        background-color: #ffeb3b; /* Amarillo */
        color: black;
    }

    .estado-verde {
        background-color: #4caf50; /* Verde */
        color: white;
    }

    .estado-azul {
        background-color: #2196f3; /* Azul */
        color: white;
    }

    .estado-rosa {
        background-color: #e91e63; /* Rosa */
        color: white;
    }
</style>
    </style>
    </head>
    
    <body>
        <div>
            <table class="display table-style-1" id="tablaAlertas" style="width:100%">
                <thead class="alinear-centro">
                    <tr>
                        <th scope="col">ID del Reporte</th>
                        <th scope="col">Fecha del Reporte</th>
                        <th scope="col">Tipo de Alerta</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Situación</th>
                        <th scope="col">Quien Reporta</th>
                        <th scope="col">Alumno Reportado</th>
                        <th scope="col">Semestre</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Especialidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Conexión a la base de datos
                        $sqlAlertas = "SELECT alertas.id_alerta,
                                            alertas.fecha_alerta,
                                            alertas.tipo_alerta,
                                            alertas.situacion,
                                            alertas.persona_reporta,
                                            alumnos.apellido_paterno,
                                            alumnos.apellido_materno,
                                            alumnos.nombre_alumno,
                                            semestre.numero_semestre,
                                            grupo.nombre_grupo,
                                            especialidad.nombre_especialidad
                                        FROM alertas
                                        INNER JOIN alumnos ON alertas.id_alumno = alumnos.id_alumno
                                        INNER JOIN grupo ON alumnos.id_grupo = grupo.id_grupo
                                        INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
                                        INNER JOIN semestre ON grupo.id_semestre = semestre.id_semestre
                                        WHERE alumnos.status = 'INSCRITO'
                                        ORDER BY alertas.id_alerta DESC;";
                        $queryTableAlertas = $conexion->prepare($sqlAlertas);
                        $queryTableAlertas->execute();
                        
                        foreach ($queryTableAlertas as $row) {
                            $nombreCompleto = htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');
                            $claseEstado = '';
                            switch ($row['situacion']) {
                                case 'NO ATENDIDA':
                                    $claseEstado = 'estado-rojo';
                                    break;
                                case 'SEGUIMIENTO':
                                    $claseEstado = 'estado-amarillo';
                                    break;
                                case 'ATENDIDA':
                                    $claseEstado = 'estado-verde';
                                    break;
                                case 'CUMPLIÓ CON SU HSS':
                                    $claseEstado = 'estado-azul';
                                    break;
                                case 'NO APLICA SANCIÓN':
                                    $claseEstado = 'estado-rosa';
                                    break;
                            }
                            echo "<tr>
                                    <td class='alinear-centro'>{$row['id_alerta']}</td>
                                    <td class='alinear-derecha'>{$row['fecha_alerta']}</td>
                                    <td class='alinear-izquierda'>{$row['tipo_alerta']}</td>
                                    <td class='alinear-izquierda $claseEstado'></td>
                                    <td class='alinear-centro'>{$row['situacion']}</td>
                                    <td class='alinear-centro'>{$row['persona_reporta']}</td>
                                    <td class='alinear-centro'>{$nombreCompleto}</td>
                                    <td class='alinear-izquierda'>{$row['numero_semestre']}</td>
                                    <td class='alinear-centro'>{$row['nombre_grupo']}</td>
                                    <td class='alinear-izquierda'>{$row['nombre_especialidad']}</td>
                                    <td class='alinear-centro'>
                                        <a class='edit-btn' id='modaleditar' onclick='editarDetalles({$row['id_alerta']})'  data-toggle='modal'>Editar</a>
                                        <a class='edit-btn' id='modal' onclick='mostrarDetalles({$row['id_alerta']})' data-toggle='modal'>Expandir</a>
                                    </td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>  

        <div id="divmodal"></div>

<script>
function mostrarDetalles(id){
    var ruta = 'modal_alerta_info.php?persona='+id;
    $.get(ruta,function(data){
        $('#divmodal').html(data);
        $('#ModalMostrar').modal('show');
    });
}

function editarDetalles(id){
    var ruta = 'modal_alerta_info.php?persona='+id;
    $.get(ruta,function(data){
        $('#divmodal').html(data);
        $('#ModalEditar').modal('show');
    });
}



</script>

        <script>
            $(document).ready(function() {
                // Inicializar DataTables con opciones personalizadas
                var table = $('#tablaAlertas').DataTable({
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

            });
        </script>
    </body>
</form>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>