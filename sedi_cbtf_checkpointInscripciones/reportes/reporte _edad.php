<?php
require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


	include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 


// Consulta para obtener todos los alumnos
$sql = $conexion->query("SELECT nombre_alumno, apellido_paterno, apellido_materno, fecha_naci FROM alumnos");

$alumnos = [];
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    $fecha_nacimiento = new DateTime($row['fecha_naci']);
    $fecha_actual = new DateTime();
    $edad = $fecha_actual->diff($fecha_nacimiento)->y;

    $alumnos[] = [
        'nombre' => $row['nombre_alumno'],
        'apellido_paterno' => $row['apellido_paterno'],
        'apellido_materno' => $row['apellido_materno'],
        'fecha_naci' => $row['fecha_naci'],
        'edad' => $edad
    ];
}

// Agrupar los alumnos por edad
$alumnosPorEdad = [];
foreach ($alumnos as $alumno) {
    $edad = $alumno['edad'];
    if (!isset($alumnosPorEdad[$edad])) {
        $alumnosPorEdad[$edad] = [];
    }
    $alumnosPorEdad[$edad][] = $alumno;
}

// Crear el archivo de Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Edades');

// Encabezados
$sheet->setCellValue('A1', 'Edad');
$sheet->setCellValue('B1', 'Cantidad de Alumnos');
$sheet->setCellValue('C1', 'Detalles');

// Contenido
$rowNumber = 2;
foreach ($alumnosPorEdad as $edad => $alumnos) {
    $sheet->setCellValue('A' . $rowNumber, $edad);
    $sheet->setCellValue('B' . $rowNumber, count($alumnos));
    
    $detalles = "";
    foreach ($alumnos as $alumno) {
        $detalles .= $alumno['nombre'] . " " . $alumno['apellido_paterno'] . " " . $alumno['apellido_materno'] . ", ";
    }
    $detalles = rtrim($detalles, ', ');
    $sheet->setCellValue('C' . $rowNumber, $detalles);
    $rowNumber++;
}

// Crear el archivo XLSX
$writer = new Xlsx($spreadsheet);

// Guardar el archivo en el servidor
$filename = 'reporte_edades_alumnos.xlsx';
$writer->save($filename);

// Descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>
