<?php
include_once "../bd/config.php";

function obtenerDatosPersonal($conexion) {
    // Inicializamos las categorías de funciones con valores por defecto.
    $datos = [
        'Directivo sin grupo' => ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0],
        'Directivo con grupo' => ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0],
        'Docente' => ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0],
        'Administrativo, Auxiliar y de Servicios' => ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0],
        'Otros' => ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0],
    ];

    // Consulta SQL que agrupa por función, género, discapacidad y lengua indígena.
    $sql = "SELECT funcion, genero, 
                   SUM(CASE WHEN discapacidad = 1 THEN 1 ELSE 0 END) AS discapacidad,
                   SUM(CASE WHEN lengua_indigena = 1 THEN 1 ELSE 0 END) AS lengua_indigena,
                   COUNT(*) as total 
            FROM docentes 
            GROUP BY funcion, genero";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rellenamos los datos en las categorías correspondientes.
    foreach ($resultados as $row) {
        if (isset($datos[$row['funcion']][$row['genero']])) {
            $datos[$row['funcion']][$row['genero']] = $row['total'];
            $datos[$row['funcion']]['Con discapacidad'] += $row['discapacidad'];
            $datos[$row['funcion']]['Hablantes de lengua indígena'] += $row['lengua_indigena'];
        }
    }

    return $datos;
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$datosPersonal = obtenerDatosPersonal($conexion);

// Calcular totales por columna y filas
$totales = ['Hombre' => 0, 'Mujer' => 0, 'Con discapacidad' => 0, 'Hablantes de lengua indígena' => 0, 'Total' => 0];

foreach ($datosPersonal as $funcion => $valores) {
    foreach ($valores as $tipo => $cantidad) {
        if ($tipo === 'Hombre' || $tipo === 'Mujer') {
            $totales[$tipo] += $cantidad;
            $totales['Total'] += $cantidad;
        } else {
            $totales[$tipo] += $cantidad;
        }
    }
}
?>
