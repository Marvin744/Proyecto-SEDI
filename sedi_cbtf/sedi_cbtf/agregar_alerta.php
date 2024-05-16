<?php 
	include_once 'bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 
 ?>

            <?php
                date_default_timezone_set('America/Mexico_City');
                $fecha=date("Y-m-d");
            ?>

        <form method="POST">    
            <div class="modal-body">
                <div class="form-group">
                <label for="id_alerta">Id Alerta:</label>
                <input type="number" name="id_alerta" id="id_alerta">
                </div>  
                <div class="form-group">
                <label for="fecha_reporte" >Fecha del Reporte:</label>
                <input type="text"  name="fecha_reporte" value="<?= $fecha?>">
                </div>
                <div class="form-group">
                <label for="tipo_alerta" >Tipo de Alerta:</label>
                <input type="text" class="form-control" name="tipo_alerta">
                </div>                
                <div class="form-group">
                <label for="situacion">Situacion:</label>
                <input type="text" class="form-control" name="situacion">
                </div>              
                <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <input type="text" name="observaciones">
                </div>              
                <div class="form-group">
                <label for="alumno">Alumno:</label>
                <input type="text"  name="alumno">
                </div>              
                <div class="form-group">
                <label for="semestre">Semetre:</label>
                <input type="number" name="semestre">
                </div>              
                <div class="form-group">
                <label for="grupo">Grupo:</label>
                <input type="text"  name="grupo">
                </div>               
                <div class="form-group">
                <label for="especialidad">Especialidad:</label>
                <input type="text" name="especialidad">
                </div>      
                <div class="form-group">
                <label for="id_alumno">Id Alumno:</label>
                <input type="number" name="id_alumno">
                </div>       
                <div class="form-group">
                <label for="id_especialidad">Id Especialidad:</label>
                <input type="number" name="id_especialidad">
                </div>   
            </div>
            <div class="modal-footer">
                <button type="submit" name="registrar" value="registrar">Agregar</button>
            </div>

            <?php

$id_alerta = (isset($_POST['id_alerta'])) ? $_POST['id_alerta'] : '';
$fecha_reporte = (isset($_POST['fecha_reporte'])) ? $_POST['fecha_reporte'] : '';
$tipo_alerta = (isset($_POST['tipo_alerta'])) ? $_POST['tipo_alerta'] : '';
$situacion = (isset($_POST['situacion'])) ? $_POST['situacion'] : '';
$observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
$alumno = (isset($_POST['alumno'])) ? $_POST['alumno'] : '';
$semestre = (isset($_POST['semestre'])) ? $_POST['semestre'] : '';
$grupo = (isset($_POST['grupo'])) ? $_POST['grupo'] : '';
$especialidad = (isset($_POST['especialidad'])) ? $_POST['especialidad'] : '';
$id_alumno = (isset($_POST['id_alumno'])) ? $_POST['id_alumno'] : '';
$id_especialidad = (isset($_POST['id_especialidad'])) ? $_POST['id_especialidad'] : ''; 


$consulta= "INSERT INTO `alertas` (`id_alerta`, `fecha_alerta`, `tipo_alerta`, `situacion`, `observaciones`, `persona_reporta`, `alumno`, `condicionamiento`, `semestre`, `grupo`, `especialidad`, `telefono_alumno`, `nombre_tutor`, `parentesco_tutor`, `telefono_tutor`, `cita`, `asistencia_padre_tutor`, `canalizacion`, `quien_atiende`, `tratamiento`, `sancion`, `fecha_cumplimiento`, `evidencias`, `quien_atiende_suspencion_hss`, `seguimiento`, `id_alumno`, `id_especialidad`) VALUES ('$id_alerta', '$fecha_reporte', '$tipo_alerta', '$situacion', '$observaciones', NULL, '$alumno', NULL, '$semestre', '$grupo', '$especialidad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$id_alumno', '$id_especialidad')";
$query = $conexion->prepare($consulta);
$query->execute();
$result = $query->fetch();
?>            

        </form>   
        </div>
    </div>
</div>