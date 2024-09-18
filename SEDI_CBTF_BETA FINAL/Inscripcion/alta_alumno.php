<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

    // Obtener los valores del formulario

    //Datos Generales
    $nombre_alumno = (isset($_POST['nombre_alumno'])) ? $_POST['nombre_alumno'] : '';
    $apellido_paterno = (isset($_POST['apellido_paterno'])) ? $_POST['apellido_paterno'] : '';
    $apellido_materno = (isset($_POST['apellido_materno'])) ? $_POST['apellido_materno'] : '';
    $semestre = (isset($_POST['semestre'])) ? $_POST['semestre'] : '';
    $status = (isset($_POST['status'])) ? $_POST['status'] : '';

    //Nacionalidad y Contactos
    $curp = (isset($_POST['curp'])) ? $_POST['curp'] : '';
    $genero = (isset($_POST['genero'])) ? $_POST['genero'] : '';
    $edad = (isset($_POST['edad'])) ? $_POST['edad'] : '';
    $fecha_naci = (isset($_POST['fecha_naci'])) ? $_POST['fecha_naci'] : '';
    $lugar_nacimiento = (isset($_POST['lugar_nacimiento'])) ? $_POST['lugar_nacimiento'] : '';
    $nacionalidad = (isset($_POST['nacionalidad'])) ? $_POST['nacionalidad'] : '';
    $ayuda_espanol = isset($_POST['ayuda_espanol']) ? 1 : 0;
    $telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';
    $correo = (isset($_POST['correo'])) ? $_POST['correo'] : '';

    $edad = !empty($edad) ? $edad : NULL;
$fecha_naci = !empty($fecha_naci) ? $fecha_naci : NULL;

    //Egreso y Salud
    $secundaria_egreso = (isset($_POST['secundaria_egreso'])) ? $_POST['secundaria_egreso'] : '';
    $promedio_secundaria = (isset($_POST['promedio_secundaria'])) ? $_POST['promedio_secundaria'] : '';
    $sangre = (isset($_POST['sangre'])) ? $_POST['sangre'] : '';
    $beca_bancomer = isset($_POST['beca_bancomer']) ? 1 : 0;
    $nss = isset($_POST['nss']) ? 1 : 0;
    $nss_numero = (isset($_POST['nss_numero'])) ? $_POST['nss_numero'] : '';
    $issste = isset($_POST['issste']) ? 1 : 0;
    $issste_numero = (isset($_POST['issste_numero'])) ? $_POST['issste_numero'] : '';
    $discapacidad_mo_psi = isset($_POST['discapacidad_mo_psi']) ? 1 : 0;
    $detalles_discapacidad = (isset($_POST['detalles_discapacidad'])) ? $_POST['detalles_discapacidad'] : '';
    $documento_validacion_discapacidad = isset($_POST['documento_validacion_discapacidad']) ? 1 : 0;
    $alergia = isset($_POST['alergia']) ? 1 : 0;
    $detalles_alergias = (isset($_POST['detalles_alergias'])) ? $_POST['detalles_alergias'] : '';
    $requiere_medicacion = isset($_POST['requiere_medicacion']) ? 1 : 0;
    $medicacion_necesaria = (isset($_POST['medicacion_necesaria'])) ? $_POST['medicacion_necesaria'] : '';
    $lentes_graduados = isset($_POST['lentes_graduados']) ? 1 : 0;
    $aparatos_asistencia = isset($_POST['aparatos_asistencia']) ? 1 : 0;
    $detalles_aparatos_asistencia = (isset($_POST['detalles_aparatos_asistencia'])) ? $_POST['detalles_aparatos_asistencia'] : '';

    $nss = !empty($nss) ? $nss : NULL;
$issste = !empty($issste) ? $issste : NULL;
$nss_numero = !empty($nss_numero) ? $nss_numero : NULL;
$issste_numero = !empty($issste_numero) ? $issste_numero : NULL;
$promedio_secundaria = !empty($promedio_secundaria) ? $promedio_secundaria : NULL;

    //Datos de contacto y tutores
    $calle_numero = (isset($_POST['calle_numero'])) ? $_POST['calle_numero'] : '';
    $colonia = (isset($_POST['colonia'])) ? $_POST['colonia'] : '';
    $codigo_postal = (isset($_POST['codigo_postal'])) ? $_POST['codigo_postal'] : '';
    $dispositivo_internet = isset($_POST['dispositivo_internet']) ? 1 : 0;
    $numero_dispositivos = isset($_POST['numero_dispositivos']) ? $_POST['numero_dispositivos'] : null;
    $nombre_tutor = (isset($_POST['nombre_tutor'])) ? $_POST['nombre_tutor'] : '';
    $telefono_tutor = (isset($_POST['telefono_tutor'])) ? $_POST['telefono_tutor'] : '';
    $nombre_madre = (isset($_POST['nombre_madre'])) ? $_POST['nombre_madre'] : '';
    $telefono_madre = (isset($_POST['telefono_madre'])) ? $_POST['telefono_madre'] : '';
    $nombre_padre = (isset($_POST['nombre_padre'])) ? $_POST['nombre_padre'] : '';
    $telefono_padre = (isset($_POST['telefono_padre'])) ? $_POST['telefono_padre'] : '';

    $codigo_postal = !empty($codigo_postal) ? $codigo_postal : NULL;

    //Entrega de documentos
    $EP_acta_nacimiento = isset($_POST['EP_acta_nacimiento']) ? 1 : 0;
    $EP_CURP = isset($_POST['EP_CURP']) ? 1 : 0;
    $EP_comprobante_domicilio = isset($_POST['EP_comprobante_domicilio']) ? 1 : 0;
    $EP_nss_issste = isset($_POST['EP_nss_issste']) ? 1 : 0;
    $EP_certificado_secundaria = isset($_POST['EP_certificado_secundaria']) ? 1 : 0;
    $EP_ficha_psicopedagogica = isset($_POST['EP_ficha_psicopedagogica']) ? 1 : 0;
    $EP_ficha_buena_conducta = isset($_POST['EP_ficha_buena_conducta']) ? 1 : 0;
    $EP_fotografias = isset($_POST['EP_fotografias']) ? 1 : 0;
    $EP_autenticacion_secundaria = isset($_POST['EP_autenticacion_secundaria']) ? 1 : 0;
    $observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
    $ticket = isset($_POST['ticket']) ? 1 : 0;

    // Insertar los datos en la tabla alumnos
    $insertar = "INSERT INTO `alumnos` (`id_alumno`, `matricula`, `nombre_alumno`, `apellido_paterno`, `apellido_materno`, `status`, `CURP`, `genero`, `edad`, `fecha_naci`, `lugar_nacimiento`, `nacionalidad`, `ayuda_espanol`, `telefono`, `correo`, `secundaria_egreso`, `promedio_secundaria`, `sangre`, `beca_bancomer`, `nss`, `nss_numero`, `issste`, `issste_numero`, `discapacidad_mo_psi`, `detalles_discapacidad`, `documento_validacion_discapacidad`, `alergia`, `detalles_alergias`, `requiere_medicacion`, `medicacion_necesaria`, `lentes_graduados`, `aparatos_asistencia`, `detalles_aparatos_asistencia`, `calle_numero`, `colonia`, `codigo_postal`, `dispositivo_internet`, `numero_dispositivos`, `nombre_tutor`, `telefono_tutor`, `nombre_madre`, `telefono_madre`, `nombre_padre`, `telefono_padre`, `EP_acta_nacimiento`, `EP_CURP`, `EP_comprobante_domicilio`, `EP_nss_issste`, `EP_certificado_secundaria`, `EP_ficha_psicopedagogica`, `EP_ficha_buena_conducta`, `EP_fotografias`, `EP_autenticacion_secundaria`, `observaciones`, `ticket`, `id_grupo`, `id_semestre`, `generacion`) 
                VALUES (NULL,
                 NULL, 
                UPPER(:nombre_alumno),
                UPPER(:apellido_paterno),
                UPPER(:apellido_materno),
                UPPER(:status),
                UPPER(:curp),
                UPPER(:genero),
                :edad,
                :fecha_naci,
                UPPER(:lugar_nacimiento),
                UPPER(:nacionalidad),
                :ayuda_espanol,
                :telefono,
                :correo,
                UPPER(:secundaria_egreso),
                :promedio_secundaria,
                UPPER(:sangre),
                :beca_bancomer,
                :nss,
                :nss_numero,
                :issste,
                :issste_numero,
                :discapacidad_mo_psi,
                UPPER(:detalles_discapacidad),
                :documento_validacion_discapacidad,
                :alergia,
                UPPER(:detalles_alergias),
                :requiere_medicacion,
                UPPER(:medicacion_necesaria),
                :lentes_graduados,
                :aparatos_asistencia,
                UPPER(:detalles_aparatos_asistencia),
                UPPER(:calle_numero),
                UPPER(:colonia),
                :codigo_postal,
                :dispositivo_internet, 
                :numero_dispositivos,
                UPPER(:nombre_tutor),
                :telefono_tutor,
                UPPER(:nombre_madre),
                :telefono_madre,
                UPPER(:nombre_padre),
                :telefono_padre,
                :EP_acta_nacimiento,
                :EP_CURP,
                :EP_comprobante_domicilio,
                :EP_nss_issste, 
                :EP_certificado_secundaria,
                :EP_ficha_psicopedagogica,
                :EP_ficha_buena_conducta,
                :EP_fotografias,
                :EP_autenticacion_secundaria,
                UPPER(:observaciones),
                :ticket,
                1,
                1,
                NULL)";
    $query = $conexion->prepare($insertar);

    // Vincular parámetros usando bindValue
    $query->bindValue(':nombre_alumno', $nombre_alumno);
    $query->bindValue(':apellido_paterno', $apellido_paterno);
    $query->bindValue(':apellido_materno', $apellido_materno);  
    $query->bindValue(':status', $status);
    $query->bindValue(':curp', $curp);
    $query->bindValue(':genero', $genero);
    $query->bindValue(':edad', $edad);
    $query->bindValue(':fecha_naci', $fecha_naci);
    $query->bindValue(':lugar_nacimiento', $lugar_nacimiento);
    $query->bindValue(':nacionalidad', $nacionalidad);
    $query->bindValue(':ayuda_espanol', $ayuda_espanol, PDO::PARAM_INT); // Suponiendo que ayuda_espanol es un entero
    $query->bindValue(':telefono', $telefono);
    $query->bindValue(':correo', $correo);
    $query->bindValue(':secundaria_egreso', $secundaria_egreso);
    $query->bindValue(':promedio_secundaria', $promedio_secundaria);
    $query->bindValue(':sangre', $sangre);
    $query->bindValue(':beca_bancomer', $beca_bancomer, PDO::PARAM_INT); // Suponiendo que beca_bancomer es un entero
    $query->bindValue(':nss', $nss, PDO::PARAM_INT); // Suponiendo que nss_issste es un entero
    $query->bindValue(':nss_numero', $nss_numero, PDO::PARAM_INT); // Suponiendo que nss_issste es un entero
    $query->bindValue(':issste', $issste, PDO::PARAM_INT); // Suponiendo que nss_issste es un entero
    $query->bindValue(':issste_numero', $issste_numero, PDO::PARAM_INT); // Suponiendo que nss_issste es un entero
    $query->bindValue(':discapacidad_mo_psi', $discapacidad_mo_psi, PDO::PARAM_INT); // Suponiendo que discapacidad_mo_psi es un entero
    $query->bindValue(':detalles_discapacidad', $detalles_discapacidad);
    $query->bindValue(':documento_validacion_discapacidad', $documento_validacion_discapacidad, PDO::PARAM_INT); // Suponiendo que documento_validacion_discapacidad es un entero
    $query->bindValue(':alergia', $alergia, PDO::PARAM_INT); // Suponiendo que alergia es un entero
    $query->bindValue(':detalles_alergias', $detalles_alergias);
    $query->bindValue(':requiere_medicacion', $requiere_medicacion, PDO::PARAM_INT); // Suponiendo que requiere_medicacion es un entero
    $query->bindValue(':medicacion_necesaria', $medicacion_necesaria);
    $query->bindValue(':lentes_graduados', $lentes_graduados, PDO::PARAM_INT); // Suponiendo que lentes_graduados es un entero
    $query->bindValue(':aparatos_asistencia', $aparatos_asistencia, PDO::PARAM_INT); // Suponiendo que aparatos_asistencia es un entero
    $query->bindValue(':detalles_aparatos_asistencia', $detalles_aparatos_asistencia);
    $query->bindValue(':calle_numero', $calle_numero);
    $query->bindValue(':colonia', $colonia);
    $query->bindValue(':codigo_postal', $codigo_postal);
    $query->bindValue(':dispositivo_internet', $dispositivo_internet, PDO::PARAM_INT); // Suponiendo que dispositivo_internet es un entero
    $query->bindValue(':numero_dispositivos', $numero_dispositivos);
    $query->bindValue(':nombre_tutor', $nombre_tutor);
    $query->bindValue(':telefono_tutor', $telefono_tutor);
    $query->bindValue(':nombre_madre', $nombre_madre);
    $query->bindValue(':telefono_madre', $telefono_madre);
    $query->bindValue(':nombre_padre', $nombre_padre);
    $query->bindValue(':telefono_padre', $telefono_padre);
    $query->bindValue(':EP_acta_nacimiento', $EP_acta_nacimiento, PDO::PARAM_INT); // Suponiendo que EP_acta_nacimiento es un entero
    $query->bindValue(':EP_CURP', $EP_CURP, PDO::PARAM_INT); // Suponiendo que EP_CURP es un entero
    $query->bindValue(':EP_comprobante_domicilio', $EP_comprobante_domicilio, PDO::PARAM_INT); // Suponiendo que EP_comprobante_domicilio es un entero
    $query->bindValue(':EP_nss_issste', $EP_nss_issste, PDO::PARAM_INT); // Suponiendo que EP_nss_issste es un entero
    $query->bindValue(':EP_certificado_secundaria', $EP_certificado_secundaria, PDO::PARAM_INT); // Suponiendo que EP_certificado_secundaria es un entero
    $query->bindValue(':EP_ficha_psicopedagogica', $EP_ficha_psicopedagogica, PDO::PARAM_INT); // Suponiendo que EP_ficha_psicopedagogica es un entero
    $query->bindValue(':EP_ficha_buena_conducta', $EP_ficha_buena_conducta, PDO::PARAM_INT); // Suponiendo que EP_ficha_buena_conducta es un entero
    $query->bindValue(':EP_fotografias', $EP_fotografias, PDO::PARAM_INT); // Suponiendo que EP_fotografias es un entero
    $query->bindValue(':EP_autenticacion_secundaria', $EP_autenticacion_secundaria, PDO::PARAM_INT);
    $query->bindValue(':observaciones', $observaciones);
    $query->bindValue(':ticket', $ticket, PDO::PARAM_INT);

    $resultado = $query->execute();

    if ($resultado) {
        // Mostrar alerta de actualización exitosa usando JavaScript
        echo "<script>alert('Alumno INGRESADO ÉXITOSAMENTE');
        window.location.href='form_procesa_inscripcion2.php'; die;
        </script>";
    } else {
        echo "Error en la inserción: " . $query->errorInfo()[2];
    }

?>
