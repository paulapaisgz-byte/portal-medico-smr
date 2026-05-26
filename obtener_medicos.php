<?php
// Incluimos la conexión a tu base de datos
include("conexion_bd.php"); // Asegúrate de que se llama así tu archivo de conexión

// Cambiar las cabeceras para avisar al navegador que vamos a responder con texto JSON
header('Content-Type: application/json');

// Comprobamos si nos ha llegado el ID de la especialidad
if (isset($_GET['especialidad_id'])) {
    // Limpiamos la variable por seguridad
    $especialidad_id = intval($_GET['especialidad_id']);
    
    // Consulta SQL para buscar solo los médicos que tengan esa especialidad_id
    $sql = "SELECT `id`, `nombre`, `apellido` FROM `profesionales` WHERE `especialidad_id` = $especialidad_id";
    $resultado = mysqli_query($conexion, $sql);
    
    $medicos = array();
    
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Guardamos cada médico en un array ordenado
            $medicos[] = array(
                'id' => $fila['id'],
                'nombre' => $fila['nombre'] . ' ' . $fila['apellido']
            );
        }
    }
    
    // Convertimos el array de PHP en el formato de texto JSON estándar y lo imprimimos
    echo json_encode($medicos);
} else {
    // Si no se envía ninguna especialidad, devolvemos un array vacío
    echo json_encode([]);
}
?>