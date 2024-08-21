<?php
require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Lista de Animales');

// Encabezado
$sheet->setCellValue('A1', 'Animales');

// Ejemplos de animales
$animales = ['Perro', 'Gato', 'Elefante', 'Tigre', 'León'];

// Añadir los ejemplos al archivo
$rowNumber = 2; // Comenzar en la fila 2 porque la fila 1 tiene el encabezado
foreach ($animales as $animal) {
    $sheet->setCellValue('A' . $rowNumber, $animal);
    $rowNumber++;
}

// Crear el archivo XLSX
$writer = new Xlsx($spreadsheet);

// Encabezados para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="lista_animales.xlsx"');
header('Cache-Control: max-age=0');

// Enviar el archivo al navegador
$writer->save('php://output');
exit();
?>
