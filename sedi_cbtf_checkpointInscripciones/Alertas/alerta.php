<?php require_once "../vistas/encabezado.php"?>
<?php 
	include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar(); 

   
 ?>

<!DOCTYPE html>
<html>
    <form method="POST">
    <head>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <title>Alertas</title>
    </head>
    
    <body>
        <br> 
            <table class="table" border="1">
                <thead>
                    <tr>
                        <th>Id de Reporte</th>
                        <th>Fecha de Reporte</th>
			            <th>Tipo de Alerta</th>
			            <th>Situación</th>
			            <th>Quien Reporta</th>
			            <th>Alumno Reportado</th>
                        <th>Semestre</th>
                        <th>Grupo</th>
                        <th>Especialidad</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <?php 
                    $sql="SELECT * from alertas";
                    $query = $conexion->prepare($sql);
                    $query->execute();
                    $result = $query->fetchAll();

                    foreach ($result as $mostrar){
                ?>

                    <tr>
                        <td style="text-align: center"><?php echo $mostrar['id_alerta'] ?></td>
                        <td style="text-align: center"><?php echo $mostrar['fecha_alerta'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['tipo_alerta'] ?></td>
			            <td><?php echo $mostrar['situacion'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['persona_reporta'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['alumno'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['semestre'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['grupo'] ?></td>
			            <td style="text-align: center"><?php echo $mostrar['especialidad'] ?></td>
                        <th style="text-align: center"><a id="modal" onclick="mostrarDetalles('<?php echo $mostrar['id_alerta'] ?>')" data-toggle="modal">Expandir</a></th>
                        <th style="text-align: center"><a id="modaleditar" onclick="editarDetalles('<?php echo $mostrar['id_alerta'] ?>')" data-toggle="modal">Modificar</a>

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alertaeditar<?php echo $mostrar['id_alerta']; ?>">
                                Modificar
                        </button>
                    </tr> 
                <?php 
	}
	 ?>

            </table>

        </br>   
        <div class="container">
        <div class="row">
            <div class="col-lg-12">            
            <a id="btnInsertarAlerta" type="submit" class="btn" href="agregar_alerta.php">Agregar Reporte</a>
            </div>    
        </div>    
    </div>    
    </br>  
    <div id="divmodal"></div>

    <script>
    function mostrarDetalles(id){
        var ruta = 'modal_alerta_info.php?persona='+id;
        $.get(ruta,function(data){
            $('#divmodal').html(data);
            $('#ModalMostrar').modal('show');
        });
    }

    function editarDetalles(id){
        var ruta = 'modal_alerta_info.php?persona='+id;
        $.get(ruta,function(data){
            $('#divmodal').html(data);
            $('#ModalEditar').modal('show');
        });
    }


    
    </script>

    </body>
</form>
</html>
<?php require_once "../vistas/pie_pagina.php"?>