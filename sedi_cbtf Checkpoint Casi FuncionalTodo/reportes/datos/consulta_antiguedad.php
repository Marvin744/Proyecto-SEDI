<?php
include_once "../bd/config.php";

function obtenerDatosAntiguedad($conexion) {
    // Inicializamos los grupos de antigüedad con valores por defecto.
    $datos = [
        'De 0 a 4 años' => ['Hombre' => 0, 'Mujer' => 0],
        '5-9' => ['Hombre' => 0, 'Mujer' => 0],
        '10-14' => ['Hombre' => 0, 'Mujer' => 0],
        '15-19' => ['Hombre' => 0, 'Mujer' => 0],
        '20-24' => ['Hombre' => 0, 'Mujer' => 0],
        '25-29' => ['Hombre' => 0, 'Mujer' => 0],
        'De 30 años o más' => ['Hombre' => 0, 'Mujer' => 0],
    ];

    // Consulta SQL que agrupa por antigüedad y género.
    $sql = "SELECT antiguedad, genero, COUNT(*) as total 
            FROM docentes 
            GROUP BY antiguedad, genero";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rellenamos los datos en las categorías correspondientes.
    foreach ($resultados as $row) {
        if (isset($datos[$row['antiguedad']][$row['genero']])) {
            $datos[$row['antiguedad']][$row['genero']] = $row['total'];
        }
    }

    return $datos;
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$datosAntiguedad = obtenerDatosAntiguedad($conexion);

// Calcular totales por columna
$totales = ['Hombre' => 0, 'Mujer' => 0, 'Total' => 0];

foreach ($datosAntiguedad as $grupoEdad => $generos) {
    foreach ($generos as $genero => $cantidad) {
        $totales[$genero] += $cantidad;
        $totales['Total'] += $cantidad;
    }
}
?>
