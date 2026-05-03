
<?php
session_start();
include ("conexion.php");

$dni =$_POST ['dni'];
$clave = $_POST['clave'];

// Buscamos al usuario
$consulta = "SELECT * FROM usuarios WHERE dni='$dni' AND clave='$clave'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $_SESSION['usuario_id'] = $fila['id'];
    header("Location: portal.php"); // ¡OJO! Esto te lleva al formulario médico
} else {
    echo "DNI o clave incorrectos. <a href='index.php'>Volver a intentar</a>";
}
?>