<!--NUMERO 6-->
<?php
session_start();  // 1. Incluimos la conexión que hiciste antes
include("conexion_bd.php");

// 2. Recogemos los datos del formulario
$medico    = $_POST['nombre_medico'] ?? '';
$dni       = $_POST['dni_manual'] ?? $_POST['dni'] ?? '';
$fecha     = $_POST['fecha'] ?? '';
$hora      = $_POST['hora'] ?? '';
$nombres   = $_POST['nombres_manual'] ?? $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos_manual'] ?? $_POST['apellidos'] ?? '';

// --- TRUCO DE CONTROL DE RELACIONES ---
// Buscamos si el DNI escrito ya tiene un ID numérico en la tabla 'usuarios'
$consulta_usuario = "SELECT `id` FROM `usuarios` WHERE `dni` = '$dni'";
$resultado_usuario = mysqli_query($conexion, $consulta_usuario);

if (mysqli_num_rows($resultado_usuario) > 0) {
    // Si ya existe, nos guardamos su ID numérico real
    $fila_u = mysqli_fetch_assoc($resultado_usuario);
    $id_enlace = $fila_u['id'];
} else {
    // Si el paciente es nuevo, lo creamos primero en la tabla 'usuarios'
    // Le asignamos la clave provisional '1234'
    $insertar_usuario = "INSERT INTO `usuarios` (`dni`, `nombre`, `apellido`, `clave`) 
                         VALUES ('$dni', '$nombres', '$apellidos', '1234')";
    mysqli_query($conexion, $insertar_usuario);
    
    // Nos quedamos con la ID que se le acaba de asignar automáticamente
    $id_enlace = mysqli_insert_id($conexion);
}
// --------------------------------------

// 3. Preparamos la orden SQL para insertar (CORREGIDA CON TUS COLUMNAS REALES)
$sql = "INSERT INTO citas (nombre_medico, fecha, hora, DNI, nombres, apellidos) 
        VALUES ('$medico', '$fecha', '$hora', '$id_enlace', '$nombres', '$apellidos')";

// 4. Ejecutamos la orden
if (mysqli_query($conexion, $sql)) {
    echo "<h2>¡Cita guardada con éxito!</h2>";
    echo "<a href='panel_profesional.php'> Volver atrás </a>";
} else {
    echo "Error al guardar la cita: " . mysqli_error($conexion);
}

// 5. Cerramos la conexión
mysqli_close($conexion);
?>