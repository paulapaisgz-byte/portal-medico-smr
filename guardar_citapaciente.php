<?php
session_start();  
include("conexion_bd.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: iniciar_sesion.php");
    exit();
}

$medico    = $_POST['nombre_medico'] ?? '';
$fecha     = $_POST['fecha'] ?? '';
$hora      = $_POST['hora'] ?? '';

$id_paciente = 99999;

$consulta_u = "SELECT `dni`, `nombre`, `apellido` FROM `usuarios` WHERE `id` = '$id_paciente'";

// ¡AQUÍ ESTÁ EL CAMBIO CLAVE 1! Habías borrado esta línea que ejecuta la consulta:
$res_u = mysqli_query($conexion, $consulta_u);

// Ahora sí, comprobamos si la consulta devolvió algún usuario
if ($res_u && mysqli_num_rows($res_u) > 0) {
    $datos_u = mysqli_fetch_assoc($res_u);

    $dni       = $datos_u['dni'];
    $nombres   = $datos_u['nombre'];
    $apellidos = $datos_u['apellido'];

    $sql = "INSERT INTO citas (nombre_medico, fecha, hora, DNI, nombres, apellidos) 
            VALUES ('$medico', '$fecha', '$hora', '$id_paciente', '$nombres', '$apellidos')";

    if (mysqli_query($conexion, $sql)) {
        echo "<h2>¡Tu cita ha sido guardada con éxito!</h2>";
        echo "<a href='pedir_cita.php'> Volver a mis citas </a>";
    } else {
        echo "Error al guardar la cita: " . mysqli_error($conexion);
    }
    
} // ¡AQUÍ ESTÁ EL CAMBIO CLAVE 2! Ponemos la llave que cierra este bloque para que el ELSE funcione ordinariamente.
else {
    // ¡Aquí controlamos la inconsistencia! Si el ID no existe, evitamos el Fatal Error
    echo "<h2>Error de consistencia: El usuario no es válido o su sesión ha expirado.</h2>";
    echo "<a href='iniciar_sesion.php'>Por favor, inicie sesión nuevamente</a>";
}

mysqli_close($conexion);
?>