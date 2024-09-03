<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';

    $id_docente = 14;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_activity'])) {
        $sql_insert = "INSERT INTO actividades_complementarias (id_docente, actividad, detalles_actividad, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
                       VALUES (1, '', '', '', '', '', '', '', '', 0)";
        $querysql_insert = $conexion->prepare($sql_insert);
        
        if ($querysql_insert->execute()) {
            echo "Nueva actividad insertada exitosamente.";
            $url_redireccion = $_SERVER['PHP_SELF'];
            echo "<script>
            alert('Nueva actividad insertada exitosamente.');
            window.location.href = '$url_redireccion'.'?id_docente='.$id_docente;
            </script>";
    
            // Redireccionar después de la inserción
            // header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Asegúrate de que el script se detenga aquí
        } else {
            echo "Error al insertar la actividad: " . $querysql_insert->errorInfo()[2];
        }
    }

    if ($id_docente) {
        // Consulta para obtener el nombre completo y el título de estudios del docente
        $sqlDocente = "SELECT titulo_estudios, CONCAT(nombre_docente, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo 
                       FROM docentes 
                       WHERE id_docente = :id_docente";
        $queryDocente = $conexion->prepare($sqlDocente);
        $queryDocente->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $queryDocente->execute();
        $docenteData = $queryDocente->fetch(PDO::FETCH_ASSOC);

        // Verificar que se obtuvieron datos
        if ($docenteData) {
            $titulo_estudios = $docenteData['titulo_estudios'];
            $nombre_docente = $docenteData['nombre_completo'];
        } else {
            $titulo_estudios = 'N/A'; // Valor por defecto en caso de no encontrar el docente
            $nombre_docente = 'Nombre no encontrado';
        }

    $sqlHorarios = "SELECT 
    MIN(h.id_horario) AS id_horario,
    a.nombre_asignatura AS asignatura, 
    a.submodulos AS submodulos,
    g.nombre_grupo AS grupo, 
    e.nombre_especialidad AS especialidad, 
    s.nombre_semestre AS nombre_semestre, 
    s.numero_semestre AS numero_semestre, 
    GROUP_CONCAT(DISTINCT h.lunes ORDER BY h.lunes SEPARATOR ' ') AS lunes,
    GROUP_CONCAT(DISTINCT h.martes ORDER BY h.martes SEPARATOR ' ') AS martes,
    GROUP_CONCAT(DISTINCT h.miercoles ORDER BY h.miercoles SEPARATOR ' ') AS miercoles,
    GROUP_CONCAT(DISTINCT h.jueves ORDER BY h.jueves SEPARATOR ' ') AS jueves,
    GROUP_CONCAT(DISTINCT h.viernes ORDER BY h.viernes SEPARATOR ' ') AS viernes,
    GROUP_CONCAT(DISTINCT h.sabado ORDER BY h.sabado SEPARATOR ' ') AS sabado,
    SUM(h.horas_semanales) AS horas_semanales
FROM horarios h
JOIN asignatura a ON h.id_asignatura = a.id_asignatura
JOIN grupo g ON h.id_grupo = g.id_grupo
JOIN especialidad e ON g.id_especialidad = e.id_especialidad
JOIN semestre s ON g.id_semestre = s.id_semestre 
GROUP BY 
    a.nombre_asignatura, 
    a.submodulos,
    g.nombre_grupo, 
    e.nombre_especialidad, 
    s.nombre_semestre, 
    s.numero_semestre 
ORDER BY 
    a.nombre_asignatura, 
    g.nombre_grupo, 
    e.nombre_especialidad, 
    s.numero_semestre ASC;";

    }

$queryTableHorarios = $conexion->prepare($sqlHorarios);
$queryTableHorarios->execute();
$result = $queryTableHorarios->fetchAll();

$prevAsignatura = '';
$rowspan = 1;
$totalHorasSemanales = 0; 
?>
<?php //require_once "../vistas/encabezado.php";?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 4px;
        text-align: center;
    }

    .header-row {
        background-color: #d3d3d3;
        /* Color más oscuro */
    }
    </style>

        <style>
        .f19_table,
        .f19_table th,
        .f19_table td {
            font-family: inherit;
            font-size: 1.4rem;
        }
        .f19_table {
            width: 100%;
            border-collapse: collapse;
        }

        .f19_th,
        .f19_td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }

        .f19_header-row {
            background-color: #d3d3d3;
            /* Color más oscuro */
        }
    </style>
</head>

<body>
<h1>Horario Académico</h1>

<div class="datos_f19">
<form action="plantilla_f191.php" method="post" target="_blank">
    <label for="">Número de Oficio: </label>
        <input type="number" name="folio" id="folio"><br>

        <label for="fecha">Fecha: </label>
        <input id="fecha" class="fecha" type="date" value="date('Y,m,d')"><br>

        <label for="">Periodo: </label>
        <input type="text" name="periodo" id="periodo">
    </div>
        <input type="hidden" name="fecha" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="titulo_estudios" value="<?php echo $titulo_estudios ?>">
        <input type="hidden" name="nombre_docente" value="<?php echo $nombre_docente ?>">
        <input type="hidden" name="id_docente" value="<?php echo $id_docente ?>">

        <!-- Campos hidden para pasar las sumas de horas -->
        <input type="hidden" id="hiddenSubtotalLunes" name="subtotal_lunes">
        <input type="hidden" id="hiddenSubtotalMartes" name="subtotal_martes">
        <input type="hidden" id="hiddenSubtotalMiercoles" name="subtotal_miercoles">
        <input type="hidden" id="hiddenSubtotalJueves" name="subtotal_jueves">
        <input type="hidden" id="hiddenSubtotalViernes" name="subtotal_viernes">
        <input type="hidden" id="hiddenSubtotalSabado" name="subtotal_sabado">
        <input type="hidden" id="hiddenSubtotalHoras" name="subtotal_horas">
        
        <button type="submit">Exportar a PDF</button>
    </form>
    </div>

<table class="f19_table">
    <thead>
        <tr class="f19_header-row">
            <th class="f19_th" colspan="3">Carga Académica</th>
            <th class="f19_th" colspan="8">Horario</th>
        </tr>
        <tr>
            <th class="f19_th">ASIGNATURA</th>
            <th class="f19_th">GRUPO</th>
            <th class="f19_th">ESPECIALIDAD</th>
            <th class="f19_th">LUNES</th>
            <th class="f19_th">MARTES</th>
            <th class="f19_th">MIÉRCOLES</th>
            <th class="f19_th">JUEVES</th>
            <th class="f19_th">VIERNES</th>
            <th class="f19_th">SÁBADO</th>
            <th class="f19_th horas-semanales">HORAS SEMANALES</th>
            <th class="f19_th">SEMESTRE</th>
        </tr>
    </thead>
    <tbody>
        <?php

            foreach ($result as $index => $row) {
                $totalHorasSemanales += $row['horas_semanales'];

                if ($row['asignatura'] === $prevAsignatura) {
                    $rowspan++;
                    echo "<tr>";
                    echo "<td class='f19_td' style='display:none;'></td>";
                } else {
                    if ($rowspan > 1) {
                        echo "<script>
                        document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                        </script>";
                    }
                    $rowspan = 1;
                    $prevAsignatura = $row['asignatura'];
                    $prevSubmodulo = $row['submodulos'];

                    $asignaturaCompleta = $row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura'];

                    if (strpos(strtolower($asignaturaCompleta), 'módulo') !== false || strpos(strtolower($asignaturaCompleta), 'submódulo') !== false) {
                        $asignaturaCompleta = preg_replace_callback('/(módulo\s*\d+|submódulo\s*\d+)/i', function ($matches) use (&$profesionalInserted) {
                            $matchText = $matches[0];
                    
                            if (!$profesionalInserted && stripos($matchText, 'módulo') !== false) {
                                $profesionalInserted = true;
                                return '<strong>' . mb_strtoupper(preg_replace('/módulo\s*(\d+)/i', 'MÓDULO PROFESIONAL $1.', $matchText), 'UTF-8') . '</strong>';
                            } else {
                                return '<strong>' . mb_strtoupper(preg_replace('/(submódulo\s*\d+)/i', '$1.', $matchText), 'UTF-8') . '</strong>';
                            }
                        }, $asignaturaCompleta);
                    
                        echo "<td class='f19_td' data-asignatura='{$row['asignatura']}'>{$asignaturaCompleta}</td>";
                    } else {
                        echo "<td class='f19_td' data-asignatura='{$row['asignatura']}'><strong>{$asignaturaCompleta}</strong></td>";
                    }
                }
                echo "<td class='f19_td'>{$row['nombre_semestre']} {$row['grupo']}</td>";
                echo "<td class='f19_td'>{$row['especialidad']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='lunes' data-id='{$row['id_horario']}'><strong>{$row['lunes']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='martes' data-id='{$row['id_horario']}'><strong>{$row['martes']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='miercoles' data-id='{$row['id_horario']}'><strong>{$row['miercoles']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='jueves' data-id='{$row['id_horario']}'><strong>{$row['jueves']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='viernes' data-id='{$row['id_horario']}'><strong>{$row['viernes']}</td>";
                echo "<td class='f19_td' contenteditable='true' data-day='sabado' data-id='{$row['id_horario']}'><strong>{$row['sabado']}</td>";
                echo "<td class='f19_td horas-semanales'><strong>{$row['horas_semanales']}</td>";
                echo "<td class='f19_td'><strong>{$row['numero_semestre']}</td>";
                echo "</tr>";
            }

            if ($rowspan > 1) {
                echo "<script>
                document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                </script>";
            }
        ?>
    </tbody>

    <tfoot>
        <tr class="f19_header-row">
            <td class="f19_td" colspan="3"><strong>Subtotal de Horas</strong></td>
            <td class="f19_td" id="subtotal-lunes">0</td>
            <td class="f19_td" id="subtotal-martes">0</td>
            <td class="f19_td" id="subtotal-miercoles">0</td>
            <td class="f19_td" id="subtotal-jueves">0</td>
            <td class="f19_td" id="subtotal-viernes">0</td>
            <td class="f19_td" id="subtotal-sabado">0</td>
            <td class="f19_td" id="subtotal-horas"><strong><?php echo $totalHorasSemanales; ?></strong></td>
            <td class="f19_td" id="subtotal-docentes"></td>
        </tr>
    </tfoot>
</table>
<br>

<!-- ------------------------------------------------------------------------------------------------------------- -->

    <h1>Horario Académico</h1>
    <form method="post">
        <button type="submit" name="insert_activity">Insertar Nueva Actividad</button>
    </form>

    <table class="f19_table">
        <thead>
        <tr class="f19_header-row">
        <th class="f19_th" colspan="2">Carga Académica</th>
        <th class="f19_th" colspan="7">Horario</th>
            </tr>
            <tr>
                <th class="f19_th" >ACTIVIDAD</th>
                <th class="f19_th" >DETALLES ACTIVIDAD</th>
                <th class="f19_th" >LUNES</th>
                <th class="f19_th" >MARTES</th>
                <th class="f19_th" >MIÉRCOLES</th>
                <th class="f19_th" >JUEVES</th>
                <th class="f19_th" >VIERNES</th>
                <th class="f19_th" >SÁBADO</th>
                <th class="f19_th horas-semanales">HORAS SEMANALES</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sqlActCom = "SELECT 
                                    MIN(a.id_actividad_complementaria) AS id_actividad_complementaria,
                                    a.actividad AS actividad, 
                                    a.detalles_actividad AS detalles_actividad,
                                    GROUP_CONCAT(DISTINCT a.lunes ORDER BY a.lunes SEPARATOR ' ') AS lunes,
                                    GROUP_CONCAT(DISTINCT a.martes ORDER BY a.martes SEPARATOR ' ') AS martes,
                                    GROUP_CONCAT(DISTINCT a.miercoles ORDER BY a.miercoles SEPARATOR ' ') AS miercoles,
                                    GROUP_CONCAT(DISTINCT a.jueves ORDER BY a.jueves SEPARATOR ' ') AS jueves,
                                    GROUP_CONCAT(DISTINCT a.viernes ORDER BY a.viernes SEPARATOR ' ') AS viernes,
                                    GROUP_CONCAT(DISTINCT a.sabado ORDER BY a.sabado SEPARATOR ' ') AS sabado,
                                    SUM(a.horas_semanales) AS horas_semanales
                                    -- Quitamos la columna de docente
                                FROM actividades_complementarias a
                                GROUP BY 
                                    a.actividad, 
                                    a.detalles_actividad
                                ORDER BY 
                                    a.actividad;";

                $queryTableActCom = $conexion->prepare($sqlActCom);
                $queryTableActCom->execute();
                $resulta = $queryTableActCom->fetchAll();
                

                $prevAsignatura = '';
                $rowspan = 1;
                $totalHorasSemanales = 0; // Inicializar variable para el total de horas semanales

                foreach ($resulta as $index => $row) {
                    // Sumar las horas semanales
                    $totalHorasSemanales += $row['horas_semanales'];

                    
                    echo "<td class='f19_td' contenteditable='true' data-dato='actividad' data-id1='{$row['id_actividad_complementaria']}'>{$row['actividad']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-dato='detalles_actividad' data-id1='{$row['id_actividad_complementaria']}'>{$row['detalles_actividad']}</td>";
                    

                    echo "<td class='f19_td' contenteditable='true' data-day1='lunes' data-id1='{$row['id_actividad_complementaria']}'>{$row['lunes']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-day1='martes' data-id1='{$row['id_actividad_complementaria']}'>{$row['martes']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-day1='miercoles' data-id1='{$row['id_actividad_complementaria']}'>{$row['miercoles']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-day1='jueves' data-id1='{$row['id_actividad_complementaria']}'>{$row['jueves']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-day1='viernes' data-id1='{$row['id_actividad_complementaria']}'>{$row['viernes']}</td>";
                    echo "<td class='f19_td' contenteditable='true' data-day1='sabado' data-id1='{$row['id_actividad_complementaria']}'>{$row['sabado']}</td>";
                    echo "<td class='horas-semanales' contenteditable='true' data-dato='horas_semanales' data-id1='{$row['id_actividad_complementaria']}'>{$row['horas_semanales']}</td>";
                    echo "</tr>";
                }
                if ($rowspan > 1) {
                    echo "<script>
                    document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                    </script>";
                }

                // Mostrar el total de horas semanales
                // echo "<tr>";
                // echo "<td colspan='9' style='text-align:right;'><strong>Total de Horas Semanales:</strong></td>";
                // echo "<td colspan='2'><strong>{$totalHorasSemanales}</strong></td>";
                // echo "</tr>";
            ?>
        </tbody>

        <tfoot>
        <tr class="f19_header-row">
                <td class="f19_td"  colspan="2">Subtotal de Horas</td>
                <td class="f19_td"  id="subtotal-lunes1">0</td>
                <td class="f19_td"  id="subtotal-martes1">0</td>
                <td class="f19_td"  id="subtotal-miercoles1">0</td>
                <td class="f19_td"  id="subtotal-jueves1">0</td>
                <td class="f19_td"  id="subtotal-viernes1">0</td>
                <td class="f19_td"  id="subtotal-sabado1">0</td>
                <td class="f19_td"  id="subtotal-horas1"><strong><?php echo $totalHorasSemanales; ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <button id="verificar-horas">Verificar Horas</button>



    <script>
        // Añadir evento al botón para verificar las horas
        document.getElementById('verificar-horas').addEventListener('click', function() {

            // Sumar las horas de los días de la semana (lunes a sábado)
            const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal-"]'))
                .slice(0, 6) // Solo considerar los días de lunes a sábado
                .reduce((total, td) => total + parseInt(td.textContent || 0, 10), 0);

            // Obtener el total de horas semanales desde el campo correspondiente
            const totalHorasSemanales = parseInt(document.getElementById('subtotal-horas').textContent || 0, 10);

            if (totalHorasDias === totalHorasSemanales) {
                alert('Las horas coinciden correctamente.');
            } else {
                alert('Las horas no coinciden. Por favor, revise los horarios.');
            }

        });
        
        // 
        document.querySelector('form[action="plantilla_f191.php"]').addEventListener('submit', function() {
            // Actualizar los campos hidden antes de enviar el formulario
            document.getElementById('hiddenSubtotalLunes').value = document.getElementById('subtotal-lunes').textContent;
            document.getElementById('hiddenSubtotalMartes').value = document.getElementById('subtotal-martes').textContent;
            document.getElementById('hiddenSubtotalMiercoles').value = document.getElementById('subtotal-miercoles').textContent;
            document.getElementById('hiddenSubtotalJueves').value = document.getElementById('subtotal-jueves').textContent;
            document.getElementById('hiddenSubtotalViernes').value = document.getElementById('subtotal-viernes').textContent;
            document.getElementById('hiddenSubtotalSabado').value = document.getElementById('subtotal-sabado').textContent;
            document.getElementById('hiddenSubtotalHoras').value = document.getElementById('subtotal-horas').textContent;
        });

        // Script para detectar fecha actual
        window.onload = function() {
            // Obtener el input de fecha
            const fechaInput = document.getElementById('fecha');

            // Obtener la fecha actual
            const hoy = new Date();

            // Formatear la fecha en YYYY-MM-DD
            const fechaFormateada = hoy.toISOString().split('T')[0];

            // Establecer la fecha actual en el input
            fechaInput.value = fechaFormateada;
        };
    </script>

    <script>
        var subtotalHoras = 0;
        var totalHorasDias = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('td[contenteditable="true"]');

            // Función para actualizar la base de datos
            function updateDatabase(id, day, value) {
                return fetch('update_horas.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id,
                            day,
                            value
                        })
                    })
                    .then(response => response.json())
                    .catch(error => console.error('Error:', error));
            }

            // Función para verificar solapamiento de horarios
            function checkColumnOverlap(columnCells) {
                const times = [];

                for (let cell of columnCells) {
                    const value = cell.textContent.trim();
                    const ranges = value.split(' ');
                    for (let range of ranges) {
                        const [start, end] = range.split('-').map(time => {
                            const [hour, minute] = time.split(':').map(Number);
                            return hour * 60 + minute; // Convertir a minutos desde medianoche
                        });
                        times.push({
                            start,
                            end,
                            cell
                        });
                    }
                }

                for (let i = 0; i < times.length - 1; i++) {
                    for (let j = i + 1; j < times.length; j++) {
                        // Verificar si los rangos de horas se solapan
                        if (times[i].start < times[j].end && times[i].end > times[j].start) {
                            return times[j].cell; // Devolver la celda que causa el empalme
                        }
                    }
                }
                return null; // No se empalman
            }

            // Función para actualizar los subtotales
            function updateSubtotals() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                let totalHorasDias = 0;
                let subtotalHoras = 0;

                days.forEach(day => {
                    const dayCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    let subtotal = 0;

                    dayCells.forEach(cell => {
                        const ranges = cell.textContent.split(' ');
                        ranges.forEach(range => {
                            const times = range.split('-');
                            if (times.length === 2) {
                                const start = times[0].split(':').map(Number);
                                const end = times[1].split(':').map(Number);
                                const duration = (end[0] * 60 + end[1]) - (start[0] * 60 + start[1]);
                                
                                subtotal += duration / 60; // Convertir minutos a horas
                            }
                        });
                    });

                    document.getElementById(`subtotal-${day}`).textContent = Math.floor(subtotal);

                    // Actualizar campos hidden para enviar al PDF
                    document.getElementById(`hiddenSubtotal${day.charAt(0).toUpperCase() + day.slice(1)}`).value = Math.floor(subtotal);
                    subtotalHoras += subtotal;
                });

                // Sumar todas las horas semanales
                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
                document.getElementById('hiddenSubtotalHoras').value = Math.floor(subtotalHoras);

                const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
                weeklyHoursCells.forEach(cell => {
                    const horas = parseInt(cell.textContent.trim(), 10);
                    subtotalHoras += isNaN(horas) ? 0 : horas;
                });

                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
                document.getElementById('hiddenSubtotalHoras').value = Math.floor(subtotalHoras);
            }

            // Evento para detectar cambios en celdas editables
            cells.forEach(cell => {
                cell.addEventListener('blur', function() {
                    const day = this.getAttribute('data-day');
                    const columnCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    const editedCell = this; // Guardamos la celda que fue editada

                    // Verificar empalme de horarios para todas las celdas de la misma columna (día)
                    const overlapCell = checkColumnOverlap(columnCells);
                    if (overlapCell) {
                        alert(`Los horarios se empalman en ${day}. El campo que acabas de modificar se limpiará para que puedas corregirlo.`);

                        // Limpiar el contenido de la celda editada (no el de la celda empalmada)
                        editedCell.textContent = '';

                        // Limpiar el contenido en la base de datos
                        const ids = editedCell.getAttribute('data-id').split(','); // Obtener todos los ids_horario
                        
                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {
                                
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals(); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });

                        return;
                    }

                    const ids = this.getAttribute('data-id').split(','); // Obtener todos los ids_horario
                    const value = this.textContent.trim();

                    // Validar el formato del rango de horas (ejemplo: 08:00-10:00 o múltiples rangos)
                    const timeRangePattern = /^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/;

                    if (value === "" || timeRangePattern.test(value)) {
                        // Si la validación de solapamiento y formato es exitosa, proceder con la actualización
                        ids.forEach(id => {
                            updateDatabase(id, day, value).then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals();
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:', id, data.error || data.message);
                                }
                            });
                        });
                    } else {
                        alert(
                            'Formato de rango de horas inválido. El campo se limpiará para que puedas corregirlo.'
                        );
                        this.textContent = ''; // Limpiar el contenido de la celda actual si el formato es inválido

                        // Limpiar el contenido en la base de datos
                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotals(); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });
                    }

                    updateSubtotals(); // <- Llamada extra para asegurarnos de actualizar los subtotales después de cada edición
                });
            });

            // Inicializar subtotales al cargar la página
            updateSubtotals();

            function checkColumnOverlap(columnCells) {
                const times = [];

                for (let cell of columnCells) {
                    const value = cell.textContent.trim();
                    const ranges = value.split(' ');

                    for (let range of ranges) {
                        const [start, end] = range.split('-').map(time => {
                            const [hour, minute] = time.split(':').map(Number);
                            return hour * 60 + minute; // Convertir a minutos desde medianoche
                        });
                        times.push({
                            start,
                            end,
                            cell
                        });
                    }
                }

                for (let i = 0; i < times.length - 1; i++) {
                    for (let j = i + 1; j < times.length; j++) {
                        // Verificar si los rangos de horas se solapan
                        if (times[i].start < times[j].end && times[i].end > times[j].start) {
                            // Comprobamos cuál es la celda actual (la que se está editando)
                            if (times[i].cell === document.activeElement) {
                                return times[i].cell; // Retornar la celda actual si es la que causa el empalme
                            } else {
                                return times[j]
                                    .cell; // De lo contrario, retornar la otra celda que causa el empalme
                            }
                        }
                    }
                }
                return null; // No se empalman
            }

            function updateSubtotals() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                const subtotals = {
                    lunes: 0,
                    martes: 0,
                    miercoles: 0,
                    jueves: 0,
                    viernes: 0,
                    sabado: 0
                };


                let aux = 0;

                days.forEach(day => {
                    const dayCells = document.querySelectorAll(`td[data-day="${day}"]`);
                    dayCells.forEach(cell => {
                        const ranges = cell.textContent.split(' '); // Separar por espacios
                        ranges.forEach(range => {
                            const times = range.split('-');
                            if (times.length === 2) {
                                const start = times[0].split(':');
                                const end = times[1].split(':');
                                const startHour = parseInt(start[0], 10);
                                const startMinute = parseInt(start[1], 10);
                                const endHour = parseInt(end[0], 10);
                                const endMinute = parseInt(end[1], 10);
                                const duration = (endHour * 60 + endMinute) - (startHour *
                                    60 + startMinute);
                                subtotals[day] += duration /
                                    60; // Convertir minutos a horas
                            }
                        });
                    });
                    // Mostrar el subtotal como entero
                    aux = Math.floor(subtotals[day]);
                    document.getElementById(`subtotal-${day}`).textContent = aux;
                    totalHorasDias += subtotals[day];
                });

                // Calcular el subtotal de horas semanales
                const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
                weeklyHoursCells.forEach(cell => {

                    const horas = parseInt(str, 10); // Convertir a número
                    //const horas = parseFloat(cell.textContent.trim());  Convertir a número
                    subtotalHoras += isNaN(horas) ? 0 : horas; // Sumar si es un número válido
                });

                // Mostrar el subtotal de horas semanales
                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
            }

            // Llamar a la función para calcular los subtotales al cargar la página
            updateSubtotals();

            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('tbody tr');
                const groupedData = {};

                rows.forEach(row => {
                    const asignatura = row.querySelector('td:nth-child(1)').textContent.trim();
                    const grupo = row.querySelector('td:nth-child(2)').textContent.trim();
                    const especialidad = row.querySelector('td:nth-child(3)').textContent.trim();
                    const key = `${asignatura}-${grupo}-${especialidad}`;

                    if (!groupedData[key]) {
                        groupedData[key] = {
                            firstRow: row,
                            count: 1
                        };
                    } else {
                            groupedData[key].count += 1;
                        const firstRow = groupedData[key].firstRow;

                        // Aumentar el rowspan en la primera celda de la asignatura
                        const rowspanCell = firstRow.querySelector('td:nth-child(1)');
                        rowspanCell.setAttribute('rowspan', groupedData[key].count);

                        // Ocultar las celdas duplicadas en las filas adicionales
                        row.querySelector('td:nth-child(1)').style.display = 'none'; // Asignatura
                        row.querySelector('td:nth-child(2)').style.display = 'none'; // Grupo
                        row.querySelector('td:nth-child(3)').style.display = 'none'; // Especialidad
                    }
                });
            });


        });
    </script>

<!-- ------------------------------------------------------------------------------------------------------------------------ -->

<script>

document.addEventListener('click', function(event) {
    // Verifica si el clic fue fuera de ambas tablas
    const table1 = document.querySelector('table:nth-of-type(1)'); // Primera tabla
    const table2 = document.querySelector('table:nth-of-type(2)'); // Segunda tabla

    if (!table1.contains(event.target) && !table2.contains(event.target)) {
        // Si el clic fue fuera de ambas tablas, recarga la página
        location.reload();
    }
});

        // Añadir evento al botón para verificar las horas
document.getElementById('verificar-horas').addEventListener('click', function() {

// Sumar las horas de los días de la semana (lunes a sábado)
const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal-"]'))
    .slice(0, 6) // Solo considerar los días de lunes a sábado
    .reduce((total, td) => total + parseInt(td.textContent || 0, 10), 0);

// Obtener el total de horas semanales desde el campo correspondiente
const totalHorasSemanales = parseInt(document.getElementById('subtotal-horas1').textContent || 0, 10);

if (totalHorasDias === totalHorasSemanales) {
    alert('Las horas coinciden correctamente.');
} else {
    alert('Las horas no coinciden. Por favor, revise los horarios.');
}

});

document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('td[contenteditable="true"]');

    // Definir el patrón para validar el rango de horas
    const timeRangePattern = /^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/;

    // Función para actualizar la base de datos
    function updateDatabase(id, dato, value) {
        return fetch('update_actividad.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id,
                dato,
                value
            })
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    }

    // Función para verificar solapamiento de horarios
    function checkOverlap(dato) {
        const dayCells = document.querySelectorAll(`td[data-day1="${dato}"]`);
        const ranges = [];

        for (let cell of dayCells) {
            const value = cell.textContent.trim();
            if (value) {
                const timeRanges = value.split(' ');
                for (let timeRange of timeRanges) {
                    const [start, end] = timeRange.split('-').map(time => {
                        const [hour, minute] = time.split(':').map(Number);
                        return hour * 60 + minute; // Convertir a minutos desde medianoche
                    });
                    ranges.push({ start, end, cell });
                }
            }
        }

        for (let i = 0; i < ranges.length - 1; i++) {
            for (let j = i + 1; j < ranges.length; j++) {
                if (ranges[i].start < ranges[j].end && ranges[i].end > ranges[j].start) {
                    return ranges[j].cell; // Devolver la celda que causa el solapamiento
                }
            }
        }

        return null; // No hay solapamiento
    }

    // Función para actualizar los subtotales
    function updateSubtotals() {
        const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        let totalHorasDias = 0;
        let subtotalHoras = 0;

        days.forEach(day => {
            const dayCells = document.querySelectorAll(`td[data-day1="${day}"]`);
            let subtotal = 0;

            dayCells.forEach(cell => {
                const ranges = cell.textContent.split(' ');
                ranges.forEach(range => {
                    const times = range.split('-');
                    if (times.length === 2) {
                        const start = times[0].split(':').map(Number);
                        const end = times[1].split(':').map(Number);
                        const duration = (end[0] * 60 + end[1]) - (start[0] * 60 + start[1]);
                        subtotal += duration / 60; // Convertir minutos a horas
                    }
                });
            });

            totalHorasDias += subtotal;
            document.getElementById(`subtotal-${day}1`).textContent = Math.floor(subtotal);
        });

        const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
        weeklyHoursCells.forEach(cell => {
            const horas = parseInt(cell.textContent.trim(), 10);
            subtotalHoras += isNaN(horas) ? 0 : horas;
        });

        document.getElementById('subtotal-horas1').textContent = Math.floor(subtotalHoras);
    }

    // Evento para detectar cambios en celdas editables
    cells.forEach(cell => {
        cell.addEventListener('blur', function() {
            const dato = this.getAttribute('data-dato') || this.getAttribute('data-day1');
            const id = this.getAttribute('data-id1');
            const value = this.textContent.trim();

            // Verificar solapamiento de horarios
            const overlapCell = checkOverlap(dato);
            if (overlapCell) {
                alert(`Los horarios se solapan en ${dato}. El campo que acabas de modificar se limpiará para que puedas corregirlo.`);
                this.textContent = ''; // Limpiar el contenido de la celda actual

                // Limpiar el contenido en la base de datos
                updateDatabase(id, dato, '').then(data => {
                    if (data.success) {
                        console.log('Actualización exitosa para ID:', id);
                        updateSubtotals();
                    } else {
                        console.error('Error en la actualización para ID:', id, data.error || data.message);
                    }
                });
                return; // Salir si hay solapamiento
            }

            if (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'].includes(dato)) {
                // Validar el formato del rango de horas antes de actualizar
                if (value === "" || timeRangePattern.test(value)) {
                    // Si el formato es válido, actualizar en la base de datos
                    updateDatabase(id, dato, value).then(data => {
                        if (data.success) {
                            console.log('Actualización exitosa para ID:', id);
                            updateSubtotals();
                        } else {
                            console.error('Error en la actualización para ID:', id, data.error || data.message);
                        }
                    });
                } else {
                    alert('Formato de rango de horas inválido. El campo se limpiará para que puedas corregirlo.');
                    this.textContent = ''; // Limpiar el contenido de la celda actual si el formato es inválido

                    // Limpiar el contenido en la base de datos
                    updateDatabase(id, dato, '').then(data => {
                        if (data.success) {
                            console.log('Actualización exitosa para ID:', id);
                            updateSubtotals();
                        } else {
                            console.error('Error en la actualización para ID:', id, data.error || data.message);
                        }
                    });
                }
            } else if (['actividad', 'detalles_actividad', 'horas_semanales'].includes(dato)) {
                // Actualizar actividad y detalles_actividad directamente sin validación
                updateDatabase(id, dato, value).then(data => {
                    if (data.success) {
                        console.log('Actualización exitosa para ID:', id);
                    } else {
                        console.error('Error en la actualización para ID:', id, data.error || data.message);
                    }
                });
            }
        });
    });

    // Inicializar subtotales al cargar la página
    updateSubtotals();
});

</script>


</body>

</html>