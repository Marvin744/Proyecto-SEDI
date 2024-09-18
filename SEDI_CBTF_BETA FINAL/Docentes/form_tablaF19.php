<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    $id_docente = $_GET['id_docente'];

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo_Docente', 'Administrativo_Jefe']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_activity'])) {

        // Asumiendo que el COUNT devuelve un número bajo el alias 'count'
        $sqlSele = "SELECT COUNT(id_actividad_complementaria) as count FROM actividades_complementarias WHERE id_docente = :id_docente AND (actividad = '' AND detalles_actividad = '')";
        $querySele = $conexion->prepare($sqlSele);
        $querySele->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $querySele->execute();
    
        $resulta = $querySele->fetch(PDO::FETCH_ASSOC);
    
        // Accede al valor real del COUNT
        if ($resulta['count'] < 1) {
            $sql_insert = "INSERT INTO actividades_complementarias (id_docente, actividad, detalles_actividad, lunes, martes, miercoles, jueves, viernes, sabado, horas_semanales)
            VALUES (:id_docente, '', '', '', '', '', '', '', '', 0)";
            $querysql_insert = $conexion->prepare($sql_insert);
            $querysql_insert->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
    
            if ($querysql_insert->execute()) {
                $url_redireccion = 'form_tablaF19.php?id_docente=' . $id_docente;
                echo "<script>
                alert('Nueva actividad insertada exitosamente.');
                window.location.href = '$url_redireccion';
                </script>";
            } else {
                $url_redireccion = 'form_tablaF19.php?id_docente=' . $id_docente;
                echo "<script>
                alert('No se pudo insertar la actividad, error de código.');
                window.location.href = '$url_redireccion';
                </script>";
            }
        } else {
            $url_redireccion = 'form_tablaF19.php?id_docente=' . $id_docente;
            echo "<script>
            alert('Ya hay un espacio vacío para llenar una Actividad, favor de llenarlo antes de insertar otra.');
            window.location.href = '$url_redireccion';
            </script>";
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
        WHERE h.id_docente = $id_docente
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
    $prevSubmodulo = '';
    $rowspan = 1;
    $totalHorasSemanales = 0; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato 19</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


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

        /* Estilo para los botones */
        .btn-azul {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-azul:hover {
            background-color: #0056b3;
        }

        .btn-verde {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-verde:hover {
            background-color: #218838;
        }

        .btn-rojo {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-rojo:hover {
            background-color: #c82333;
        }

        .btn-gris {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-gris:hover {
            background-color: #5a6268;
        }

        .btn-rojo {
            background-color: #ff4d4d; /* Rojo */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.8rem;
            display: inline-flex;
            align-items: center;
        }

        .btn-rojo i {
            margin-right: 8px; /* Espacio entre el ícono y el texto */
        }

        .btn-rojo:hover {
            background-color: #e60000; /* Rojo más oscuro al pasar el mouse */
        }

        .button-container {
            width: 100%;
            margin-bottom: 20px; /* Espacio inferior entre los botones y la tabla */
            position: relative; /* Para asegurar que el botón de verificar horas se alinee correctamente */
        }

        .btn-azul {
            font-size: 1.8rem;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-verde {
            font-size: 1.8rem;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: absolute;
            right: 0; /* Alinea el botón a la derecha del contenedor */
            top: 0;
        }

        .btn-verde:hover {
            background-color: #218838;
        }

        /* Estilo inputs último formulario */
        .datos_f19 form {
            display: block; /* Cambia a block para permitir alineación natural a la izquierda */
        }

        .datos_f19 button {
            margin-top: 20px;
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.8rem;
            display: inline-flex;
            align-items: center;
            max-width: 200px; /* Establece un ancho máximo para el botón */
        }

        .datos_f19 button i {
            margin-right: 8px; /* Mantén el espacio entre el ícono y el texto */
        }

        .datos_f19 input[type="text"],
        .datos_f19 input[type="number"],
        .datos_f19 input[type="date"] {
            font-size: 1.8rem;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 20%; /* Ajustamos el ancho para que los inputs sean más cortos */
            transition: border-color 0.3s ease;
            margin-bottom: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .datos_f19 input[type="text"]:focus,
        .datos_f19 input[type="number"]:focus,
        .datos_f19 input[type="date"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .datos_f19 label {
            margin-right: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    <h1 style="font-size: 2.4rem; font-weight: bold; text-align: center;">HORARIO ACADÉMICO DE : <?php echo $nombre_docente; ?></h1>
    <p style="font-size: 2rem; margin-top: 1rem;">A continuación se presentan las tablas del Formato 19 del docente, en la cual puede ingresar las horas en los recuadros en blanco. En el caso de las actividades 
        complementarias toda la tabla es editable pero cuenta igualmente con las validaciones correspondientes. Asegúrese de INSERTAR CORRECTAMENTE la información requerida.
    </p><br>
    <p style="font-size: 1.6rem;">NOTA: Corrobore los datos de las tablas y del último formulario (datos extra del formato como horas de contrato del docente) antes de generar el PDF.</p>
    <br>
    <h1 style="font-weight: bold;">Horario Académico</h1>

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


                    if ($row['asignatura'] === $prevAsignatura && $row['submodulos'] === $prevSubmodulo) {
                        $rowspan++;
                        echo "<tr>";
                        echo "<td class='f19_td' style='display:none;'></td>";
                    } else {
                        // Si ya hemos agrupado filas, aplicar el rowspan a la asignatura anterior
                        if ($rowspan > 1) {
                            echo "<script>
                            document.querySelector('td[data-asignatura=\"$prevAsignatura\"]').rowSpan = $rowspan;
                            </script>";
                        }

                        $rowspan = 1;
                        $prevAsignatura = $row['asignatura'];
                        $prevSubmodulo = $row['submodulos'];

                        // Mostrar la asignatura con submódulos si existen
                        $asignaturaCompleta = $row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura'];

                        // echo "<td class='f19_td' data-asignatura='{$row['asignatura']}'><strong>{$asignaturaCompleta}</strong></td>";

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

                // Al final del ciclo, si hubo agrupación, aplicamos el rowspan final
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

    <!-- -------------------------------------------------------------- TABLA ACTIVIDADES COMPLEMENTARIAS ----------------------------------------------- -->

    <h1 style="font-weight: bold;">Horario Actividades Complementarias</h1>

    <div class="button-container">
        <form method="post" id="actividadForm" style="display: inline-block;">
            <button id="insertarActividadBtn" type="submit" class="btn-azul" name="insert_activity">
                <i class="fas fa-plus"></i> <!-- Ícono de agregar -->
            </button>
        </form>

        <button id="verificar-horas" class="btn-verde float-right">Verificar Horas</button>
    </div>

    <table class="f19_table">
        <thead>
            <tr class="f19_header-row">
                <th class="f19_th" colspan="2">Carga Académica</th>
                <th class="f19_th" colspan="7">Horario</th>
            </tr>

            <tr>
                <th class="f19_th">ACTIVIDAD</th>
                <th class="f19_th">DETALLES ACTIVIDAD</th>
                <th class="f19_th">LUNES</th>
                <th class="f19_th">MARTES</th>
                <th class="f19_th">MIÉRCOLES</th>
                <th class="f19_th">JUEVES</th>
                <th class="f19_th">VIERNES</th>
                <th class="f19_th">SÁBADO</th>
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
                                WHERE a.id_docente = $id_docente
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
                // $totalHorasSemanales = 0; // Inicializar variable para el total de horas semanales

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
                <td class="f19_td" colspan="2"><strong>Subtotal de Horas</strong></td>
                <td class="f19_td" id="subtotal1-lunes">0</td>
                <td class="f19_td" id="subtotal1-martes">0</td>
                <td class="f19_td" id="subtotal1-miercoles">0</td>
                <td class="f19_td" id="subtotal1-jueves">0</td>
                <td class="f19_td" id="subtotal1-viernes">0</td>
                <td class="f19_td" id="subtotal1-sabado">0</td>
                <td class="f19_td" id="subtotal1-horas"><strong>0</strong></td>
            </tr>
            <tr class="f19_header-row">
                <td class="f19_td" colspan="2">Total de Horas</td>
                <td class="f19_td" id="total1-lunes-total">0</td>
                <td class="f19_td" id="total1-martes-total">0</td>
                <td class="f19_td" id="total1-miercoles-total">0</td>
                <td class="f19_td" id="total1-jueves-total">0</td>
                <td class="f19_td" id="total1-viernes-total">0</td>
                <td class="f19_td" id="total1-sabado-total">0</td>
                <td class="f19_td" id="total1-horas-total" value="<?php echo $totalHorasSemanales; ?>">
                    <strong><?php echo $totalHorasSemanales; ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <br>

    

    <!-- Formulario con el resto de información -->
    <div class="datos_f19">
        <form action="plantilla_f19.php" method="post" target="_blank">
            <div>
                <label for="">Número de Oficio: </label>
                <input type="number" name="folio" id="folio"><br>

                <label for="fecha">Fecha: </label>
                <input id="fecha" name="fecha" class="fecha" type="date"><br>

                <label for="">Periodo: </label>
                <input type="text" name="periodo" id="periodo"> <br>

                <label for="">Horas: </label>
                <input type="text" name="horas" id="horas" value="<?php echo $totalHorasSemanales ?>"><br>

                
                <input type="hidden" name="titulo_estudios" value="<?php echo $titulo_estudios ?>">
                <input type="hidden" name="nombre_docente" value="<?php echo $nombre_docente ?>">
                <input type="hidden" name="id_docente" value="<?php echo $id_docente ?>">
            </div>
            

            <!-- Campos hidden para pasar las sumas de horas -->
            <input type="hidden" id="hiddenSubtotalLunes" name="subtotal_lunes">
            <input type="hidden" id="hiddenSubtotalMartes" name="subtotal_martes">
            <input type="hidden" id="hiddenSubtotalMiercoles" name="subtotal_miercoles">
            <input type="hidden" id="hiddenSubtotalJueves" name="subtotal_jueves">
            <input type="hidden" id="hiddenSubtotalViernes" name="subtotal_viernes">
            <input type="hidden" id="hiddenSubtotalSabado" name="subtotal_sabado">
            <!-- <input type="hidden" id="hiddenSubtotalHoras" name="subtotal_horas"> -->

            <input type="hidden" id="hiddenSubtotalLunes1" name="subtotal1_lunes">
            <input type="hidden" id="hiddenSubtotalMartes1" name="subtotal1_martes">
            <input type="hidden" id="hiddenSubtotalMiercoles1" name="subtotal1_miercoles">
            <input type="hidden" id="hiddenSubtotalJueves1" name="subtotal1_jueves">
            <input type="hidden" id="hiddenSubtotalViernes1" name="subtotal1_viernes">
            <input type="hidden" id="hiddenSubtotalSabado1" name="subtotal1_sabado">
            <input type="hidden" id="hiddenSubtotalHoras" name="subtotal1_horas">

            <input type="hidden" id="hiddentotalLunes1" name="total1_lunes">
            <input type="hidden" id="hiddentotalMartes1" name="total1_martes">
            <input type="hidden" id="hiddentotalMiercoles1" name="total1_miercoles">
            <input type="hidden" id="hiddentotalJueves1" name="total1_jueves">
            <input type="hidden" id="hiddentotalViernes1" name="total1_viernes">
            <input type="hidden" id="hiddentotalSabado1" name="total1_sabado">
            <input type="hidden" id="hiddenTotal1Horas" name="total1_horas1">

            <button type="submit" class="btn-rojo">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
        </form>
    </div>
    <script>
        // Validar al INSERTAR NUEVA FILA
        document.getElementById('actividadForm').addEventListener('submit', function(event) {
            // Lanzar la ventana de confirmación
            var confirmacion = confirm("¿Estás seguro de que deseas insertar una nueva actividad?");

            // Si el usuario cancela, prevenir el envío del formulario
            if (!confirmacion) {
                event.preventDefault(); // Evita que se envíe el formulario
            }
        });

        // Añadir evento al botón para verificar las horas
        document.getElementById('verificar-horas').addEventListener('click', function() {

            // Sumar las horas de los días de la semana (lunes a sábado)
            const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal-"]'))
                .slice(0, 6) // Solo considerar los días de lunes a sábado
                .reduce((total, td) => total + parseInt(td.textContent || 0, 10), 0);

            // Obtener el total de horas semanales desde el campo correspondiente
            const totalHorasSemanales = parseInt(document.getElementById('subtotal-horas').textContent || 0, 10);

            if (totalHorasDias === totalHorasSemanales) {
                alert('Las horas en CARGA ACADÉMICA coinciden correctamente.');
            } else {
                alert('Las horas en CARGA ACADÉMICA no coinciden. Por favor, revise los horarios.');
            }

        });

        // 
        document.querySelector('form[action="plantilla_f19.php"]').addEventListener('submit', function() {
            // Actualizar los campos hidden antes de enviar el formulario
            document.getElementById('hiddenSubtotalLunes').value = document.getElementById('subtotal-lunes')
                .textContent;
            document.getElementById('hiddenSubtotalMartes').value = document.getElementById('subtotal-martes')
                .textContent;
            document.getElementById('hiddenSubtotalMiercoles').value = document.getElementById('subtotal-miercoles')
                .textContent;
            document.getElementById('hiddenSubtotalJueves').value = document.getElementById('subtotal-jueves')
                .textContent;
            document.getElementById('hiddenSubtotalViernes').value = document.getElementById('subtotal-viernes')
                .textContent;
            document.getElementById('hiddenSubtotalSabado').value = document.getElementById('subtotal-sabado')
                .textContent;
            // document.getElementById('hiddenSubtotalHoras').value = document.getElementById('subtotal-horas')
            //     .textContent;

            document.getElementById('hiddenSubtotalLunes1').value = document.getElementById('subtotal1-lunes')
                .textContent;
            document.getElementById('hiddenSubtotalMartes1').value = document.getElementById('subtotal1-martes')
                .textContent;
            document.getElementById('hiddenSubtotalMiercoles1').value = document.getElementById(
                    'subtotal1-miercoles')
                .textContent;
            document.getElementById('hiddenSubtotalJueves1').value = document.getElementById('subtotal1-jueves')
                .textContent;
            document.getElementById('hiddenSubtotalViernes1').value = document.getElementById('subtotal1-viernes')
                .textContent;
            document.getElementById('hiddenSubtotalSabado1').value = document.getElementById('subtotal1-sabado')
                .textContent;
            document.getElementById('hiddenSubtotalHoras').value = document.getElementById('subtotal1-horas')
                .textContent;

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
            if (!fechaInput.value) {
                fechaInput.value = fechaFormateada;
            }
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

                    document.getElementById(`subtotal-${day}`).textContent = Math.floor(subtotal);

                    // Actualizar campos hidden para enviar al PDF
                    document.getElementById(`hiddenSubtotal${day.charAt(0).toUpperCase() + day.slice(1)}`)
                        .value = Math.floor(subtotal);
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
                        alert(
                            `Los horarios se empalman en ${day}. El campo que acabas de modificar se limpiará para que puedas corregirlo.`
                        );

                        // Limpiar el contenido de la celda editada (no el de la celda empalmada)
                        editedCell.textContent = '';

                        // Limpiar el contenido en la base de datos
                        const ids = editedCell.getAttribute('data-id').split(
                            ','); // Obtener todos los ids_horario

                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {

                                if (data.success) {
                                    console.log('Actualización exitosa para ID:',
                                        id);
                                    updateSubtotals
                                        (); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });

                        return;
                    }

                    const ids = this.getAttribute('data-id').split(
                        ','); // Obtener todos los ids_horario
                    const value = this.textContent.trim();

                    // Validar el formato del rango de horas (ejemplo: 08:00-10:00 o múltiples rangos)
                    const timeRangePattern =
                        /^(\d{2}:\d{2}-\d{2}:\d{2})( \d{2}:\d{2}-\d{2}:\d{2}){0,3}$/;

                    if (value === "" || timeRangePattern.test(value)) {
                        // Si la validación de solapamiento y formato es exitosa, proceder con la actualización
                        ids.forEach(id => {
                            updateDatabase(id, day, value).then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:',
                                        id);
                                    updateSubtotals();
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });
                    } else {
                        alert(
                            'Formato de rango de horas inválido. El campo se limpiará para que puedas corregirlo.'
                        );
                        this.textContent =
                            ''; // Limpiar el contenido de la celda actual si el formato es inválido

                        // Limpiar el contenido en la base de datos
                        ids.forEach(id => {
                            updateDatabase(id, day, '').then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:',
                                        id);
                                    updateSubtotals
                                        (); // Actualizar los subtotales después de limpiar
                                } else {
                                    console.error(
                                        'Error en la actualización para ID:',
                                        id, data.error || data.message);
                                }
                            });
                        });
                    }

                    updateSubtotals
                (); // <- Llamada extra para asegurarnos de actualizar los subtotales después de cada edición
                    updateTotalGeneral();
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

    <!-- ----------------------------------------------------ACTIVIDADES COMPLEMENTARIAS-------------------------------------------------------------------- -->

    <script>
        // document.addEventListener('click', function(event) {
        //     // Verifica si el clic fue fuera de ambas tablas
        //     const table1 = document.querySelector('table:nth-of-type(1)'); // Primera tabla
        //     const table2 = document.querySelector('table:nth-of-type(2)'); // Segunda tabla

        //     if (!table1.contains(event.target) && !table2.contains(event.target)) {
        //         // Si el clic fue fuera de ambas tablas, recarga la página
        //         location.reload();
        //     }
        // });

        // Añadir evento al botón para verificar las horas
        // Añadir evento al botón para verificar las horas

        document.getElementById('verificar-horas').addEventListener('click', function() {

            // Sumar las horas de los días de la semana (lunes a sábado)
            const totalHorasDias = Array.from(document.querySelectorAll('tfoot td[id^="subtotal1-"]'))
                .slice(0, 6) // Solo considerar los días de lunes a sábado
                .reduce((total, td) => total + parseInt(td.textContent || 0, 10), 0);

            // Obtener el total de horas semanales desde el campo correspondiente
            const totalHorasSemanales1 = parseInt(document.getElementById('subtotal1-horas').textContent || 0, 10);
            const totalHorasSemanales = parseInt(document.getElementById('subtotal-horas').textContent || 0, 10);
            const totalHorasGeneral = parseInt(document.getElementById('total1-horas-total').textContent || 0, 10);
            const HS = totalHorasDias + totalHorasSemanales;

            if (HS === totalHorasGeneral) {
                alert('Las horas en ACTIVIDADES COMPLEMENTARIAS coinciden correctamente.');
            } else {
                alert('Las horas NO coinciden en ACTIVIDADES COMPLEMENTARIAS. Por favor, revise los horarios.');
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
                            ranges.push({
                                start,
                                end,
                                cell
                            });
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

            // Función para actualizar los subtotales de los días y la columna de Horas en la segunda tabla (Actividades Complementarias)
            function updateSubtotalsAct() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                let subtotalHorasSemanales = 0;

                // Sumar los valores de la columna "Horas" (horas semanales) solo de la segunda tabla
                document.querySelectorAll('table:nth-of-type(2) td.horas-semanales').forEach(cell => {
                    const horas = parseFloat(cell.textContent.trim()) || 0;
                    subtotalHorasSemanales += horas;
                });

                // Asignar la suma total de la columna "Horas" de la segunda tabla a la fila de Subtotal de Horas
                document.getElementById('subtotal1-horas').textContent = Math.floor(subtotalHorasSemanales);

                // Sumar las horas de los días de la semana solo de la segunda tabla
                days.forEach(day => {
                    const dayCells = document.querySelectorAll(
                        `table:nth-of-type(2) td[data-day1="${day}"]`);
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

                    // Mostrar el subtotal como entero en la fila de subtotales de la segunda tabla
                    document.getElementById(`subtotal1-${day}`).textContent = Math.floor(subtotal);
                });
            }

            // Función para sumar las horas de ambas tablas y mostrar el total en la fila "Total de Horas" en la segunda tabla
            function updateTotalHorasDias() {
                const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

                days.forEach(day => {
                    // Inicializamos las variables para las sumas de ambas tablas
                    let totalHorasTabla1 = 0;
                    let totalHorasTabla2 = 0;

                    // Sumar las horas de la primera tabla (académica)
                    document.querySelectorAll(`table:nth-of-type(1) td[data-day="${day}"]`).forEach(
                        cell => {
                            const cellText = cell.textContent.trim();
                            if (cellText) {
                                const ranges = cellText.split(' ');
                                ranges.forEach(range => {
                                    const times = range.split('-');
                                    if (times.length === 2) {
                                        const start = times[0].split(':').map(Number);
                                        const end = times[1].split(':').map(Number);
                                        const duration = (end[0] * 60 + end[1]) - (start[0] *
                                            60 + start[1]);
                                        totalHorasTabla1 += duration /
                                            60; // Convertimos minutos a horas
                                    }
                                });
                            }
                        });

                    // Sumar las horas de la segunda tabla (actividades complementarias)
                    document.querySelectorAll(`table:nth-of-type(2) td[data-day1="${day}"]`).forEach(
                        cell => {
                            const cellText = cell.textContent.trim();
                            if (cellText) {
                                const ranges = cellText.split(' ');
                                ranges.forEach(range => {
                                    const times = range.split('-');
                                    if (times.length === 2) {
                                        const start = times[0].split(':').map(Number);
                                        const end = times[1].split(':').map(Number);
                                        const duration = (end[0] * 60 + end[1]) - (start[0] *
                                            60 + start[1]);
                                        totalHorasTabla2 += duration /
                                            60; // Convertimos minutos a horas
                                    }
                                });
                            }
                        });

                    // Sumar los resultados de ambas tablas y mostrar el total en la fila de "Total de Horas" de la segunda tabla
                    const totalHorasDia = Math.floor(totalHorasTabla1 + totalHorasTabla2);
                    document.getElementById(`subtotal1-${day}-total`).textContent =
                        totalHorasDia; // Mostrar el total como entero
                });

                // Calcular el total de horas semanales de ambas tablas
                updateTotalHorasSemanal();
            }

            // Función para sumar las horas semanales de ambas tablas y actualizar el total
            function updateTotalHorasSemanal() {
                let totalHorasTabla1 = 0;
                let totalHorasTabla2 = 0;

                // Sumar las horas semanales de la primera tabla (académica)
                document.querySelectorAll('table:nth-of-type(1) td.horas-semanales').forEach(cell => {
                    totalHorasTabla1 += parseFloat(cell.textContent.trim()) || 0;
                });

                // Sumar las horas semanales de la segunda tabla (actividades complementarias)
                document.querySelectorAll('table:nth-of-type(2) td.horas-semanales').forEach(cell => {
                    totalHorasTabla2 += parseFloat(cell.textContent.trim()) || 0;
                });

                // Mostrar el total de horas semanales en la fila de "Total de Horas" de la segunda tabla
                const totalHorasSemanales = Math.floor(totalHorasTabla1 + totalHorasTabla2);
                document.getElementById('total1-horas-total').textContent =
                totalHorasSemanales; // Mostrar el total como entero
            }

            // Llamar a las funciones cuando la página cargue y después de cada cambio en las celdas editables
            document.addEventListener('DOMContentLoaded', function() {
                updateSubtotalsAct();
                updateTotalHorasDias();

                // Asegurarnos de que las funciones se llamen correctamente cuando cambien las horas
                document.querySelectorAll('td[contenteditable="true"]').forEach(cell => {
                    cell.addEventListener('input', function() {
                        updateSubtotalsAct();
                        updateTotalHorasDias();
                        updateTotalHorasSemanal();
                    });
                });
            });

            function updateTotalGeneral() {
                var lun1 = document.getElementById('subtotal-lunes').textContent;
                var lun2 = document.getElementById('subtotal1-lunes').textContent;
                var mar1 = document.getElementById('subtotal-martes').textContent;
                var mar2 = document.getElementById('subtotal1-martes').textContent;
                var mier1 = document.getElementById('subtotal-miercoles').textContent;
                var mier2 = document.getElementById('subtotal1-miercoles').textContent;
                var jue1 = document.getElementById('subtotal-jueves').textContent;
                var jue2 = document.getElementById('subtotal1-jueves').textContent;
                var vie1 = document.getElementById('subtotal-viernes').textContent;
                var vie2 = document.getElementById('subtotal1-viernes').textContent;
                var sab1 = document.getElementById('subtotal-sabado').textContent;
                var sab2 = document.getElementById('subtotal1-sabado').textContent;
                var tlun = parseInt(lun1) + parseInt(lun2);
                var tmar = parseInt(mar1) + parseInt(mar2);
                var tmier = parseInt(mier1) + parseInt(mier2);
                var tjue = parseInt(jue1) + parseInt(jue2);
                var tvie = parseInt(vie1) + parseInt(vie2);
                var tsab = parseInt(sab1) + parseInt(sab2);
                document.getElementById('total1-lunes-total').textContent = tlun;
                document.getElementById('total1-martes-total').textContent = tmar;
                document.getElementById('total1-miercoles-total').textContent = tmier;
                document.getElementById('total1-jueves-total').textContent = tjue;
                document.getElementById('total1-viernes-total').textContent = tvie;
                document.getElementById('total1-sabado-total').textContent = tsab;

                document.querySelector('form[action="plantilla_f19.php"]').addEventListener('submit', function() {

                    document.getElementById('hiddentotalLunes1').value = document.getElementById(
                            'total1-lunes-total')
                        .textContent;
                    document.getElementById('hiddentotalMartes1').value = document.getElementById(
                            'total1-martes-total')
                        .textContent;
                    document.getElementById('hiddentotalMiercoles1').value = document.getElementById(
                            'total1-miercoles-total')
                        .textContent;
                    document.getElementById('hiddentotalJueves1').value = document.getElementById(
                            'total1-jueves-total')
                        .textContent;
                    document.getElementById('hiddentotalViernes1').value = document.getElementById(
                            'total1-viernes-total')
                        .textContent;
                    document.getElementById('hiddentotalSabado1').value = document.getElementById(
                            'total1-sabado-total')
                        .textContent;

                    var total1 = document.getElementById('subtotal-horas').textContent;
                    var total2 = document.getElementById('subtotal1-horas').textContent;
                    var totalT = parseInt(total1) + parseInt(total2);

                    document.getElementById('hiddenTotal1Horas').value = totalT;
                });
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
                        alert(
                            `Los horarios se solapan en ${dato}. El campo que acabas de modificar se limpiará para que puedas corregirlo.`
                        );
                        this.textContent = ''; // Limpiar el contenido de la celda actual

                        // Limpiar el contenido en la base de datos
                        updateDatabase(id, dato, '').then(data => {
                            if (data.success) {
                                console.log('Actualización exitosa para ID:', id);
                                updateSubtotalsAct();
                            } else {
                                console.error('Error en la actualización para ID:', id, data
                                    .error || data.message);
                            }
                        });
                        return; // Salir si hay solapamiento
                    }

                    if (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'].includes(
                            dato)) {
                        // Validar el formato del rango de horas antes de actualizar
                        if (value === "" || timeRangePattern.test(value)) {
                            // Si el formato es válido, actualizar en la base de datos
                            updateDatabase(id, dato, value).then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotalsAct();
                                    updateTotalHorasSemanal();
                                } else {
                                    console.error('Error en la actualización para ID:', id,
                                        data.error || data.message);
                                }
                            });
                        } else {
                            alert(
                                'Formato de rango de horas inválido. El campo se limpiará para que puedas corregirlo.'
                            );
                            this.textContent =
                                ''; // Limpiar el contenido de la celda actual si el formato es inválido

                            // Limpiar el contenido en la base de datos
                            updateDatabase(id, dato, '').then(data => {
                                if (data.success) {
                                    console.log('Actualización exitosa para ID:', id);
                                    updateSubtotalsAct();
                                    updateTotalHorasSemanal();
                                } else {
                                    console.error('Error en la actualización para ID:', id,
                                        data.error || data.message);
                                }
                            });
                        }
                    } else if (['actividad', 'detalles_actividad', 'horas_semanales'].includes(
                            dato)) {
                        // Actualizar actividad y detalles_actividad directamente sin validación
                        updateDatabase(id, dato, value).then(data => {
                            if (data.success) {
                                console.log('Actualización exitosa para ID:', id);
                            } else {
                                console.error('Error en la actualización para ID:', id, data
                                    .error || data.message);
                            }
                        });
                    }
                    updateSubtotalsAct();
                    updateTotalGeneral();
                    updateTotalHorasSemanal();
                });
            });

            // Inicializar subtotales al cargar la página
            updateSubtotalsAct();
            updateTotalGeneral();
            updateTotalHorasSemanal();

        });
    </script>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"; ?>