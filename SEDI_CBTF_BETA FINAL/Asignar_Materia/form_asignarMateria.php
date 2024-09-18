<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_y_docente', 'Administrativo_Docente', 'Administrativo_Jefe']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de grupos</title>

    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
    <!-- Normalización y fuentes -->
    <link rel="preload" href="css/styles.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link al CSS -->
    <link rel="preload" href="styles/framework.css" as="style">
    <link rel="stylesheet" href="styles/framework.css">
</head>
<body>

<div class="container">
    <h1 class="alinear-centro">Alumnos Registrados</h1>
</div>

<form action="Insert_materia.php" method="POST">
    <div>
        <label for="filtro_semestre">Seleccionar Semestre:</label>
        <select class="form-control" name="filtro_semestre" id="filtro_semestre">
            <?php 
                $sqlSemestre = "SELECT s.id_semestre, s.numero_semestre FROM semestre s";
                $queryTableSemestre = $conexion->prepare($sqlSemestre);
                $queryTableSemestre->execute();

                foreach ($queryTableSemestre as $semestre): ?>
                    <option value="<?= $semestre['id_semestre'] ?>"><?= $semestre['numero_semestre']?></option>
            <?php endforeach; ?>
        </select>
    
        <label for="filtro_especialidad">Seleccionar Especialidad:</label>
        <select class="form-control" name="filtro_especialidad" id="filtro_especialidad">
            <?php 
                $sqlEspecialidad = "SELECT MIN(e.id_especialidad) as id_especialidad, e.nombre_especialidad
                                    FROM especialidad e
                                    GROUP BY e.nombre_especialidad
                                    ORDER BY e.nombre_especialidad;";
                $queryTableEspecialidad = $conexion->prepare($sqlEspecialidad);
                $queryTableEspecialidad->execute();

                foreach ($queryTableEspecialidad as $especialidad): ?>
                    <option value="<?= $especialidad['nombre_especialidad'] ?>"><?= $especialidad['nombre_especialidad']?></option>
            <?php endforeach; ?>
        </select>

        <label for="filtro_programa">Seleccionar el Tipo de Programa:</label>      
        <select class="form-control" name="programa" id="programa">
            <option value="1">MCCEMS</option>
            <option value="2">Acuerdo 653</option>
        </select>

        <button type="button" id="filtro" class="enlace-azul-grande2">Filtrar</button>

        <div class="form-group">
            <label for="grupo">Seleccionar Grupo:</label>
            <select class="form-control" name="grupo" id="grupo">
                <?php 
                    $sqlGrupo = "SELECT g.id_grupo, g.nombre_grupo as nombre_grupo, s.numero_semestre, e.nombre_especialidad
                                 FROM grupo g
                                 JOIN semestre s ON g.id_semestre = s.id_semestre
                                 JOIN especialidad e ON g.id_especialidad = e.id_especialidad
                                 GROUP BY g.nombre_grupo";
                    $queryTableGrupo = $conexion->prepare($sqlGrupo);
                    $queryTableGrupo->execute();

                    foreach ($queryTableGrupo as $grupo): ?>
                        <option value="<?= $grupo['nombre_grupo'] ?>"><?= $grupo['nombre_grupo'] ?></option>
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
                    <th scope="col">Programa</th>
                    <th scope="col">Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargarán los datos filtrados -->
            </tbody>
        </table>

        <button class="enlace-verde-grande2" type="submit" onclick="return confirm('¿Estás seguro de que deseas asignar estas materias a este grupo?');">Actualizar Grupo</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#filtro").on("click", function(e) { 
        e.preventDefault();
        loaderF(true); 

        var filtro_semestre = $('#filtro_semestre').val();
        var filtro_especialidad = $('#filtro_especialidad').val();
        var programa = $('#programa').val();

        if(filtro_semestre && filtro_especialidad && programa) {
            $.post("filtro1.php", {filtro_semestre: filtro_semestre, filtro_especialidad: filtro_especialidad, programa: programa}, function(data) {
                console.log(data);  // Verificar la respuesta del servidor
                $("#tablaMaterias tbody").html(data);
                loaderF(false); 
            });
        } else {
            $("#tablaMaterias tbody").html('<tr><td colspan="5" style="color:red; font-weight:bold;">Debe seleccionar ambos filtros</td></tr>');
            loaderF(false); 
        }
    });

    function loaderF(statusLoader) {
        if(statusLoader) {
            $("#loaderFiltro").show(); 
        } else {
            $("#loaderFiltro").hide();
        }
    }
});
</script>

</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>