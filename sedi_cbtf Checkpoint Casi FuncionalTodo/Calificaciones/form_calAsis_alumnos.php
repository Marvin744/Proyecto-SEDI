<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    // Obtener los estados de los botones desde la base de datos
    $botones = ['p1', 'p2', 'p3'];
    $estados = [];

    foreach ($botones as $boton) {
        $sql = "SELECT estado FROM config_botones WHERE boton = :boton";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':boton', $boton);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $estados[$boton] = $result ? $result['estado'] : 0;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias y Calificaciones</title>
    <style>
        /* Estilo para el contenedor principal */
        body.paginaexcel-page {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding-top: 8rem;
            margin: 0;
        }

        /* Estilo del encabezado */
        .paginaexcel-header {
            font-size: 3rem;
            color: #007BFF;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Estilo del contenedor de botones */
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

        /* Estilo de los grupos de botones */
        .paginaexcel-button-group {
            display: block;
            margin-bottom: 3rem;
        }

        /* Estilo del título de cada grupo de botones */
        .paginaexcel-button-group h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1.2rem;
        }

        /* Estilo de los enlaces de botones */
        .paginaexcel-button-group a,
        .paginaexcel-button-group button {
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            padding: 0.8rem 2rem;
            border-radius: 1rem;
            margin: 1rem;
            display: inline-block;
            font-size: 1.8rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
            border: none; /* Quitar el borde de los botones */
            cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
        }

        /* Estilo para cuando se pasa el mouse por encima de los enlaces/botones */
        .paginaexcel-button-group a:hover,
        .paginaexcel-button-group button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Estilo para cuando se presiona un enlace/botón */
        .paginaexcel-button-group a:active,
        .paginaexcel-button-group button:active {
            background-color: #003f7f;
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Estilo para los botones deshabilitados */
        .paginaexcel-button-group button:disabled {
            background-color: #dcdcdc;
            color: #888;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        

/* Estilo general para las tablas */
table {
    width: 100%;
    border-collapse: separate; /* Permitir bordes redondeados */
    margin-bottom: 2rem; /* Espacio inferior */
    font-size: 1.6rem; /* Tamaño de fuente */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Sombra para la tabla */
    border-spacing: 0; /* Eliminar espacios entre celdas */
    border-radius: 10px; /* Bordes redondeados para la tabla */
    overflow: hidden; /* Asegurar que los bordes redondeados se mantengan */
}

/* Estilo para las celdas del encabezado */
table thead th {
    background-color: #007BFF; /* Fondo azul */
    color: white; /* Texto blanco */
    padding: 1rem; /* Espaciado interno */
    text-align: center; /* Alineación centrada */
    font-weight: bold; /* Texto en negrita */
    border-bottom: 2px solid #0056b3; /* Borde inferior */
}

/* Estilo para las celdas del cuerpo */
table tbody td {
    padding: 1rem; /* Espaciado interno */
    border-bottom: 1px solid #ddd; /* Borde inferior */
    text-align: center; /* Alineación centrada */
    font-weight: bold; /* Texto en negrita */
}

/* Línea divisora entre 'Nombre Grupo' y 'Calificación P1' solo en el cuerpo */
table tbody td:nth-child(5) { /* Ajusta el índice para apuntar a la columna de 'Nombre Grupo' */
    border-right: 2px solid #ccc; /* Línea divisora */
}

/* Estilo para las filas alternas */
table tbody tr:nth-child(odd) {
    background-color: #f9f9f9; /* Color de fondo para filas alternas */
}

/* Estilo para la fila al pasar el mouse */
table tbody tr:hover {
    background-color: #f1f1f1; /* Color de fondo al pasar el mouse */
    transition: background-color 0.2s ease; /* Transición suave */
}

/* Estilo para la primera columna (número de fila) */
table tbody th {
    background-color: #f4f4f4; /* Fondo ligeramente gris */
    font-weight: bold; /* Texto en negrita */
    text-align: center; /* Alineación centrada */
}

/* Bordes redondeados para las esquinas de la tabla */
table thead th:first-child {
    border-top-left-radius: 10px; /* Esquina superior izquierda */
}

table thead th:last-child {
    border-top-right-radius: 10px; /* Esquina superior derecha */
}

table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 10px; /* Esquina inferior izquierda */
}

table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 10px; /* Esquina inferior derecha */
}
    </style>
</head>
<body class="paginaexcel-page">
    <div>
        <h1 class="paginaexcel-header">Asistencias y Calificaciones</h1>
    </div>

    <div class="paginaexcel-button-container">
        <div class="paginaexcel-button-group">
            <h2>Asistencias de los Alumnos por parciales</h2>
            <a href="../Alertas/form_asistencias_alumnos_p1.php">Asistencias Parcial 1</a>
            <a href="../Alertas/form_asistencias_alumnos_p2.php">Asistencias Parcial 2</a>
            <a href="../Alertas/form_asistencias_alumnos_p3.php">Asistencias Parcial 3</a>
        </div>
        <div class="paginaexcel-button-group">
            <h2>Calificaciones y asistencias de los Alumnos por parciales</h2>
            <button class="" onclick="window.location.href='../Alertas/form_caliAsis_alumnos_p1.php';" <?php if(!$estados['p1']) echo 'disabled'; ?>>Calificaciones y Asistencias Parcial 1</button>
            <button class="" onclick="window.location.href='../Alertas/form_caliAsis_alumnos_p2.php';" <?php if(!$estados['p2']) echo 'disabled'; ?>>Calificaciones y Asistencias Parcial 2</button>
            <button class="" onclick="window.location.href='../Alertas/form_caliAsis_alumnos_p3.php';" <?php if(!$estados['p3']) echo 'disabled'; ?>>Calificaciones y Asistencias Parcial 3</button>
        </div>
    </div>

        <br>
    <div>
        <form id="filterForm" action="" method="GET">
            <div class="mb-3 text-center">
                <select name="asignatura" id="asignatura" class="form-control">
                    <option value="">Selecciona una asignatura</option>
                    <?php 
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

<?php
    require_once "../vistas/pie_pagina.php";
?>