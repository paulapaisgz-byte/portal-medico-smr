<?php
session_start();
include("conexion_bd.php");

if (isset($_POST['profesional_id']) && isset($_POST['dni_paciente']) && isset($_POST['fecha']) && isset($_POST['hora'])) {
    
    $profesional_id = intval($_POST['profesional_id']);
    $dni_paciente = mysqli_real_escape_string($conexion, trim($_POST['dni_paciente']));
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $hora = mysqli_real_escape_string($conexion, $_POST['hora']);

    $sql_usuario = "SELECT `id` FROM `usuarios` WHERE `dni` = '$dni_paciente'";
    $res_usuario = mysqli_query($conexion, $sql_usuario);

    if ($res_usuario && mysqli_num_rows($res_usuario) > 0) {
        $fila_user = mysqli_fetch_assoc($res_usuario);
        $usuario_id = $fila_user['id'];

        $sql_insertar = "INSERT INTO `citas` (`fecha`, `hora`, `usuario_id`, `profesional_id`) 
                         VALUES ('$fecha', '$hora', $usuario_id, $profesional_id)";
        
        if (mysqli_query($conexion, $sql_insertar)) {
            echo "<script>
                    alert('Cita registrada con éxito total.');
                    window.location.href = 'panel_profesional.php'; 
                  </script>";
        } else {
            echo "Error al registrar la cita: " . mysqli_error($conexion);
        }

    } else {
        echo "<script>
                alert('Error: El DNI introducido ($dni_paciente) no corresponde a ningún usuario registrado. Regístralo primero en la pestaña Usuario.');
                window.history.back();
              </script>";
    }

} else {
    echo "Faltan datos obligatorios en el formulario.";
}
?>