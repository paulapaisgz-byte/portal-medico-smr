<?php
$conexion = mysqli_connect("localhost", "root", "", "portal_medico_db", 3307);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>