<?php
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "Pablo";

$ano = date("Y", strtotime($_POST['fecha']));   // Año en formato 4 dígitos, por ejemplo, 2024
$mes = date("m", strtotime($_POST['fecha']));   // Mes en formato 2 dígitos, por ejemplo, 07
$dia = date("d", strtotime($_POST['fecha']));   // dias en formato 2 digitos, por ejemplo, 26

switch($mes){
    case '01':
        $mesNombre = 'Enero';
        break;
    case '02':
        $mesNombre = 'Febrero';
        break;
    case '03':
        $mesNombre = 'Marzo';
        break;
    case '04':
        $mesNombre = 'Abril';
        break;
    case '05':
        $mesNombre = 'Mayo';
        break;
    case '06':
        $mesNombre = 'Junio';
        break;
    case '07':
        $mesNombre = 'Julio';
        break;
    case '08':
        $mesNombre = 'Agosto';
        break;
    case '09':
        $mesNombre = 'Septiembre';
        break;
    case '10':
        $mesNombre = 'Octubre';
        break;
    case '11':
        $mesNombre = 'Noviembre';
        break;
    case '12':
        $mesNombre = 'Diciembre';
        break;
}

$folio = isset($_POST['folio']) ? $_POST['folio'] : "Pablo";
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "Pablo";
$titulo = isset($_POST['titulo_estudios']) ? $_POST['titulo_estudios'] : "Pablo";
$docente = isset($_POST['nombre_docente']) ? $_POST['nombre_docente'] : "Pablo";
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : "Pablo";
$horas = isset($_POST['horas']) ? $_POST['horas'] : "Pablo";


$subtotal_lunes = isset($_POST['subtotal_lunes']) ? $_POST['subtotal_lunes'] : 0;
$subtotal_martes = isset($_POST['subtotal_martes']) ? $_POST['subtotal_martes'] : 0;
$subtotal_miercoles = isset($_POST['subtotal_miercoles']) ? $_POST['subtotal_miercoles'] : 0;
$subtotal_jueves = isset($_POST['subtotal_jueves']) ? $_POST['subtotal_jueves'] : 0;
$subtotal_viernes = isset($_POST['subtotal_viernes']) ? $_POST['subtotal_viernes'] : 0;
$subtotal_sabado = isset($_POST['subtotal_sabado']) ? $_POST['subtotal_sabado'] : 0;
// $subtotal_horas = isset($_POST['subtotal_horas']) ? $_POST['subtotal_horas'] : 0;

$subtotal_lunes1 = isset($_POST['subtotal1_lunes']) ? $_POST['subtotal1_lunes'] : 0;
$subtotal_martes1 = isset($_POST['subtotal1_martes']) ? $_POST['subtotal1_martes'] : 0;
$subtotal_miercoles1 = isset($_POST['subtotal1_miercoles']) ? $_POST['subtotal1_miercoles'] : 0;
$subtotal_jueves1 = isset($_POST['subtotal1_jueves']) ? $_POST['subtotal1_jueves'] : 0;
$subtotal_viernes1 = isset($_POST['subtotal1_viernes']) ? $_POST['subtotal1_viernes'] : 0;
$subtotal_sabado1 = isset($_POST['subtotal1_sabado']) ? $_POST['subtotal1_sabado'] : 0;
$subtotal_horas1 = isset($_POST['subtotal1_horas']) ? $_POST['subtotal1_horas'] : 0;

$total_lunes1 = isset($_POST['total1_lunes']) ? $_POST['total1_lunes'] : 0;
$total_martes1 = isset($_POST['total1_martes']) ? $_POST['total1_martes'] : 0;
$total_miercoles1 = isset($_POST['total1_miercoles']) ? $_POST['total1_miercoles'] : 0;
$total_jueves1 = isset($_POST['total1_jueves']) ? $_POST['total1_jueves'] : 0;
$total_viernes1 = isset($_POST['total1_viernes']) ? $_POST['total1_viernes'] : 0;
$total_sabado1 = isset($_POST['total1_sabado']) ? $_POST['total1_sabado'] : 0;
$total_horas1 = isset($_POST['total1_horas1']) ? $_POST['total1_horas1'] : 0;


function getPlantilla($folio,
                        $fecha,
                        $titulo,
                        $docente,
                        $horas,
                        $array,
                        $array2,
                        $dia,
                        $mesNombre,
                        $ano,
                        $periodo,
                        $subtotal_lunes,
                        $subtotal_martes,
                        $subtotal_miercoles,
                        $subtotal_jueves,
                        $subtotal_viernes,
                        $subtotal_sabado,
                        $subtotal_lunes1,
                        $subtotal_martes1,
                        $subtotal_miercoles1,
                        $subtotal_jueves1,
                        $subtotal_viernes1,
                        $subtotal_sabado1,
                        $subtotal_horas1,
                        $total_lunes1,
                        $total_martes1,
                        $total_miercoles1,
                        $total_jueves1,
                        $total_viernes1,
                        $total_sabado1,
                        $total_horas1){
    $contenido = '
    <body>
        <div class="contenedor">
            <div id="logo">
                <img src="../img/logo_sep_c.png" width="250" height="60">
            </div>
            <div class="columna encabezados">
                <h2>Subsecretaría de Educación Media Superior</h2>
                <h2>Dirección General de Educación Tecnológica Agropecuaria y Ciencias del Mar</h2>
                <h2>Centro de Bachillerato Tecnológico Forestal No. 2</h2>
                <h2>CCT: 10DTA0106J</h2>
            </div>
        </div>

        <div>
            
            <h1>“2024, Año de Felipe Carrillo Puerto, Benemérito del Proletariado, Revolucionario y Defensor del Mayab.” </h1>
        </div>
        <br></br>
        <div>
            <p>DEPTO. Académico</p>
            <p>SECCIÓN: Subdirección Académica</p>
            <p>DEGETAYCM/'.$folio.'/'.$ano.'</p>
            <p>EXPEDIENTE: Personal</p>
            <p>Asunto: ASIGNACION DE FUNCIONES</p>
        </div>

        <div>
            <h3>Santiago Papasquiaro, Dgo., a <span>'.$dia.' de '.$mesNombre.' del '.$ano.'</span></h3>
        </div>

        <div>
            <h4>'.$titulo." ".$docente.'</h4>
            <h4> Docente del plantel </h4>
            <p>Presente</p>
            <br> 
        </div>

        <div>
            <p>
                En base a su nombramiento de <b>'.$horas.' horas</b> que tiene en esta institución, me permito
                por este conducto comunicar el horario que deberá cubrir en el semestre de <b>'.$periodo.'</b> en las actividades que le han sido asignadas por esta subdirección
                a mi cargo.
            </p>
            <br>
        </div>

        <!-- TABLA CARGA ACADEMICA --> 
        
         <table border="1" cellpadding="0" cellspacing="0" style="width:100%; text-align:center; font-size:0.8rem;">
            <thead>
                <tr class="f19_header-row oscuro" >
                    <th style="font-weight: bold;" colspan="3">CARGA ACADÉMICA</th>
                    <th style="font-weight: bold;" colspan="8">HORARIO</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">ASIGNATURA</th>
                    <th style="font-weight: bold;">GRUPO</th>
                    <th style="font-weight: bold;">ESPECIALIDAD</th>
                    <th style="font-weight: bold;">LUNES</th>
                    <th style="font-weight: bold;">MARTES</th>
                    <th style="font-weight: bold;">MIÉRCOLES</th>
                    <th style="font-weight: bold;">JUEVES</th>
                    <th style="font-weight: bold;">VIERNES</th>
                    <th style="font-weight: bold;">SÁBADO</th>
                    <th style="font-weight: bold;">HORAS</th>
                    <th style="font-weight: bold;">SEMESTRE</th>
                </tr>
            </thead>
            <tbody>';

    $prevAsignatura = '';
    $rowspan = 1;
    $totalHorasSemanales = 0;

    foreach ($array as $row) {
        $totalHorasSemanales += $row['horas_semanales'];

         
        if ($rowspan > 1) {
            // Ajuste de rowspan para la asignatura previa
            $contenido = str_replace("<!-- rowspan -->", 'rowspan="' . $rowspan . '"', $contenido);
        }
        $rowspan = 1;
        $prevAsignatura = $row['asignatura'];

        $asignaturaCompleta = $row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura'];

        $contenido .= "<tr>";
        $contenido .= "<td class='negritas' style='text-align: center; padding: 2px 2px;' data-asignatura='{$row['asignatura']}'>{$asignaturaCompleta}</td>";
        

        $contenido .= "<td style='text-align: center; padding: 1px 1px;'>{$row['nombre_semestre']} {$row['grupo']}</td>";
        $contenido .= "<td style='text-align: center; padding: 1px 1px;'>{$row['especialidad']}</td>";
        $contenido .= "<td class='negritas'>{$row['lunes']}</td>";
        $contenido .= "<td class='negritas'>{$row['martes']}</td>";
        $contenido .= "<td class='negritas'>{$row['miercoles']}</td>";
        $contenido .= "<td class='negritas'>{$row['jueves']}</td>";
        $contenido .= "<td class='negritas'>{$row['viernes']}</td>";
        $contenido .= "<td class='negritas'>{$row['sabado']}</td>";
        $contenido .= "<td class='negritas' style='text-align: center;'>{$row['horas_semanales']}</td>";
        $contenido .= "<td class='negritas' style='text-align: center;'>{$row['numero_semestre']}</td>";
        $contenido .= "</tr>";
    }

    $contenido .= '
            </tbody>
            <tfoot>
                <tr class="oscuro">
                    <td style="font-weight: bold; padding: 1px 1px;" colspan="3"><strong>Subtotal de Horas</strong></td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-lunes">'.$subtotal_lunes.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-martes">'.$subtotal_martes.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-miercoles">'.$subtotal_miercoles.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-jueves">'.$subtotal_jueves.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-viernes">'.$subtotal_viernes.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-sabado">'.$subtotal_sabado.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-horas"><strong>'.$totalHorasSemanales.'</strong></td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-docentes"></td>
                </tr>
            </tfoot>
        </table>


        <!-- TABLA CARGA ACADEMICA --> 


        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:center; font-size: 0.8rem;">
            <thead>
                <tr class="oscuro">
                    <th style="font-weight: bold; padding: 1px 1px;" colspan="2">ACTIVIDADES COMPLEMENTARIAS</th>
                    <th style="font-weight: bold; padding: 1px 1px;" colspan="7">HORARIO</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">FUNCIONES</th>
                    <th style="font-weight: bold;">ACTIVIDAD</th>
                    <th style="font-weight: bold;">LUNES</th>
                    <th style="font-weight: bold;">MARTES</th>
                    <th style="font-weight: bold;">MIÉRCOLES</th>
                    <th style="font-weight: bold;">JUEVES</th>
                    <th style="font-weight: bold;">VIERNES</th>
                    <th style="font-weight: bold;">SÁBADO</th>
                    <th style="font-weight: bold;">HORAS</th>
                </tr>
            </thead>
            <tbody>';

    // Agregar filas dinámicas para Actividades Complementarias
    foreach ($array2 as $row) {
        $contenido .= '
        <tr style="text-align: center; padding: 1px 1px;">
            <td style="text-align: center; font-weight: bold; padding: 1px 1px;">'.$row['actividad'].'</td>
            <td style="text-align: center; padding: 1px 1px;">'.$row['detalles_actividad'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['lunes'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['martes'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['miercoles'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['jueves'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['viernes'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['sabado'].'</td>
            <td class="negritas" style="text-align: center;">'.$row['horas_semanales'].'</td>
        </tr>';
    }

    $contenido .= '
            </tbody>
            <tfoot>
                <tr class="oscuro">
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" colspan="2"><strong>Subtotal de Horas</strong></td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-lunes1">'.$subtotal_lunes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-martes1">'.$subtotal_martes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-miercoles1">'.$subtotal_miercoles1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-jueves1">'.$subtotal_jueves1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-viernes1">'.$subtotal_viernes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-sabado1">'.$subtotal_sabado1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="subtotal-horas"><strong>'.$subtotal_horas1.'</strong></td>
                </tr>
                <tr class="oscuro">
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" colspan="2"><strong>Total de Horas</strong></td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-lunes1">'.$total_lunes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-martes1">'.$total_martes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-miercoles1">'.$total_miercoles1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-jueves1">'.$total_jueves1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-viernes1">'.$total_viernes1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-sabado1">'.$total_sabado1.'</td>
                    <td style="font-weight: bold; text-align: center; padding: 1px 1px;" id="total-horas"><strong>'.$total_horas1.'</strong></td>
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
            <br></br>
            <h5>ATENTAMENTE</h5>
                <h5>
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;"><b>M en P. DAVID IGNACIO SEPÚLVEDA</b></td>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;"><b>ING. HUMBERTO FERNÁNDEZ</b></td>
                        </tr>
                    </table>
                </h5>
                <h5>
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;"><b>ARELLANO</b></td>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;"><b>SÁNCHEZ</b></td>
                        </tr>
                    </table>
                </h5>
                <h5>
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;">SUBDIRECTOR ACADÉMICO</td>
                            <td style="text-align: center; border: none; width: 50%; padding: 0; font-size: 0.8rem;">DIRECTOR</td>
                        </tr>
                    </table>
                </h5>
            <br></br>

            <p style="font-size: 0.7rem">Cc expediente personal</p>
            <p style="font-size: 0.7rem">Cc archivo</p>
            <p style="font-size: 0.7rem">HFS`DISA*scsz</p>

        </div>
       
        
    </body>
    <footer>
        <div class="footer-contenedor">
            <div id="logo3">
                <img src="../img/FelipeCarrillo.png" alt="Logo Felipe Carrillo">
            </div>
        </div>
    </footer>
    ';

    return $contenido;
}

?>