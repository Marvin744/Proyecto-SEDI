<?php require_once "../vistas/encabezado.php"?>
<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_Jefe']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignacción de grupos</title>

    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />
    <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script> -->

    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <!-- Las siguientes dos lineas son el link a normalize -->
    <link rel="preload" href="css/styles.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Las siguientes tres lineas son el link a la fuente de texto de google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Link al CSS -->
    <link rel="preload" href="styles/framework.css" as="style">
    <!-- link genera un vinculo
                                                            rel indica que relacion tiene con el archivo actual (preload es para que precargue primero el css y luego la pagina para optimizar la carga de la pagina)
                                                            as indica que cargue como una hoja de estilos  -->
    <link rel="stylesheet" href="styles/framework.css">
    <!-- link genera un vinculo
                                                    rel stylesheet indica que es una hoja de estilos del documento actual
                                                    href indica la ubicacion del archivo css-->
</head>

<body>

    <div class="container">
        <h1 class="alinear-centro">Alumnos Registrados</h1>
    </div>

    <br>


        <form method="post" action="update_grupo.php">
        <div>
            <label for="nuevo_grupo">Seleccionar Grupo:</label>
            <select name="nuevo_grupo" id="nuevo_grupo">
                <?php 
                            $sqlGrupo = "SELECT g.id_grupo,
                                            g.nombre_grupo,
                                            s.id_semestre,
                                            s.numero_semestre,
                                            e.id_especialidad,
                                            e.nombre_especialidad,
                                            t.tipo_programa
                                    FROM   
                                            grupo g
                                    JOIN
                                            semestre s ON g.id_semestre = s.id_semestre
                                    JOIN
                                            especialidad e ON g.id_especialidad = e.id_especialidad
                                    JOIN
                                            tipo_programa t ON e.id_tipo_programa = t.id_tipo_programa
                                    WHERE        
                                            g.id_semestre = '5' OR g.id_semestre = '6';";
                            $queryTableGrupo = $conexion->prepare($sqlGrupo);
                            $queryTableGrupo->execute();

                        foreach ($queryTableGrupo as $grupo): ?>
                <option value="<?= $grupo['id_grupo'] ?>">
                    <?= $grupo['numero_semestre']," ", $grupo['nombre_grupo']," ", $grupo['nombre_especialidad'],", Programa: ", $grupo['tipo_programa']?>
                </option>
                <?php endforeach; ?>
            </select>
            <div id="conteoGenero">
                <!-- Aquí se mostrará el conteo de hombres y mujeres -->
                Seleccione un grupo para ver el conteo de hombres y mujeres.
            </div>
            <label for="nuevo_grupo">Filtrar resultados:</label>
            <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Busqueda">


            <label for="nuevo_grupo">Filtrar por Grupo:</label>
            <select class="form-control" id="grupos" name="grupos">
                <option value=""></option>
                <option value="X">X</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
            </select>
            <br>
            <button type="button" id="filtro" class="enlace-azul-grande2">Filtrar</button>
    </div>

    <div>
        <br>
        <table id="tablaAlumnos">
            <thead>
                <tr>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Status</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Nombre/s</th>
                    <th scope="col">Género</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                                        semestre s ON g.id_semestre = s.id_semestre
                                    JOIN
                                        especialidad e ON g.id_especialidad = e.id_especialidad
                                    WHERE (g.id_semestre = '5' OR g.id_semestre = '6') AND a.status = 'INSCRITO' 
                                    ORDER BY apellido_paterno ASC;";
                    $queryTableAlumnos = $conexion->prepare($sqlAlumnos);
                    $queryTableAlumnos->execute();
                    
                    foreach ($queryTableAlumnos as $row) {
                        $nombreCompleto = htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');
?>
                <tr>
                    <td class='alinear-izquierda'><?= $row['matricula'] ?></td>
                    <td class='alinear-centro'><?= $row['status'] ?></td>
                    <td class='alinear-derecha'><?= $row['numero_semestre'] ?></td>
                    <td class='alinear-izquierda'><?= $row['nombre_grupo'] ?></td>
                    <td class='alinear-izquierda'><?= $row['nombre_especialidad'] ?></td>
                    <td class='alinear-centro'><?= $row['apellido_paterno'] ?></td>
                    <td class='alinear-centro'><?= $row['apellido_materno'] ?></td>
                    <td class='alinear-centro'><?= $row['nombre_alumno'] ?></td>
                    <td class='alinear-centro'><?= $row['genero'] ?></td>
                    <td>
                        <input type='checkbox' name='alumnos[]' value=<?= $row['id_alumno'] ?>>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <input type="hidden" name="formulario" value="formulario3">
        <button type="submit" class="enlace-verde-grande2"
            onclick="return confirm('¿Estás seguro de que deseas asignar estos alumnos a este grupo?');">Actualizar
            Grupo</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        // Evento para capturar el cambio en el select de grupo
        $('#nuevo_grupo').on('change', function() {
            var id_grupo = $(this).val();
            if (id_grupo) {
                $.ajax({
                    url: 'contar_genero.php', // Archivo PHP que ejecutará la consulta SQL
                    type: 'POST',
                    data: {
                        id_grupo: id_grupo
                    },
                    success: function(data) {
                        $('#conteoGenero').html(
                            data
                            ); // Actualiza el contenido de conteoGenero con el resultado
                    }
                });
            } else {
                $('#conteoGenero').html('Seleccione un grupo para ver el conteo de hombres y mujeres.');
            }
        })
    });

    $(document).ready(function() {
        $("#filtro").on("click", function(e) {
            e.preventDefault();
            loaderF(true); // Muestra el loader

            var buscar = $('input[name=buscar]').val().trim();
            var grupos = $('select[name=grupos]').val();

            if (buscar || grupos !== '') {
                $.post("filtro4.php", {
                    buscar: buscar,
                    grupos: grupos
                }, function(data) {
                    console.log(data); // Verifica qué datos se están recibiendo
                    $("#tablaAlumnos tbody").html(data);
                    loaderF(false); // Oculta el loader
                });
            } else {
                $("#tablaAlumnos tbody").html(
                    '<tr><td colspan="12" style="color:red; font-weight:bold;">Debe seleccionar un filtro válido</td></tr>'
                );
                loaderF(false); // Oculta el loader
            }
        });

        // Función para mostrar u ocultar el loader
        function loaderF(statusLoader) {
            if (statusLoader) {
                $("#loaderFiltro").show(); // Asegúrate de tener un elemento con id "loaderFiltro"
            } else {
                $("#loaderFiltro").hide();
            }
        }
    });
    </script>

    <script>
        $(document).ready( function () {
            $('#tablaAlumnos').DataTable({
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
<?php require_once "../vistas/pie_pagina.php"?>