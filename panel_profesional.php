<?php
session_start();
include("conexion_bd.php");
if (!isset($_SESSION["usuario"])) {
    header("Location: profesional.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Gestión Médica</title>
    <link rel="stylesheet" href="panel.css" type=text/css>
</head>
<body>

    <h1>Panel de Administración: Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
    
    <div class="menu-gestion" id="panel">

        <ul>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-citas')" href="javascript:void(0)">Modificar/Eliminar citas</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-nueva')" href="javascript:void(0)">Nueva cita</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-profesional')" href="javascrip:void(0)">Profesionales</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-usuario')" href="javascript:void(0)">Usuario</a></li>
            <li><a class="ex1" href="profesional.php">Cerrar sesión</a></li>
        </ul>
    </div>

                                                    <!-- 1. PANEL DE GESTIÓN DE CITAS -->
    <div id="contenido-citas" class="panel-desplegable" style="display: none;">
        <h2>Gestion de citas</h2>
        <table border="1" class="tabla-panel">
            <thead>
                <tr>
                    <th>Profesional</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT `ids`, `nombre_medico`, `fecha`, `hora`, `nombres`, `apellidos`, usuarios.`dni` AS dni_real  FROM `citas` INNER JOIN `usuarios` ON citas.`DNI` = usuarios.`id`";
                $resultado = mysqli_query($conexion,$sql);
                while ($fila = mysqli_fetch_assoc($resultado)){
                    echo "<tr>";
                    echo "<td>" . $fila["nombre_medico"] . "</td>";
                    echo "<td>" . $fila["fecha"] . "</td>";
                    echo "<td>" . $fila["hora"] . "</td>";
                    echo "<td>" . $fila["dni_real"] . "</td>";
                    echo "<td>" . $fila["nombres"] . "</td>";
                    echo "<td>" . $fila["apellidos"] . "</td>";
                    echo "<td class='centrado'>";
                    echo "<a href='editar_cita.php?id=" . $fila['ids'] . "' class='enlace-modificar'> Modificar </a> | ";
                    echo "<a href='eliminar_cita.php?id=" . $fila['ids'] . "' class='enlace-eliminar' onclick='return confirm(\"¿Seguro?\")'> Eliminar </a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

                                                         <!-- 2. PANEL DE NUEVA CITA -->
<div id="contenido-nueva" class="panel-desplegable" style="display: none;">
        <h2>Agendar nueva cita</h2>
        <form action="guardar_cita.php" method="POST" class="formulario-panel">
            
            <label>Seleccionar Profesional:</label><br>
            <select name="nombre_medico" required class="input-panel">
                <option value="">--Seleccione un profesional--</option>
                <?php
                // Traemos los profesionales operativos de tu tabla
                $sql_profesionales = "SELECT `nombre` FROM `profesionales`";
                $res_profesionales = mysqli_query($conexion, $sql_profesionales);
                while ($prof = mysqli_fetch_assoc($res_profesionales)){
                    echo "<option value='".$prof["nombre"]."'>".$prof["nombre"]."</option>";
                }
                ?>
            </select>

            <br><label for="dni_paciente">DNI del Paciente:</label><br>
                <input type="text" id="dni_paciente" name="dni_manual" required placeholder="Ej. 12345678A" class="input-panel">

            <br><label for="nombre_paciente">Nombre del Paciente:</label><br>
                <input type="text" id="nombre_paciente" name="nombres_manual" required placeholder="Ej. Juan" class="input-panel">

            <br><label for="apellido_paciente">Apellido del Paciente:</label><br>
                <input type="text" id="apellido_paciente" name="apellidos_manual" required placeholder="Ej. Pérez" class="input-panel">

            <br><label for="campo_fecha">Fecha:</label><br>
                <input type="date" id="campo_fecha" name="fecha" required class="input-panel">
            
            <br><label for="campo_hora">Hora:</label><br>
                <input type="time" id="campo_hora" name="hora" required class="input-panel">
            
            <br><br>
            <input type="submit" value="Registrar cita" class="boton-panel">
        </form>
    </div>
    
                                                         <!-- 3. PANEL DE GESTIÓN DE PROFESIONALES -->
    <div id="contenido-profesional" class="panel-desplegable" style="display: none;">
        <h2>Gestión de Profesionales</h2>
        
        <div class="bloque-formulario">
            <h3>Registrar Nuevo Profesional</h3>
            <form action="guardar_profesional.php" method="POST" class="formulario-panel ancho-fijo">
                <label for="nombre_profesional">Nombre: </label><br>
                <input type="text" id="nombre_profesional" name="nombre_profesional" required placeholder="Ej. Dr. Carlos Gómez" class="input-panel">
                <label for="apellido_profesional">Apellido:</label><br>
                <input type="text" id="apellido_profesional" name="apellido_profesional" required placeholder="Ej. Jimenez" class="input-panel">
                <label for="clave_profesional">Clave:</label><br>
                <input type="text" id="clave_profesional" name="clave_profesional" required placeholder="Solo numeros" class="input-panel">
                <br>
                <input type="submit" value="Registrar" class="boton-panel ancho-total">
            </form>
        </div>

        <hr class="linea-separadora">

        <h3>Listado de Profesionales Activos</h3>               <!--Profesionales activos-->
        <table border="1" class="tabla-panel">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Profesional</th>
                    <th>Apellido</th>
                    <th>clave</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_prof = "SELECT `id`, `nombre`, `apellido`, `clave` FROM `profesionales`";
                $resultado_prof = mysqli_query($conexion, $sql_prof);

                if (mysqli_num_rows($resultado_prof) > 0) {
                    while ($fila_prof = mysqli_fetch_assoc($resultado_prof)) {
                        echo "<tr>";
                        echo "<td>" . $fila_prof["id"] . "</td>";
                        echo "<td>" . $fila_prof["nombre"] . "</td>";
                        echo "<td>" . $fila_prof["apellido"] . "</td>";
                        echo "<td>" . $fila_prof["clave"] . "</td>";
                        echo "<td class='centrado'>";
                        echo "<a href='eliminar_profesional.php?id=" . $fila_prof['id'] . "' class='enlace-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este profesional?\")'> Eliminar </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='texto-vacio'>No hay profesionales registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
                                                    <!-- 4. PANEL DE GESTIÓN DE USUARIOS (PACIENTES) -->
    <div id="contenido-usuario" class="panel-desplegable" style="display: none;">
        <h2>Gestión de Usuarios / Pacientes</h2>
        
                                                    <!-- Formulario para dar de alta a usuarios-->
        <div class="bloque-formulario">
            <form action="guardar_usuario.php" method="POST" class="formulario-panel ancho-fijo">
                <label for="dni_usuario">DNI:</label><br>
                <input type="text" id="dni_usuario" name="dni" required placeholder="Ej. 12345678A" class="input-panel"><br>
                <label for="clave_usuario">Clave para el usuario:</label><br>
                <input type="text" id="clave_usuario" name="clave" required placeholder="Introduzca una clave" class="input-panel">
                <label for="nombre_usuario">Nombre:</label><br>
                <input type="text" id="nombre_usuario" name="nombre" required placeholder="Ej. Juan Pérez" class="input-panel">
                <label for="apellido_usuario">Apellido:</label><br>
                <input type="text" id="apellido_usuario" name="apellido" required placeholder="Ej. Jimenez" class="input-panel">
                <br><br>
                <input type="submit" value="Dar de alta usuario" class="boton-panel ancho-total">
            </form>
        </div>

        <hr class="linea-separadora">

                                                        <!-- Tabla para listar y dar de baja -->
        <h3>Listado de Usuarios Registrados</h3>
        <table border="1" class="tabla-panel">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                             // Hacemos la consulta a la tabla 'usuarios' (el ID es autoincrement, no hace falta pedirlo en el form)
                $sql_user = "SELECT `id`, `dni`, `clave`, `nombre`, `apellido` FROM `usuarios`";
                $resultado_user = mysqli_query($conexion, $sql_user);

                if (mysqli_num_rows($resultado_user) > 0) {
                    while ($fila_user = mysqli_fetch_assoc($resultado_user)) {
                        echo "<tr>";
                        echo "<td>" . $fila_user["id"] . "</td>";
                        echo "<td>" . $fila_user["dni"] . "</td>";
                        echo "<td>" . $fila_user["clave"] . "</td>";
                        echo "<td>" . $fila_user["nombre"] . "</td>";
                        echo "<td>" . $fila_user["apellido"] . "</td>";
                        echo "<td class='centrado'>";
                        // Enlace para dar de baja (eliminar) pasando el ID por URL
                        echo "<a href='eliminar_usuario.php?id=" . $fila_user['id'] . "' class='enlace-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas dar de baja a este usuario?\")'> Eliminar </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='texto-vacio'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- SCRIPT (Nota: Mantenemos el style='display: none;' en las declaraciones de las etiquetas div de los paneles principales arriba para asegurar que arranquen ocultas por defecto antes de que actúe JS) -->
    <script>
        function togglePanel(id){
            var paneles = document.getElementsByClassName("panel-desplegable");
            for (var i = 0; i < paneles.length; i++) {
                paneles[i].style.display = "none";
            }
            var x = document.getElementById(id);
            if (x){
                x.style.display = "block";
            } 
        }
    </script>
    <a href="cerrar_sesionpro.php" class="boton-cerrar-sesion">Cerrar sesion</a>
</body>
</html>