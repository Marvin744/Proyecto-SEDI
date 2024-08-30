<?php
include_once "../bd/config.php";

function obtenerDatosEstudioActual($conexion) {
    // Inicializamos los niveles de estudio con valores por defecto.
    $datos = [
        'Doctorado' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
        'Maestría' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
        'Especialidad' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
        'Licenciatura' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
        'Técnico Superior' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
        'Normal' => ['Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0],
    ];

    $becas = [
        'Mexico' => ['Hombres' => 0, 'Mujeres' => 0],
        'Extranjero' => ['Hombres' => 0, 'Mujeres' => 0],
    ];

    // Consulta SQL que agrupa por estudio actual, género, país de estudio, y beca.
    $sql = "SELECT estudio_actual, genero, pais_estudio, COUNT(*) as total, 
                   SUM(CASE WHEN beca = 1 THEN 1 ELSE 0 END) as total_becas
            FROM docentes 
            GROUP BY estudio_actual, genero, pais_estudio";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rellenamos los datos en las categorías correspondientes.
    foreach ($resultados as $row) {
        $pais = $row['pais_estudio'] === 'México' ? 'Mexico' : 'Extranjero';
        $genero = $row['genero'] === 'Hombre' ? 'Hombres' : 'Mujeres';

        if (isset($datos[$row['estudio_actual']]["$genero $pais"])) {
            $datos[$row['estudio_actual']]["$genero $pais"] = $row['total'];
            $becas[$pais][$genero] += $row['total_becas'];
        }
    }

    return [$datos, $becas];
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
list($datosEstudioActual, $becas) = obtenerDatosEstudioActual($conexion);

// Calcular totales por secciones y columnas
$totales = [
    'Hombres Mexico' => 0, 'Mujeres Mexico' => 0, 
    'Hombres Extranjero' => 0, 'Mujeres Extranjero' => 0
];

foreach ($datosEstudioActual as $nivel => $valores) {
    foreach ($valores as $tipo => $cantidad) {
        $totales[$tipo] += $cantidad;
    }
}
?>

