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
  <div class="container">
      <h1 class="text-center" style="background-color: blue; color: white">
      Editar calificaciones</h1>
    </div>
    <br>



 <!--<script>
      function advertencia(){
          var not = confirm("¿ESTÁ SEGURO QUE QUIERE MODIFICAR?");
          return not;
      }
    </script>
    -->









    
    <form class="container" onsubmit="advertencia(event)" action="editar_calificaciones.php" method="POST">
        <?php  
        include_once('config.php');

        $sql = "SELECT * FROM calificaciones WHERE id_alumno =".$_REQUEST['id_alumno'];
        $resultado = $con -> query($sql); 
        
        $row = $resultado-> fetch_assoc();
       ?>
       <!-- los datos pertenecientes a los id estan presentes de manera
        oculta para poder hacer el mapeo de datos ya que estos no se
        modifican por el usuario-->
        <input type="hidden" class="form-control" name="id_docente"
        value="<?php echo $row['id_docente']; ?>">
        <input type="hidden" class="form-control" name="id_alumno"
        value="<?php echo $row['id_alumno']; ?>">
        <input type="hidden" class="form-control" name="id_asignatura"
        value="<?php echo $row['id_asignatura']; ?>">
        <input type="hidden" class="form-control" name="id_especialidad"
        value="<?php echo $row['id_especialidad']; ?>">
    <!-- Se presenta una consulta con la que extraemos los datos 
    para que sean visualisados por el usuario, estos datos no se modifican
    son simplemente una consulta -->
    <div class="mb3">
    <label class="form-label">Nombre</label>
        <select  class="form-select" aria-label="Default select example">
        <option selected disabled>-- Nombre del alumno -- </option>
        <?php
            include('config.php');

            $sql1 = "SELECT * FROM alumnos
            WHERE id_alumno=".$row['id_alumno'];
            $resultado1 = $con -> query($sql1);

            $row1 = $resultado1 -> fetch_assoc();
            
            echo "<option selected value='".$row1['id_alumno']."'>".$row1['nombre_alumno']."</option>"
        ?>
        </select>
    </div>
    <div class="mb3">
    <label class="form-label">Apellido paterno</label>
        <select  class="form-select" aria-label="Default select example">
        <option selected disabled>-- Apellido paterno -- </option>
        <?php
            include('config.php');

            $sql1 = "SELECT * FROM alumnos
            WHERE id_alumno=".$row['id_alumno'];
            $resultado1 = $con -> query($sql1);

            $row1 = $resultado1 -> fetch_assoc();
            
            echo "<option selected value='".$row1['id_alumno']."'>".$row1['apellido_paterno']."</option>"
        ?>
        
        </select>
    </div>
    <div class="mb3">
    <label class="form-label">Apellido materno</label>
        <select  class="form-select" aria-label="Default select example">
        <option selected disabled>-- Apellido materno -- </option>
        <?php
            include('config.php');

            $sql1 = "SELECT * FROM alumnos
            WHERE id_alumno=".$row['id_alumno'];
            $resultado1 = $con -> query($sql1);

            $row1 = $resultado1 -> fetch_assoc();
            
            echo "<option selected value='".$row1['id_alumno']."'>".$row1['apellido_materno']."</option>"
        ?>
        </select>
    </div>
    <!-- A partir de este punto los datos si pueden ser utilizados para 
    hacer las modificaciones necesarias -->
    <div class="mb-3">
        <label class="form-label">Calificación del parcial 1</label>
        <input type="text" class="form-control" name="calificacion_parcial1"
        value="<?php echo $row['calificacion_parcial1']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Calificación del parcial 2</label>
        <input type="text" class="form-control" name="calificacion_parcial2"
        value="<?php echo $row['calificacion_parcial2']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Calificación del parcial 3</label>
        <input type="text" class="form-control" name="calificacion_parcial3"
        value="<?php echo $row['calificacion_parcial3']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Calificación final</label>
        <input type="text" class="form-control" name="calificacion_final"
        value="<?php echo $row['calificacion_final']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Asistencia del parcial 1</label>
        <input type="text" class="form-control" name="asistencia_parcial1"
        value="<?php echo $row['asistencia_parcial1']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Asistencia del parcial 2</label>
        <input type="text" class="form-control" name="asistencia_parcial2"
        value="<?php echo $row['asistencia_parcial2']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Asistencia del parcial 3</label>
        <input type="text" class="form-control" name="asistencia_parcial3"
        value="<?php echo $row['asistencia_parcial3']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Asistencia total</label>
        <input type="text" class="form-control" name="asistencia_total"
        value="<?php echo $row['asistencia_total']; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Acreditacion</label>
        <input type="text" class="form-control" name="acreditacion"
        value="<?php echo $row['acreditacion']; ?>">
    </div>
     <button type="submit" class="btn btn-primary">Modificar datos</button>
  </fieldset>
</form>
    




 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="JS/sweet.js">JS/sweet.js</script>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>