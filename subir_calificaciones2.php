<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subida de calificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">  
  </head>



  <br>
  <body>
<!-- Primera etiqueta correspondiente al titulo -->
    <div class="container">
      <h1 class="text-center" style="background-color: blue; color: white">
      Alta de calificaciones</h1>
    </div>
    <br>
<!-- Los botones para cargar los excel y para enviar los archivos -->
      <td>
      <div class="row">
    <div class="col-md-7">
      <form action="recibe_excel2.php" method="POST" enctype="multipart/form-data"/>
        <div class="file-input text-center">
          <!-- La variable dataCliente es la que se usara para hacer el escaneo
          de los elementos que contiene el archivo csv -->
            <input  type="file" name="dataCliente" id="file-input" class="file-input__input"/>
            <label class="file-input__label" for="file-input">
              <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
              <span>Elegir Archivo Excel</span></label>
          </div>
      </td>
      <td>
            <div class="text-center mt-5">
          <input type="submit" onclick="advertencia(event)" name="subir" class="btn btn-enviar" value="Subir Excel"/>
          
          
      </div>
      </form>
    </div>

      </td>









      <div class="mb3">
      <h6 class="text-center">
         <strong>Lista de Alumnos</strong>
      </h6>
</div>

    
<!-- Contenedor con una tabla dentro de el -->
    <div class="container">
          <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Alumno</th>
            <th scope="col"> </th>
            <th scope="col"> </th>
            <th scope="col">Calificación P1</th>
            <th scope="col">Calificación P2</th>
            <th scope="col">Calificación P3</th>
            <th scope="col">Asistencia P1</th>
            <th scope="col">Asistencia P2</th>
            <th scope="col">Asistencia P3</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>





<!-- inicio del código php para visualizar los datos en la tabla -->          
            <?php
                require('config.php');

                
                  $sql = $con -> query("SELECT * FROM calificaciones 
                    INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
                  ");
                  $i = 1;
                  while ($resultado = $sql-> fetch_assoc()){
                  ?>
                  <tr>
                  <th scope="row"><?php echo $i++; ?></th>
                  <td><?php echo $resultado['nombre_alumno']; ?></td>
                  <td><?php echo $resultado['apellido_paterno']; ?></td>
                  <td><?php echo $resultado['apellido_materno']; ?></td>
                  <td><?php echo $resultado['calificacion_parcial1']; ?></td>
                  <td><?php echo $resultado['calificacion_parcial2']; ?></td>
                  <td><?php echo $resultado['calificacion_parcial3']; ?></td>
                  <td><?php echo $resultado['asistencia_parcial1']; ?></td>
                  <td><?php echo $resultado['asistencia_parcial2']; ?></td>
                  <td><?php echo $resultado['asistencia_parcial3']; ?></td>
                  <td>
                    <a href="editar.php?id_alumno=<?php echo $resultado['id_alumno']?>
                    " class="btn btn-warning">Modificar</a>
                  </td>
                </tr>

              <?php
                }
                ?>
          
        </tbody>
      </table>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>