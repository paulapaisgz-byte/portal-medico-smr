<?php
session_start();
include("conexion_bd.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: panel_profesional.php");
    exit();
}

$id_cita = intval($_GET['id']);

$sql_cita = "SELECT c.*, u.`dni`, p.`especialidad_id` 
             FROM `citas` c
             INNER JOIN `usuarios` u ON c.`usuario_id` = u.`id`
             INNER JOIN `profesionales` p ON c.`profesional_id` = p.`id`
             WHERE c.`ids` = $id_cita";

$resultado_cita = mysqli_query($conexion, $sql_cita);

if ($resultado_cita && mysqli_num_rows($resultado_cita) > 0) {
    $cita = mysqli_fetch_assoc($resultado_cita);
} else {
    echo "Cita no encontrada.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profesional_id = intval($_POST['profesional_id']);
    $dni_paciente = mysqli_real_escape_string($conexion, trim($_POST['dni_paciente']));
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $hora = mysqli_real_escape_string($conexion, $_POST['hora']);

    $sql_user = "SELECT `id` FROM `usuarios` WHERE `dni` = '$dni_paciente'";
    $res_user = mysqli_query($conexion, $sql_user);

    if ($res_user && mysqli_num_rows($res_user) > 0) {
        $user_data = mysqli_fetch_assoc($res_user);
        $usuario_id = $user_data['id'];

        $sql_update = "UPDATE `citas` 
                       SET `fecha` = '$fecha', `hora` = '$hora', `usuario_id` = $usuario_id, `profesional_id` = $profesional_id 
                       WHERE `ids` = $id_cita";

        if (mysqli_query($conexion, $sql_update)) {
            echo "<script>
                    alert('Cita modificada con éxito.');
                    window.location.href = 'panel_profesional.php';
                  </script>";
        } else {
            echo "Error al actualizar la cita: " . mysqli_error($conexion);
        }
    } else {
        echo "<script>
                alert('Error: El DNI introducido no corresponde a ningún usuario.');
                window.history.back();
              </script>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Cita</title>
    <link rel="stylesheet" href="panel.css" type="text/css">
</head>
<body>
    <h1>Modificar Cita</h1>
    <div class="panel-desplegable" style="display: block; max-width: 500px; margin: 0 auto;">
        <form action="" method="POST" class="formulario-panel">
            
            <label>Seleccionar Especialidad:</label><br>
            <select id="select-especialidad" required class="input-panel">
                <option value="">--Seleccione una especialidad--</option>
                <?php
                $query_esp = "SELECT `id`, `nombre_especialidades` FROM `especialidades`";
                $res_esp = mysqli_query($conexion, $query_esp);
                while($esp = mysqli_fetch_assoc($res_esp)) {
                    $selected = ($esp['id'] == $cita['especialidad_id']) ? "selected" : "";
                    echo "<option value='".$esp['id']."' $selected>".htmlspecialchars($esp['nombre_especialidades'])."</option>";
                }
                ?>
            </select>

            <br><label>Seleccionar Profesional:</label><br>
            <select id="select-profesional" name="profesional_id" required class="input-panel">
                <?php
                $esp_id = intval($cita['especialidad_id']);
                $query_prof = "SELECT `id`, `nombre`, `apellido` FROM `profesionales` WHERE `especialidad_id` = $esp_id";
                $res_prof = mysqli_query($conexion, $query_prof);
                while($prof = mysqli_fetch_assoc($res_prof)) {
                    $selected = ($prof['id'] == $cita['profesional_id']) ? "selected" : "";
                    echo "<option value='".$prof['id']."' $selected>".htmlspecialchars($prof['nombre'] . " " . $prof['apellido'])."</option>";
                }
                ?>
            </select>

            <br><label for="dni_paciente">DNI del Paciente:</label><br>
            <input type="text" id="dni_paciente" name="dni_paciente" required value="<?php echo htmlspecialchars($cita['dni']); ?>" class="input-panel">

            <br><label for="campo_fecha">Fecha:</label><br>
            <input type="date" id="campo_fecha" name="fecha" required value="<?php echo $cita['fecha']; ?>" class="input-panel">
            
            <br><label for="campo_hora">Hora:</label><br>
            <input type="time" id="campo_hora" name="hora" required value="<?php echo $cita['hora']; ?>" class="input-panel">
            
            <br><br>
            <input type="submit" value="Guardar Cambios" class="boton-panel">
            <a href="panel_profesional.php" style="margin-left: 10px; color: #f44336; text-decoration: none;">Cancelar</a>
        </form>
    </div>

    <script>
    document.getElementById('select-especialidad').addEventListener('change', function() {
        var especialidadId = this.value;
        var selectProfesional = document.getElementById('select-profesional');
        
        selectProfesional.innerHTML = '<option value="">--Seleccione un profesional--</option>';
        
        if (especialidadId === "") {
            selectProfesional.disabled = true;
            return;
        }
        
        fetch('obtener_medicos.php?especialidad_id=' + especialidadId)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(medico => {
                        var option = document.createElement('option');
                        option.value = medico.id;
                        option.textContent = medico.nombre;
                        selectProfesional.appendChild(option);
                    });
                    selectProfesional.disabled = false;
                } else {
                    selectProfesional.innerHTML = '<option value="">No hay profesionales en esta área</option>';
                    selectProfesional.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error al cargar médicos:', error);
            });
    });
    </script>
</body>
</html>