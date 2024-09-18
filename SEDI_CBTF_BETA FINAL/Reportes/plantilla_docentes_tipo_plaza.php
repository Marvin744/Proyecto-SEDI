<?php

function getPlantillaTipoPlaza($datosTipoPlaza) {

    $totalesPorPlaza = [
        '40' => ['Hombre' => 0, 'Mujer' => 0],
        '30' => ['Hombre' => 0, 'Mujer' => 0],
        '20' => ['Hombre' => 0, 'Mujer' => 0],
        'Por horas' => ['Hombre' => 0, 'Mujer' => 0],
    ];
    $totalGeneral = 0;

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

    <h1>|||. PERSONAL DOCENTE</h1>
    <br></br>
    <br></br>
    <p>Escriba por sexo y <strong>tiempo de dedicación</strong> el total</p>
    <p>de personal docente más directivo con grupo y</p>
    <p>desglóselo por el <strong>nivel de estudios</strong> con el que</p>
    <p>cuenta actualmente</p>
    <br></br>
    <br></br>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:center; border-collapse: collapse;">
        <thead>
            <tr>
                <th rowspan="2">Nivel de estudios</th>
                <th colspan="2">Tiempo Completo</th>
                <th colspan="2">3/4 tiempo</th>
                <th colspan="2">Medio Tiempo</th>
                <th colspan="2">Por horas</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th>Hombre</th>
                <th>Mujer</th>
                <th>Hombre</th>
                <th>Mujer</th>
                <th>Hombre</th>
                <th>Mujer</th>
                <th>Hombre</th>
                <th>Mujer</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($datosTipoPlaza as $nivel => $plazas) {
        $contenido .= '<tr>';
        $contenido .= '<td>' . $nivel . '</td>';
        $totalFila = 0;

        foreach ([40, 30, 20, 'Por horas'] as $tipoPlaza) {
            foreach (['Hombre', 'Mujer'] as $genero) {
                $cantidad = $plazas[$tipoPlaza][$genero];
                $contenido .= '<td>' . $cantidad . '</td>';
                $totalFila += $cantidad;
                $totalesPorPlaza[$tipoPlaza][$genero] += $cantidad;
            }
        }
        $contenido .= '<td><strong>' . $totalFila . '</strong></td>';
        $contenido .= '</tr>';
    }

    // Agregar la fila de totales
    $contenido .= '<tr>';
    $contenido .= '<td><strong>Total</strong></td>';
    foreach ([40, 30, 20, 'Por horas'] as $tipoPlaza) {
        foreach (['Hombre', 'Mujer'] as $genero) {
            $contenido .= '<td><strong>' . $totalesPorPlaza[$tipoPlaza][$genero] . '</strong></td>';
            $totalGeneral += $totalesPorPlaza[$tipoPlaza][$genero];
        }
    }
    $contenido .= '<td><strong>' . $totalGeneral . '</strong></td>';
    $contenido .= '</tr>';

    $contenido .= '</tbody>
    </table>
    <br></br>
    <br></br>
    <p>*Si el plantel no utiliza el termino 3/4 de tiempo, no lo considere</p>';

    return $contenido;
}
?>