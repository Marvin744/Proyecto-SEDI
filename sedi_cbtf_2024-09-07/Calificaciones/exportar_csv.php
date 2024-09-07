<?php
if (isset($_POST['exportar_csv'])) {
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Obtener la fecha de hace 3 años
    $fecha_hoy = date('Y-m-d');
    $fecha_3_anos_atras = date('Y-m-d', strtotime('-3 years'));

    // Consulta SQL para obtener los registros desde hoy hasta hace 3 años
    $sql = "SELECT
                s.nombre_semestre,
                g.nombre_grupo,
                e.nombre_especialidad,
                a.nombre_alumno,
                a.apellido_paterno,
                a.apellido_materno,
                a.matricula,
                a.status,
                a.CURP,
                a.genero,
                a.edad,
                a.fecha_naci,
                a.lugar_nacimiento,
                a.nacionalidad,
                a.ayuda_espanol,
                a.telefono,
                a.correo,
                a.secundaria_egreso,
                a.promedio_secundaria,
                a.sangre,
                a.beca_bancomer,
                a.nss,
                a.nss_numero,
                a.issste,
                a.issste_numero,
                a.discapacidad_mo_psi,
                a.detalles_discapacidad,
                a.documento_validacion_discapacidad,
                a.alergia,
                a.detalles_alergias,
                a.requiere_medicacion,
                a.medicacion_necesaria,
                a.lentes_graduados,
                a.aparatos_asistencia,
                a.detalles_aparatos_asistencia,
                a.calle_numero,
                a.colonia,
                a.codigo_postal,
                a.dispositivo_internet,
                a.numero_dispositivos,
                a.nombre_tutor,
                a.telefono_tutor,
                a.nombre_madre,
                a.telefono_madre,
                a.nombre_padre,
                a.telefono_padre,
                a.EP_acta_nacimiento,
                a.EP_CURP,
                a.EP_comprobante_domicilio,
                a.EP_nss_issste,
                a.EP_certificado_secundaria,
                a.EP_ficha_psicopedagogica,
                a.EP_ficha_buena_conducta,
                a.EP_fotografias,
                a.EP_autenticacion_secundaria,
                a.observaciones,
                a.ticket,
                a.fecha_creacion,
                a.id_grupo,
                a.id_semestre,
                a.generacion
            FROM alumnos a
            JOIN grupo g ON a.id_grupo = g.id_grupo
            JOIN semestre s ON g.id_semestre = s.id_semestre
            JOIN especialidad e ON g.id_especialidad = e.id_especialidad
            WHERE fecha_creacion BETWEEN :fecha_3_anos_atras AND :fecha_hoy
            ORDER BY s.id_semestre, e.nombre_especialidad, g.nombre_grupo ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':fecha_3_anos_atras', $fecha_3_anos_atras);
    $stmt->bindParam(':fecha_hoy', $fecha_hoy);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultados)) {
        // Nombre del archivo CSV
        $archivo_csv = 'alumnos_' . date('Ymd') . '.csv';

        // Establecer cabeceras para descarga
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $archivo_csv . '"');

        // Abrir un archivo en memoria para escribir el CSV
        $output = fopen('php://output', 'w');

        // Escribir la primera línea con los nombres de las columnas
        fputcsv($output, array_keys($resultados[0]));

        // Escribir los datos obtenidos de la consulta
        foreach ($resultados as $fila) {
            fputcsv($output, $fila);
        }

        // Cerrar el archivo en memoria
        fclose($output);
    } else {
        echo "No se encontraron registros.";
    }

    // Cerrar la conexión
    $conexion = null;
    exit;
}
?>
