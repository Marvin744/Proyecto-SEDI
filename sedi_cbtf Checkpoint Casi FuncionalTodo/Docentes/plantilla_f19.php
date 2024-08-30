<?php
include_once '../vendor/autoload.php';
include_once 'plantilla_asignaFunciones.php';
include_once 'tablaF19.php';

$mpdf= new \Mpdf\Mpdf();
$css = file_get_contents ("../css/style_pdf.css");


$plantilla = getPlantilla($folio, $year, $fecha, $docente, $titulo, $horas, $array, $array2);
$mpdf-> writeHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf-> writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

$mpdf -> output("horario","I");



?>