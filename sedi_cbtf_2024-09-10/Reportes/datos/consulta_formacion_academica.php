<?php
include_once "../bd/config.php";

function obtenerDatosFormacionAcademica($conexion) {
    // Inicializamos las categorías de formación académica con valores por defecto.
    $datos = [
        'Educación' => ['Hombre' => 0, 'Mujer' => 0],
        'Artes y Humanidades' => ['Hombre' => 0, 'Mujer' => 0],
        'Ciencias sociales y derecho' => ['Hombre' => 0, 'Mujer' => 0],
        'Administración y Negocios' => ['Hombre' => 0, 'Mujer' => 0],
        'Ciencias Naturales, Matemáticas y estadística' => ['Hombre' => 0, 'Mujer' => 0],
        'Tecnologías de la Información y la Comunicación' => ['Hombre' => 0, 'Mujer' => 0],
        'Ingeniería, Manufactura y Construcción' => ['Hombre' => 0, 'Mujer' => 0],
        'Agronomía y Veterinaria' => ['Hombre' => 0, 'Mujer' => 0],
        'Ciencias de la Salud' => ['Hombre' => 0, 'Mujer' => 0],
        'Servicios' => ['Hombre' => 0, 'Mujer' => 0],
    ];

    // Consulta SQL que agrupa por formación académica y género.
    $sql = "SELECT formacion_academica, genero, COUNT(*) as total 
            FROM docentes 
            GROUP BY formacion_academica, genero";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rellenamos los datos en las categorías correspondientes.
    foreach ($resultados as $row) {
        if (isset($datos[$row['formacion_academica']][$row['genero']])) {
            $datos[$row['formacion_academica']][$row['genero']] = $row['total'];
        }
    }

    return $datos;
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$datosFormacionAcademica = obtenerDatosFormacionAcademica($conexion);

// Calcular totales por columna
$totales = ['Hombre' => 0, 'Mujer' => 0, 'Total' => 0];

foreach ($datosFormacionAcademica as $formacion => $generos) {
    foreach ($generos as $genero => $cantidad) {
        $totales[$genero] += $cantidad;
        $totales['Total'] += $cantidad;
    }
}
?>
