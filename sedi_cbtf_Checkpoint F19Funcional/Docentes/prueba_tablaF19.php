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
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }
        .header-row {
            background-color: #d3d3d3; /* Color más oscuro */
        }
    </style>
</head>
<body>
    <h1>Horario Académico</h1>
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
                $sqlHorarios = "SELECT a.nombre_asignatura AS asignatura, 
                                        g.nombre_grupo AS grupo, 
                                        e.nombre_especialidad AS especialidad, 
                                        s.nombre_semestre AS semestre,
                                        GROUP_CONCAT(h.lunes SEPARATOR ' ') AS lunes,
                                        GROUP_CONCAT(h.martes SEPARATOR ' ') AS martes,
                                        GROUP_CONCAT(h.miercoles SEPARATOR ' ') AS miercoles,
                                        GROUP_CONCAT(h.jueves SEPARATOR ' ') AS jueves,
                                        GROUP_CONCAT(h.viernes SEPARATOR ' ') AS viernes,
                                        GROUP_CONCAT(h.sabado SEPARATOR ' ') AS sabado,
                                        SUM(h.horas_semanales) AS horas_semanales,
                                        CONCAT(d.nombre_docente, ' ', d.apellido_paterno, ' ', d.apellido_materno) AS docente
                                    FROM horarios h
                                    JOIN docentes d ON h.id_docente = d.id_docente
                                    JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                                    JOIN grupo g ON h.id_grupo = g.id_grupo
                                    JOIN especialidad e ON g.id_especialidad = e.id_especialidad
                                    JOIN semestre s ON g.id_semestre = s.id_semestre
                                    GROUP BY a.nombre_asignatura, g.nombre_grupo, e.nombre_especialidad, s.nombre_semestre, d.nombre_docente, d.apellido_paterno, d.apellido_materno
                                    ORDER BY g.nombre_grupo ASC;
                            ";
                $queryTableHorarios = $conexion->prepare($sqlHorarios);
                $queryTableHorarios->execute();
                
                foreach ($queryTableHorarios as $row) {
                    echo "<tr>
                            <td>{$row['asignatura']}</td>
                            <td>{$row['semestre']} {$row['grupo']}</td>
                            <td>{$row['especialidad']}</td>
                            <td contenteditable='true' data-day='lunes'>{$row['lunes']}</td>
                            <td contenteditable='true' data-day='martes'>{$row['martes']}</td>
                            <td contenteditable='true' data-day='miercoles'>{$row['miercoles']}</td>
                            <td contenteditable='true' data-day='jueves'>{$row['jueves']}</td>
                            <td contenteditable='true' data-day='viernes'>{$row['viernes']}</td>
                            <td contenteditable='true' data-day='sabado'>{$row['sabado']}</td>
                            <td class='horas-semanales'>{$row['horas_semanales']}</td>
                            <td>{$row['docente']}</td>
                        </tr>";
                }
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
                <td id="subtotal-horas">0</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <button id="verificar-horas">Verificar Horas</button>

    <script>
        document.getElementById('validar-horas').addEventListener('click', function() {
            var totalHoras = 0;
            var cells = document.querySelectorAll('td.horas-semanales');

            cells.forEach(function(cell) {
                var horas = parseInt(cell.textContent);
                if (!isNaN(horas)) {
                    totalHoras += horas;
                }
            });

            document.getElementById('subtotal-horas').textContent = totalHoras;
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('td[contenteditable="true"]');
    
    cells.forEach(cell => {
        cell.addEventListener('blur', function() {
            const id = this.getAttribute('data-id');
            const day = this.getAttribute('data-day');
            const value = this.textContent;

            // Enviar la solicitud AJAX para actualizar la base de datos
            fetch('update_horas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, day, value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Actualización exitosa');
                    updateSubtotals();
                } else {
                    console.error('Error en la actualización');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

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
        let subtotalHoras = 0;
        let totalHorasDias = 0;

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
                        const duration = (endHour * 60 + endMinute) - (startHour * 60 + startMinute);
                        subtotals[day] += duration / 60; // Convertir minutos a horas
                    }
                });
            });
            // Mostrar el subtotal como entero
            document.getElementById(`subtotal-${day}`).textContent = Math.floor(subtotals[day]);
            totalHorasDias += subtotals[day];
        });

        // Calcular el subtotal de horas semanales
        const weeklyHoursCells = document.querySelectorAll('.horas-semanales');
        weeklyHoursCells.forEach(cell => {
            subtotalHoras += parseFloat(cell.textContent) || 0;
        });

        // Mostrar el subtotal de horas semanales
        document.getElementById('subtotal-horas').textContent = Math.floor(subtotalHoras);
    }

    // Llamar a la función para calcular los subtotales al cargar la página
    updateSubtotals();

    // Añadir evento al botón para verificar las horas
    document.getElementById('verificar-horas').addEventListener('click', function() {
        const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal-"]'))
            .slice(0, 6) // Excluir el último subtotal (horas semanales)
            .reduce((total, cell) => total + parseFloat(cell.textContent), 0);

        const subtotalHoras = parseFloat(document.getElementById('subtotal-horas').textContent);

        if (Math.floor(totalHorasDias) === Math.floor(subtotalHoras)) {
            alert('Las horas coinciden.');
        } else {
            alert('Verifique las horas, no coinciden.');
        }
    });
});
    </script>

    <!-- <div>
        <br>
        <table class="display" id="tablaHorarios" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">ID Registro</th>
                    <th scope="col">Nombre Docente</th>
                    <th scope="col">Clases</th>
                    <th scope="col">Asignatura</th>
                    <th scope="col">Horas Totales</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    // $sqlHorarios = ("SELECT * FROM datos_horarios ORDER BY profesor ASC;");
                    // $queryTableHorarios = $conexion->prepare($sqlHorarios);
                    // $queryTableHorarios->execute();
                    
                    // foreach ($queryTableHorarios as $row) {
                    //     $nombreCompleto = htmlspecialchars($row['profesor']);

                    //     echo "<tr>
                    //             <td>{$row['id_datos_horarios']}</td>
                    //             <td>{$row['profesor']}</td>
                    //             <td>{$row['clases']}</td>
                    //             <td>{$row['asignatura']}</td>
                    //             <td>{$row['total_horas']}</td>
                    //             <td>
                    //                 <button class='edit-btn' data-id='{$row['id_datos_horarios']}' data-name='{$nombreCompleto}'>Editar</button>
                    //                 <button class='info-btn' data-id='{$row['id_datos_horarios']}' data-name='{$nombreCompleto}'>Info</button>
                    //                 <!-- <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id_datos_horarios']}'  data-name='{$nombreCompleto}'>Info</button> -->
                    //             </td>
                    //           </tr>";
                    // }
                ?>
            </tbody>
        </table> -->
</body>
</html>