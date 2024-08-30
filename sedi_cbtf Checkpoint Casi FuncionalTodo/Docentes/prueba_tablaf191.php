<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
?>
<?php require_once '../General_Actions/validar_sesion.php';?>
<?php //require_once "../vistas/encabezado.php"?>

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
</head>

<body>
    <h1>Horario Académico</h1>

    <div class="datos_f19">
        <label for="">Número de Oficio: </label>
        <input type="number"><br>

        <label for="">Docente: </label>
        <select name="docentes" id="docentes">
            <?php

                $sql="SELECT DISTINCT id_docente FROM docentes ORDER BY apellido_paterno ASC;";
                
                        $query = $conexion->prepare($sql);
                       
                        $query->execute();
                        $result = $query->fetchAll();

                        foreach ($result as $row) {
                    
                            $nombre_docente = $row['id_docente'];
                            //$apellido_paterno = $row['apellido_paterno'];
                            //$apellido_materno = $row['apellido_materno'];
                            // Genera una opción con el nombre y apellido del docente
                            echo "<option value=\"$nombre_docente\">$nombre_docente</option>";
                        }
                
            ?>
        </select><br>

        <label for="fecha">Fecha: </label>
        <input id="fecha" class="fecha" type="date"><br>

        <label for="">Periodo: </label>
        <input type="text" name="" id="">
    </div>

    <table>
        <thead>
            <tr class="header-row">
                <th colspan="3">Carga Académica</th>
                <th colspan="8">Horario</th>
            </tr>
            <tr>
                <th>ASIGNATURA</th>
                <th>GRUPO</th>
                <th>ESPECIALIDAD</th>
                <th>LUNES</th>
                <th>MARTES</th>
                <th>MIÉRCOLES</th>
                <th>JUEVES</th>
                <th>VIERNES</th>
                <th>SÁBADO</th>
                <th class="horas-semanales">HORAS SEMANALES</th>
                <th>DOCENTE</th>
            </tr>
        </thead>
        <tbody>
            <?php
$sqlHorarios = "SELECT MIN(h.id_horario) AS id_horario,
                    a.nombre_asignatura AS asignatura, 
                    g.nombre_grupo AS grupo, 
                    e.nombre_especialidad AS especialidad, 
                    s.nombre_semestre AS semestre,
                    GROUP_CONCAT(DISTINCT h.lunes ORDER BY h.lunes SEPARATOR ' ') AS lunes,
                    GROUP_CONCAT(DISTINCT h.martes ORDER BY h.martes SEPARATOR ' ') AS martes,
                    GROUP_CONCAT(DISTINCT h.miercoles ORDER BY h.miercoles SEPARATOR ' ') AS miercoles,
                    GROUP_CONCAT(DISTINCT h.jueves ORDER BY h.jueves SEPARATOR ' ') AS jueves,
                    GROUP_CONCAT(DISTINCT h.viernes ORDER BY h.viernes SEPARATOR ' ') AS viernes,
                    GROUP_CONCAT(DISTINCT h.sabado ORDER BY h.sabado SEPARATOR ' ') AS sabado,
                    SUM(h.horas_semanales) AS horas_semanales,
                    CONCAT(d.nombre_docente, ' ', d.apellido_paterno, ' ', d.apellido_materno) AS docente
                FROM horarios h
                JOIN docentes d ON h.id_docente = d.id_docente
                JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                JOIN grupo g ON h.id_grupo = g.id_grupo
                JOIN especialidad e ON g.id_especialidad = e.id_especialidad
                JOIN semestre s ON g.id_semestre = s.id_semestre
                GROUP BY a.nombre_asignatura, g.nombre_grupo, e.nombre_especialidad, s.nombre_semestre, d.nombre_docente, d.apellido_paterno, d.apellido_materno
                ORDER BY a.nombre_asignatura, g.nombre_grupo, e.nombre_especialidad, s.nombre_semestre ASC;";

$queryTableHorarios = $conexion->prepare($sqlHorarios);
$queryTableHorarios->execute();
$result = $queryTableHorarios->fetchAll();

$prevAsignatura = '';
$rowspan = 1;
$totalHorasSemanales = 0; // Inicializar variable para el total de horas semanales

foreach ($result as $index => $row) {
    // Sumar las horas semanales
    $totalHorasSemanales += $row['horas_semanales'];

    // Checar si la asignatura es la misma que la anterior
    if ($row['asignatura'] === $prevAsignatura) {
        $rowspan++;
        echo "<tr>";
        // No imprimir la asignatura de nuevo, simplemente incrementar el rowspan
        echo "<td style='display:none;'></td>";
    } else {
        // Nueva asignatura, imprimir la fila completa
        if ($rowspan > 1) {
            // Si hubo un rowspan anterior, cerrarlo
            echo "<script>
            document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
            </script>";
        }
        $rowspan = 1;
        $prevAsignatura = $row['asignatura'];
        echo "<tr>";
        echo "<td data-asignatura='{$row['asignatura']}'>{$row['asignatura']}</td>";
    }
    echo "<td>{$row['semestre']} {$row['grupo']}</td>";
    echo "<td>{$row['especialidad']}</td>";
    echo "<td contenteditable='true' data-day='lunes' data-id='{$row['id_horario']}'>{$row['lunes']}</td>";
    echo "<td contenteditable='true' data-day='martes' data-id='{$row['id_horario']}'>{$row['martes']}</td>";
    echo "<td contenteditable='true' data-day='miercoles' data-id='{$row['id_horario']}'>{$row['miercoles']}</td>";
    echo "<td contenteditable='true' data-day='jueves' data-id='{$row['id_horario']}'>{$row['jueves']}</td>";
    echo "<td contenteditable='true' data-day='viernes' data-id='{$row['id_horario']}'>{$row['viernes']}</td>";
    echo "<td contenteditable='true' data-day='sabado' data-id='{$row['id_horario']}'>{$row['sabado']}</td>";
    echo "<td>{$row['horas_semanales']}</td>";
    echo "<td>{$row['docente']}</td>";
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
            <tr class="header-row">
                <td colspan="3">Subtotal de Horas</td>
                <td id="subtotal-lunes">0</td>
                <td id="subtotal-martes">0</td>
                <td id="subtotal-miercoles">0</td>
                <td id="subtotal-jueves">0</td>
                <td id="subtotal-viernes">0</td>
                <td id="subtotal-sabado">0</td>
                <td id="subtotal-horas"><strong><?php echo $totalHorasSemanales; ?></strong></td>
                <td id="subtotal-docentes"></td>

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
                                const duration = (end[0] * 60 + end[1]) - (start[0] * 60 +
                                    start[1]);
                                subtotal += duration / 60; // Convertir minutos a horas
                            }
                        });
                    });

                    totalHorasDias += subtotal;
                    document.getElementById(`subtotal-${day}`).textContent = Math.floor(subtotal);
                });

                const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
                weeklyHoursCells.forEach(cell => {
                    const horas = parseInt(cell.textContent.trim(), 10);
                    subtotalHoras += isNaN(horas) ? 0 : horas;
                });

                document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
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
</body>

</html>