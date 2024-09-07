<?php 
require_once '../General_Actions/validar_sesion.php';
require_once "../vistas/encabezado.php";
include_once '../bd/config.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$id_usuario = $_GET['id_usuario'];

// Consulta para obtener los detalles actuales del usuario
$sql = "SELECT u.id_usuario, u.perfil, CONCAT(d.apellido_paterno, ' ', d.apellido_materno, ' ', d.nombre_docente) AS nombre_completo
        FROM usuarios u
        JOIN docentes d ON u.id_usuario = d.id_usuario
        WHERE u.id_usuario = :id_usuario";

$query = $conexion->prepare($sql);
$query->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Verifica si se ha actualizado correctamente
$success = isset($_GET['success']) ? $_GET['success'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Perfil</title>
    <style>
        .mod-perfil-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            /* background-color: #f0f0f0; */
        }

        .mod-perfil-container {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 50rem;
            width: 100%;
        }

        .mod-perfil-container h1 {
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        .mod-perfil-mensaje-exito, .mod-perfil-mensaje-error {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .mod-perfil-mensaje-exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mod-perfil-mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .mod-perfil-container label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .mod-perfil-container p {
            margin: 0 0 15px;
            color: #333;
            font-size: 1.7rem;
        }

        .mod-perfil-container select, .mod-perfil-container button {
            width: 100%;
            padding: 10px;
            margin-top: 1.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1.8rem;
        }

        .mod-perfil-container button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .mod-perfil-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="mod-perfil-wrapper">
        <div class="mod-perfil-container">
            <div>
                <h1>Modificar Perfil de Usuario</h1>
            </div>
            
            <div>
                <form action="Actions/actualiza_perfil.php" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

                    <div>
                        <label>Nombre Completo:</label>
                        <p><?php echo htmlspecialchars($usuario['nombre_completo']); ?></p>
                    </div>

                    <div>
                        <label for="perfil">Perfil Actual:</label>
                        <p><?php echo htmlspecialchars($usuario['perfil']); ?></p>
                    </div>

                    <div>
                        <label for="nuevo_perfil">Nuevo Perfil:</label>
                        <select name="nuevo_perfil" id="nuevo_perfil" required>
                            <option value="Admin">Admin</option>
                            <option value="Docente">Docente</option>
                            <option value="Directivo">Directivo</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Administrativo_Jefe">Administrativo en Jefe</option>
                            <option value="Administrativo_Docente">Administrativo Docentes</option>
                            <!-- Agrega más opciones según sea necesario -->
                        </select>
                    </div>

                    <div>
                        <button type="submit">Actualizar Perfil</button>
                    </div>
                </form>
                <br>

                <?php if ($success === '1'): ?>
                    <div id="mensaje" class="mod-perfil-mensaje-exito">El perfil se actualizó correctamente.</div>
                <?php elseif ($success === '0'): ?>
                    <div id="mensaje" class="mod-perfil-mensaje-error">Hubo un error al actualizar el perfil. Por favor, inténtalo de nuevo.</div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>

    <script>
        // Hacer que el mensaje desaparezca después de 5 segundos
        setTimeout(function() {
            var mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.display = 'none';
            }
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>