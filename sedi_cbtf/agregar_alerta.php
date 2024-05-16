<?php 
	$conexion=mysqli_connect('localhost','root','','sedi_cbtf');
 ?>

            <?php
                date_default_timezone_set('America/Mexico_City');
                $fecha=date("Y-m-d");
            ?>

        <form method="POST">    
            <div class="modal-body">
                <div class="form-group">
                <label for="id_alerta">Id Alerta:</label>
                <input type="number" name="id_alerta">
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
                <button type="submit" value="Registrar">Agregar</button>
            </div>
        </form>   

        <?php
                $id_alerta=$_POST['id_alerta'];
                $fecha_reporte=$_POST['fecha_reporte'];
                $tipo_alerta=$_POST['tipo_alerta'];
                $situacion=$_POST['situacion'];
                $observaciones=$_POST['observaciones'];
                $alumno=$_POST['alumno'];
                $semestre=$_POST['semestre'];
                $grupo=$_POST['grupo'];
                $especialidad=$_POST['especialidad'];
                $id_alumno=$_POST['id_alumno'];
                $id_especialidad=$_POST['id_especialidad'];

                $consulta= "INSERT INTO alertas VALUES ($id_alerta,'$fecha_reporte','$tipo_alerta','$situacion','$observaciones','$alumno',$semestre,'$grupo','$especialidad',$id_alumno,$id_especialidad)";
                $resultado= mysqli_query($conexion,$consulta);
                if($resultado){
                    echo "Datos agregados Correctamente";
                }else{
                    echo "Error al ingresar los datos";
                }
                mysqli_close($conexion);
                ?>

        </div>
    </div>
</div>