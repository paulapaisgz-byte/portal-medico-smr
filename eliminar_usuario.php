<?php
session_start();
include("conexion_bd.php");

// Seguridad: Si no está logueado, al login
if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

// Verificamos que el ID del usuario venga en la URL
if (isset($_GET['id'])) {
    
    // Convertimos a entero por seguridad
    $id = intval($_GET['id']);
    
    // Orden de eliminación para ese usuario específico
    $sql = "DELETE FROM `usuarios` WHERE `id` = $id";
    
    mysqli_query($conexion, $sql);
}

// Al terminar, regresamos al panel principal
header("Location: panel_profesional.php");
exit();
?>