<!---------Modal Agregar---------->

<?php
session_start(); // Asegúrate de iniciar la sesión si no lo has hecho

$alumno = $_REQUEST['alumno'];

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 

// Consulta para obtener datos del alumno
$alumnos = "SELECT * FROM alumnos
            INNER JOIN grupo ON alumnos.id_grupo = grupo.id_grupo
            INNER JOIN especialidad ON grupo.id_especialidad = especialidad.id_especialidad
            INNER JOIN semestre ON grupo.id_semestre = semestre.id_semestre
            WHERE alumnos.id_alumno = :alumno
            ORDER BY alumnos.apellido_paterno ASC;";
$query = $conexion->prepare($alumnos);
$query->bindParam(':alumno', $alumno, PDO::PARAM_INT);
$query->execute();
$mostrar = $query->fetch(PDO::FETCH_ASSOC);

// Verifica si se encontraron datos
if ($mostrar !== false) {
    $nombreCompleto = htmlspecialchars($mostrar['nombre_alumno'] . ' ' . $mostrar['apellido_paterno'] . ' ' . $mostrar['apellido_materno'], ENT_QUOTES, 'UTF-8');
} else {
    // Manejo de error si no se encuentran datos
    $nombreCompleto = "No se encontraron datos del alumno";
}

// Consulta para obtener datos del docente
$docente = "SELECT d.nombre_docente, d.apellido_paterno, d.apellido_materno, u.id_usuario 
            FROM docentes d 
            JOIN usuarios u ON d.id_usuario = u.id_usuario
            WHERE u.id_usuario = :id_usuario";
$queryDocente = $conexion->prepare($docente);
$queryDocente->bindParam(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT); // Aquí se usa el ID de la sesión
$queryDocente->execute();
$doc = $queryDocente->fetch(PDO::FETCH_ASSOC);

// Verifica si se encontraron datos del docente
if ($doc !== false) {
    $persona_reporta = htmlspecialchars($doc['nombre_docente'] . ' ' . $doc['apellido_paterno'] . ' ' . $doc['apellido_materno'], ENT_QUOTES, 'UTF-8');
} else {
    // Manejo de error si no se encuentran datos del docente
    $persona_reporta = "No se encontraron datos del docente";
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<style>
/* Bordes redondeados para el modal */
.modal-content {
    border-radius: 15px !important; /* Bordes redondeados */
    border: none; /* Eliminar el borde predeterminado */
    overflow: hidden; /* Asegurar que los bordes redondeados se apliquen correctamente */
}

/* Estilo del encabezado del modal */
.modal-header {
    background-color: #631328 !important; /* Color original del encabezado */
    color: white; /* Texto blanco */
    padding: 15px;
    border-top-left-radius: 15px; /* Bordes redondeados en las esquinas superiores */
    border-top-right-radius: 15px;
}

/* Estilo del botón de cerrar */
.modal-header .close {
    color: white;
    font-size: 1.5rem;
}

/* Estilo de los botones en el pie del modal */
.modal-footer .btn {
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 1.6rem
}

/* Botón de cerrar */
.modal-footer .btn-secondary {
    background-color: #ccc; /* Color gris */
    color: #333; /* Texto oscuro */
}

.modal-footer .btn-secondary:hover {
    background-color: #bbb; /* Hover más oscuro */
}

/* Botón de guardar */
.modal-footer .btn-primary {
    background-color: #003f7f; /* Azul oscuro */
    color: white; /* Texto blanco */
}

.modal-footer .btn-primary:hover {
    background-color: #002f5f; /* Azul más oscuro en hover */
}

/* Asegurar que los inputs y selects se ajusten al tamaño del modal sin desbordarse */
.modal-body .form-group input,
.modal-body .form-group select,
.modal-body .form-group textarea {
    width: 100%; /* Ocupa el 100% del contenedor */
    max-width: 500px; /* Tamaño máximo de 500px */
    padding: 10px;
    color: #333;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
    box-sizing: border-box; /* Asegura que el padding y border se incluyan en el ancho */
}

/* Asegurar que el primer select tenga el mismo comportamiento */
.modal-body .form-group select#tipo_alerta {
    max-width: 500px !important; /* Asegurar que no se desborde */
}
</style>

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
                                <select class="form-control" id="tipo_alerta" name="tipo_alerta">
                                    <option value="INDISCIPLINA">INDISCIPLINA</option>
                                    <option value="ACADEMICO: REPROBACION, FALTAS">ACADEMICO: REPROBACION, FALTAS
                                    </option>
                                    <option value="ADICCIONES">ADICCIONES</option>
                                    <option value="PROBLEMAS EMOCIONALES">PROBLEMAS EMOCIONALES</option>
                                    <option value="DISCAPACIDAD">DISCAPACIDAD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="situacion" id="situacion" class="form-control"
                                    value="NO ATENDIDA">
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
                                <input type="text" name="alumno" id="alumno" class="form-control" readonly
                                    value="<?php echo $nombreCompleto; ?>">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Condicionamiento:</label>
                                <select class="form-control" id="condicionamiento" name="condicionamiento">
                                    <option value="NULL"></option>
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
                            <button type="submit" class="btn btn-primary" name="Editar" id="agregarAlerta">Guardar
                                Cambios</button>
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
            var numero_semestre = $.trim($("#numero_semestre").val());
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
                    numeor_semestre: numero_semestre,
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
                    numero_semestre = data[0].numero_semestre;
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