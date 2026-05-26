<!--NUMERO 4-->
<?php
include ("conexion_bd.php");

$dni   = $_POST['dni'] ?? '';
$clave = $_POST['clave'] ?? '';

// Buscamos al usuario
$consulta = "SELECT * FROM `usuarios` WHERE `dni` = '$dni' AND `clave` = '$clave'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $usuario_registrado = mysqli_fetch_assoc($resultado);
    session_start();
    $_SESSION['id_usuario'] = $usuario_registrado['id'];
    $_SESSION['usuario']    = $usuario_registrado['nombre'];
    header("Location: pedir_cita.php");
    exit();
} else {
    echo "<h2>DNI o clave incorrectos.<h2>";
    echo "<a href='iniciar_sesion.php'>Volver a intentar</a>";
}
mysqli_close($conexion)
?>