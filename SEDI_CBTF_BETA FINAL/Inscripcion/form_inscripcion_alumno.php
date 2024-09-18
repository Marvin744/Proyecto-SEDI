<?php
    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Admin', 'Administrativo', 'Administrativo_y_docente', 'Administrativo_Jefe']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="diseno.css?v=<?php echo (rand()); ?>">
    <title>Document</title>

    <script>
    function calcularEdad() {
        const fechaNacimiento = document.getElementById('fecha_naci').value;
        const fechaNacimientoObj = new Date(fechaNacimiento);
        const hoy = new Date();

        // Calcula la diferencia en años, meses y días
        const diff = hoy - fechaNacimientoObj;
        const edad = new Date(diff).getFullYear() - 1970;

        document.getElementById('edad').value = edad;
    }
    </script>

</head>

<body>
    <form action="alta_alumno.php" method="POST" id="form_inscripcion" class="flex">

        <div class="form-page active">              <!-- Fomrulario DATOS GENERALES alumno -->

            <h1>Datos Generales del Alumno</h1>

            <div class="form-group">
                <label for="nombre_alumno">Nombre:</label>
                <input type="text" class="form-control" id="nombre_alumno" name="nombre_alumno" >
            </div>

            <div class="form-group">
                <label for="apellido_paterno">Apellido Paterno:</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" >
            </div>

            <div class="form-group">
                <label for="apellido_materno">Apellido Materno:</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="Inscrito">Inscrito</option>
                    <option value="Baja">Baja</option>
                    <option value="Pendiente">Pendiente</option>
                </select>
            </div>
        </div>


        <div class="form-page">              <!-- Formulario NACIONALIDAD Y CONTACTOS alumno -->

            <h1>Nacionalidad y Contacto del Alumno</h1>

            <div class="form-group">
                <label for="curp">CURP:</label>
                <input type="text" class="form-control" id="curp" name="curp" maxlenght="18">
            </div>

            <div class="form-group">
                <label for="genero">Género:</label>
                <select class="form-control" id="genero" name="genero">
                    <option value="">-- Seleccione un Género --</option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_naci">Fecha de Nacimiento:</label>
                <!-- <input type="date" class="form-control" id="fecha_naci" name="fecha_naci" onchange="calcularEdad()" > -->
                <input type="date" class="form-control" id="fecha_naci" name="fecha_naci">
            </div>

            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" class="form-control" id="edad" name="edad" >
            </div>

            <div class="form-group">
                <label for="lugar_nacimiento">Lugar de Nacimiento:</label>
                <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" >
            </div>

            <div class="form-group">
                <label for="nacionaldad">Nacionalidad:</label>
                <select class="form-control" id="nacionalidad" name="nacionalidad">
                    <option value="">-- Selecciona una Nacionalidad --</option>
                    <option value="Mexicano(a)">Mexicano(a)</option>
                    <option value="Extranjero(a)">Extranjero(a)</option>
                </select>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="ayuda_espanol" name="ayuda_espanol">
                <label class="form-check-label" for="ayuda_espanol">¿Necesita ayuda en español?</label>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono del alumno:</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
            </div>

            <div class="form-group">
                <label for="correo">Correo del Alumno:</label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>
        </div>

        <div class="form-page">              <!-- Fomrulario EGRESO Y SALUD alumno -->
            
            <h1>Egreso y Salud</h1>

            <div class="form-group">
                <label for="secundaria_egreso">Secundaria de Egreso:</label>
                <input type="text" class="form-control" id="secundaria_egreso" name="secundaria_egreso" >
            </div>

            <div class="form-group">
                <label for="promedio_secundaria">Promedio de Secundaria:</label>
                <input type="text" class="form-control" id="promedio_secundaria" name="promedio_secundaria" >
            </div>

            <div class="form-group">
                <label for="sangre">Tipo de Sangre:</label>
                <select class="form-control" id="sangre" name="sangre">
                    <option value="">-- Seleccione el Tipo de Sangre</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="Sin Definir">Sin Definir</option>
                </select>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="beca_bancomer" name="beca_bancomer">
                <label class="form-check-label" for="beca_bancomer">¿Tiene beca Bancomer?</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="nss" name="nss"
                    onclick="toggleVisibility('nss_fields')">
                <label class="form-check-label" for="nss">¿Tiene NSS?</label>
            </div>

            <div id="nss_fields" class="hidden">
                <div class="form-group">
                    <label for="nss_numero">Número de Seguridad Social:</label>
                    <input type="text" class="form-control" id="nss_numero" name="nss_numero">
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="issste" name="issste"
                    onclick="toggleVisibility('issste_fields')">
                <label class="form-check-label" for="discapacidad_mo_psi">¿Tiene ISSSTE?</label>
            </div>

            <div id="issste_fields" class="hidden">
                <div class="form-group">
                    <label for="detalles">Detalles:</label>
                    <input type="text" class="form-control" id="issste_numero" name="issste_numero">
                </div>
            </div>


            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="discapacidad_mo_psi" name="discapacidad_mo_psi"
                    onclick="toggleVisibility('discapacidad_fields')">
                <label class="form-check-label" for="discapacidad_mo_psi">¿Tiene discapacidad motriz o psíquica?</label>
            </div>

            <div id="discapacidad_fields" class="hidden">
                <div class="form-group">
                    <label for="detalles">Detalles:</label>
                    <input type="text" class="form-control" id="detalles_discapacidad" name="detalles_discapacidad">
                </div>
            </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="documento_validacion_discapacidad"
                        name="documento_validacion_discapacidad">
                    <label class="form-check-label" for="documento_validacion_discapacidad">¿Tiene documento de
                        validación
                        de discapacidad?</label>
                </div>


            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="alergia" name="alergia"
                    onclick="toggleVisibility('alergia_fields')">
                <label class="form-check-label" for="alergia">¿Tiene alguna alergia?</label>
            </div>

            <div id="alergia_fields" class="hidden">
                <div class="form-group">
                    <label for="detalles_alergias">Detalles de la alergia:</label>
                    <input type="text" class="form-control" id="detalles_alergias" name="detalles_alergias">
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="requiere_medicacion" name="requiere_medicacion"
                    onclick="toggleVisibility('medicacion_fields')">
                <label class="form-check-label" for="requiere_medicacion">¿Requiere medicación?</label>
            </div>

            <div id="medicacion_fields" class="hidden">
                <div class="form-group">
                    <label for="medicacion_necesaria">Medicación necesaria:</label>
                    <input type="text" class="form-control" id="medicacion_necesaria" name="medicacion_necesaria">
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="lentes_graduados" name="lentes_graduados">
                <label class="form-check-label" for="lentes_graduados">¿Usa lentes graduados?</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="aparatos_asistencia" name="aparatos_asistencia"
                    onclick="toggleVisibility('aparatos_asistencia_fields')">
                <label class="form-check-label" for="aparatos_asistencia">¿Usa aparatos de asistencia?</label>
            </div>

            <div id="aparatos_asistencia_fields" class="hidden">
                <div class="form-group">
                    <label for="detalles_aparatos_asistencia">Detalles de los aparatos de asistencia:</label>
                    <input type="text" class="form-control" id="detalles_aparatos_asistencia"
                        name="detalles_aparatos_asistencia">
                </div>
            </div>

        </div>

        <div class="form-page">              <!-- Fomrulario CONTACTOS Y TUTORES alumno -->

            <h1>Datos de Contacto y Tutores</h1>

            <div class="form-group">
                <label for="calle_numero">Calle y Número:</label>
                <input type="text" class="form-control" id="calle_numero" name="calle_numero" >
            </div>

            <div class="form-group">
                <label for="colonia">Colonia:</label>
                <input type="text" class="form-control" id="colonia" name="colonia" >
            </div>

            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" >
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="dispositivo_internet" name="dispositivo_internet"
                    onclick="toggleVisibility('dispositivos_fields')">
                <label class="form-check-label" for="dispositivo_internet">¿Tiene dispositivo con acceso a
                    internet?</label>
            </div>

            <div id="dispositivos_fields" class="hidden">
                <div class="form-group">
                    <label for="numero_dispositivos">Número de dispositivos:</label>
                    <select class="form-control" id="numero_dispositivos" name="numero_dispositivos">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="+5">+5</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="nombre_tutor">Nombre del Tutor:</label>
                <input type="text" class="form-control" id="nombre_tutor" name="nombre_tutor" >
            </div>

            <div class="form-group">
                <label for="telefono_tutor">Teléfono del Tutor:</label>
                <input type="text" class="form-control" id="telefono_tutor" name="telefono_tutor" >
            </div>

            <div class="form-group">
                <label for="nombre_madre">Nombre de la Madre:</label>
                <input type="text" class="form-control" id="nombre_madre" name="nombre_madre">
            </div>

            <div class="form-group">
                <label for="telefono_madre">Teléfono de la Madre:</label>
                <input type="text" class="form-control" id="telefono_madre" name="telefono_madre">
            </div>

            <div class="form-group">
                <label for="nombre_padre">Nombre del Padre:</label>
                <input type="text" class="form-control" id="nombre_padre" name="nombre_padre">
            </div>

            <div class="form-group">
                <label for="telefono_padre">Teléfono del Padre:</label>
                <input type="text" class="form-control" id="telefono_padre" name="telefono_padre">
            </div>
        </div>

        <div class="form-page">              <!-- Fomrulario DOCUMENTOS alumno -->

            <h1>Entrega de Documentos</h1>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_acta_nacimiento" name="EP_acta_nacimiento">
                <label class="form-check-label" for="EP_acta_nacimiento">Acta de Nacimiento</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_CURP" name="EP_CURP">
                <label class="form-check-label" for="EP_CURP">CURP</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_comprobante_domicilio"
                    name="EP_comprobante_domicilio">
                <label class="form-check-label" for="EP_comprobante_domicilio">Comprobante de Domicilio</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_nss_issste" name="EP_nss_issste">
                <label class="form-check-label" for="EP_nss_issste">NSS ISSSTE</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_certificado_secundaria"
                    name="EP_certificado_secundaria">
                <label class="form-check-label" for="EP_certificado_secundaria">Certificado de Secundaria</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_ficha_psicopedagogica"
                    name="EP_ficha_psicopedagogica">
                <label class="form-check-label" for="EP_ficha_psicopedagogica">Ficha Psicopedagógica</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_ficha_buena_conducta"
                    name="EP_ficha_buena_conducta">
                <label class="form-check-label" for="EP_ficha_buena_conducta">Ficha de Buena Conducta</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_fotografias" name="EP_fotografias">
                <label class="form-check-label" for="EP_fotografias">Fotografías</label>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_autenticacion_secundaria"
                    name="EP_autenticacion_secundaria">
                <label class="form-check-label" for="EP_autenticacion_secundaria">Autenticación de Secundaria</label>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <input type="text" class="form-control" id="observaciones" name="observaciones">
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="ticket"
                    name="ticket">
                <label class="form-check-label" for="ticket">Voucher de Inscripción:</label>
            </div>

        </div>

        <div class="form-buttons font-2">              <!-- BOTONES INSCRIPCIÓN Y NAVEGACIÓN -->
            <br>
            <button style="align-content: center; font-size: 1.8rem; padding: 5px;" type="button" id="btn-prev" disabled>Anterior</button>
            <button style="align-content: center; font-size: 1.8rem; padding: 5px;" type="button" id="btn-next">Siguiente</button>
            <button type="submit" id="btn-insc" class="button_slide slide_down">Inscribir</button>
        </div>
    </form>
</body>

</html>

<script>
function toggleVisibility(id) {
    var element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}
</script>
<!-- <script>
// Si lo haces con ID y números de página, se va a complicar agregar más páginas. Te sugiero hacerlo con clases, de forma que puedas obtener todos los elementos de página con document.querySelectorAll() y agregar o quitar clase para mostrar y ocultar, respectivamente.

// Al iniciar, la primera página debe tener la clase active y el botón "Anterior" debe estar deshabilitado (disabled). A partir de ahí, el usuario podrá navegar entre páginas haciendo clic en el botón correspondiente.

// Revisa los comentarios en el código para saber qué hace cada cosa:

// Página actual
let curPage = 0;
// Obtener páginas y botones
let pages = document.querySelectorAll('.form-page');
let btnPrev = document.querySelector('#btn-prev');
let btnNext = document.querySelector('#btn-next');
let btnInsc = document.querySelector('#btn-insc');

// Función para avanzar o retroceder, recibe 1 (avanzar) o -1 (retroceder)
function showPage(action) {
    // Página a mostrar según el valor recibido
    curPage += action;
    // Validar que la página a mostrar esté dentro de los límites
    if (curPage < 0) {
        curPage = 0;
    }
    if (curPage >= pages.length) {
        curPage = pages.length - 1;
    }
    // Recorrer para mostrar u ocultar
    pages.forEach((page, index) => {
        if (index == curPage) {
            // Es página actual, se debe mostrar
            page.classList.add('active');
        } else {
            // Las demás se van a ocultar
            page.classList.remove('active');
        }
    });
    // Habilitar o deshabilitar botones
    btnPrev.disabled = (curPage == 0);
    btnNext.disabled = (curPage == pages.length - 1);
    // btnInsc.disabled = (curPage != pages.length - 1);
}
// Agregar un evento de click al botón
btnNext.addEventListener('disabled', function() {
    // Verificar el estado actual del elemento
    if (btnInsc.style.display === 'none') {
        // Si está oculto, mostrarlo
        btnInsc.style.display = 'block'; // O 'inline', o 'flex', etc., dependiendo de lo que necesites
    } else {
        // Si está mostrado, ocultarlo
        btnInsc.style.display = 'none';
    }
});
// Asignar evento a botones para avanzar y retroceder
btnPrev.addEventListener('click', e => showPage(-1));
btnNext.addEventListener('click', e => showPage(1));
</script> -->



<script>
    // Página actual
    let curPage = 0;
    // Obtener páginas y botones
    let pages = document.querySelectorAll('.form-page');
    let btnPrev = document.querySelector('#btn-prev');
    let btnNext = document.querySelector('#btn-next');
    let btnInsc = document.querySelector('#btn-insc');

    // Función para mostrar la página según el índice
    function showPage(index) {
        // Validar el índice de página
        if (index < 0 || index >= pages.length) {
            return; // Salir si el índice está fuera de rango
        }

        // Ocultar todas las páginas
        pages.forEach(page => {
            page.classList.remove('active');
        });

        // Mostrar la página específica
        pages[index].classList.add('active');

        // Actualizar la página actual
        curPage = index;

        // Habilitar o deshabilitar botones de navegación
        btnPrev.disabled = (curPage === 0);
        btnNext.disabled = (curPage === pages.length - 1);
        btnInsc.style.display = (curPage === pages.length - 1) ? 'block' : 'none';
    }

    // Mostrar la primera página al cargar
    showPage(curPage);

    // Asignar evento a botones para avanzar y retroceder
    btnPrev.addEventListener('click', () => showPage(curPage - 1));
    btnNext.addEventListener('click', () => showPage(curPage + 1));

    // Asignar evento al botón de inscripción
    // btnInsc.addEventListener('click', () => {
    //     alert('Botón de inscripción clickeado'); // Aquí puedes colocar tu lógica para la inscripción
    // });
</script>

<script>
    document.getElementById('curp').addEventListener('blur', procesarCURP); // Cuando el el campo de la CURP pierda el foco se procesara la funcion
    document.getElementById('form_inscripcion').addEventListener('submit', confirmarEnvio);

    function procesarCURP() {
        const curp = document.getElementById('curp').value;

        if (curp.length !== 18) {
            alert("La CURP debe tener 18 caracteres.");
            return;
        }

        // Extraer fecha de nacimiento de la CURP
        const year = parseInt(curp.substring(4, 6));
        const month = parseInt(curp.substring(6, 8)) - 1; // Los meses en JavaScript son 0-11
        const day = parseInt(curp.substring(8, 10));

        // Determinar siglo de nacimiento
        const currentYear = new Date().getFullYear() % 100;
        const century = year <= currentYear ? 2000 : 1900;

        const birthDate = new Date(century + year, month, day);

        // Calcular edad de manera precisa
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();

        // Ajustar edad si el cumpleaños aún no ha ocurrido este año
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        // Determinar nacionalidad (posición 11 de la CURP)
        const nationalityCode = curp.charAt(11).toUpperCase();
        const nationality = nationalityCode === 'N' ? 'Extranjero(a)' : 'Mexicano(a)';

        // Formatear la fecha para el input de tipo date
        const birthDateString = birthDate.toISOString().split('T')[0];

        // Mostrar resultados en el formulario
        document.getElementById('fecha_naci').value = birthDateString;
        document.getElementById('edad').value = age;
        
        // Seleccionar la nacionalidad en la lista desplegable
        const selectNacionalidad = document.getElementById('nacionalidad');
        selectNacionalidad.value = nationality;

        // DETERMINAR GENERO (posición 10 de la CURP)
        const generoCode = curp.charAt(10).toUpperCase();
        const genero = generoCode === 'H' ? 'Hombre' : 'Mujer';
        
        // SELECCIONAR el GENERO en la lista desplegable
        const selectGenero = document.getElementById('genero');
        selectGenero.value = genero;

        
    }

    function confirmarEnvio(event) {
        const confirmacion = confirm("¿Desea terminar el proceso de INSCRIPCIÓN?");
        if (!confirmacion) {
            event.preventDefault(); // Evita el envío del formulario
        }
    }
</script>


<?php require_once "../vistas/pie_pagina.php"; ?>