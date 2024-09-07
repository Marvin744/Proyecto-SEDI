<?php
    include_once '../bd/config.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    require_once '../General_Actions/validar_sesion.php';
    require_once "../vistas/encabezado.php";

    include_once "../General_Actions/verificar_permiso.php";
    verificarPermiso(['Directivo','Admin', 'Administrativo', 'Administrativo_Jefe', 'Administrativo_Docente', 'Docente']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>

    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Incluir DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
    <!-- Las siguientes dos líneas son el link a normalize -->
    <link rel="preload" href="css/styles.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Las siguientes tres líneas son el link a la fuente de texto de google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    
    <!-- Link al CSS -->
    <link rel="preload" href="styles/framework.css" as="style">
    <link rel="stylesheet" href="styles/framework.css">
    
    <!-- CSS personalizado para la tabla y los botones -->
    <style>
        /* Ajuste del contenedor personalizado para aprovechar mejor el espacio */
.custom-container {
    margin: 0 auto;
    padding: 10px 20px;
    max-width: 100%;
}

/* Estilos minimalistas para la tabla */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-family: 'Krub', sans-serif;
    font-size: 1.7rem; /* Ajuste del tamaño de la fuente */
    color: #333;
    text-align: left;
    border-radius: 8px; /* Bordes redondeados */
    overflow: hidden; /* Evitar que el contenido sobresalga en las esquinas redondeadas */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra sutil */
}

/* Encabezado de la tabla con fondo azul */
.custom-table thead th {
    background-color: #007BFF ; /* Azul para el encabezado */
    color: white;
    padding: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

/* Celdas del cuerpo de la tabla */
.custom-table tbody td {
    padding: 10px;
    background-color: #fff;
}

/* Estilo más oscuro para la columna de la matrícula */
.custom-table tbody td.matricula-column {
    background-color: #f0f0f0; /* Fondo ligeramente más oscuro */
}

/* Bordes estilizados entre columnas */
.custom-table tbody td {
    border-right: 1px solid #ddd; /* Borde entre columnas */
}

/* Eliminar el borde de la última columna */
.custom-table tbody td:last-child {
    border-right: none;
}

/* Estilo hover para filas */
.custom-table tbody tr:hover {
    background-color: #f9f9f9; /* Color de fondo sutil al hacer hover */
}

/* Redondear las esquinas de la tabla */
.custom-table tbody tr:first-child td:first-child {
    border-top-left-radius: 8px;
}
.custom-table tbody tr:first-child td:last-child {
    border-top-right-radius: 8px;
}
.custom-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}
.custom-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

/* Estilos responsivos para dispositivos móviles */
@media screen and (max-width: 768px) {
    .custom-table thead {
        display: none;
    }

    .custom-table tbody td {
        display: block;
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .custom-table tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 10px;
        font-weight: bold;
        text-align: left;
    }
}

/* Estilo general para los botones en la columna de Acciones */
.custom-action-btn {
    display: inline-block;
    padding: 8px 12px;
    font-size: 1.7rem; /* Ajuste del tamaño de la fuente */
    font-family: 'Krub', sans-serif;
    color: white;
    background-color: #007BFF; /* Azul como el encabezado */
    border: none;
    border-radius: 5px; /* Bordes redondeados */
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Efecto hover para los botones */
.custom-action-btn:hover {
    background-color: #007ACC; /* Azul más oscuro al pasar el mouse */
    transform: translateY(-2px); /* Efecto de elevación */
}

/* Estilo adicional para los botones de alerta */
.custom-action-btn.alert {
    background-color: #FF6347; /* Color rojo suave para alertas */
}

.custom-action-btn.alert:hover {
    background-color: #FF4500; /* Rojo más oscuro al pasar el mouse */
}

/* Estilos personalizados para los controles de DataTables */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    font-size: 1.7rem; /* Ajustar el tamaño de fuente */
    padding: 5px 10px;
    border-radius: 5px;
    background-color: transparent !important; /* Fondo transparente por defecto */
    color: #007ACC !important; /* Color de fuente azul oscuro por defecto */
    margin: 0 2px;
    border: none !important; /* Eliminar borde */
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease !important;
}

/* Efecto hover */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button:focus,
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
    background-color: #1E90FF !important; /* Fondo azul al pasar el cursor */
    color: white !important; /* Forzar fuente blanca */
    border: none !important; /* Sin borde */
    outline: none !important; /* Eliminar contorno */
}

/* Botón seleccionado - azul más oscuro */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #003f7f !important; /* Azul más oscuro para el botón seleccionado */
    color: white !important; /* Fuente blanca para el botón seleccionado */
    border: none !important;
}

/* Botones deshabilitados */
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background-color: transparent !important; /* Fondo transparente */
    color: #aaa !important; /* Color gris para botones deshabilitados */
    cursor: not-allowed !important;
    pointer-events: none !important;
}
    </style>
</head>
<body>
    <div class="custom-container">
        <h1 class="alinear-centro">Alumnos Registrados</h1>
        <p class="alinear-izquierda">A continuación se presentan todos los alumnos registrados en el plantel. Puede modificar sus datos en el apartado de "Acciones" de la tabla.
            <br>De igual forma puede elegir los datos a mostrar en la tabla y filtrar por cualquier dato del alumno.
        </p>
    </div>

    <div class="custom-container">
        <table class="custom-table" id="tablaAlertas" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Nombre/s</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sqlAlumnos = "SELECT * FROM alumnos 
                                    JOIN grupo ON alumnos.id_grupo = grupo.id_grupo
                                    JOIN semestre ON grupo.id_semestre = semestre.id_semestre
                                    ORDER BY apellido_paterno ASC;";
                    $queryTableAlumnos = $conexion->prepare($sqlAlumnos);
                    $queryTableAlumnos->execute();
                    
                    foreach ($queryTableAlumnos as $row) {
                        $nombreCompleto = htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'], ENT_QUOTES, 'UTF-8');

                        echo "<tr>
                                <td data-label='Matrícula' class='custom-align-left matricula-column'>{$row['matricula']}</td>
                                <td data-label='Semestre' class='custom-align-right'>{$row['numero_semestre']}</td>
                                <td data-label='Grupo' class='custom-align-left'>{$row['nombre_grupo']}</td>
                                <td data-label='Apellido Paterno' class='custom-align-center'>{$row['apellido_paterno']}</td>
                                <td data-label='Apellido Materno' class='custom-align-center'>{$row['apellido_materno']}</td>
                                <td data-label='Nombre/s' class='custom-align-center'>{$row['nombre_alumno']}</td>
                                <td data-label='Acciones'>
                                    <a class='custom-action-btn' id='modalagregar' onclick='agregarAlerta({$row['id_alumno']})' data-toggle='modal'>Agregar Alerta</a>
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>   
    <div id="divmodal"></div>

    <script>
        function agregarAlerta(id){
            var ruta = 'modal_agregar.php?alumno='+id;
            $.get(ruta,function(data){
                $('#divmodal').html(data);
                $('#ModalAgregar').modal('show');
            });
        }

        $(document).ready(function() {
            // Inicializar DataTables con opciones personalizadas
            var table = $('#tablaAlertas').DataTable({
                "pageLength": 10, // Número de filas por página
                "lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
                "pagingType": "full_numbers", // Estilo de paginación: "simple", "simple_numbers", "full", "full_numbers"
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });

            // Configuración para que la búsqueda se aplique en tiempo real
            $('#campo').on("keyup", function() {
                table.search($(this).val()).draw();
            });
        });
    </script>
</body>
</html>

<?php require_once "../vistas/pie_pagina.php"?>