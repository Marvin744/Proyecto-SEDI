<?php
$persona = $_REQUEST['persona'];

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
    

$sql="SELECT 
        a.id_alerta,
        a.fecha_alerta,
        a.tipo_alerta,
        a.situacion,
        a.observaciones,
        a.persona_reporta,
        a.alumno,
        a.condicionamiento,
        a.telefono_alumno,
        a.nombre_tutor,
        a.telefono_tutor,
        a.cita,
        a.asistencia_padre_tutor,
        a.canalizacion,
        a.quien_atiende,
        a.tratamiento,
        a.sancion,
        a.fecha_cumplimiento,
        a.evidencias,
        a.quien_atiende_suspencion_hss,
        a.seguimiento,
        g.nombre_grupo,
        s.numero_semestre,
        e.nombre_especialidad
        from alertas a
        JOIN alumnos al ON a.id_alumno = al.id_alumno
        JOIN grupo g ON al.id_grupo = g.id_grupo
        JOIN semestre s on g.id_semestre = s.id_semestre
        JOIN especialidad e ON g.id_especialidad = e.id_especialidad
        where $persona=id_alerta;";
$query = $conexion->prepare($sql);
$query->execute();
$mostrar = $query->fetch();

?>


<!-- Modal Mostrar Dettalle -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <div class="modal fade" id="ModalMostrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Detalles de la Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table" border="1">
                        <thead>
                            <tr>
                                <th>Id de Reporte</th>
                                <td style="text-align: center"><?php echo $mostrar['id_alerta'] ?></td>
                            </tr>
                            <tr>
                                <th>Fecha de Reporte</th>
                                <td style="text-align: center"><?php echo $mostrar['fecha_alerta'] ?></td>
                            </tr>
                            <tr>
                                <th>Tipo de Alerta</th>
                                <td style="text-align: center"><?php echo $mostrar['tipo_alerta'] ?></td>
                            </tr>
                            <tr>
                                <th>Situación</th>
                                <td style="text-align: center"><?php echo $mostrar['situacion'] ?></td>
                            </tr>
                            <tr>
                                <th>Observaciones</th>
                                <td style="text-align: center"><?php echo $mostrar['observaciones'] ?></td>
                            </tr>
                            <tr>
                                <th>Quien Reporta</th>
                                <td style="text-align: center"><?php echo $mostrar['persona_reporta'] ?></td>
                            </tr>
                            <tr>
                                <th>Alumno Reportado</th>
                                <td style="text-align: center"><?php echo $mostrar['alumno'] ?></td>
                            </tr>
                            <tr>
                                <th>Condicionamiento</th>
                                <td style="text-align: center"><?php if($mostrar['condicionamiento'] == 1){ echo 'SI'; }else{ echo 'NO'; }; ?></td>
                            </tr>
                            <tr>
                                <th>Semestre</th>
                                <td style="text-align: center"><?php echo $mostrar['numero_semestre'] ?></td>
                            </tr>
                            <tr>
                                <th>Grupo</th>
                                <td style="text-align: center"><?php echo $mostrar['nombre_grupo'] ?></td>
                            </tr>
                            <tr>
                                <th>Especialidad</th>
                                <td style="text-align: center"><?php echo $mostrar['nombre_especialidad'] ?></td>
                            </tr>
                            <tr>
                                <th>Telefono del alumno</th>
                                <td style="text-align: center"><?php echo $mostrar['telefono_alumno'] ?></td>
                            </tr>
                            <tr>
                                <th>Nombre tutor</th>
                                <td style="text-align: center"><?php echo $mostrar['nombre_tutor'] ?></td>
                            </tr>
                            <tr>
                                <th>Teléfono del tutor</th>
                                <td style="text-align: center"><?php echo $mostrar['telefono_tutor'] ?></td>
                            </tr>
                            <tr>
                                <th>Cita</th>
                                <td style="text-align: center"><?php echo $mostrar['cita'] ?></td>
                            </tr>
                            <tr>
                                <th>Asistencia del padre/madre de familia o tutor</th>
                                <td style="text-align: center"><?php if($mostrar['asistencia_padre_tutor'] == 1){ echo 'SI'; }else{ echo 'NO'; }; ?></td>
                            </tr>
                            <tr>
                                <th>Canalización</th>
                                <td style="text-align: center"><?php echo $mostrar['canalizacion'] ?></td>
                            </tr>
                            <tr>
                                <th>Quien Atiende</th>
                                <td style="text-align: center"><?php echo $mostrar['quien_atiende'] ?></td>
                            </tr>
                            <tr>
                                <th>Tratamiento</th>
                                <td style="text-align: center"><?php echo $mostrar['tratamiento'] ?></td>
                            </tr>
                            <tr>
                                <th>Sanción</th>
                                <td style="text-align: center"><?php echo $mostrar['sancion'] ?></td>
                            </tr>
                            <tr>
                                <th>Fecha de Cumplimiento</th>
                                <td style="text-align: center"><?php echo $mostrar['fecha_cumplimiento'] ?></td>
                            </tr>
                            <tr>
                                <th>Evidencia</th>
                                <td style="text-align: center"><?php echo $mostrar['evidencias'] ?></td>
                            </tr>
                            <tr>
                                <th>Quien Atiendo Suspención o Horas de Servicio Social</th>
                                <td style="text-align: center"><?php echo $mostrar['quien_atiende_suspencion_hss'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Seguimiento</th>
                                <td style="text-align: center"><?php echo $mostrar['seguimiento'] ?></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Dettalle -->
    <div class="modal fade" id="ModalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #631328 !important;">
                    <h5 class="modal-title" id="myModalLabel" style="color: #f7f6f2;">Detalles de la Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="recib_update.php" method="POST">


                    <div class="modal-body" id="cont_modal">

                        <div class="modal-body">
                            <div class="form-group">
                                <!-- <label for="recipient-name" class="col-form-label">ID Alerta:</label> -->
                                <input type="hidden" name="id_alerta" id="id_alerta" class="form-control"
                                    value="<?php echo $mostrar['id_alerta']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tipo de Alerta:</label>
                                <select class="form-control" id="tipo_alerta" name="tipo_alerta">
                                        <option value="<?php echo $mostrar['tipo_alerta']; ?>"><?php echo $mostrar['tipo_alerta']; ?></option>
                                        <option value="INDISCIPLINA">INDISCIPLINA</option>
                                        <option value="ACADEMICO: REPROBACION, FALTAS">ACADEMICO: REPROBACION, FALTAS</option>
                                        <option value="ADICCIONES">ADICCIONES</option>
                                        <option value="PROBLEMAS EMOCIONALES">PROBLEMAS EMOCIONALES</option>
                                        <option value="DISCAPACIDAD">DISCAPACIDAD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Situación:</label>
                                <select class="form-control" id="situacion" name="situacion">
                                        <option value="<?php echo $mostrar['situacion']; ?>"><?php echo $mostrar['situacion']; ?></option>
                                        <option value="NO ATENDIDA">NO ATENDIDA</option>
                                        <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                                        <option value="ATENDIDA">ATENDIDA</option>
                                        <option value="CUMPLIÓ CON SU HSS">CUMPLIÓ CON SU HSS</option>
                                        <option value="NO APLICA SANCIÓN">NO APLICA SANCIÓN</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Observaciones:</label>
                                <input type="text" name="observaciones" id="observaciones" class="form-control"
                                    value="<?php echo $mostrar['observaciones']; ?>">
                            </div>
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Persona que Reporta:</label>
                                <input type="text" name="persona_reporta" class="form-control"
                                    value="<?php echo $mostrar['persona_reporta']; ?>">
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Alumno:</label>
                                <input type="text" name="alumno" class="form-control"
                                    value="<?php echo $mostrar['alumno']; ?>">
                            </div> -->
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Condicionamiento:</label>
                                <select class="form-control" id="condicionamiento" name="condicionamiento">
                                        <option value="<?php echo $mostrar['condicionamiento']; ?>"><?php if($mostrar['condicionamiento'] == 1){ echo 'SI'; }else{ echo 'NO'; }; ?></option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                 </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Semestre:</label>
                                <input type="number" name="semestre" class="form-control"
                                    value="<?php echo $mostrar['numero_semestre']; ?>">
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Grupo:</label>
                                <input type="text" name="grupo" class="form-control"
                                    value="<?php echo $mostrar['nombre_grupo']; ?>">
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Especialidad:</label>
                                <input type="text" name="especialidad" class="form-control"
                                    value="<?php echo $mostrar['nombre_especialidad']; ?>">
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Telefono del Alumno:</label>
                                <input type="number" name="telefono_alumno" class="form-control"
                                    value="<?php echo $mostrar['telefono_alumno']; ?>">
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombre del tutor:</label>
                                <input type="text" name="nombre_tutor" class="form-control"
                                    value="<?php echo $mostrar['nombre_tutor']; ?>"
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Telefono del Tutor:</label>
                                <input type="number" name="telefono_tutor" class="form-control"
                                    value="<?php echo $mostrar['telefono_tutor']; ?>">
                            </div> -->
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Asistió a cita:</label>
                                <input type="datetime-local" name="cita" id="cita" class="form-control"
                                    value="<?php echo $mostrar['cita']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Asistencia del Padre o Tutor:</label>
                                <select class="form-control" id="asistencia_padre_tutor" name="asistencia_padre_tutor">
                                        <option value="<?php echo $mostrar['asistencia_padre_tutor']; ?>"><?php if($mostrar['asistencia_padre_tutor'] == 1){ echo 'SI'; }else{ echo 'NO'; }; ?></option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                 </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Canalización:</label>
                                <input type="text" name="canalizacion" id="canalizacion" class="form-control"
                                    value="<?php echo $mostrar['canalizacion']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Quien Atiende la Alerta:</label>
                                <input type="text" name="quien_atiende" id="quien_atiende" class="form-control"
                                    value="<?php echo $mostrar['quien_atiende']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tratamiento:</label>
                                <input type="text" name="tratamiento" id="tratamiento" class="form-control"
                                    value="<?php echo $mostrar['tratamiento']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Sanción:</label>
                                <input type="text" name="sancion" id="sancion" class="form-control"
                                    value="<?php echo $mostrar['sancion']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Fecha de Cumplimiento:</label>
                                <input type="date" name="fecha_cumplimiento" id="fechacumplimiento" class="form-control"
                                    value="<?php echo $mostrar['fecha_cumplimiento']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Evidencia:</label>
                                <input type="text" name="evidencias" id="evidencias" class="form-control"
                                    value="<?php echo $mostrar['evidencias']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Quien Atiende la Suspención o las
                                    Horas de SS:</label>
                                <input type="text" name="quien_atiende_suspencion_hss" id="quien_atiende_suspencion_hss" class="form-control"
                                    value="<?php echo $mostrar['quien_atiende_suspencion_hss']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Seguimiento:</label>
                                <input type="text" name="seguimiento" id="seguimiento" class="form-control"
                                    value="<?php echo $mostrar['seguimiento']; ?>">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="Editar" id="editarAlerta">Guardar Cambios</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
function editarDetalles() {
    $(document).ready(function() {
        $("#editarAlerta").submit(function() {
            
            var id_alerta = $.trim($("#id_alerta").val());
            var tipo_alerta = $.trim($("#tipo_alerta").val());
            var situacion = $.trim($("#situacion").val());
            var observaciones = $.trim($("#observaciones").val());
            var condicionamiento = $.trim($("#condicionamiento").val());
            var cita = $.trim($("#cita").val());
            var asistencia_padre_tutor = $.trim($("#asistencia_padre_tutor").val());
            var canalizacion = $.trim($("#canalizacion").val());
            var quien_atiende = $.trim($("#quien_atiende").val());
            var tratamiento = $.trim($("#tratamiento").val());
            var sancion = $.trim($("#sancion").val());
            var fecha_cumplimiento = $.trim($("#fecha_cumplimiento").val());
            var evidencias = $.trim($("#evidencias").val());
            var quien_atiende_suspencion_hss = $.trim($("#quien_atiende_suspencion_hss").val());
            var seguimiento = $.trim($("#seguimiento").val());
            $.ajax({
                url: "recib_update.php",
                type: "POST",
                dataType: "json",
                data: {
                    id_alerta: id_alerta,
                    tipo_alerta: tipo_alerta,
                    situacion: situacion,
                    observaciones: observaciones,
                    condicionamiento: condicionamiento,
                    cita: cita,
                    asistencia_padre_tutor: asistencia_padre_tutor,
                    canalizacion: canalizacion,
                    quien_atiende: quien_atiende,
                    tratamiento: tratamiento,
                    sancion: sancion,
                    fecha_cumplimiento: fecha_cumplimiento,
                    evidencias: evidencias,
                    quien_atiende_suspencion_hss: quien_atiende_suspencion_hss,
                    seguimiento: seguimiento
                },
                success: function(data) {
                    console.log(data);
                        // Asignar los valores recibidos en la respuesta AJAX
                        id_alerta = data[0].id_alerta;
                        tipo_alerta = data[0].tipo_alerta;
                        situacion = data[0].situacion;
                        observaciones = data[0].observaciones;
                        condicionamiento = data[0].condicionamiento;
                        cita = data[0].cita;
                        asistencia_padre_tutor = data[0].asistencia_padre_tutor;
                        canalizacion = data[0].canalizacion;
                        quien_atiende = data[0].quien_atiende;
                        tratamiento = data[0].tratamiento;
                        sancion = data[0].sancion;
                        fecha_cumplimiento = data[0].fecha_cumplimiento;
                        evidencias = data[0].evidencias;
                        quien_atiende_suspencion_hss = data[0].quien_atiende_suspencion_hss;
                        seguimiento = data[0].seguimiento;
                    
                }
            })
        });
        $("#ModalEditar").modal("show");
    });
}
</script>


</html>