<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SEDI CBTF</title>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>SEDI</title>
    <!-- bootstrap css -->
    <!-- <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css?v=<?php echo (rand()); ?>"> -->
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" type="text/css" href="../styles/style_index2.css?v=<?php echo (rand()); ?>">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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



</head>

<body>
    <!-- header section start -->
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
            <div></div>
            <!-- <div class="search_icon"><a href="#"><img src="../img/user-icon.png"><span class="padding_right" style="color: #ffffff">Usuario</span></a></div> -->
            <div class="search_icon"><a href="../General_Actions/logout.php"><img src="../img/icono_cerrar_sesion.png"><span class="padding_left"
                        style="color: #ffffff">Cerrar Sesión</span></a></div>
            <div class="wrapper">
            </div>
        </nav>





        <!-- Barra Lateral -->
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
                    <a class="boton" href="../Inscripcion/procesa_inscripcion2.php">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="36"
                            height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                            <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                        </svg>
                        <span>Alumnos</span>
                    </a>
                </div>

                <nav class="navegacion">
                    <ul>
                        <li>
                            <a id="inscripcion" href="../Inscripcion/incripcion_alumno.php">
                                <ion-icon name="paper-plane-outline"></ion-icon>
                                <span>Inscripcion</span>
                            </a>
                        </li>
                        <!-- <li>
                    <a href="#">
                        <ion-icon name="star-outline"></ion-icon>
                        <span>Horario Docentes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="paper-plane-outline"></ion-icon>
                        <span>Subir Calificaciones</span>
                    </a>
                </li>
                <li>
                    <a href="../Alertas/alerta.php">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Alertas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="bookmark-outline"></ion-icon>
                        <span>Important</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <span>Spam</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="trash-outline"></ion-icon>
                        <span>Trash</span>
                    </a>
                </li> -->
                    </ul>
                </nav>

            </div>
        </form>

        <main>