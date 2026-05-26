<?php
session_start();
include("conexion_bd.php");

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
    
    // Recibimos el TEXTO de la especialidad
    $especialidad_texto = trim(mysqli_real_escape_string($conexion, $_POST['especialidad_id']));

    // Buscamos si ya existe
    $buscar = "SELECT `id` FROM `especialidades` WHERE `nombre_especialidades` = '$especialidad_texto'";
    $res_buscar = mysqli_query($conexion, $buscar);

    if (mysqli_num_rows($res_buscar) > 0) {
        $fila = mysqli_fetch_assoc($res_buscar);
        $especialidad_id = $fila['id'];
    } else {
        // Si es nueva, la creamos al vuelo
        $insertar = "INSERT INTO `especialidades` (`nombre_especialidades`) VALUES ('$especialidad_texto')";
        mysqli_query($conexion, $insertar);
        $especialidad_id = mysqli_insert_id($conexion);
    }

    // Actualizamos al profesional con la ID correcta
    $sql = "UPDATE `profesionales` 
            SET `nombre` = '$nombre', `apellido` = '$apellido', `especialidad_id` = '$especialidad_id', `clave` = '$clave' 
            WHERE `id` = '$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>