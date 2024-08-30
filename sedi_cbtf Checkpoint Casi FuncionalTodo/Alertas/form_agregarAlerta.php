<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";
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
    
    <!-- Las siguientes dos lineas son el link a normalize -->
    <link rel="preload" href="css/styles.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Las siguientes tres lineas son el link a la fuente de texto de google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Link al CSS -->
    <link rel="preload" href="styles/framework.css" as="style"> <!-- link genera un vinculo
                                                            rel indica que relacion tiene con el archivo actual (preload es para que precargue primero el css y luego la pagina para optimizar la carga de la pagina)
                                                            as indica que cargue como una hoja de estilos  -->
    <link rel="stylesheet" href="styles/framework.css"> <!-- link genera un vinculo
                                                    rel stylesheet indica que es una hoja de estilos del documento actual
                                                    href indica la ubicacion del archivo css-->
</head>
<body>
    <!-- <div class="centrar">
        <nav class="navbar">
            <a href="#" class="logo">
                <img src="img/LogoCBTF2_minimalista.png" alt="Logo">
                <span>SEDI</span>
            </a>



            <ul class="nav-links">
                <li><a href="#">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div> -->

    <div class="container">
        <h1 class="alinear-centro">Alumnos Registrados</h1>
        <p class="alinear-izquierda">A continuación se presentan todos los alumnos registrados en el plantel. Puede modificar sus datos en el apartado de "Acciones" de la tabla.
            <br><br>De igual forma puede elegir los datos a mostrar en la tabla y filtrar por cualquier dato del alumno.
        </p>
    </div>
    
    <br>

    <div>
        <br>
        <table class="display" class="table-style-1" id="tablaAlertas" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Nombre/s</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    $sqlAlumnos = "SELECT * FROM alumnos 
                                        JOIN grupo ON alumnos.id_grupo = grupo.id_grupo
                                        JOIN semestre ON grupo.id_semestre = semestre.id_semestre
                                        ORDER BY apellido_paterno ASC;";
                    $queryTableAlumnos = $conexion->prepare($sqlAlumnos);
                    $queryTableAlumnos->execute();
                    
                    foreach ($queryTableAlumnos as $row) {
                        $nombreCompleto = htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>
                                <td class='alinear-izquierda'>{$row['matricula']}</td>
                                <td class='alinear-derecha'>{$row['numero_semestre']}</td>
                                <td class='alinear-izquierda'>{$row['nombre_grupo']}</td>
                                <td class='alinear-centro'>{$row['apellido_paterno']}</td>
                                <td class='alinear-centro'>{$row['apellido_materno']}</td>
                                <td class='alinear-centro'>{$row['nombre_alumno']}</td>
                                <td>
                                        <a class='edit-btn' id='modalagregar' onclick='agregarAlerta({$row['id_alumno']})'  data-toggle='modal'>Agregar Alerta</a>
                                    <!-- <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id_alumno']}'  data-name='{$nombreCompleto}'>Info</button> -->
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   
    <div id="divmodal"></div>

    <script>
function agregarAlerta(id){
    var ruta = 'modal_agregar.php?alumno='+id;
    $.get(ruta,function(data){
        $('#divmodal').html(data);
        $('#ModalAgregar').modal('show');
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
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"?>