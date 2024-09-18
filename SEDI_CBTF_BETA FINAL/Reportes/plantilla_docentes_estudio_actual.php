<?php

function getPlantillaEstudioActual($datosEstudioActual, $totales, $becas){
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
    <p>5. Del total de <strong>personal docente más directivos con</strong></p>
    <p><strong>grupo </strong> que laboran en el plantel, desglóselos por sexo </p>
    <p>y especifique el <strong>nivel que estudian actualmente, </strong>al</p>
    <p>dónde lo hacen y si cuentan con alguna beca.</p>
    <br></br>

<table cellpadding="2" cellspacing="0" style="width:100%; text-align:center; border-collapse: collapse; margin: 0;">
    <thead>
        <tr>
            <th style="text-align: left;">Nivel que estudian</th>
            <th colspan="3">Estudios en México</th>
            <th colspan="3">Estudios en el extranjero</th>
        </tr>
        <tr>
            <th></th>
            <th style="border: 1px solid black;">Hombres</th>
            <th style="border: 1px solid black;">Mujeres</th>
            <th style="border: 1px solid black;"><strong>Total</strong></th>
            <th style="border: 1px solid black;">Hombres</th>
            <th style="border: 1px solid black;">Mujeres</th>
            <th style="border: 1px solid black;"><strong>Total</strong></th>
        </tr>
    </thead>
    <tbody>';

    // Generar las filas de la tabla
    foreach ($datosEstudioActual as $nivel => $valores) {
        $totalMexico = $valores['Hombres Mexico'] + $valores['Mujeres Mexico'];
        $totalExtranjero = $valores['Hombres Extranjero'] + $valores['Mujeres Extranjero'];
        $contenido .= '
        <tr>
            <td style="text-align: left; padding: 2px;">' . $nivel . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Hombres Mexico'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Mujeres Mexico'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totalMexico . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Hombres Extranjero'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $valores['Mujeres Extranjero'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totalExtranjero . '</strong></td>
        </tr>';
    }

    // Agregar la fila de totales
    $contenido .= '
        <tr>
            <td style="text-align: left; padding: 2px;"><strong>Total</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Hombres Mexico'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Mujeres Mexico'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . ($totales['Hombres Mexico'] + $totales['Mujeres Mexico']) . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Hombres Extranjero'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Mujeres Extranjero'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . ($totales['Hombres Extranjero'] + $totales['Mujeres Extranjero']) . '</strong></td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 2px;">Con beca</td>
            <td style="border: 1px solid black; padding: 2px;">' . $becas['Mexico']['Hombres'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $becas['Mexico']['Mujeres'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"></td>
            <td style="border: 1px solid black; padding: 2px;">' . $becas['Extranjero']['Hombres'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $becas['Extranjero']['Mujeres'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"></td>
        </tr>
    </tbody>
</table>


    <br></br>
    <br></br>
    ';

    return $contenido;
}
?>

