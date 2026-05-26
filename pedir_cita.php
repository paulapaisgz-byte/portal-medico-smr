<!--NUMERO 5-->
<?php
session_start();
include("conexion_bd.php"); 

// Validamos que el paciente tenga la sesión iniciada
if (!isset($_SESSION['usuario'])) { 
    header("Location: iniciar_sesion.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal del Paciente - Portal Médico</title>
    <link rel="stylesheet" href="pedir_cita.css?v=1.1" type="text/css">
</head>
<body>

    <div class="cabecera-paciente">
        Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?>
    </div>

    <div class="menu-paciente">
        <button type="button" onclick="mostrarPestana('consultar')">Consultar citas</button>
        <button type="button" onclick="mostrarPestana('pedir')">Pedir cita</button>
        <a href="cerrar_sesion.php" onclick="return confirm('¿Deseas cerrar la sesión?')">Cerrar sesión</a>
    </div>

    <div id="pestana-consultar" class="seccion-paciente" style="display: block;">
        <h2>Mis Citas Programadas</h2>
        <p>Aquí mostraremos la tabla con las citas que este paciente ya tiene reservadas.</p>
        </div>

    <div id="pestana-pedir" class="seccion-paciente" style="display: none;">
        <h2>Pedir una nueva cita</h2>
        
        <form action="guardar_citapaciente.php" method="POST" class="form-paciente">
            
            <p>1. Selecciona el Servicio y Médico:</p>
            <select name="nombre_medico" required class="select-paciente">
                <option value="">--Seleccione un profesional--</option>
                <?php
                $sql_profesionales = "SELECT `nombre` FROM `profesionales`";
                $res_profesionales = mysqli_query($conexion, $sql_profesionales);
                while ($prof = mysqli_fetch_assoc($res_profesionales)){
                    echo "<option value='".htmlspecialchars($prof["nombre"])."'>".htmlspecialchars($prof["nombre"])."</option>";
                }
                ?>
            </select>
            
            <p>2. Seleccionar Fecha</p>
            <input type="date" name="fecha" required class="input-paciente">

            <p>3. Seleccionar Hora</p>
            <select name="hora" required class="select-paciente">
                <option value="08:00:00">08:00</option>
                <option value="09:00:00">09:00</option>
                <option value="10:00:00">10:00</option>
                <option value="11:00:00">11:00</option>
            </select>

            <br><br>
            <button type="submit" class="btn-confirmar">CONFIRMAR CITA</button>
        </form>
    </div>

    <script>
        function mostrarPestana(pestana) {
            // Ocultamos ambos bloques
            document.getElementById('pestana-consultar').style.display = 'none';
            document.getElementById('pestana-pedir').style.display = 'none';
            
            // Mostramos el seleccionado
            if (pestana === 'consultar') {
                document.getElementById('pestana-consultar').style.display = 'block';
            } else if (pestana === 'pedir') {
                document.getElementById('pestana-pedir').style.display = 'block';
            }
        }
    </script>
    
</body>
</html>