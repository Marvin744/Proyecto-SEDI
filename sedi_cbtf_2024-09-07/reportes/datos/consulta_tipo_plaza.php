<?php
include_once "../bd/config.php";

function obtenerDatosTipoPlaza($conexion) {
    // Estructura de datos que representará los conteos para cada combinación de nivel de estudios, género, y tipo de plaza
    $datos = [
        'Doctorado' => [40 => ['Hombre' => 0, 'Mujer' => 0], 30 => ['Hombre' => 0, 'Mujer' => 0], 20 => ['Hombre' => 0, 'Mujer' => 0], 'Por horas' => ['Hombre' => 0, 'Mujer' => 0]],
        'Maestría y especialidad' => [40 => ['Hombre' => 0, 'Mujer' => 0], 30 => ['Hombre' => 0, 'Mujer' => 0], 20 => ['Hombre' => 0, 'Mujer' => 0], 'Por horas' => ['Hombre' => 0, 'Mujer' => 0]],
        'Licenciatura completa' => [40 => ['Hombre' => 0, 'Mujer' => 0], 30 => ['Hombre' => 0, 'Mujer' => 0], 20 => ['Hombre' => 0, 'Mujer' => 0], 'Por horas' => ['Hombre' => 0, 'Mujer' => 0]],
        'Licenciatura incompleta o menos' => [40 => ['Hombre' => 0, 'Mujer' => 0], 30 => ['Hombre' => 0, 'Mujer' => 0], 20 => ['Hombre' => 0, 'Mujer' => 0], 'Por horas' => ['Hombre' => 0, 'Mujer' => 0]]
    ];

    $sql = "SELECT nivel_estudios, genero, tipo_plaza, COUNT(*) as total 
            FROM docentes 
            GROUP BY nivel_estudios, genero, tipo_plaza";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados as $row) {
        if (isset($datos[$row['nivel_estudios']][$row['tipo_plaza']][$row['genero']])) {
            $datos[$row['nivel_estudios']][$row['tipo_plaza']][$row['genero']] = $row['total'];
        }
    }

    return $datos;
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$datosTipoPlaza = obtenerDatosTipoPlaza($conexion);

// Calcular totales por columna
$totales = [
    40 => ['Hombre' => 0, 'Mujer' => 0],
    30 => ['Hombre' => 0, 'Mujer' => 0],
    20 => ['Hombre' => 0, 'Mujer' => 0],
    'Por horas' => ['Hombre' => 0, 'Mujer' => 0]
];

foreach ($datosTipoPlaza as $nivel => $plazas) {
    foreach ($plazas as $tipoPlaza => $generos) {
        foreach ($generos as $genero => $cantidad) {
            $totales[$tipoPlaza][$genero] += $cantidad;
        }
    }
}
?>