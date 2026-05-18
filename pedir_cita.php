<!--NUMERO 5-->
<?php
session_start();
include("conexion_bd.php"); 
if (!isset($_SESSION['usuario'])) { 
    header("Location: iniciar_sesion.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedir Cita - Portal Médico</title>
</head>
<body>

    <h1>Panel de Pacientes: Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
    
    <h2>PEDIR CITA</h2>
    <form action="guardar_citapaciente.php" method="POST">
        
        <p>1. Selecciona el Servicio y Médico:</p>
        <select name="nombre_medico" required class="input-panel">
            <option value="">--Seleccione un profesional--</option>
            <?php
            $sql_profesionales = "SELECT `nombre` FROM `profesionales`";
            $res_profesionales = mysqli_query($conexion, $sql_profesionales);
            
            // Recorremos la base de datos fila por fila creando un <option> para cada médico
            while ($prof = mysqli_fetch_assoc($res_profesionales)){
                echo "<option value='".$prof["nombre"]."'>".$prof["nombre"]."</option>";
            }
            ?>
        </select>
        <p>2. Seleccionar Fecha</p>
        <input type="date" name="fecha" required>

        <p>3. Seleccionar Hora</p>
        <select name="hora" required>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
        </select>

        <br><br>
        <button type="submit">CONFIRMAR CITA</button>
        <a href="cerrar_sesion.php" style="text-decoration: none; color: black">CERRAR SESION</a>
    </form>
    
</body>
</html>