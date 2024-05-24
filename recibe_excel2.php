<!-- Formulario necesario para leer los archivos de excel .csv -->
<?php
# incluimos la conexion
require('config.php');
# la variable dataCliente que guarda el archivo temporal es usada para
# leer las propiedades del documento
$tipo       = $_FILES['dataCliente']['type'];
$tamanio    = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas     = file($archivotmp);

$i = 0;

# los registros se leen por fila, no se cuenta la primera ya que esta es un encabezadp
foreach ($lineas as $linea) {
    $cantidad_registros = count($lineas);
    $cantidad_regist_agregados =  ($cantidad_registros - 1);

    # validamos que los registros sean mayores a 0
    if ($i != 0) {

        $datos = explode(",", $linea);
       
        $calificacion_parcial1                 = !empty($datos[0])  ? ($datos[0]) : '';
		$calificacion_parcial2                = !empty($datos[1])  ? ($datos[1]) : '';
        $calificacion_parcial3               = !empty($datos[2])  ? ($datos[2]) : '';
        $calificacion_final               = !empty($datos[3])  ? ($datos[3]) : '';
        $asistencia_parcial1               = !empty($datos[4])  ? ($datos[4]) : '';
        $asistencia_parcial2               = !empty($datos[5])  ? ($datos[5]) : '';
        $asistencia_parcial3               = !empty($datos[6])  ? ($datos[6]) : '';
        $asistencia_total               = !empty($datos[7])  ? ($datos[7]) : '';
        $acreditacion              = !empty($datos[8])  ? ($datos[8]) : '';
        $id_docente               = !empty($datos[9])  ? ($datos[9]) : '';
        $id_alumno               = !empty($datos[10])  ? ($datos[10]) : '';
        $id_asignatura               = !empty($datos[11])  ? ($datos[11]) : '';
        $id_especialidad               = !empty($datos[12])  ? ($datos[12]) : '';

        # validaciones para que si el id del alumno se encuentra repetido no se haga una nueva insercción
        if(!empty($id_alumno)){
            $revisar_alumno = ("SELECT id_alumno FROM calificaciones WHERE id_alumno='".($id_alumno)."' ");
                    $revisar_duplicado = mysqli_query($con, $revisar_alumno);
                    $datos_duplicados = mysqli_num_rows($revisar_duplicado);
        }


    # Si no contamos con id repetidos procedemos a dar de alta los datos
    if($datos_duplicados == 0){

    $insertar = "INSERT INTO calificaciones( 
            calificacion_parcial1,
			calificacion_parcial2,
            calificacion_parcial3,
            calificacion_final,
            asistencia_parcial1,
            asistencia_parcial2,
            asistencia_parcial3,
            asistencia_total,
            acreditacion,
            id_docente,
            id_alumno,
            id_asignatura,
            id_especialidad
        ) VALUES(
            '$calificacion_parcial1',
			'$calificacion_parcial2',
            '$calificacion_parcial3',
            '$calificacion_final',
            '$asistencia_parcial1',
            '$asistencia_parcial2',
            '$asistencia_parcial3',
            '$asistencia_total',
            '$acreditacion',
            '$id_docente',
            '$id_alumno',
            '$id_asignatura',
            '$id_especialidad'
        )";
        #mysqli_query($con, $insertar);
        if($con -> query($insertar)){
            header("Location: subir_calificaciones2.php");
            
        }
        else{
            header("Location: subir_calificaciones2.php");
        }
        }
        # si tenemos id duplicado procede a hacer una modificación en los datos que presenten un id duplicado
        else{
            $update_data = ("UPDATE calificaciones SET
            calificacion_parcial1 = '" .$calificacion_parcial1. "',
            calificacion_parcial2 = '" .$calificacion_parcial2. "',
            calificacion_parcial3 = '" .$calificacion_parcial3. "',
            calificacion_final = '" .$calificacion_final. "',
            asistencia_parcial1 = '" .$asistencia_parcial1. "',
            asistencia_parcial2 = '" .$asistencia_parcial2. "',
            asistencia_parcial3 = '" .$asistencia_parcial3. "',
            asistencia_total = '" .$asistencia_total. "',
            acreditacion = '" .$acreditacion. "',
            id_docente = '" .$id_docente. "',
            id_alumno = '" .$id_alumno. "',
            id_asignatura = '" .$id_asignatura. "',
            id_especialidad = '" .$id_especialidad. "'
            WHERE id_alumno = '".$id_alumno."'
            ");
            #$update_info = mysqli_query($con, $update_data);
            if($con -> query($update_data)){
                header("Location: subir_calificaciones2.php");
                
            }
            else{
                header("Location: subir_calificaciones2.php");
            }
        }
    }
    

    # Esta parte solo corresponde a las pruebas, los datos presentes en el csv se mimprimen separados por ,
    #  echo '<div>'. $i. "). " .$linea.'</div>';
    $i++;
}

  # de igual manera solo corresponde a pruebas, el número de datos se cuentan y se imprimen con una variable
  echo '<p style="text-aling:center; color:#333;">Total de Registros: '. $cantidad_regist_agregados .'</p>';

?>

<!-- redireccionamiento a la página anterior -->
<a href="subir_calificaciones2.php">Atras</a>