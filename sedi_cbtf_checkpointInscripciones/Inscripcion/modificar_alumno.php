<?php require_once '../General_Actions/validar_sesion.php';?>
<?php require_once "../vistas/encabezado.php"?>
<?php
$persona = $_REQUEST['id_alumno'];

include_once '../bd/config.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar(); 
    


$sql="SELECT * FROM `alumnos` WHERE $persona=id_alumno";
$query = $conexion->prepare($sql);
$query->execute();
$mostrar = $query->fetch();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="diseno.css?v=<?php echo (rand()); ?>">
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
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
    <form action="update_alumno.php" method="POST" class="flex">
        <input type="hidden" id="id_alumno" name="id_alumno" value="<?php echo $mostrar['id_alumno']; ?>">
        <div class="form-page active">
            <h1>Datos generales del alumno</h1>
            <div class="form-group">
                <label for="id_alumno">Matricula:</label>
                <input type="text" class="form-control" id="matricula" name="matricula"
                    value="<?php echo $mostrar['matricula']; ?>">
            </div>
            <div class="form-group">
                <label for="nombre_alumno">Nombre:</label>
                <input type="text" class="form-control" id="nombre_alumno" name="nombre_alumno"
                    value="<?php echo $mostrar['nombre_alumno']; ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido_paterno">Apellido Paterno:</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                    value="<?php echo $mostrar['apellido_paterno']; ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido_materno">Apellido Materno:</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                    value="<?php echo $mostrar['apellido_materno']; ?>">
            </div>
            <div class="form-group">
                <label for="semestre">Semestre:</label>
                <input type="text" class="form-control" id="semestre" name="semestre"
                    value="<?php echo $mostrar['semestre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="grupo">Grupo:</label>
                <input type="text" class="form-control" id="grupo" name="grupo"
                    value="<?php echo $mostrar['grupo']; ?>">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="<?php echo $mostrar['status']; ?>"><?php echo $mostrar['status']; ?></option>
                    <option value="Inscrito">Inscrito</option>
                    <option value="Baja">Baja</option>
                    <option value="Pendiente">Pendiente</option>
                </select>
            </div>
        </div>


        <div class="form-page">
            <h1>Nacionalidad y contactos</h1>
            <div class="form-group">
                <label for="curp">CURP:</label>
                <input type="text" class="form-control" id="curp" name="curp" value="<?php echo $mostrar['CURP']; ?>">
            </div>
            <div class="form-group">
                <label for="genero">Género:</label>
                <select class="form-control" id="genero" name="genero" value="<?php echo $mostrar['genero']; ?>">
                    <option value="<?php echo $mostrar['genero']; ?>"><?php echo $mostrar['genero']; ?></option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_naci">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="fecha_naci" name="fecha_naci"
                    value="<?php echo $mostrar['fecha_naci']; ?>" onchange="calcularEdad()" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" class="form-control" id="edad" name="edad" value="<?php echo $mostrar['edad']; ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="lugar_nacimiento">Lugar de Nacimiento:</label>
                <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento"
                    value="<?php echo $mostrar['lugar_nacimiento']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nacionalidad">Nacionalidad:</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad"
                    value="<?php echo $mostrar['nacionalidad']; ?>" required>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="ayuda_espanol" name="ayuda_espanol"
                    value="<?php echo $mostrar['ayuda_español']; ?>"
                    <?php $retVal = ($mostrar['ayuda_español'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="ayuda_espanol">¿Necesita ayuda en español?</label>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono"
                    value="<?php echo $mostrar['telefono']; ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo"
                    value="<?php echo $mostrar['correo']; ?>">
            </div>
        </div>

        <div class="form-page">
            <h1>Egreso y salud</h1>
            <div class="form-group">
                <label for="secundaria_egreso">Secundaria de Egreso:</label>
                <input type="text" class="form-control" id="secundaria_egreso" name="secundaria_egreso"
                    value="<?php echo $mostrar['secundaria_egreso']; ?>" required>
            </div>
            <div class="form-group">
                <label for="promedio_secundaria">Promedio de Secundaria:</label>
                <input type="text" class="form-control" id="promedio_secundaria" name="promedio_secundaria"
                    value="<?php echo $mostrar['promedio_secundaria']; ?>" required>

            </div>
            <div class="form-group">
                <label for="sangre">Tipo de Sangre:</label>
                <select class="form-control" id="sangre" name="sangre">
                    <option value="<?php echo $mostrar['sangre']; ?>"><?php echo $mostrar['sangre']; ?></option>
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
                <input type="checkbox" class="form-check-input" id="beca_bancomer" name="beca_bancomer"
                    value="<?php echo $mostrar['beca_bancomer']; ?>"
                    <?php $retVal = ($mostrar['beca_bancomer'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="beca_bancomer">¿Tiene beca Bancomer?</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="nss_issste" name="nss_issste"
                    value="<?php echo $mostrar['nss_issste']; ?>"
                    <?php $retVal = ($mostrar['nss_issste'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="nss_issste">¿Tiene NSS ISSSTE?</label>
            </div>
            <div class="form-check"> <!-- Checkbox ya funcional -->
                <input type="checkbox" class="form-check-input" id="discapacidad_mo_psi" name="discapacidad_mo_psi"
                    onclick="toggleVisibility('discapacidad_fields')" value="<?php echo $mostrar['discapacidad_mo_psi']; ?>"
                
                <?php $retVal = ($mostrar['discapacidad_mo_psi'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="discapacidad_mo_psi">¿Tiene discapacidad motriz o psíquica?</label>
            </div>


            <?php 
                if($mostrar['discapacidad_mo_psi'] == 1){
                    $retVal = ($mostrar['documento_validacion_discapacidad'] == 1) ? 'checked' : "" ;
                    echo "<div id=\"discapacidad_fields\">
                            <div class=\"form-group\">
                                <label for=\"detalles\">Detalles:</label>
                                <input type=\"text\" class=\"form-control\" id=\"detalles_discapacidad\" name=\"detalles_discapacidad\"
                                    value=".$mostrar['detalles_discapacidad'].">
                            </div>
                            <div class=\"form-check\">
                                <input type=\"checkbox\" class=\"form-check-input\" id=\"documento_validacion_discapacidad\"
                                    name=\"documento_validacion_discapacidad\" value=".$mostrar['documento_validacion_discapacidad']." ".$retVal.";>
                                <label class=\"form-check-label\" for=\"documento_validacion_discapacidad\">¿Tiene documento de
                                    validación de discapacidad?</label>
                            </div>";
                }else{
                    echo "<div id=\"discapacidad_fields\" class=\"hidden\">
                            <div class=\"form-group\">
                                <label for=\"detalles\">Detalles:</label>
                                <input type=\"text\" class=\"form-control\" id=\"detalles_discapacidad\" name=\"detalles_discapacidad\">
                            </div>
                            <div class=\"form-check\">
                                <input type=\"checkbox\" class=\"form-check-input\" id=\"documento_validacion_discapacidad\"
                                    name=\"documento_validacion_discapacidad\">
                                <label class=\"form-check-label\" for=\"documento_validacion_discapacidad\">¿Tiene documento de validación
                                    de discapacidad?</label>
                            </div>";
                }

        ?>

        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="alergia" name="alergia"
                onclick="toggleVisibility('alergia_fields')" value="<?php echo $mostrar['alergia']; ?>"
                <?php $retVal = ($mostrar['alergia'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
            <label class="form-check-label" for="alergia">¿Tiene alguna alergia?</label>
        </div>
        <div id="alergia_fields" <?php $retVal = ($mostrar['alergia'] == 1) ? "" : "hidden" ; echo $retVal; ?>>
            <div class="form-group">
                <label for="detalles_alergia">Detalles de la alergia:</label>
                <input type="text" class="form-control" id="detalles_alergias" name="detalles_alergias"
                    value="<?php echo $mostrar['detalles_alergias']; ?>">
            </div>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="requiere_medicacion" name="requiere_medicacion"
                onclick="toggleVisibility('medicacion_fields')" value="<?php echo $mostrar['requiere_medicacion']; ?>"
                <?php $retVal = ($mostrar['requiere_medicacion'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
            <label class="form-check-label" for="requiere_medicacion">¿Requiere medicación?</label>
        </div>
        <div id="medicacion_fields"
            <?php $retVal = ($mostrar['requiere_medicacion'] == 1) ? "" : "hidden" ; echo $retVal; ?>>
            <div class="form-group">
                <label for="medicacion_necesaria">Medicación necesaria:</label>
                <input type="text" class="form-control" id="medicacion_necesaria" name="medicacion_necesaria"
                    value="<?php echo $mostrar['medicacion_necesaria']; ?>">
            </div>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="lentes_graduados" name="lentes_graduados"
                value="<?php echo $mostrar['lentes_graduados']; ?>"
                <?php $retVal = ($mostrar['lentes_graduados'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
            <label class="form-check-label" for="lentes_graduados">¿Usa lentes graduados?</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="aparatos_asistencia" name="aparatos_asistencia"
                onclick="toggleVisibility('aparatos_asistencia_fields')"
                value="<?php echo $mostrar['aparatos_asistencia']; ?>"
                <?php $retVal = ($mostrar['aparatos_asistencia'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
            <label class="form-check-label" for="aparatos_asistencia">¿Usa aparatos de asistencia?</label>
        </div>
        <div id="aparatos_asistencia_fields"
            <?php $retVal = ($mostrar['aparatos_asistencia'] == 1) ? "" : "hidden" ; echo $retVal; ?>>
            <div class="form-group">
                <label for="detalles_aparatos_asistencia">Detalles de los aparatos de asistencia:</label>
                <input type="text" class="form-control" id="detalles_aparatos_asistencia"
                    name="detalles_aparatos_asistencia" value="<?php echo $mostrar['detalles_aparatos_asistencia']; ?>">
            </div>
        </div>
        </div>

        <div class="form-page">
            <h1>Datos de Contacto y Tutores</h1>
            <div class="form-group">
                <label for="calle_numero">Calle y Número:</label>
                <input type="text" class="form-control" id="calle_numero" name="calle_numero"
                    value="<?php echo $mostrar['calle_numero']; ?>" required>
            </div>
            <div class="form-group">
                <label for="colonia">Colonia:</label>
                <input type="text" class="form-control" id="colonia" name="colonia"
                    value="<?php echo $mostrar['colonia']; ?>" required>
            </div>
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal"
                    value="<?php echo $mostrar['codigo_postal']; ?>" required>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="dispositivo_internet" name="dispositivo_internet"
                    onclick="toggleVisibility('dispositivos_fields')"
                    value="<?php echo $mostrar['dispositivo_internet']; ?>"
                    <?php $retVal = ($mostrar['dispositivo_internet'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="dispositivo_internet">¿Tiene dispositivo con acceso a
                    internet?</label>
            </div>
            <div id="dispositivos_fields"
                <?php $retVal = ($mostrar['dispositivo_internet'] == 1) ? "" : "hidden" ; echo $retVal; ?>>
                <div class="form-group">
                    <label for="numero_dispositivos">Número de dispositivos:</label>
                    <select class="form-control" id="numero_dispositivos" name="numero_dispositivos"
                        value="<?php echo $mostrar['numero_dispositivos']; ?>">
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
                <input type="text" class="form-control" id="nombre_tutor" name="nombre_tutor"
                    value="<?php echo $mostrar['nombre_tutor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono_tutor">Teléfono del Tutor:</label>
                <input type="text" class="form-control" id="telefono_tutor" name="telefono_tutor"
                    value="<?php echo $mostrar['telefono_tutor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre_madre">Nombre de la Madre:</label>
                <input type="text" class="form-control" id="nombre_madre" name="nombre_madre"
                    value="<?php echo $mostrar['nombre_madre']; ?>">
            </div>
            <div class="form-group">
                <label for="telefono_madre">Teléfono de la Madre:</label>
                <input type="text" class="form-control" id="telefono_madre" name="telefono_madre"
                    value="<?php echo $mostrar['telefono_madre']; ?>">
            </div>
            <div class="form-group">
                <label for="nombre_padre">Nombre del Padre:</label>
                <input type="text" class="form-control" id="nombre_padre" name="nombre_padre"
                    value="<?php echo $mostrar['nombre_padre']; ?>">
            </div>
            <div class="form-group">
                <label for="telefono_padre">Teléfono del Padre:</label>
                <input type="text" class="form-control" id="telefono_padre" name="telefono_padre"
                    value="<?php echo $mostrar['telefono_padre']; ?>">
            </div>

        </div>

        <div class="form-page">
            <h1>Entrega de Documentos</h1>
            <input type="hidden" id="id_alumno" name="id_alumno" value="<?php echo $mostrar['id_alumno']; ?>">

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_acta_nacimiento" name="EP_acta_nacimiento"
                    value="<?php echo $mostrar['EP_acta_nacimiento']; ?>"
                    <?php $retVal = ($mostrar['EP_acta_nacimiento'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_acta_nacimiento">Acta de Nacimiento</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_CURP" name="EP_CURP"
                    value="<?php echo $mostrar['EP_CURP']; ?>"
                    <?php $retVal = ($mostrar['EP_CURP'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_CURP">CURP</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_comprobante_domicilio"
                    name="EP_comprobante_domicilio" value="<?php echo $mostrar['EP_comprobante_domicilio']; ?>"
                    <?php $retVal = ($mostrar['EP_comprobante_domicilio'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_comprobante_domicilio">Comprobante de Domicilio</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_nss_issste" name="EP_nss_issste"
                    value="<?php echo $mostrar['EP_nss_issste']; ?>"
                    <?php $retVal = ($mostrar['EP_nss_issste'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_nss_issste">NSS ISSSTE</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_certificado_secundaria"
                    name="EP_certificado_secundaria" value="<?php echo $mostrar['EP_certificado_secundaria']; ?>"
                    <?php $retVal = ($mostrar['EP_certificado_secundaria'] == 1) ? 'checked' : "" ; echo $retVal; ?>>

                <label class="form-check-label" for="EP_certificado_secundaria">Certificado de Secundaria</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_ficha_psicopedagogica"
                    name="EP_ficha_psicopedagogica" value="<?php echo $mostrar['EP_ficha_psicopedagogica']; ?>"
                    <?php $retVal = ($mostrar['EP_ficha_psicopedagogica'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_ficha_psicopedagogica">Ficha Psicopedagógica</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_ficha_buena_conducta"
                    name="EP_ficha_buena_conducta" value="<?php echo $mostrar['EP_ficha_buena_conducta']; ?>"
                    <?php $retVal = ($mostrar['EP_ficha_buena_conducta'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_ficha_buena_conducta">Ficha de Buena Conducta</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_fotografias" name="EP_fotografias"
                    value="<?php echo $mostrar['EP_fotografias']; ?>"
                    <?php $retVal = ($mostrar['EP_fotografias'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_fotografias">Fotografías</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="EP_autenticacion_secundaria"
                    name="EP_autenticacion_secundaria" value="<?php echo $mostrar['EP_autenticacion_secundaria']; ?>"
                    <?php $retVal = ($mostrar['EP_autenticacion_secundaria'] == 1) ? 'checked' : "" ; echo $retVal; ?>>
                <label class="form-check-label" for="EP_autenticacion_secundaria">Autenticación de
                    Secundaria</label>
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <input type="text" class="form-control" id="observaciones" name="observaciones"
                    value="<?php echo $mostrar['observaciones']; ?>">
            </div>

        </div>

        <div class="form-buttons">
            <br>
            <button type="button" id="btn-prev" disabled>Anterior</button>
            <button type="button" id="btn-next">Siguiente</button>
            <button type="submit" id="btn-act" class="butto_slide slide_diagonal">Actualizar</button>
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


/*document.addEventListener("DOMContentLoaded", function() {
    // Obtener el estado inicial de los checkboxes y mostrar u ocultar campos adicionales
    var alergiaCheckbox = document.getElementById('alergia');
    var alergiaFields = document.getElementById('alergia_fields');

    if (alergiaCheckbox.checked) {
        alergiaFields.classList.remove('hidden');
    }

    // Otros checkboxes y campos adicionales aquí
});*/
</script>

<style>
.hidden {
    display: none;
}
</style>

<script>
// Página actual
let curPage = 0;
// Obtener páginas y botones
let pages = document.querySelectorAll('.form-page');
let btnPrev = document.querySelector('#btn-prev');
let btnNext = document.querySelector('#btn-next');
let btnAct = document.querySelector('#btn-act');

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
    //btnAct.style.display = (curPage === pages.length - 1) ? 'block' : 'none';
}

// Mostrar la primera página al cargar
showPage(curPage);

// Asignar evento a botones para avanzar y retroceder
btnPrev.addEventListener('click', () => showPage(curPage - 1));
btnNext.addEventListener('click', () => showPage(curPage + 1));

// Asignar evento al botón de inscripción
btnInsc.addEventListener('click', () => {
    alert('Botón de inscripción clickeado'); // Aquí puedes colocar tu lógica para la inscripción
});
</script>
<?php require_once "../vistas/pie_pagina.php"?>