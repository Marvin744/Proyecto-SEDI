<?php
    include('config.php');


    $calificacion_parcial1 = $_POST['calificacion_parcial1'];
    $calificacion_parcial2 = $_POST['calificacion_parcial2'];
    $calificacion_parcial3 = $_POST['calificacion_parcial3'];
    $calificacion_final = $_POST['calificacion_final'];
    $asistencia_parcial1 = $_POST['asistencia_parcial1'];
    $asistencia_parcial2 = $_POST['asistencia_parcial2'];
    $asistencia_parcial3 = $_POST['asistencia_parcial3'];
    $asistencia_total = $_POST['asistencia_total'];
    $acreditacion = $_POST['acreditacion'];
    $id_docente = $_POST['id_docente'];
    $id_alumno = $_POST['id_alumno'];
    $id_asignatura = $_POST['id_asignatura'];
    $id_especialidad = $_POST['id_especialidad'];



    $update_data = ("UPDATE calificaciones SET
                calificacion_parcial1 = '" .$calificacion_parcial1. "',
                calificacion_parcial2 = '" .$calificacion_parcial2. "',
                calificacion_parcial3 = '" .$calificacion_parcial3. "',
                calificacion_final = '" .$calificacion_final. "',
                asistencia_parcial1 = '" .$asistencia_parcial1. "',
                asistencia_parcial2 = '" .$asistencia_parcial2. "',
                asistencia_parcial3 = '" .$asistencia_parcial3. "',
                asistencia_total = '" .$asistencia_total. "',
                acreditacion = '" .$acreditacion. "',
                id_docente = '" .$id_docente. "',
                id_alumno = '" .$id_alumno. "',
                id_asignatura = '" .$id_asignatura. "',
                id_especialidad = '" .$id_especialidad. "'
                WHERE id_alumno = '".$id_alumno."'
                ");
    
    if($con -> query($update_data)){
        header("Location: subir_calificaciones2.php");
        
    }
    else{
        header("Location: subir_calificaciones2.php");
    }
       
?>
<a href="subir_calificaciones2.php">Atras</a>