<?php
session_start();
include("conexion_bd.php");

// Seguridad: Si no está logueado, al login
if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

    // Limpiamos los datos recibidos para evitar errores o inyecciones
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']); 
    
    // Preparamos el INSERT (asumiendo que tus columnas se llaman id, dni, clave, nombre)
    // No ponemos el 'id' en la consulta porque al ser AUTO_INCREMENT la base de datos lo genera solo.
    $sql = "INSERT INTO `usuarios` (`dni`, `clave`, `nombre`,`apellido` ) VALUES ('$dni', '$clave', '$nombre','$apellido')";
    
    mysqli_query($conexion, $sql);


// Al terminar, regresamos de inmediato al panel principal
header("Location: panel_profesional.php");
exit();
?>