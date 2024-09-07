<?php
$persona = $_REQUEST['persona'];

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
    

$sql="SELECT *
        from alumnos a
        JOIN usuarios u ON a.id_usuario = u.id_usuario
        JOIN grupo g ON a.id_grupo = g.id_grupo
        JOIN semestre s on g.id_semestre = s.id_semestre
        JOIN especialidad e ON g.id_especialidad = e.id_especialidad
        where $persona=id_alumno;";
$query = $conexion->prepare($sql);
$query->execute();
$mostrar = $query->fetch();

$nombreCompleto = htmlspecialchars($mostrar['nombre_alumno'] . ' ' . $mostrar['apellido_paterno'] . ' ' . $mostrar['apellido_materno'], ENT_QUOTES, 'UTF-8');

?>


<!-- Modal Mostrar Dettalle -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <div class="modal fade" id="ModalUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                <th>Nombre del Alumno</th>
                                <td style="text-align: center"><?php echo $nombreCompleto ?></td>
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
                                <th>Usuario</th>
                                <td style="text-align: center"><?php echo $mostrar['usuario'] ?></td>
                            </tr>
                            <tr>
                                <th>Contraseña</th>
                                <td style="text-align: center"><?php echo $mostrar['password'] ?></td>
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