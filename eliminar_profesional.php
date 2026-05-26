<?php
session_start();
include("conexion_bd.php");

// Seguridad: Si no está logueado, al login
if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

// Verificamos que el ID del profesional venga en la URL
if (isset($_GET['id'])) {
    
    // Guardamos el ID y lo convertimos en número entero por seguridad
    $id = intval($_GET['id']);
    
    // Creamos la orden para borrar SOLO al profesional con ese ID
    $sql = "DELETE FROM `profesionales` WHERE `id` = $id";
    
    // Ejecutamos la orden
    mysqli_query($conexion, $sql);
}

// AL FINALIZAR: Volvemos al panel principal
header("Location: panel_profesional.php");
exit();
?>