<?php 
    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Registro</title>

    <script src="https://kit.fontawesome.com/a2dd6045c4.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="styles/normalize.css">

    <style>
        section {
            font-family: Arial, sans-serif;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra más visible */
            width: 100%;
            max-width: 400px;
            margin: 50px auto; /* Para centrar el section */
        }

        fieldset {
            border: none;
            padding: 0;
        }

        legend {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .password-wrapper {
            position: relative;
        }

        input[type="password"], input[type="text"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
            font-family: inherit; /* Asegura que la fuente sea consistente */
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 60%; /* Mover el ícono más abajo */
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .toggle-password:hover {
            color: #333;
        }

        .error-mensaje {
            color: red;
            font-size: 0.9em;
            display: none; /* Oculto por defecto */
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            display: block; /* Hacer que el botón ocupe todo el ancho del bloque padre */
            margin: 20px auto 0 auto; /* Centramos el botón horizontalmente */
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <section>
        <form id="registroForm" action="procesa_actualizaPassword.php" method="POST" onsubmit="return validarContrasena()">
            <fieldset>
                <legend>Cambio de Contraseña</legend>

                <!-- Campo oculto para el nombre de usuario -->
                <input type="hidden" id="usuario" name="usuario" value="<?php echo $_SESSION['user']; ?>">

                <div class="password-wrapper">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword('password', this)">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>

                <div class="password-wrapper">
                    <label for="confirmPassword">Confirmar Contraseña:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                    <span class="toggle-password" onclick="togglePassword('confirmPassword', this)">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>

                <p id="errorMensaje" class="error-mensaje"></p>

                <input type="submit" value="Cambiar">
            </fieldset>
        </form>
    </section>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            // Cambiar el ícono
            if (type === 'text') {
                iconElement.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Ícono de ojo cerrado
            } else {
                iconElement.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Ícono de ojo abierto
            }
        }

        function validarContrasena() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMensaje = document.getElementById('errorMensaje');

            if (password !== confirmPassword) {
                errorMensaje.textContent = "Las contraseñas no coinciden.";
                errorMensaje.style.display = "block";

                // Ocultar el mensaje después de 3 segundos
                setTimeout(() => {
                    errorMensaje.style.display = "none";
                }, 3000);

                return false; // Evita el envío del formulario
            } else {
                errorMensaje.style.display = "none";
                return true; // Permite el envío del formulario
            }
        }
    </script>
</body>
</html>
<?php require_once "../vistas/pie_pagina.php"; ?>