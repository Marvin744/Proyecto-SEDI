<?php
    $modulo = $_REQUEST['id'];

    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 
        

    $sql="SELECT * FROM modulos WHERE $modulo=id_modulo";
    $query = $conexion->prepare($sql);
    $query->execute();
    $mostrar = $query->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
 <!-- Modal Editar Dettalle -->
 <div class="modal fade" id="modalModulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #631328 !important;">
                    <h5 class="modal-title" id="myModalLabel" style="color: #f7f6f2;">Detalles de la Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="procesar_modificacionModulo.php" >


                    <div class="modal-body" id="cont_modal">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">ID Modulo:</label>
                                <input type="number" name="id_modulo" id="id_modulo" class="form-control"
                                    value="<?php echo $mostrar['id_modulo']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Especialidad:</label>
                                <input type="text" name="especialidad" class="form-control"
                                    value="<?php echo $mostrar['especialidad']; ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombre Modulo:</label>
                                <input type="text" name="nombre_modulo" class="form-control"
                                    value="<?php echo $mostrar['nombre_modulo']; ?>" required="true">
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="Editar" id="editarModulo">Guardar Cambios</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

</body>

<script>
function editarDetalles() {
    $(document).ready(function() {
        $("#editarModulo").submit(function() {
            
            id_modulo = $.trim($("#id_modulo").val());
            nombre_modulo = $.trim($("#nombre_modulo").val());
            especialidad = $.trim($("#especialidad").val());
            $.ajax({
                url: "procesar_modificacionModulo.php",
                type: "POST",
                dataType: "json",
                data: {
                    id_modulo: id_modulo,
                    nombre_modulo: nombre_modulo,
                    especialidad: especilaidad
                },
                success: function(data) {
                    console.log(data);
                    id_modulo = data[0].id_modulo;
                    nombre_modulo = data[0].nombre_modulo;
                    especialidad = data[0].especialidad;
                    
                }
            })
        });
        $("#modalModulo").modal("hide");
    });
}
</script>

</html>