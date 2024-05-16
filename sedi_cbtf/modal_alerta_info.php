<?php
$persona = $_REQUEST['persona'];

include_once 'bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
    

$sql="SELECT * from alertas";
$query = $conexion->prepare($sql);
$query->execute();
$mostrar = $query->fetch();
?>


<!-- Modal -->
            <div class="modal-body">
            <table class="table" border="1">
                <thead>
                    <tr>
                        <th>Id de Reporte</th> <td style="text-align: center"><?php echo $mostrar['id_alerta'] ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Reporte</th> <td style="text-align: center"><?php echo $mostrar['fecha_alerta'] ?></td>
                    </tr>
                    <tr>
			            <th>Tipo de Alerta</th> <td style="text-align: center"><?php echo $mostrar['tipo_alerta'] ?></td>
                    </tr>
                    <tr>
			            <th>Situación</th> <td style="text-align: center"><?php echo $mostrar['situacion'] ?></td>
                    </tr>
                    <tr>
			            <th>Observaciones</th> <td style="text-align: center"><?php echo $mostrar['observaciones'] ?></td>
                    </tr>
                    <tr>
			            <th>Quien Reporta</th> <td style="text-align: center"><?php echo $mostrar['persona_reporta'] ?></td>
                    </tr>
                    <tr>
			            <th>Alumno Reportado</th> <td style="text-align: center"><?php echo $mostrar['alumno'] ?></td>
                    </tr>
                    <tr>
			            <th>Condicionamiento</th> <td style="text-align: center"><?php echo $mostrar['condicionamiento'] ?></td>
                    </tr>
                    <tr>
                        <th>Semestre</th> <td style="text-align: center"><?php echo $mostrar['semestre'] ?></td>
                    </tr>
                    <tr>
                        <th>Grupo</th> <td style="text-align: center"><?php echo $mostrar['grupo'] ?></td>
                    </tr>
                    <tr>
                        <th>Especialidad</th> <td style="text-align: center"><?php echo $mostrar['especialidad'] ?></td>
                    </tr>
                    <tr>
                        <th>Telefono del alumno</th> <td style="text-align: center"><?php echo $mostrar['telefono_alumno'] ?></td>
                    </tr>
                    <tr>
                        <th>Nombre tutor</th> <td style="text-align: center"><?php echo $mostrar['nombre_tutor'] ?></td>
                    </tr>
                    <tr>
                        <th>Parentesco del tutor</th> <td style="text-align: center"><?php echo $mostrar['parentesco_tutor'] ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono del tutor</th> <td style="text-align: center"><?php echo $mostrar['telefono_tutor'] ?></td>
                    </tr>
                    <tr>
                        <th>Cita</th> <td style="text-align: center"><?php echo $mostrar['cita'] ?></td>
                    </tr>
                    <tr>
                        <th>Asistencia del padre/madre de familia o tutor</th> <td style="text-align: center"><?php echo $mostrar['asistencia_padre_tutor'] ?></td>
                    </tr>
                    <tr>
                        <th>Canalización</th> <td style="text-align: center"><?php echo $mostrar['canalizacion'] ?></td>
                    </tr>
                    <tr>
                        <th>Quien Atiende</th> <td style="text-align: center"><?php echo $mostrar['quien_atiende'] ?></td>
                    </tr>
                    <tr>
                        <th>Tratamiento</th> <td style="text-align: center"><?php echo $mostrar['tratamiento'] ?></td>
                    </tr>
                    <tr>
                        <th>Sanción</th> <td style="text-align: center"><?php echo $mostrar['sancion'] ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Cumplimiento</th> <td style="text-align: center"><?php echo $mostrar['fecha_cumplimiento'] ?></td>
                    </tr>
                    <tr>
                        <th>Evidencia</th> <td style="text-align: center"><?php echo $mostrar['evidencias'] ?></td>
                    </tr>
                    <tr>
                        <th>Quien Atiendo Suspención o Horas de Servicio Social</th> <td style="text-align: center"><?php echo $mostrar['quien_atiende_suspencion_hss'] ?></td>
                    </tr>
                    <tr>
                        <th>Seguimiento</th> <td style="text-align: center"><?php echo $mostrar['seguimiento'] ?></td>
                    </tr>
                </thead>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>