<!--NUMERO 8-->
<?php
session_start();
include("conexion_bd.php");

$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
$clave = mysqli_real_escape_string($conexion, $_POST['clave']);

$consulta = "SELECT * FROM profesionales WHERE nombre='$nombre' AND apellido='$apellido' AND clave='$clave'";
$resultado = mysqli_query($conexion, $consulta);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $_SESSION['usuario'] = $fila['nombre'];
    $_SESSION['id_profesional'] = $fila['id']; // <--- Guarda esto para el CRUD
    $_SESSION['rol'] = 'profesional';
    
    header("Location: panel_profesional.php");
    exit(); 
} else {
    echo "Datos incorrectos. <a href='profesional.php'>Volver a intentar</a>";
    exit();
}
?>