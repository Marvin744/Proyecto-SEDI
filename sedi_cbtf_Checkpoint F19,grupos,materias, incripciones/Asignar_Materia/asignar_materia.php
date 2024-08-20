<?php require_once "../vistas/encabezado.php"?>
<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();


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


<div class="container">
        <h1 class="alinear-centro">Alumnos Registrados</h1>
    </div>
    
    <br>
    <div>
        <form method="post" action="Insert_materia.php">
           
        <label for="filtro_semestre">Seleccionar Semestre:</label>
            <select name="filtro_semestre" id="filtro_semestre">
                <?php 
                    $sqlSemestre = "SELECT s.id_semestre,
                                    s.nombre_semestre
                            FROM
                                    semestre s";
                    $queryTableSemestre = $conexion->prepare($sqlSemestre);
                    $queryTableSemestre->execute();

                    foreach ($queryTableSemestre as $semestre): ?>
                        <option value="<?= $semestre['id_semestre'] ?>"><?= $semestre['nombre_semestre']?></option>
                <?php endforeach; ?>
            </select>
        
            <label for="filtro_especialidad">Seleccionar Especialidad:</label>
            <select name="filtro_especialidad" id="filtro_especialidad">
                <?php 
                    $sqlEspecialidad = "SELECT DISTINCT e.id_especialidad, e.nombre_especialidad
                                        FROM especialidad e
                                        ORDER BY e.nombre_especialidad;";
                    $queryTableEspecialidad = $conexion->prepare($sqlEspecialidad);
                    $queryTableEspecialidad->execute();

                    foreach ($queryTableEspecialidad as $especialidad): ?>
                        <option value="<?= $especialidad['id_especialidad'] ?>"><?= $especialidad['nombre_especialidad'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="button" id="filtro" class="btn btn-dark mb-2">Filtrar</button>
            <div>

            <label for="grupo">Seleccionar Grupo:</label>
                <select name="grupo" id="grupo">
                    <?php 
                        $sqlGrupo = "SELECT g.id_grupo,
                                        g.nombre_grupo,
                                        s.id_semestre,
                                        s.nombre_semestre,
                                        e.id_especialidad,
                                        e.nombre_especialidad
                                FROM   
                                        grupo g
                                JOIN
                                        semestre s ON g.id_semestre = s.id_semestre
                                JOIN
                                        especialidad e ON g.id_especialidad = e.id_especialidad
                                WHERE        
                                        g.id_semestre = '2' OR g.id_semestre = '1';";
                         $queryTableGrupo = $conexion->prepare($sqlGrupo);
                         $queryTableGrupo->execute();

                      foreach ($queryTableGrupo as $grupo): ?>
                            <option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre_semestre']," ", $grupo['nombre_grupo']," ", $grupo['nombre_especialidad']?></option>
                 <?php endforeach; ?>
             </select>
            </div>
            
            <br>
            <table id="tablaMaterias">
                <thead>
                    <tr>
                        <th scope="col">Semestre</th>
                        <th scope="col">Asignatura/Módulo</th>
                        <th scope="col">Submódulo</th>
                        <th scope="col">Especialidad</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se cargarán los datos filtrados -->
                </tbody>
            </table>
            <button type="submit">Actualizar Grupo</button>
        </form>
    </div>



    <!-- <div class="container">
        <h1 class="alinear-centro">Alumnos Registrados</h1>
    </div>
    
    <br>
    <div>

    <form method="post" action="update_grupo.php">
        <label for="grupo">Seleccionar Grupo:</label>
        <select name="filtro_grupo" id="filtro_grupo">
            <?php 
                $sqlGrupo = "SELECT g.id_grupo,
                                    g.nombre_grupo,
                                    s.id_semestre,
                                    s.nombre_semestre,
                                    e.id_especialidad,
                                    e.nombre_especialidad
                            FROM
                                    grupo g
                            JOIN
                                    semestre s ON g.id_semestre = s.id_semestre
                            JOIN
                                    especialidad e ON g.id_especialidad = e.id_especialidad;";
                $queryTableGrupo = $conexion->prepare($sqlGrupo);
                $queryTableGrupo->execute();

            foreach ($queryTableGrupo as $grupo): ?>
                <option value="<?= $grupo['id_semestre'] ?>"><?= $grupo['id_semestre'],"° ", $grupo['nombre_grupo'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="filtro_especialidad" id="filtro_especialidad">
            <?php 
                $sqlGrupo = "SELECT g.id_grupo,
                                    g.nombre_grupo,
                                    s.id_semestre,
                                    s.nombre_semestre,
                                    e.id_especialidad,
                                    e.nombre_especialidad
                            FROM
                                    grupo g
                            JOIN
                                    semestre s ON g.id_semestre = s.id_semestre
                            JOIN
                                    especialidad e ON g.id_especialidad = e.id_especialidad;";
                $queryTableGrupo = $conexion->prepare($sqlGrupo);
                $queryTableGrupo->execute();

            foreach ($queryTableGrupo as $grupo): ?>
                    <option value="<?= $grupo['id_especialidad'] ?>"><?= $grupo['nombre_especialidad'] ?></option>
            <?php endforeach; ?>
        </select>
        <div>
        <span type="button" class="btn btn-dark mb-2" id="filtro">Filtrar</span>
        </div>
    </div>
    <div>
        <br>
        <table id="tablaMaterias">
            <thead>
                <tr>
                    <th scope="col">Semestre</th>
                    <th scope="col">Asignatura/Módulo</th>
                    <th scope="col">Submódulo</th>
                    <th scope="col">Especialidad</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    $sqlAsignaturas = "SELECT a.id_asignatura,
                                        a.nombre_asignatura,
                                        a.submodulos,
                                        e.nombre_especialidad,
                                        s.nombre_semestre
                                    FROM
                                        asignatura a
                                    JOIN
                                        especialidad e ON a.id_especialidad = e.id_especialidad
                                    JOIN
                                        semestre s ON a.id_semestre = s.id_semestre
                                    ORDER BY nombre_asignatura ASC;";
                    $queryTableAsignaturas = $conexion->prepare($sqlAsignaturas);
                    $queryTableAsignaturas->execute();
                    
                    foreach ($queryTableAsignaturas as $row) {
?>
                        <tr>
                                <td class='alinear-izquierda'><?= $row['nombre_semestre'] ?></td>
                                <td class='alinear-izquierda'><?= $row['nombre_asignatura'] ?></td>
                                <td class='alinear-centro'><?= $row['submodulos'] ?></td>
                                <td class='alinear-derecha'><?= $row['nombre_especialidad'] ?></td>
                                <td>
                                    <input type='checkbox' name='asignaturas[]' value=<?= $row['id_asignatura'] ?>>
                                </td>
                              </tr>
                              <?php
                    }
                ?>
            </tbody>
        </table>
        <button type="submit">Actualizar Grupo</button>
    </form>
    </div>    -->

    <script>

$(document).ready(function() {
    // Manejo del evento de clic en el botón de filtro
    $("#filtro").on("click", function(e) { 
        e.preventDefault();
        loaderF(true); // Muestra el loader

        var filtro_semestre = $('#filtro_semestre').val();
        var filtro_especialidad = $('#filtro_especialidad').val();

        if(filtro_semestre && filtro_especialidad) {
            $.post("filtro1.php", {filtro_semestre: filtro_semestre, filtro_especialidad: filtro_especialidad}, function(data) {
                $("#tablaMaterias tbody").html(data);
                loaderF(false); // Oculta el loader
            });
        } else {
            $("#tablaMaterias tbody").html('<tr><td colspan="5" style="color:red; font-weight:bold;">Debe seleccionar ambos filtros</td></tr>');
            loaderF(false); // Oculta el loader
        }
    });

    // Función para mostrar u ocultar el loader
    function loaderF(statusLoader) {
        if(statusLoader) {
            $("#loaderFiltro").show(); // Asegúrate de tener un elemento con id "loaderFiltro"
        } else {
            $("#loaderFiltro").hide();
        }
    }
});




//   $(function() {
//       setTimeout(function(){
//         $('body').addClass('loaded');
//       }, 1000);


// //FILTRANDO REGISTROS
// $("#filtro").on("click", function(e){ 
//   e.preventDefault();
  
//   loaderF(true);

// //  var f_ingreso = $('input[name=fecha_ingreso]').val();
// //  var f_fin = $('input[name=fechaFin]').val();
// //  console.log(f_ingreso + '' + f_fin);

// var filtro_grupo = $('input[name=filtro_grupo]').val();
// var filtro_especialidad = $('input[name=filtro_especialidad]').val()
// console.log(filtro_grupo, filtro_especialidad)


//   if(filtro_grupo !="" && filtro_especialidad !=""){
//     $.post("filtro1.php", {filtro_grupo, filtro_especialidad}, function (data) {
//       $("#tablaMaterias").hide();
//       $(".resultadoFiltro").html(data);
//       loaderF(false);
//     });  
//   }else{
//     $("#loaderFiltro").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
//   }
// } );


// function loaderF(statusLoader){
//     console.log(statusLoader);
//     if(statusLoader){
//       $("#loaderFiltro").show();
//     }else{
//       $("#loaderFiltro").hide();
//     }
//   }
//});
</script>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"?>