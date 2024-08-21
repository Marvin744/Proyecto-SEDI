<!---------Modal Agregar---------->

<?php

$alumno = $_REQUEST['alumno'];

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 

$alumnos = "SELECT * FROM alumnos
                    INNER JOIN grupo ON alumnos.id_grupo = grupo.id_grupo
                    INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
                    INNER JOIN semestre ON grupo.id_semestre = semestre.id_semestre
                    WHERE alumnos.id_alumno = $alumno;
                    ORDER BY alumnos.apellido_paterno ASC;";
$query = $conexion->prepare($alumnos);
$query->execute();
$mostrar = $query->fetch();


$nombreCompleto = htmlspecialchars($mostrar['nombre_alumno'] . ' ' . $mostrar['apellido_paterno'] . ' ' . $mostrar['apellido_materno'], ENT_QUOTES, 'UTF-8');
$persona_reporta = 'Mario castañeda';
$id_usuario=1;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <!-- Modal Agregar Alerta -->
    <div class="modal fade" id="ModalAgregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #631328 !important;">
                    <h5 class="modal-title" id="myModalLabel" style="color: #f7f6f2;">Detalles de la Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="recib_insert.php" method="POST">


                    <div class="modal-body" id="cont_modal">

                        <div class="modal-body">
                            <div class="form-group">
                                <!-- <label for="recipient-name" class="col-form-label">ID Alerta:</label> -->
                                <input type="hidden" name="id_alumno" id="id_alumno" class="form-control"
                                    value="<?php echo $mostrar['id_alumno']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tipo de Alerta:</label>
                                <input type="text" name="tipo_alerta" id="tipo_alerta" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Situación:</label>
                                <input type="text" name="situacion" id="situacion" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Observaciones:</label>
                                <input type="text" name="observaciones" id="observaciones" class="form-control">                           
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="persona_reporta" id="persona_reporta" class="form-control"
                                    value="<?php echo $persona_reporta; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Alumno:</label>
                                <input type="text" name="alumno" id="alumno" class="form-control"
                                    value="<?php echo $nombreCompleto; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Condicionamiento:</label>
                                <input type="number" name="condicionamiento" id="condicionamiento" class="form-control">
                                <select class="form-control" id="sangre" name="sangre">
                                        <option value="<?php echo $mostrar['condicionamiento']; ?>"><?php if($mostrar['condicionamiento'] == 1){ echo 'SI'; }else{ echo 'NO'; }; ?></option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="telefono" id="telefono" class="form-control"
                                    value="<?php echo $mostrar['telefono']; ?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="nombre_tutor" id="nombre_tutor" class="form-control"
                                    value="<?php echo $mostrar['nombre_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="telefono_tutor" id="telefono_tutor" class="form-control"
                                    value="<?php echo $mostrar['telefono_tutor']; ?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id_usuario" id="id_usuario" class="form-control"
                                    value="<?php echo $id_usuario ?>">
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="Editar" id="agregarAlerta">Guardar Cambios</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </body>
<script>
 function agregarAlerta() {
    $(document).ready(function() {
         $("#agregarAlerta").submit(function() {
            
             var id_alumno = $.trim($("#id_alumno").val());
             var tipo_alerta = $.trim($("#tipo_alerta").val());
             var situacion = $.trim($("#situacion").val());
             var observaciones = $.trim($("#observaciones").val());
             var alumno = $.trim($("#alumno").val());
             var persona_reporta = $.trim($("#persona_reporta").val());
             var condicionamiento = $.trim($("#condicionamiento").val());
             var nombre_semestre = $.trim($("#nombre_semestre").val());
             var nombre_grupo = $.trim($("#nombre_grupo").val());
             var nombre_especialidad = $.trim($("#nombre_especialidad").val());
             var telefono = $.trim($("#telefono").val());
             var nombre_tutor = $.trim($("#nombre_tutor").val());
             var telefono_tutor = $.trim($("#telefono_tutor").val());
             var id_usuario = $.trim($("#id_usuario").val());
             $.ajax({
                 url: "recib_insert.php",
                 type: "POST",
                 dataType: "json",
                 data: {
                    id_alumno: id_alumno,
                    tipo_alerta: tipo_alerta,
                    situacion: situacion,
                    observaciones: observaciones,
                    alumno: alumno,
                    persona_reporta: persona_reporta,
                    condicionamiento: condicionamiento,
                    nombre_semestre: nombre_semestre,
                    nombre_grupo: nombre_grupo,
                    nombre_especialidad: nombre_especialidad,
                    telefono: telefono,
                    nombre_tutor: nombre_tutor,
                    telefono_tutor: telefono_tutor
                    id_usuario: id_usuario
                    },
                 success: function(data) {
                     console.log(data);
                         // Asignar los valores recibidos en la respuesta AJAX
                         id_alumno = data[0].id_alumno;
                        tipo_alerta = data[0].tipo_alerta;
                        situacion = data[0].situacion;
                        observaciones = data[0].observaciones;
                        alumno = data[0].alumno;
                        persona_reporta = data[0].persona_reporta;
                        condicionamiento = data[0].condicionamiento;
                        nombre_semestre = data[0].nombre_semestre;
                        nombre_grupo = data[0].nombre_grupo;
                        nombre_especialidad = data[0].nombre_especialidad;
                        telefono = data[0].telefono;
                        nombre_tutor = data[0].nombre_tutor;
                        telefono_tutor = data[0].telefono_tutor;
                        id_usuario = data[0].id_usuario;
                    
                 }
             })
         });
         $("#ModalAgregar").modal("show");
     });
 }
</script>


</html>