<?php
include_once '../vendor/autoload.php';
include_once 'plantilla_asignaFunciones.php';
// include_once 'tablaF19.php';

$mpdf= new \Mpdf\Mpdf();
$css = file_get_contents ("../css/style_pdf.css");

include_once '../bd/config.php';

// Crear una instancia de la conexión
$objeto = new Conexion();
$conexion = $objeto->Conectar();


$id_docente = isset($_POST['id_docente']) ? $_POST['id_docente'] : "Pablo";

// Consulta 1: Horarios
$sql = $conexion->prepare("SELECT 
    MIN(h.id_horario) AS id_horario,
    a.nombre_asignatura AS asignatura, 
    a.submodulos AS submodulos,
    g.nombre_grupo AS grupo, 
    e.nombre_especialidad AS especialidad, 
    s.nombre_semestre AS nombre_semestre, 
    s.numero_semestre AS numero_semestre, 
    GROUP_CONCAT(DISTINCT h.lunes ORDER BY h.lunes SEPARATOR ' ') AS lunes,
    GROUP_CONCAT(DISTINCT h.martes ORDER BY h.martes SEPARATOR ' ') AS martes,
    GROUP_CONCAT(DISTINCT h.miercoles ORDER BY h.miercoles SEPARATOR ' ') AS miercoles,
    GROUP_CONCAT(DISTINCT h.jueves ORDER BY h.jueves SEPARATOR ' ') AS jueves,
    GROUP_CONCAT(DISTINCT h.viernes ORDER BY h.viernes SEPARATOR ' ') AS viernes,
    GROUP_CONCAT(DISTINCT h.sabado ORDER BY h.sabado SEPARATOR ' ') AS sabado,
    SUM(h.horas_semanales) AS horas_semanales
FROM horarios h
JOIN asignatura a ON h.id_asignatura = a.id_asignatura
JOIN grupo g ON h.id_grupo = g.id_grupo
JOIN especialidad e ON g.id_especialidad = e.id_especialidad
JOIN semestre s ON g.id_semestre = s.id_semestre 
WHERE id_docente = $id_docente
GROUP BY 
    a.nombre_asignatura, 
    a.submodulos,
    g.nombre_grupo, 
    e.nombre_especialidad, 
    s.nombre_semestre, 
    s.numero_semestre 
ORDER BY 
    a.nombre_asignatura, 
    g.nombre_grupo, 
    e.nombre_especialidad, 
    s.numero_semestre ASC;");
$sql->execute();

$array = array();

while($row = $sql->fetch(PDO::FETCH_ASSOC)){
    $array[] = $row;
}

// Consulta 2: Actividades Complementarias
                $sqlActCom = "SELECT 
                                    MIN(a.id_actividad_complementaria) AS id_actividad_complementaria,
                                    a.actividad AS actividad, 
                                    a.detalles_actividad AS detalles_actividad,
                                    GROUP_CONCAT(DISTINCT a.lunes ORDER BY a.lunes SEPARATOR ' ') AS lunes,
                                    GROUP_CONCAT(DISTINCT a.martes ORDER BY a.martes SEPARATOR ' ') AS martes,
                                    GROUP_CONCAT(DISTINCT a.miercoles ORDER BY a.miercoles SEPARATOR ' ') AS miercoles,
                                    GROUP_CONCAT(DISTINCT a.jueves ORDER BY a.jueves SEPARATOR ' ') AS jueves,
                                    GROUP_CONCAT(DISTINCT a.viernes ORDER BY a.viernes SEPARATOR ' ') AS viernes,
                                    GROUP_CONCAT(DISTINCT a.sabado ORDER BY a.sabado SEPARATOR ' ') AS sabado,
                                    SUM(a.horas_semanales) AS horas_semanales
                                    -- Quitamos la columna de docente
                                FROM actividades_complementarias a
                                WHERE a.id_docente = :id_docente
                                GROUP BY 
                                    a.actividad, 
                                    a.detalles_actividad
                                ORDER BY 
                                    a.actividad;";

                $queryTableActCom = $conexion->prepare($sqlActCom);
                $queryTableActCom->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
                $queryTableActCom->execute();
                $resulta = $queryTableActCom->fetchAll();


                foreach ($resulta as $index => $row) {
    $array2[] = $row;
}

// Ahora $array contiene los datos de la tabla horarios y $array2 contiene los datos de la tabla actividades_complementarias
// Puedes trabajar con ambos arrays según lo necesites, por ejemplo, generando una plantilla en un PDF o HTML.

// Ejemplo de uso de los arrays (aquí es donde usarías tu función getPlantilla, por ejemplo):
// $contenido = getPlantilla($folio, $year, $fecha, $docente, $horas, $array, $array2);

// var_dump($array);
// var_dump($array2);

$plantilla = getPlantilla($folio,
                            $fecha,
                            $titulo,
                            $docente,
                            $horas,
                            $array,
                            $array2,
                            $dia,
                            $mesNombre,
                            $ano,
                            $periodo,
                            $subtotal_lunes,
                            $subtotal_martes,
                            $subtotal_miercoles,
                            $subtotal_jueves,
                            $subtotal_viernes,
                            $subtotal_sabado,
                            $subtotal_lunes1,
                            $subtotal_martes1,
                            $subtotal_miercoles1,
                            $subtotal_jueves1,
                            $subtotal_viernes1,
                            $subtotal_sabado1,
                            $subtotal_horas1,
                            $total_lunes1,
                            $total_martes1,
                            $total_miercoles1,
                            $total_jueves1,
                            $total_viernes1,
                            $total_sabado1,
                            $total_horas1);
$mpdf-> writeHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf-> writeHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

$mpdf -> output("horario","I");



?>