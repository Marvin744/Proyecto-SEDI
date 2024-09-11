<?php
include_once '../bd/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Recibir los datos enviados desde el formulario
$folio = $_POST['folio'] ?? '';
$year = $_POST['year'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$titulo_estudios = $_POST['titulo_estudios'] ?? '';
$nombre_docente = $_POST['nombre_completo'] ?? '';

// Recibir las sumas de horas desde los campos hidden
$subtotal_lunes = $_POST['subtotal_lunes'] ?? 0;
$subtotal_martes = $_POST['subtotal_martes'] ?? 0;
$subtotal_miercoles = $_POST['subtotal_miercoles'] ?? 0;
$subtotal_jueves = $_POST['subtotal_jueves'] ?? 0;
$subtotal_viernes = $_POST['subtotal_viernes'] ?? 0;
$subtotal_sabado = $_POST['subtotal_sabado'] ?? 0;
$subtotal_horas = $_POST['subtotal_horas'] ?? 0;

// Consulta los horarios desde la base de datos
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

$queryTableHorarios = $conexion->prepare($sqlHorarios);
$queryTableHorarios->execute();
$array = $queryTableHorarios->fetchAll(PDO::FETCH_ASSOC);

// Generar el contenido del PDF
$contenido = getPlantilla($folio, $year, $fecha, $titulo_estudios, $nombre_docente, $subtotal_lunes, $subtotal_martes, $subtotal_miercoles, $subtotal_jueves, $subtotal_viernes, $subtotal_sabado, $subtotal_horas);

function getPlantilla($folio, $year, $fecha, $docente, $array, $subtotal_lunes, $subtotal_martes, $subtotal_miercoles, $subtotal_jueves, $subtotal_viernes, $subtotal_sabado, $subtotal_horas) {
    $contenido = '
    <body>
        <div class="contenedor">
            <div id="logo">
                <img src="../img/logo_sep_c.png" width="250" height="90">
            </div>
            <div class="columna encabezados">
                <h2>Subsecretaría de Educación Media Superior</h2>
                <h2>Dirección General de Educación Tecnológica Agropecuaria y Ciencias del Mar</h2>
                <h2>Centro de Bachillerato Tecnológico Forestal No. 2</h2>
                <h2>CCT: 10DTA0106J</h2>
            </div>
        </div>

        <div>
            <br><br>
            <h1>“2024, Año de Felipe Carrillo Puerto, Benemérito del Proletariado, Revolucionario y Defensor del Mayab.” </h1>
        </div>
        <br><br>
        <div>
            <p>DEPTO. Académico</p>
            <p>SECCIÓN: Subdirección Académica</p>
            <p>DEGETAYCM/'.$folio.'/'.$year.'</p>
            <p>EXPEDIENTE: Personal</p>
            <p>Asunto: ASIGNACION DE FUNCIONES</p>
            <br><br>
        </div>

        <div>
            <h3>Santiago Papasquiaro, Dgo., a <span>'.$fecha.'</span></h3>
            <br><br>
        </div>

        <div>
            <h4>'.$nombre_docente.'</h4>
            <h4> Docente del plantel </h4>
            <p>Presente</p>
            <br><br>
        </div>

        <div>
            <p>
                En base a su nombramiento de <b>'.$subtotal_horas.' horas</b> que tiene en esta institución, me permito
                por este conducto comunicar el horario que debe cubrir el semestre de <b>Agosto 2024
                Enero 2025</b> en las actividades que le han sido asignadas por esta subdirección
                a mi cargo.
            </p>
            <br><br>
        </div>

        <table border="1" cellpadding="0" cellspacing="0" style="width:100%; text-align:center; font-size:1rem;">
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
                    <th class="f19_th f19_horas-semanales">HORAS SEMANALES</th>
                    <th class="f19_th">SEMESTRE</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($array as $row) {
        $contenido .= '<tr>
            <td class="f19_td">'.($row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura']).'</td>
            <td class="f19_td">'.$row['nombre_semestre'].'</td>
            <td class="f19_td">'.$row['especialidad'].'</td>
            <td class="f19_td">'.$row['lunes'].'</td>
            <td class="f19_td">'.$row['martes'].'</td>
            <td class="f19_td">'.$row['miercoles'].'</td>
            <td class="f19_td">'.$row['jueves'].'</td>
            <td class="f19_td">'.$row['viernes'].'</td>
            <td class="f19_td">'.$row['sabado'].'</td>
            <td class="f19_td">'.$row['horas_semanales'].'</td>
            <td class="f19_td">'.$row['numero_semestre'].'</td>
        </tr>';
    }

    $contenido .= '
            </tbody>
            <tfoot>
                <tr class="f19_header-row">
                    <td class="f19_td" colspan="3"><strong>Subtotal de Horas</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_lunes.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_martes.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_miercoles.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_jueves.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_viernes.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_sabado.'</strong></td>
                    <td class="f19_td"><strong>'.$subtotal_horas.'</strong></td>
                    <td class="f19_td"></td>
                </tr>
            </tfoot>
        </table>

        <div>
            <p>
                Cabe mencionar que el horario y las funciones que le han sido asignadas estarán sujetas a cambios, 
                de acuerdo a las condiciones específicas de trabajo del personal de la <b>DGETAYCM</b> de la <b>SEP</b> y el 
                reglamento de las condiciones generales de trabajo del personal de la <b>SEP</b>, por lo que le ruego se 
                presente ante esta subdirección académica para que le indique sus funciones laborales docentes. 
            </p>
            <br><br>
            <h5>ATENTAMENTE</h5>
            <h5>
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;"><b>M en P. DAVID IGNACIO SEPÚLVEDA</b></td>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;"><b>ING. HUMBERTO FERNÁNDEZ</b></td>
                    </tr>
                </table>
            </h5>
            <h5>
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;"><b>ARELLANO</b></td>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;"><b>SÁNCHEZ</b></td>
                    </tr>
                </table>
            </h5>
            <h5>
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;">SUBDIRECTOR ACADÉMICO</td>
                        <td style="text-align: center; border: none; width: 50%; padding: 0;">DIRECTOR</td>
                    </tr>
                </table>
            </h5>
            <br><br>

            <p>Cc expediente personal</p>
            <p>Cc archivo</p>
            <p>HFS`DISA*dasm</p>
        </div>
    </body>';

    return $contenido;
}

// Crear el PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($contenido);
$mpdf->Output('Horario_Academico.pdf', 'I'); // El argumento 'I' fuerza la descarga del archivo
