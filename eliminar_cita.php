<?php
session_start();
include("conexion_bd.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_cita = intval($_GET['id']);
    
    $sql = "DELETE FROM `citas` WHERE `ids` = $id_cita";
    
    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Cita eliminada correctamente.');
                window.location.href = 'panel_profesional.php';
              </script>";
    } else {
        echo "Error al eliminar la cita: " . mysqli_error($conexion);
    }
} else {
    header("Location: panel_profesional.php");
    exit();
}
?>