<?php
    // Conexion a la base de datos
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    
    require_once '../General_Actions/validar_sesion.php';
    require_once '../vistas/encabezado.php';

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Docente', 'Psicologo_y_docente', 'Administrativo_y_docente', 'Directivo_y_docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>

</head>
<body>
    <div>
        <h1 style="font-weight: bold; text-align: center;">Asignaturas Disponibles de la Institución</h1><br>
        <p>Puede buscar fácilmente la asignatura deseada desde la barra de búsqueda o las opciones de navegación. <span><strong>Copie el ID</strong></span> de la asignatura y <span><strong> péguela en su archivo Excel</strong></span> con la estructura <span><strong>"(ID_asignatura) Nombre_de_la_materia".</strong></span> </p>
        <br>
        <p style="font-size: 1.8rem">El <span><strong>"Nombre_de_la_materia"</strong></span> puede ser de edición libre para que pueda identificar la materia que esté dando, pero el <span><strong>"ID_asignatura"</strong></span> es importante que siga esa misma estructura.</p>
        <p style="font-size: 1.8rem">Ejemplo: <span><strong>(219) Cálculo Integral</strong></span>.</p>
    </div>

    <div>
        <table class="display" id="tablaAsignaturas">
            <thead>
                <tr>
                    <th>ID Asignatura</th>
                    <th>Nombre Asignatura</th>
                    <th>Submódulos</th>
                    <th>Especialidad</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    // Consulta a la base de datos
                    $sqlAsignaturas = "SELECT * FROM asignatura a
                                        JOIN especialidad e ON a.id_especialidad = e.id_especialidad
                                        ";
                    $queryAsignaturas = $conexion->prepare($sqlAsignaturas);
                    $queryAsignaturas->execute();

                    foreach ($queryAsignaturas as $row) {
                        echo " <tr>
                                    <td> {$row['id_asignatura']} </td>    
                                    <td> {$row['nombre_asignatura']} </td>    
                                    <td> {$row['submodulos']} </td>    
                                    <td> {$row['nombre_especialidad']} </td>    
                                </tr> ";

                    }
                ?>
            </tbody>

            <tfoot>

            </tfoot>
        </table>
    </div>
    <script>
        $(document).ready( function () {
            $('#tablaAsignaturas').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 20, 50],
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
        } );
    </script>
</body>
</html>

<?php include '../vistas/pie_pagina.php'; ?>