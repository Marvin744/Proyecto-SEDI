<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subida de calificaciones</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Incluir CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center bg-primary text-white p-3">Alta de calificaciones</h1>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <form id="uploadForm" action="recibe_excel2.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3 text-center">
                    <input type="file" name="dataCliente" id="file-input" class="form-control"/>
                </div>
                <div class="text-center mt-4">
                    <input type="submit" name="subir" class="btn btn-primary" value="Subir Excel"/>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="container">
</div>
<br>
<div class="row justify-content-center">
    <div class="col-md-7">
        <form id="filterForm" action="" method="GET">
            <div class="mb-3 text-center">
                <select name="asignatura" id="asignatura" class="form-control">
                    <option value="">Selecciona una asignatura</option>
                    <?php 
                    include_once '../bd/config.php';
                    $objeto = new Conexion();
                    $conexion = $objeto->Conectar();

                    $id_usuario = '47';
                    
                    $sqlAsignaturas = "SELECT *
                                       FROM asignatura a
                                       JOIN horarios h ON a.id_asignatura = h.id_asignatura
                                       JOIN docentes d ON h.id_docente = d.id_docente
                                       JOIN usuarios u ON d.id_usuario = u.id_usuario
                                       WHERE u.id_usuario = $id_usuario
                                       ORDER BY nombre_asignatura ASC;";
                    $queryTableAsignaturas = $conexion->prepare($sqlAsignaturas);
                    $queryTableAsignaturas->execute();

                    foreach ($queryTableAsignaturas as $grupo): ?>
                        <option value="<?= $grupo['id_asignatura'] ?>"><?= $grupo['nombre_asignatura'] . " " . $grupo['submodulos'] ?></option>
                    <?php endforeach; ?>
                </select>



                <select name="grupo" id="grupo" class="form-control">
                    <option value="">Selecciona una asignatura</option>
                    <?php 

                    $id_usuario = '47';
                    
                    $sqlGrupo = "SELECT * FROM grupo g 
                                    JOIN horarios h ON g.id_grupo = h.id_grupo 
                                    JOIN docentes d ON h.id_docente = d.id_docente 
                                    JOIN usuarios u ON d.id_usuario = u.id_usuario 
                                    JOIN semestre s ON g.id_semestre = s.id_semestre 
                                    JOIN especialidad e ON g.id_especialidad = e.id_especialidad 
                                    WHERE u.id_usuario = $id_usuario;";
                    $queryTableGrupo = $conexion->prepare($sqlGrupo);
                    $queryTableGrupo->execute();

                    foreach ($queryTableGrupo as $grupo): ?>
                            <option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre_semestre']," ", $grupo['nombre_grupo']," ", $grupo['nombre_especialidad']?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="text-center mt-4">
                <input type="submit" class="btn btn-primary" value="Filtrar"/>
            </div>
        </form>
    </div>
</div>
<br>

    <div class="container">
        <h6 class="text-center"><strong>Lista de Alumnos</strong></h6>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">nombre grupo</th>
                    <th scope="col">Calificación P1</th>
                    <th scope="col">Calificación P2</th>
                    <th scope="col">Calificación P3</th>
                    <th scope="col">Asistencia P1</th>
                    <th scope="col">Asistencia P2</th>
                    <th scope="col">Asistencia P3</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <?php

// Obtener el valor seleccionado en la lista desplegable
$selectedGrupo = isset($_GET['grupo']) ? $_GET['grupo'] : '';
$selectedAsignatura = isset($_GET['asignatura']) ? $_GET['asignatura'] : '';

$sql = "SELECT * FROM calificaciones 
        INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
        JOIN asignatura ON calificaciones.id_asignatura = asignatura.id_asignatura
        JOIN grupo ON alumnos.id_grupo = grupo.id_grupo";

if (!empty($selectedGrupo)) {
    // Agregar la condición para filtrar por grupo si se ha seleccionado uno
    $sql .= " WHERE grupo.id_grupo = :selectedGrupo";
}

if (!empty($selectedAsignatura)) {
    // Agregar la condición para filtrar por asignatura si se ha seleccionado una
    if (!empty($selectedGrupo)) {
        $sql .= " AND asignatura.id_asignatura = :selectedAsignatura";
    } else {
        $sql .= " WHERE asignatura.id_asignatura = :selectedAsignatura";
    }
}

$sql .= " ORDER BY apellido_paterno ASC";

$query = $conexion->prepare($sql);

// Vincular los valores seleccionados si corresponden
if (!empty($selectedGrupo)) {
    $query->bindParam(':selectedGrupo', $selectedGrupo, PDO::PARAM_INT);
}
if (!empty($selectedAsignatura)) {
    $query->bindParam(':selectedAsignatura', $selectedAsignatura, PDO::PARAM_INT);
}

$query->execute();
$resultados = $query->fetchAll(PDO::FETCH_ASSOC);
?>

       
       <tbody>
    <?php
    $i = 1;
    foreach ($resultados as $resultado) {
        ?>
        <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><?php echo htmlspecialchars($resultado['nombre_alumno']); ?></td>
            <td><?php echo htmlspecialchars($resultado['apellido_paterno']); ?></td>
            <td><?php echo htmlspecialchars($resultado['apellido_materno']); ?></td>
            <td><?php echo htmlspecialchars($resultado['nombre_grupo']); ?></td>
            <td><?php echo htmlspecialchars($resultado['calificacion_parcial1']); ?></td>
            <td><?php echo htmlspecialchars($resultado['calificacion_parcial2']); ?></td>
            <td><?php echo htmlspecialchars($resultado['calificacion_parcial3']); ?></td>
            <td><?php echo htmlspecialchars($resultado['asistencia_parcial1']); ?></td>
            <td><?php echo htmlspecialchars($resultado['asitencia_parcial2']); ?></td>
            <td><?php echo htmlspecialchars($resultado['asistencia_parcial3']); ?></td>
            <td><a href="editar.php?id_alumno=<?php echo $resultado['id_alumno']?>" class="btn btn-warning">Modificar</a></td>
        </tr>
        <?php
    }
    ?>
</tbody>
        </table>
    </div>
    <!-- Incluir SweetAlert2 para alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Incluir JS personalizado -->
    <script src="JS/sweet_error.js"></script>
</body>
</html>



<!-- <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subida de calificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center" style="background-color: blue; color: white">Alta de calificaciones</h1>
    </div>
    <br>
    <div class="row">
        <div class="col-md-7">
            <form id="uploadForm" action="recibe_excel2.php" method="POST" enctype="multipart/form-data">
                <div class="file-input text-center">
                    <input type="file" name="dataCliente" id="file-input" class="file-input__input"/>
                    <label class="file-input__label" for="file-input">
                        <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
                        <span>Elegir Archivo Excel</span>
                    </label>
                </div>
                <div class="text-center mt-5">
                    <input type="submit" name="subir" class="btn btn-enviar" value="Subir Excel"/>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <h6 class="text-center"><strong>Lista de Alumnos</strong></h6>
    </div>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Alumno</th>
                    <th scope="col"> </th>
                    <th scope="col"> </th>
                    <th scope="col">Calificación P1</th>
                    <th scope="col">Calificación P2</th>
                    <th scope="col">Calificación P3</th>
                    <th scope="col">Asistencia P1</th>
                    <th scope="col">Asistencia P2</th>
                    <th scope="col">Asistencia P3</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require('config.php');
                $sql = $con->query("SELECT * FROM calificaciones INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno ORDER BY apellido_paterno asc");
                $i = 1;
                while ($resultado = $sql->fetch_assoc()) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $i++; ?></th>
                        <td><?php echo $resultado['nombre_alumno']; ?></td>
                        <td><?php echo $resultado['apellido_paterno']; ?></td>
                        <td><?php echo $resultado['apellido_materno']; ?></td>
                        <td><?php echo $resultado['calificacion_parcial1']; ?></td>
                        <td><?php echo $resultado['calificacion_parcial2']; ?></td>
                        <td><?php echo $resultado['calificacion_parcial3']; ?></td>
                        <td><?php echo $resultado['asistencia_parcial1']; ?></td>
                        <td><?php echo $resultado['asistencia_parcial2']; ?></td>
                        <td><?php echo $resultado['asistencia_parcial3']; ?></td>
                        <td><a href="editar.php?id_alumno=<?php echo $resultado['id_alumno']?>" class="btn btn-warning">Modificar</a></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="JS/sweet_error.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html> -->
