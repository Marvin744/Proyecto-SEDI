<?php

function getPlantilla($folio, $year, $fecha, $docente, $horas, $array, $array2) {
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
            <h4>'.$docente.'</h4>
            <h4> Docente del plantel </h4>
            <p>Presente</p>
            <br><br>
        </div>

        <div>
            <p>
                En base a su nombramiento de <b>'.$horas.' horas</b> que tiene en esta institución, me permito
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

    $prevAsignatura = '';
    $rowspan = 1;
    $totalHorasSemanales = 0;

    foreach ($array as $row) {
        $totalHorasSemanales += $row['horas_semanales'];

        if ($row['asignatura'] === $prevAsignatura) {
            $rowspan++;
            $contenido .= "<tr>";
            $contenido .= "<td class='f19_td' style='display:none;'></td>";
        } else {
            if ($rowspan > 1) {
                // Ajuste de rowspan para la asignatura previa
                $contenido = str_replace("<!-- rowspan -->", 'rowspan="' . $rowspan . '"', $contenido);
            }
            $rowspan = 1;
            $prevAsignatura = $row['asignatura'];

            $asignaturaCompleta = $row['submodulos'] ? "{$row['asignatura']} - {$row['submodulos']}" : $row['asignatura'];

            $contenido .= "<tr>";
            $contenido .= "<td class='f19_td' data-asignatura='{$row['asignatura']}'>{$asignaturaCompleta}</td>";
        }

        $contenido .= "<td class='f19_td'>{$row['grupo']}</td>";
        $contenido .= "<td class='f19_td'>{$row['especialidad']}</td>";
        $contenido .= "<td class='f19_td'>{$row['lunes']}</td>";
        $contenido .= "<td class='f19_td'>{$row['martes']}</td>";
        $contenido .= "<td class='f19_td'>{$row['miercoles']}</td>";
        $contenido .= "<td class='f19_td'>{$row['jueves']}</td>";
        $contenido .= "<td class='f19_td'>{$row['viernes']}</td>";
        $contenido .= "<td class='f19_td'>{$row['sabado']}</td>";
        $contenido .= "<td class='f19_td'>{$row['horas_semanales']}</td>";
        $contenido .= "<td class='f19_td'>{$row['numero_semestre']}</td>";
        $contenido .= "</tr>";
    }

    $contenido .= '
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
                    <td class="f19_td" id="subtotal-horas"><strong>'.$totalHorasSemanales.'</strong></td>
                    <td class="f19_td" id="subtotal-docentes"></td>
                </tr>
            </tfoot>
        </table>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:center;">
            <thead>
                <tr>
                    <th colspan="2">Actividades Complementarias</th>
                    <th colspan="8">Horario</th>
                </tr>
                <tr>
                    <th>Funciones</th>
                    <th>Actividad</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                    <th>Sábado</th>
                    <th>Horas</th>
                    <th>Semestre</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($array2 as $row) {
        $contenido .= '
        <tr>
            <td><b>Paraescolar: </b>'.$row['nombre_asignatura'].'</td>
            <td>'.$row['nombre_semestre'].' '.$row['nombre_grupo'].' '.$row['nombre_especialidad'].'</td>
            <td>'.$row['lunes'].'</td>
            <td>'.$row['martes'].'</td>
            <td>'.$row['miercoles'].'</td>
            <td>'.$row['jueves'].'</td>
            <td>'.$row['viernes'].'</td>
            <td>'.$row['sabado'].'</td>
            <td>'.$row['horas_semanales'].'</td>
            <td>'.$row['nombre_semestre'].'</td>
        </tr>';
    }

    $contenido .= '
            </tbody>
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
    </body>
    <footer>
        <div class="footer-contenedor">
            <div id="logo3">
                <img src="../img/FelipeCarrillo.png" alt="Logo Felipe Carrillo">
            </div>
        </div>
    </footer>';

    return $contenido;
}

?>