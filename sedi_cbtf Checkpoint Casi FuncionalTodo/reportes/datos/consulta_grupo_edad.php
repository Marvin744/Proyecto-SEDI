<?php
include_once "../bd/config.php";

function obtenerDatosGrupoEdad($conexion) {
    $datos = [
        '24 años o menos' => ['Hombre' => 0, 'Mujer' => 0],
        '25-29' => ['Hombre' => 0, 'Mujer' => 0],
        '30-34' => ['Hombre' => 0, 'Mujer' => 0],
        '35-39' => ['Hombre' => 0, 'Mujer' => 0],
        '40-44' => ['Hombre' => 0, 'Mujer' => 0],
        '45-49' => ['Hombre' => 0, 'Mujer' => 0],
        '50-54' => ['Hombre' => 0, 'Mujer' => 0],
        '55-59' => ['Hombre' => 0, 'Mujer' => 0],
        '60-64' => ['Hombre' => 0, 'Mujer' => 0],
        '65 años o más' => ['Hombre' => 0, 'Mujer' => 0],
    ];

    $sql = "SELECT grupo_edad, genero, COUNT(*) as total 
            FROM docentes 
            GROUP BY grupo_edad, genero";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados as $row) {
        if (isset($datos[$row['grupo_edad']][$row['genero']])) {
            $datos[$row['grupo_edad']][$row['genero']] = $row['total'];
        }
    }

    return $datos;
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$datosGrupoEdad = obtenerDatosGrupoEdad($conexion);

// Calcular totales por columna
$totales = ['Hombre' => 0, 'Mujer' => 0, 'Total' => 0];

foreach ($datosGrupoEdad as $grupoEdad => $generos) {
    foreach ($generos as $genero => $cantidad) {
        $totales[$genero] += $cantidad;
        $totales['Total'] += $cantidad;
    }
}
?>