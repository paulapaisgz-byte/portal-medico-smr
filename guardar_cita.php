<?php
// 1. Incluimos la conexión que hiciste antes
include 'funciones/conexion.php';

// 2. Recogemos los datos del formulario
$medico = $_POST['medico'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$usuario_id = 1; // Ponemos 1 de momento para probar

// 3. Preparamos la orden SQL para insertar
$sql = "INSERT INTO citas (nombre_medico, fecha, hora, usuario_id) 
        VALUES ('$medico', '$fecha', '$hora', '$usuario_id')";

// 4. Ejecutamos la orden
if (mysqli_query($conexion, $sql)) {
    echo "<h2>¡Cita guardada con éxito!</h2>";
    echo "<a href='portal.php'>Volver atrás</a>";
} else {
    echo "Error al guardar la cita: " . mysqli_error($conexion);
}

// 5. Cerramos la conexión
mysqli_close($conexion);
?>