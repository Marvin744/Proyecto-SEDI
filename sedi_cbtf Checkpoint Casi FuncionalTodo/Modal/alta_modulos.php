<?php require_once "../vistas/encabezado.php"?>
<?php
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Alta Módulos y Submódulos</title>
</head>
<body>
    <h1>Módulos</h1>

    <form method="POST" action="procesar_altaModulo">
        <fieldset>
            <legend>Ingrese los datos solicitados</legend>
            <div>
                <label for="d_modulo">ID Módulo: </label>
                <input type="number" name="id_modulo" >
                    
                <label for="nombre_modulo">Nombre del Módulo:</label>
                <input type="text" name="nombre_modulo" required>
            </div>

            <div class="boton_altaModulo">
                <input type="submit" value="Enviar"> 
            </div>
        </fieldset>

    </form>

    <div>
        <table class="tabla_modulos">
            <thead>
                <tr>
                    <th scope="col">ID Modulo</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Nombre Módulo</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $sqlModulos = ("SELECT * FROM modulos ORDER BY especialidad ASC;");
                    $queryTable = $conexion->prepare($sqlModulos);
                    $queryTable->execute();
                    foreach ($queryTable as $row) {
                ?>
                    <tr>
                        <td><?php echo $row['id_modulo']; ?></td>
                        <td><?php echo $row['especialidad']; ?></td>
                        <td><?php echo $row['nombre_modulo']; ?></td>
                        <td><a id="modal_modificarModulo" onclick="modificarModulo('<?php echo $row['id_modulo'] ?>')" data-toggle="modal">Modificar</a></td>

                    </tr>
            </tbody>
                <?php
                    }   
                ?>
            

        </table>
    </div>

    <div id="divModal"></div>

    <script>
        function modificarModulo(id) {
            var ruta = 'modal_modulos.php?id='+id;
            $.get(ruta, function(data) {
                $('#divModal').html(data);
                $('#modalModulo').modal('show');
            });
        }
    </script>
    
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"?>