<?php
    include_once "../bd/config.php";
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";
    $query = $conexion->prepare($consulta);
    $query->execute();

    $resultado = $query->fetchAll();

   // $filas = mysqli_num_rows($resultado);

    if($resultado) {
        session_start();
        $_SESSION['user'] = $usuario;
       header("location: ../Inscripcion/procesa_inscripcion2.php"); die;
    } else {
        ?>
        <h1>Error en la autenticacion</h1>
        <?php
    }

    // mysqli_free_result($resultado);
    // mysqli_close($conexion);

?>