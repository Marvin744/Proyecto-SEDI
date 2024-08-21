<?php
include_once '../vendor/autoload.php';
include_once 'plantilla_asignaFunciones.php';

$mpdf= new \Mpdf\Mpdf();

$plantilla = getPlantilla();
$mpdf-> writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

$mpdf -> output("horario","I");

?>