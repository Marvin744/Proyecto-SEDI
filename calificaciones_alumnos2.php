<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de calificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
  </head>
  <body>
    <br>
    <div class="container">
      <h1 class="text-center" style="background-color: blue; color: white">Tu consulta de calificaciones</h1>
    </div>
    <br>

    <!-- Formulario 1: Seleccionar Alumno -->
    <form action="procesar_seleccion.php" method="POST">
      <div class="container">
        <label class="form-label">Seleccionar Alumno</label>
        <select id="alumnoSelect" name="id_alumno" class="form-select" aria-label="Default select example">
          <option selected disabled>-- Seleccione alumno --</option>
          <?php
            include('config.php');
            $sql1 = "SELECT * FROM alumnos";
            $resultado1 = $con->query($sql1);
            while($row1 = $resultado1->fetch_assoc()) { 
              echo "<option value='".$row1['id_alumno']."'>".$row1['id_alumno']."</option>"; 
            }
          ?>
        </select>
        <button type="submit" class="btn btn-primary mt-2">Enviar</button>
      </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
