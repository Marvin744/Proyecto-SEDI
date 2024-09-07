<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';

    if (!isset($_SESSION['perfil'])) {
        header("Location: ../login.html");
        exit;
    }
    $perfil = $_SESSION['perfil'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <!-- <title>SEDI CBTF</title> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" type="text/css" href="../styles/style_index2.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

        // Función para marcar una notificación como leída y redirigir
        function marcarNotificacionComoLeidaYRedirigir(mensaje, url) {
            fetch('../Alertas/Notificaciones/marcar_leido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mensaje: mensaje })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = url; // Redirigir al formulario después de marcar como leída
                } else {
                    console.error('No se pudo marcar como leída.');
                }
            })
            .catch(error => console.error('Error al marcar como leída:', error));
        }

        // Función para marcar una notificación como leída
        function marcarNotificacionComoLeida(mensaje, button) {
            fetch('../Alertas/Notificaciones/marcar_leido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mensaje: mensaje })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    let listItem = button.closest('li');
                    let linkElement = listItem.querySelector('a');
                    linkElement.style.textDecoration = 'line-through';
                    linkElement.style.color = '#999';
                    button.style.display = 'none'; // Oculta el botón de "Marcar como Leída"
                    actualizarContadorNotificaciones(); // Actualizar el contador después de marcar como leída
                } else {
                    console.error('No se pudo marcar como leída.');
                }
            })
            .catch(error => console.error('Error al marcar como leída:', error));
        }

        // Función para cargar las notificaciones
        function cargarNotificaciones() {
            fetch('../Alertas/Notificaciones/notificaciones.php')
                .then(response => response.json())
                .then(data => {
                    let notificationList = $('.notification-list ul');
                    let notificationCount = $('.notification-count');
                    
                    notificationList.empty(); // Limpiar notificaciones anteriores
                    
                    if (data.length > 0) {
                        notificationCount.text(data.length); // Mostrar el número de notificaciones
                        
                        data.forEach(notification => {
                            let listItem = $(`
                                <li>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="flex-grow: 1; cursor: pointer;" onclick="marcarNotificacionComoLeidaYRedirigir('${notification.mensaje}', '../Alertas/form_alerta.php')">
                                            <a href="#">${notification.mensaje}</a>
                                            <span>${new Date(notification.fecha_creacion).toLocaleString()}</span>
                                        </div>
                                        <div>
                                            <button class="btn-form">Leído</button>
                                        </div>
                                    </div>
                                </li>
                            `);

                            // Evento para marcar como leída al hacer clic en el mensaje y redirigir
                            listItem.find('a').on('click', function(e) {
                                e.preventDefault();
                                marcarNotificacionComoLeidaYRedirigir(notification.mensaje, '../Alertas/form_alerta.php');
                            });

                            // Evento para marcar como leída al hacer clic en el botón
                            listItem.find('button').on('click', function() {
                                marcarNotificacionComoLeida(notification.mensaje, this);
                            });

                            notificationList.append(listItem);
                        });
                    } else {
                        notificationCount.text('0'); // No hay notificaciones
                        notificationList.html('<li><a href="#">No hay nuevas notificaciones</a></li>');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Función para actualizar solo el número de notificaciones en el ícono
        function actualizarContadorNotificaciones() {
            fetch('../Alertas/Notificaciones/notificaciones.php')
                .then(response => response.json())
                .then(data => {
                    let notificationCount = $('.notification-count');
                    if (data.length > 0) {
                        notificationCount.text(data.length);
                        notificationCount.show(); // Mostrar el contador si hay notificaciones
                    } else {
                        notificationCount.hide(); // Ocultar el contador si no hay notificaciones
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Mostrar u ocultar notificaciones al hacer clic en el icono
        $('.notification-icon').on('click', function(event) {
            $('.notification-list').toggleClass('show'); // Alternar la visibilidad de la lista
            cargarNotificaciones(); // Cargar notificaciones cuando se haga clic en el icono
            event.stopPropagation(); // Evitar que el clic se propague
        });

        // Ocultar las notificaciones si se hace clic en cualquier lugar fuera de la lista de notificaciones
        $(document).click(function(event) {
            if (!$(event.target).closest('.notification-icon').length && !$(event.target).closest('.notification-list').length) {
                $('.notification-list').removeClass('show');
            }
        });

        // Cargar el contador de notificaciones al iniciar
        actualizarContadorNotificaciones();

        // Cargar notificaciones cada 60 segundos (opcional)
        setInterval(actualizarContadorNotificaciones, 3000);
        });
    </script>
    
    <script>
        posicionarMenu();

        $(window).scroll(function() {
            posicionarMenu();
        });

        function posicionarMenu() {
            var altura_del_header = $('.header').outerHeight(true);
            var altura_del_menu = $('.navbar').outerHeight(true);

            if ($(window).scrollTop() >= altura_del_header) {
                $('.navbar').addClass('fixed');
                $('.wrapper').css('margin-top', (altura_del_menu) + 'px');
            } else {
                $('.navbar').removeClass('fixed');
                $('.wrapper').css('margin-top', '0');
            }
        }
    </script>

    <style>
.search_icon {
    display: flex;
    align-items: center;
}

.notification-icon {
    position: relative;
    cursor: pointer;
    margin-left: 20px; /* Espacio a la izquierda del ícono */
    margin-right: 20px; /* Espacio a la derecha del ícono */
    color: white;
}

.notification-icon .notification-count {
    position: absolute;
    top: -10px;
    right: -10px;
    background: red;
    color: white;
    border-radius: 50%;
    padding: 5px;
    font-size: 12px;
}

.notification-list {
    display: none;
    position: absolute;
    right: 15rem;
    top: 100%;
    background: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 300px;
    max-height: 300px;
    overflow-y: auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.notification-list.show {
    display: block;
}

.notification-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.notification-list ul li {
    padding: 10px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0; /* Asegúrate de que no haya margen inferior */
}

.notification-list ul li a {
    text-decoration: none;
    color: #333;
    cursor: pointer;
    font-size: 14px; /* Tamaño de la fuente */
    line-height: 1.2; /* Espacio entre líneas */
    margin-right: 10px; /* Margen derecho entre texto y botón */
    margin-bottom: 0; /* Asegúrate de que no haya margen inferior */
}

.notification-list ul li span {
    font-size: 12px;
    color: #999;
    line-height: 1.2; /* Espacio entre líneas */
    margin-bottom: 0; /* Asegúrate de que no haya margen inferior */
}

.btn-form {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 12px;
    margin-left: 10px; /* Margen izquierdo */
    margin-bottom: 0; /* Asegúrate de que no haya margen inferior */
}

.btn-form:hover {
    background-color: #218838;
}
    </style>
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="#" class="logo">
                <img src="../img/LogoCBTF2_minimalista.png" alt="Logo">
                <span style="color: #f7f6f2;" style="font-size: 30px">CBTF #2</span>
            </a>

            <a class="navbar-brand mb-0" style="color: #f7f6f2;">Sistema Escolar De Información</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            </button>

            <div class="search_icon">
                <span style="color: #ffffff">
                    <?php
                    if (isset($_SESSION['nombre_completo'])) {
                        echo $_SESSION['nombre_completo'];
                    } else {
                        echo 'Usuario';
                    }
                    ?>
                </span>
                <!-- Colocar el botón de notificaciones aquí, junto al nombre del usuario -->
                <div class="notification-icon">
                    <i class="fa fa-bell"></i>
                    <span class="notification-count" style="display:none;">0</span>
                </div>
                <a href="../General_Actions/logout.php">
                    <img src="../img/icono_cerrar_sesion.png">
                    <span class="padding_left" style="color: #ffffff">Cerrar Sesión</span>
                </a>
            </div>

            <!-- Mover el contenedor de la lista de notificaciones aquí -->
            <div class="notification-list">
                <ul></ul>
            </div>
            <div class="wrapper"></div>
        </nav>
    </div>

    <form method="POST">
        <div class="menu">
            <ion-icon name="menu-outline"></ion-icon>
            <ion-icon name="close-outline"></ion-icon>
        </div>

        <div class="barra-lateral">
            <div>
                <div class="nombre-pagina">
                    <ion-icon id="cloud" name="cloud-outline"></ion-icon>
                    <a>SEDI</a>
                </div>
                <!-- <a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                    </svg>
                    <span>Alumnos</span>
                </a> -->
            </div>

            <nav class="navegacion">
                <ul>
                    <?php
                    switch ($perfil) {
                        // Perfil PODEROSÍSIMO ADMIN
                        case 'Admin':
                            echo '<a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span>Alumnos</span>
                                </a>';
                            echo '<li><a href="../Docentes/form_docente_f19.php"><ion-icon name="home-outline"></ion-icon><span>Formato 19</span></a></li>';
                            echo '<li><a href="../Docentes/form_alta_docente.php"><ion-icon name="home-outline"></ion-icon><span>Registrar Personal</span></a></li>';
                            echo '<li><a href="../Usuarios/form_perfilUsuario.php"><ion-icon name="people-outline"></ion-icon><span>Gestionar Usuarios</span></a></li>';
                            echo '<li><a href="../Alertas/form_alerta.php"><ion-icon name="stats-chart-outline"></ion-icon><span>Alertas</span></a></li>';
                            echo '<li><a href="../Alertas/form_agregarAlerta.php"><ion-icon name="stats-chart-outline"></ion-icon><span>Nueva Alerta</span></a></li>';
                            echo '<li><a href="../Usuarios/form_olvidar_password.php"><ion-icon name="checkmark-outline"></ion-icon><span>Resetear Docente</span></a></li>';
                            break;

                        // Perfil DOCENTE
                        case 'Docente':
                            echo '<a class="boton" href="../Calificaciones/form_calAsis_alumnos.php">
                                    <ion-icon name="book-outline"></ion-icon><span>Calificaciones</span>
                                </a>';
                            // echo '<li><a href="../Calificaciones/form_calAsis_alumnos.php"><ion-icon name="book-outline"></ion-icon><span>Calificaciones</span></a></li>';
                            echo '<li><a href="../Alertas/form_agregarAlerta.php"><ion-icon name="clipboard-outline"></ion-icon><span>Alertas</span></a></li>';
                            echo '<li><a href="../vistas/proximamente.php"><ion-icon name="eye-outline"></ion-icon><span>Reportes Hechos</span></a></li>';
                            break;

                        // Perfil ALUMNO
                        case 'Alumno':
                            echo '<a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span>Alumnos</span>
                                </a>';
                            echo '<li><a href="#"><ion-icon name="home-outline"></ion-icon><span>Dashboard Alumno</span></a></li>';
                            echo '<li><a href="#"><ion-icon name="eye-outline"></ion-icon><span>Ver Calificaciones</span></a></li>';
                            echo '<li><a href="#"><ion-icon name="calendar-outline"></ion-icon><span>Horario</span></a></li>';
                            break;

                        // Perfil ADMINISTRATIVO
                        case 'Administrativo':
                            echo '<a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span>Alumnos</span>
                                </a>';
                            echo '<li><a href="../Inscripcion/inscripcion_alumno.php"><ion-icon name="home-outline"></ion-icon><span>Inscribir Alumno</span></a></li>';
                            echo '<li><a href="../Docentes/docente_f19"><ion-icon name="folder-outline"></ion-icon><span>Docentes</span></a></li>';
                            echo '<li><a href="../Usuarios/usuarios_alumnos.php"><ion-icon name="checkmark-outline"></ion-icon><span>Usuarios Alumnos</span></a></li>';
                            echo '<li><a href="../Usuarios/olvidar_password.php"><ion-icon name="checkmark-outline"></ion-icon><span>Resetear Docente</span></a></li>';
                            break;

                        // Perfil ADMINISTRATIVO DOCENTE
                        case 'Administrativo_Docente':
                            echo '<a class="boton" href="../Docentes/form_docente_f19">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span> Formato 19</span>
                                </a>';
                            echo '<li><a href="../Docentes/form_alta_docente"><ion-icon name="home-outline"></ion-icon><span>Alta Docente</span></a></li>';
                            echo '<li><a href="../Usuarios/form_olvidar_password.php"><ion-icon name="book-outline"></ion-icon><span>Reseteo Password</span></a></li>';
                            echo '<li><a href="../Alertas/form_agregarAlerta.php"><ion-icon name="clipboard-outline"></ion-icon><span>Alertas</span></a></li>';
                            break;

                        // Perfil ADMINISTRATIVO EN JEFE
                        case 'Administrativo_Jefe':
                            echo '<a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span> Alumnos</span>
                                </a>';
                            echo '<li><a href="../Asignar_Grupo/form_asignarGrupos"><ion-icon name="home-outline"></ion-icon><span>Grupos</span></a></li>';
                            echo '<li><a href="../Asignar_Materia/form_asignarMateria.php"><ion-icon name="book-outline"></ion-icon><span>Materias</span></a></li>';
                            echo '<li><a href="../Alertas/form_agregarAlerta.php"><ion-icon name="clipboard-outline"></ion-icon><span>Alertas</span></a></li>';
                            echo '<li><a href="../Calificaciones/form_habilitar_subida"><ion-icon name="clipboard-outline"></ion-icon><span>Acciones</span></a></li>';
                            break;

                        // Perfil DIRECTIVO
                        case 'Directivo':
                            echo '<a class="boton" href="../Inscripcion/form_procesa_inscripcion2.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                                        height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                        <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                    </svg>
                                    <span>Alumnos</span>
                                </a>';
                            echo '<li><a href="#"><ion-icon name="home-outline"></ion-icon><span>Dashboard Directivo</span></a></li>';
                            echo '<li><a href="#"><ion-icon name="people-outline"></ion-icon><span>Gestionar Personal</span></a></li>';
                            echo '<li><a href="#"><ion-icon name="bulb-outline"></ion-icon><span>Planificación Estratégica</span></a></li>';
                            break;
                            
                        default:
                            echo '<li><a href="../login.html"><ion-icon name="log-in-outline"></ion-icon><span>Iniciar Sesión</span></a></li>';
                            break;
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </form>

    <main>