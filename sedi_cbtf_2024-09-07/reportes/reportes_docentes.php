<?php
if (isset($_POST['generate_pdf'])) {
    include_once '../vendor/autoload.php';

    $mpdf = new \Mpdf\Mpdf([
        'format' => 'letter',
        'orientation' => 'L'
    ]);

    // Load the CSS file
    $css = file_get_contents("../css/style_pdf_reportes.css");
    $mpdf->writeHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Determine which PDF to generate based on the form submission
    $pdfType = $_POST['generate_pdf'];
    
    switch ($pdfType) {
        case 'pdf':
            include_once 'plantilla_personal.php';
            include_once 'datos/consulta_personal.php';
            $plantilla = getPlantillaPersonal($datosPersonal, $totales);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte.pdf", "I");
            break;
        case 'pdf1':
            include_once 'plantilla_docentes_tipo_plaza.php';
            include_once 'datos/consulta_tipo_plaza.php';
            $plantilla = getPlantillaTipoPlaza($datosTipoPlaza);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte_plaza.pdf", "I");
            break;
        case 'pdf2':
            include_once 'plantilla_docentes_formacion_academica.php';
            include_once 'datos/consulta_formacion_academica.php';
            $plantilla = getPlantillaFormacionAcademica($datosFormacionAcademica, $totales);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte2.pdf", "I");
            break;
        case 'pdf3':
            include_once 'plantilla_docentes_grupo_edad.php';
            include_once 'datos/consulta_grupo_edad.php';
            $plantilla = getPlantillaGrupoEdad($datosGrupoEdad, $totales);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte_edades.pdf", "I");
            break;
        case 'pdf4':
            include_once 'plantilla_docentes_antiguedad.php';
            include_once 'datos/consulta_antiguedad.php';
            $plantilla = getPlantillaDocentesAntiguedad($datosAntiguedad, $totales);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte4.pdf", "I");
            break;
        case 'pdf5':
            include_once 'plantilla_docentes_estudio_actual.php';
            include_once 'datos/consulta_estudio_actual.php';
            $plantilla = getPlantillaEstudioActual($datosEstudioActual, $totales, $becas);  
            $mpdf->writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output("reporte5.pdf", "I");
            break;
        default:
            echo "Invalid PDF type selected.";
            break;
    }
}
?>
