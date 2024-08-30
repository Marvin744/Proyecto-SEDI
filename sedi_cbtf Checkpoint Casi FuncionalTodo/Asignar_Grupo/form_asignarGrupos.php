<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'habilitar_p1':
                    $_SESSION['habilitar_p1'] = true;
                    break;
                case 'deshabilitar_p1':
                    $_SESSION['habilitar_p1'] = false;
                    break;
                case 'habilitar_p2':
                    $_SESSION['habilitar_p2'] = true;
                    break;
                case 'deshabilitar_p2':
                    $_SESSION['habilitar_p2'] = false;
                    break;
                case 'habilitar_p3':
                    $_SESSION['habilitar_p3'] = true;
                    break;
                case 'deshabilitar_p3':
                    $_SESSION['habilitar_p3'] = false;
                    break;
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias y Calificaciones</title>
    <style>
        body.paginaexcel-page {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding-top: 8rem;
            margin: 0;
        }

        .paginaexcel-header {
            font-size: 3rem;
            color: #007BFF;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .paginaexcel-button-container {
            display: block;
            width: 100%;
            max-width: 130rem;
            margin: 0 auto;
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 0 1.5rem rgba(0, 0, 0, 0.1);
        }

        .paginaexcel-button-group {
            display: block;
            margin-bottom: 3rem;
        }

        .paginaexcel-button-group h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1.2rem;
        }

        .paginaexcel-button-group a {
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            margin: 1rem;
            display: inline-block;
            font-size: 1.8rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .paginaexcel-button-group a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .paginaexcel-button-group a:active {
            background-color: #003f7f;
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="paginaexcel-page">
    <div>
        <h1 class="paginaexcel-header">Asignación de Grupos</h1>
    </div>

    <div class="paginaexcel-button-container">
        <div class="paginaexcel-button-group">
            <h2>Seleccione el semestre del alumno al que desea asignar el grupo</h2>
            <a href="form_asignar_grupo1.php">Semestre 1 y 2</a>
            <a href="form_asignar_grupo2.php">Semestre 3 y 4</a>
            <a href="form_asignar_grupo3.php">Semestre 5 y 6</a>
        </div>
        <div class="paginaexcel-button-group">
            <h2>Calificaciones y asistencias de los Alumnos por parciales</h2>
            <a href="../Asignar_Materia/form_asignarMateria.php">Asignación de Materias</a>
        </div>
    </div>
        <br>
    <div>
        <form id="filterForm" action="" method="GET">
            <div class="mb-3 text-center">
                <select name="asignatura" id="asignatura" class="form-control">
                    <option value="">Selecciona una asignatura</option>
                    <?php 
                    include_once '../bd/config.php';
                    $objeto = new Conexion();
                    $conexion = $objeto->Conectar();

                    $id_usuario = $_SESSION['id_usuario'];
                    
                    $sqlAsignaturas = "SELECT *
                                       FROM asignatura a
                                       JOIN horarios h ON a.id_asignatura = h.id_asignatura
                                       JOIN docentes d ON h.id_docente = d.id_docente
                                       JOIN usuarios u ON d.id_usuario = u.id_usuario
                                       WHERE u.id_usuario = :id_usuario
                                       ORDER BY nombre_asignatura ASC;";
                    $queryTableAsignaturas = $conexion->prepare($sqlAsignaturas);
                    $queryTableAsignaturas->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $queryTableAsignaturas->execute();

                    foreach ($queryTableAsignaturas as $grupo): ?>
                        <option value="<?= $grupo['id_asignatura'] ?>"><?= $grupo['nombre_asignatura'] . " " . $grupo['submodulos'] ?></option>
                    <?php endforeach; ?>
                </select>



                <select name="grupo" id="grupo" class="form-control">
                    <option value="">Selecciona un grupo</option>
                    <?php 

                        $sqlGrupo = "SELECT * FROM grupo g 
                        JOIN horarios h ON g.id_grupo = h.id_grupo 
                        JOIN docentes d ON h.id_docente = d.id_docente 
                        JOIN usuarios u ON d.id_usuario = u.id_usuario 
                        JOIN semestre s ON g.id_semestre = s.id_semestre 
                        JOIN especialidad e ON g.id_especialidad = e.id_especialidad 
                        WHERE u.id_usuario = :id_usuario;";
                        $queryTableGrupo = $conexion->prepare($sqlGrupo);
                        $queryTableGrupo->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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

    <?php
        // Obtener los valores seleccionados
        $selectedGrupo = isset($_GET['grupo']) ? $_GET['grupo'] : '';
        $selectedAsignatura = isset($_GET['asignatura']) ? $_GET['asignatura'] : '';

        // Mostrar la tabla solo si se ha seleccionado un filtro
        if (!empty($selectedGrupo) || !empty($selectedAsignatura)) {
            // Construir la consulta SQL con las condiciones apropiadas
            $sql = "SELECT * 
                    FROM calificaciones 
                    INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
                    JOIN asignatura ON calificaciones.id_asignatura = asignatura.id_asignatura
                    JOIN asistencias ON calificaciones.id_asistencia = asistencias.id_asistencia
                    JOIN grupo ON alumnos.id_grupo = grupo.id_grupo";

            $conditions = [];

            if (!empty($selectedGrupo)) {
                $conditions[] = "grupo.id_grupo = :selectedGrupo";
            }

            if (!empty($selectedAsignatura)) {
                $conditions[] = "asignatura.id_asignatura = :selectedAsignatura";
            }

            if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $sql .= " ORDER BY apellido_paterno ASC";

            $query = $conexion->prepare($sql);

            // Vincular los valores seleccionados
            if (!empty($selectedGrupo)) {
                $query->bindParam(':selectedGrupo', $selectedGrupo, PDO::PARAM_INT);
            }
            if (!empty($selectedAsignatura)) {
                $query->bindParam(':selectedAsignatura', $selectedAsignatura, PDO::PARAM_INT);
            }

            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            // Renderizar la tabla con los resultados filtrados
            if (count($resultados) > 0) {
                echo '<h6 class="text-center"><strong>Lista de Alumnos</strong></h6>';
                echo '<table class="table table-striped">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
                echo '<th scope="col">Nombre</th>';
                echo '<th scope="col">Apellido Paterno</th>';
                echo '<th scope="col">Apellido Materno</th>';
                echo '<th scope="col">Nombre Grupo</th>';
                echo '<th scope="col">Calificación P1</th>';
                echo '<th scope="col">Calificación P2</th>';
                echo '<th scope="col">Calificación P3</th>';
                echo '<th scope="col">Asistencia P1</th>';
                echo '<th scope="col">Asistencia P2</th>';
                echo '<th scope="col">Asistencia P3</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $i = 1;
                foreach ($resultados as $resultado) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $i++ . "</th>";
                    echo "<td>" . htmlspecialchars($resultado['nombre_alumno']) . "</td>";
                    echo "<td>" . htmlspecialchars($resultado['apellido_paterno']) . "</td>";
                    echo "<td>" . htmlspecialchars($resultado['apellido_materno']) . "</td>";
                    echo "<td>" . htmlspecialchars($resultado['nombre_grupo']) . "</td>";
                    echo "<td>" . $resultado['calificacion_p1'] . "</td>";
                    echo "<td>" . $resultado['calificacion_p2'] . "</td>";
                    echo "<td>" . $resultado['calificacion_p3'] . "</td>";
                    echo "<td>" . $resultado['asistencias_p1'] . "</td>";
                    echo "<td>" . $resultado['asistencias_p2'] . "</td>";
                    echo "<td>" . $resultado['asistencias_p3'] . "</td>";
                    echo "</tr>";
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p class="text-center">No se encontraron resultados para los filtros seleccionados.</p>';
            }
        } else {
            echo '<p class="text-center">Selecciona una asignatura y/o grupo para ver los resultados.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>