<?php

function getPlantillaPersonal($datosPersonal, $totales){
    $contenido = '
    <div style="background-color: #000000; color: #ffffff; padding: 20px;">
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td align="left" style="font-weight: bold; color: #ffffff;">
                    PLANTEL
                    <div style="font-weight: normal;">Información del ciclo actual 2024-2025</div>
                </td>
                <td align="right" style="font-weight: bold; color: #ffffff;">
                    CICLO ESCOLAR, 2024-2025
                    <div style="font-weight: normal;">911.7</div>
                </td>
            </tr>
        </table>
    </div>
    <br></br>

    <h1>|||. PERSONAL DOCENTE (continuación)</h1>
    <br></br>
    <br></br>
    <p>6. Escriba por sexo el <strong>total de personal docente más</strong></p>
    <p><strong>directivo con grupo,</strong> reportados en la pregunta 1 y</p>
    <p>desglóselo por <strong>función que desempeñan</strong> que corresponda.</p>
    <br></br>
    <br></br>

  <table cellpadding="2" cellspacing="0" style="width:100%; text-align:center; border-collapse: collapse; margin: 0; padding: 2px;">
    <thead>
        <tr>
            <th rowspan="2" style="padding: 2px; text-align: center;">Función que desempeñan</th>
            <th colspan="2" style="padding: 2px; border: none;">Género</th>
            <th rowspan="2" style="padding: 2px; text-align: center;">Total</th>
            <th rowspan="2" style="padding: 2px; text-align: center;">Con discapacidad</th>
            <th rowspan="2" style="padding: 2px; text-align: center;">Hablantes de lengua indígena</th>
        </tr>
        <tr>
            <th style="padding: 2px; border: none;">Hombres</th>
            <th style="padding: 2px; border: none;">Mujeres</th>
        </tr>
    </thead>
    <tbody>';

    // Generar las filas de la tabla
    foreach ($datosPersonal as $funcion => $valores) {
        $totalGrupo = $valores['Hombre'] + $valores['Mujer'];
        $contenido .= '
        <tr>
            <td style="padding: 2px; text-align: center;">' . $funcion . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Hombre'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Mujer'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totalGrupo . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Con discapacidad'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Hablantes de lengua indígena'] . '</td>
        </tr>';
    }

    // Agregar la fila de totales
    $contenido .= '
        <tr>
            <td style="padding: 2px; text-align: center;"><strong>Total</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Hombre'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Mujer'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Total'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Con discapacidad'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Hablantes de lengua indígena'] . '</strong></td>
        </tr>
    </tbody>
</table>


    <br></br>
    <br></br>
    ';

    return $contenido;
}
?>

