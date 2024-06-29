<?php
$persona = $_REQUEST['persona'];

include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
    

$sql="SELECT * from alertas where $persona=id_alerta";
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
                                <td style="text-align: center"><?php echo $mostrar['condicionamiento'] ?></td>
                            </tr>
                            <tr>
                                <th>Semestre</th>
                                <td style="text-align: center"><?php echo $mostrar['semestre'] ?></td>
                            </tr>
                            <tr>
                                <th>Grupo</th>
                                <td style="text-align: center"><?php echo $mostrar['grupo'] ?></td>
                            </tr>
                            <tr>
                                <th>Especialidad</th>
                                <td style="text-align: center"><?php echo $mostrar['especialidad'] ?></td>
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
                                <th>Parentesco del tutor</th>
                                <td style="text-align: center"><?php echo $mostrar['parentesco_tutor'] ?></td>
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
                                <td style="text-align: center"><?php echo $mostrar['asistencia_padre_tutor'] ?></td>
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
                                <label for="recipient-name" class="col-form-label">ID de la Alerta:</label>
                                <input type="number" name="id_alerta" id="id_alerta" class="form-control"
                                    value="<?php echo $mostrar['id_alerta']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Fecha de la alerta:</label>
                                <input type="text" name="fecha_alerta" class="form-control"
                                    value="<?php echo $mostrar['fecha_alerta']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tipo de Alerta:</label>
                                <input type="text" name="tipo_alerta" id="tipo_alerta" class="form-control"
                                    value="<?php echo $mostrar['tipo_alerta']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Situación:</label>
                                <input type="text" name="situacion" class="form-control"
                                    value="<?php echo $mostrar['situacion']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Observaciones:</label>
                                <input type="text" name="observaciones" class="form-control"
                                    value="<?php echo $mostrar['observaciones']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Persona que Reporta:</label>
                                <input type="text" name="persona_reporta" class="form-control"
                                    value="<?php echo $mostrar['persona_reporta']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Alumno:</label>
                                <input type="text" name="alumno" class="form-control"
                                    value="<?php echo $mostrar['alumno']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Condicionamiento:</label>
                                <input type="text" name="condicionamiento" class="form-control"
                                    value="<?php echo $mostrar['condicionamiento']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Semestre:</label>
                                <input type="number" name="semestre" class="form-control"
                                    value="<?php echo $mostrar['semestre']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Grupo:</label>
                                <input type="text" name="grupo" class="form-control"
                                    value="<?php echo $mostrar['grupo']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Especialidad:</label>
                                <input type="text" name="especialidad" class="form-control"
                                    value="<?php echo $mostrar['especialidad']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Telefono del Alumno:</label>
                                <input type="number" name="telefono_alumno" class="form-control"
                                    value="<?php echo $mostrar['telefono_alumno']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombre del tutor:</label>
                                <input type="text" name="nombre_tutor" class="form-control"
                                    value="<?php echo $mostrar['nombre_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Parentesco del tutor:</label>
                                <input type="text" name="parentesco_tutor" class="form-control"
                                    value="<?php echo $mostrar['parentesco_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Telefono del Tutor:</label>
                                <input type="number" name="telefono_tutor" class="form-control"
                                    value="<?php echo $mostrar['telefono_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Asistió a cita:</label>
                                <input type="number" name="cita" class="form-control"
                                    value="<?php echo $mostrar['cita']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Asistencia del Padre o Tutor:</label>
                                <input type="text" name="asistencia_padre_tutor" class="form-control"
                                    value="<?php echo $mostrar['asistencia_padre_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Canalización:</label>
                                <input type="text" name="canalizacion" class="form-control"
                                    value="<?php echo $mostrar['canalizacion']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Quien Atiende la Alerta:</label>
                                <input type="text" name="quien_atiende" class="form-control"
                                    value="<?php echo $mostrar['quien_atiende']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tratamiento:</label>
                                <input type="text" name="tratamiento" class="form-control"
                                    value="<?php echo $mostrar['tratamiento']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Sanción:</label>
                                <input type="text" name="sancion" class="form-control"
                                    value="<?php echo $mostrar['sancion']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Fecha de Cumplimiento:</label>
                                <input type="text" name="fecha_cumplimiento" class="form-control"
                                    value="<?php echo $mostrar['fecha_cumplimiento']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Evidencia:</label>
                                <input type="text" name="evidencias" class="form-control"
                                    value="<?php echo $mostrar['evidencias']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Quien Atiende la Suspención o las
                                    Horas de SS:</label>
                                <input type="text" name="quien_atiende_suspencion_hss" class="form-control"
                                    value="<?php echo $mostrar['quien_atiende_suspencion_hss']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Seguimiento:</label>
                                <input type="text" name="seguimiento" class="form-control"
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
            
            id_alerta = $.trim($("#id_alerta").val());
            tipo_alerta = $.trim($("#tipo_alerta").val());
            $.ajax({
                url: "recib_update.php",
                type: "POST",
                dataType: "json",
                data: {
                    id_alerta: id_alerta,
                    tipo_alerta: tipo_alerta
                },
                success: function(data) {
                    console.log(data);
                    id_alerta = data[0].id_alerta;
                    tipo_alerta = data[0].tipo_alerta;
                    
                }
            })
        });
        $("#ModalEditar").modal("hide");
    });
}
</script>


</html>