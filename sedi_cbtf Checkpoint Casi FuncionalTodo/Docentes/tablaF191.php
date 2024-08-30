<?php
include_once '../bd/config.php';

// Crear una instancia de la conexión
$objeto = new Conexion();
$conexion = $objeto->Conectar();

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
WHERE id_docente = 
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
$sql1 = $conexion->prepare("SELECT * FROM actividades_complementarias
                            INNER JOIN asignatura ON actividades_complementarias.id_asignatura = asignatura.id_asignatura
                            INNER JOIN grupo ON actividades_complementarias.id_grupo = grupo.id_grupo
                            INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
                            INNER JOIN semestre ON grupo.id_semestre = semestre.id_semestre
                            INNER JOIN docentes ON actividades_complementarias.id_docente = docentes.id_docente
                            WHERE docentes.id_docente = 39");
$sql1->execute();

$array2 = array();

while($row = $sql1->fetch(PDO::FETCH_ASSOC)){
    $array2[] = $row;
}

// Ahora $array contiene los datos de la tabla horarios y $array2 contiene los datos de la tabla actividades_complementarias
// Puedes trabajar con ambos arrays según lo necesites, por ejemplo, generando una plantilla en un PDF o HTML.

// Ejemplo de uso de los arrays (aquí es donde usarías tu función getPlantilla, por ejemplo):
// $contenido = getPlantilla($folio, $year, $fecha, $docente, $horas, $array, $array2);

// var_dump($array);
// var_dump($array2);

?>
