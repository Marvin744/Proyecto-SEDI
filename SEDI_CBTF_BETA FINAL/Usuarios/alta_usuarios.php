<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <script src="https://kit.fontawesome.com/a2dd6045c4.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="styles/normalize.css">

    <style>
        .password-wrapper {
            position: relative;
            display: inline-block;
        }

        .password-wrapper input {
            padding-right: 30px; /* Espacio para el ícono */
        }

        .toggle-password {
            /*position: absolute;*/
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .error-mensaje {
            color: red;
            font-size: 14px;
            display: none; /* Oculto por defecto */
        }
    </style>
</head>
<body>
    <section>
        <form id="registroForm" action="procesa_usuarios.php" method="POST" onsubmit="return validarContrasena()">
            <fieldset>
                <legend>Registro de Nuevos Usuarios</legend>

                <div>
                    <div>
                        <label for="perfil">Perfil: </label>
                        <select name="perfil" id="perfil" required>
                            <option value="">-- Selecciona una opción --</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Directivo">Directivo</option>
                            <option value="Docente">Docente</option>
                        </select>
                    </div>

                    <div>
                        <label for="usuario">Correo: </label>
                        <input type="email" id="usuario" name="usuario" required>
                    </div>

                    <div>
                        <label for="password">Contraseña: </label>
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword('password')">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>

                    <div>
                        <label for="confirmPassword">Confirmar contraseña: </label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <span class="toggle-password" onclick="togglePassword('confirmPassword')">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>

                    <div>
                        <p id="errorMensaje" class="error-mensaje"></p>
                    </div>

                    <div>
                        <input type="submit" value="Registrar">
                    </div>
                </div>
            </fieldset>
        </form>
    </section>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        function validarContrasena() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMensaje = document.getElementById('errorMensaje');

            if (password !== confirmPassword) {
                errorMensaje.textContent = "Las contraseñas no coinciden.";
                errorMensaje.style.display = "block";
                return false; // Evita el envío del formulario
            } else {
                errorMensaje.style.display = "none";
                return true; // Permite el envío del formulario
            }
        }
    </script>
</body>
</html>