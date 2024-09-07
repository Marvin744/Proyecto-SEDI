<?php require_once 'General_Actions/validar_sesion.php';?>
<?php require_once "vistas/encabezado.php"?>

<?php
    include_once 'bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Acciones</title>
</head>
<body>
    <div>
        <table class="display" class="table-style-1" id="tablaAlumnos" style="width:100%" >
            <thead class="alinear-centro">
                <tr>
                    <th scope="col">ID Registro</th>
                    <th scope="col">Acción</th>
                    <th scope="col">Fecha y Hora del Registro</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    // Conexión a la base de datos
                    //$conexion = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');
                    $sqlBitacora = "SELECT * FROM log;";
                    $queryTableBitacora = $conexion->prepare($sqlBitacora);
                    $queryTableBitacora->execute();
                    
                    foreach ($queryTableBitacora as $row) {
                        echo "<tr>
                                <td class='alinear-centro'>{$row['id_registro']}</td>
                                <td class='alinear-derecha'>{$row['accion']}</td>
                                <td class='alinear-izquierda'>{$row['fecha_hora']}</td>
                             </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   
    
</body>
</html>