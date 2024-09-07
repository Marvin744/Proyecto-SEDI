<?php

function getPlantillaGrupoEdad($datosGrupoEdad, $totales){
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
    <p>3. Escriba por sexo el <strong>total de personal docente</strong></p>
    <p><strong> más directivo con grupo,</strong> reportados en la </p>
    <p>pregunta 1 y desglóselo por <strong>grupo de edad </strong>al</p>
    <p>que corresponda.</p>
    <br></br>

<table cellpadding="2" cellspacing="0" style="width:100%; text-align:center; border-collapse: collapse; margin: 0;">
    <thead>
        <tr>
            <th rowspan="2" style="padding: 2px;">Grupo de edad</th>
            <th colspan="3">Personal Docente</th>
        </tr>
        <tr>
            <th style="border: 1px solid black; padding: 2px;">Hombres</th>
            <th style="border: 1px solid black; padding: 2px;">Mujeres</th>
            <th style="border: 1px solid black; padding: 2px;"><strong>Total</strong></th>
        </tr>
    </thead>
    <tbody>';

    foreach ($datosGrupoEdad as $grupoEdad => $generos) {
        $totalGrupo = $generos['Hombre'] + $generos['Mujer'];
        $contenido .= '
        <tr>
            <td style="padding: 2px;">' . $grupoEdad . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $generos['Hombre'] . '</td>
            <td style="border: 1px solid black; padding: 2px;">' . $generos['Mujer'] . '</td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totalGrupo . '</strong></td>
        </tr>';
    }

    $contenido .= '
        <tr>
            <td style="padding: 2px;"><strong>Total</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Hombre'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Mujer'] . '</strong></td>
            <td style="border: 1px solid black; padding: 2px;"><strong>' . $totales['Total'] . '</strong></td>
        </tr>
    </tbody>
</table>

    <br></br>
    <br></br>
    ';

    return $contenido;
}
?>