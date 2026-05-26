<?php
session_start();
include("conexion_bd.php");

// Seguridad: Si alguien intenta entrar a este archivo sin loguearse, lo mandamos al login
if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

    // Guardamos el nombre que escribió el usuario en una variable
    // mysqli_real_escape_string evita que metan símbolos raros o hackeos
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_profesional']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido_profesional']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave_profesional']);
    $especialidad_texto = trim(mysqli_real_escape_string($conexion, $_POST['especialidad_profesional']));

    $sql_buscar_esp = "SELECT `id` FROM `especialidades` WHERE `nombre_especialidades` = '$especialidad_texto'";
    $resultado_buscar = mysqli_query($conexion, $sql_buscar_esp); //esa especialidad existe ya?

    if (mysqli_num_rows($resultado_buscar) > 0) {
        $fila_esp = mysqli_fetch_assoc($resultado_buscar);
        $especialidad_id = $fila_esp['id'];
    } else {
        $sql_insertar_esp = "INSERT INTO `especialidades` (`nombre_especialidades`) VALUES ('$especialidad_texto')";
        mysqli_query($conexion, $sql_insertar_esp);
        
        $especialidad_id = mysqli_insert_id($conexion);
    }
    
    $sql = "INSERT INTO `profesionales` (`nombre`,`apellido`,`clave`,`especialidad_id`) 
            VALUES ('$nombre','$apellido','$clave', '$especialidad_id')";
    
    // Le decimos a la base de datos que ejecute la orden
    mysqli_query($conexion, $sql);
    
header("Location: panel_profesional.php");
exit();
?>