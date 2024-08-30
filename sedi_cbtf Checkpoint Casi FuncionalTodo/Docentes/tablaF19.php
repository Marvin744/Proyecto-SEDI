<?php
include_once '../bd/config.php';

// Crear una instancia de la conexión
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Definir las variables necesarias
$folio = 5; // Puedes asignar el valor que corresponda
$year = 2001; // Asigna el año que corresponda
$fecha = "21 de septiembre 2001"; // Ajusta la fecha según sea necesario
$docente = "M. en Pa. Christian Humberto Irigoyen Ruiz"; // Nombre del docente
$horas = 20; // Número de horas


// Consulta 1: Horarios
$sqlHorarios = "SELECT 
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
    s.numero_semestre ASC;";

$queryTableHorarios = $conexion->prepare($sqlHorarios);
$queryTableHorarios->execute();
$array = $queryTableHorarios->fetchAll(PDO::FETCH_ASSOC);

// Consulta 2: Actividades Complementarias
$sql1 = $conexion->prepare("SELECT * FROM actividades_complementarias
    INNER JOIN asignatura ON actividades_complementarias.id_asignatura = asignatura.id_asignatura
    INNER JOIN grupo ON actividades_complementarias.id_grupo = grupo.id_grupo
    INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
    INNER JOIN semestre ON grupo.id_semestre = semestre.id_semestre
    INNER JOIN docentes ON actividades_complementarias.id_docente = docentes.id_docente
    WHERE docentes.id_docente = 40");
$sql1->execute();
$array2 = $sql1->fetchAll(PDO::FETCH_ASSOC);

// Generar PDF usando mPDF
require_once __DIR__ . '/../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();

// Generar contenido de la plantilla
$contenido = getPlantilla($folio, $year, $fecha, $docente, $horas, $array, $array2);

// Escribir contenido al PDF
$mpdf->WriteHTML($contenido);

// Output del PDF en el navegador
$mpdf->Output();

// Ahora $array contiene los datos de la tabla horarios y $array2 contiene los datos de la tabla actividades_complementarias
// Puedes trabajar con ambos arrays según lo necesites, por ejemplo, generando una plantilla en un PDF o HTML.

// Ejemplo de uso de los arrays (aquí es donde usarías tu función getPlantilla, por ejemplo):
// $contenido = getPlantilla($folio, $year, $fecha, $docente, $horas, $array, $array2);

// var_dump($array);
// var_dump($array2);

?>
