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
    <link rel="stylesheet" href="panel.css" type="text/css">
</head>
<body>

    <h1>Panel de Administración: Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
    
    <div class="menu-gestion" id="panel">
        <ul>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-citas')" href="javascript:void(0)">Consultar citas</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-nueva')" href="javascript:void(0)">Nueva cita</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-profesional')" href="javascript:void(0)">Profesionales</a></li>
            <li><a class="ex1 flip" onclick="togglePanel('contenido-usuario')" href="javascript:void(0)">Usuario</a></li>
            <li><a class="ex1" href="profesional.php">Cerrar sesión</a></li>
        </ul>
    </div>

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
                // Modificado con INNER JOIN dinámicos para extraer los datos reales desde los IDs de la tabla citas
                $sql = "SELECT c.`ids`, c.`fecha`, c.`hora`, 
                               u.`dni` AS dni_real, u.`nombre` AS nombre_usuario, u.`apellido` AS apellido_usuario,
                               p.`nombre` AS nombre_medico, p.`apellido` AS apellido_medico
                        FROM `citas` c
                        INNER JOIN `usuarios` u ON c.`usuario_id` = u.`id`
                        INNER JOIN `profesionales` p ON c.`profesional_id` = p.`id`";
                
                $resultado = mysqli_query($conexion, $sql);
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)){
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fila["nombre_medico"]) . " " . htmlspecialchars($fila["apellido_medico"]) . "</td>";
                        echo "<td>" . $fila["fecha"] . "</td>";
                        echo "<td>" . $fila["hora"] . "</td>";
                        echo "<td>" . htmlspecialchars($fila["dni_real"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["nombre_usuario"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["apellido_usuario"]) . "</td>";
                        echo "<td class='centrado'>";
                        echo "<a href='editar_cita.php?id=" . $fila['ids'] . "' class='enlace-modificar'> Modificar </a> | ";
                        echo "<a href='eliminar_cita.php?id=" . $fila['ids'] . "' class='enlace-eliminar' onclick='return confirm(\"¿Seguro?\")'> Eliminar </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='texto-vacio'>No hay citas programadas de momento.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="contenido-nueva" class="panel-desplegable" style="display: none;">
        <h2>Agendar nueva cita</h2>
        <form action="guardar_cita.php" method="POST" class="formulario-panel">
            
            <label>Seleccionar Especialidad:</label><br>
            <select id="select-especialidad" required class="input-panel">
                <option value="">--Seleccione una especialidad--</option>
                <?php
                $query_esp = "SELECT `id`, `nombre_especialidades` FROM `especialidades`";
                $res_esp = mysqli_query($conexion, $query_esp);
                while($esp = mysqli_fetch_assoc($res_esp)) {
                    echo "<option value='".$esp['id']."'>".htmlspecialchars($esp['nombre_especialidades'])."</option>";
                }
                ?>
            </select>

            <br><label>Seleccionar Profesional:</label><br>
            <select id="select-profesional" name="profesional_id" required class="input-panel" disabled>
                <option value="">--Seleccione primero una especialidad--</option>
            </select>

            <br><label for="dni_paciente">DNI del Paciente:</label><br>
            <input type="text" id="dni_paciente" name="dni_paciente" required placeholder="Ej. 12345678A" class="input-panel">

            <br><label for="campo_fecha">Fecha:</label><br>
            <input type="date" id="campo_fecha" name="fecha" required class="input-panel">
            
            <br><label for="campo_hora">Hora:</label><br>
            <input type="time" id="campo_hora" name="hora" required class="input-panel">
            
            <br><br>
            <input type="submit" value="Registrar cita" class="boton-panel">
        </form>
    </div>
    
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
                <input type="text" id="clave_profesional" name="clave_profesional" required placeholder="Solo numeros" class="input-panel"><br>
                <label for="especialidad_profesional">Especialidad: </label><br>
                <input type="text" id="especialidad_profesional" name="especialidad_profesional" list="lista-especialidades" required placeholder="Selecciona o escribe una nueva" class="input-panel" autocomplete="off">

                <datalist id="lista-especialidades">
                    <?php
                    $sql_esp = "SELECT `nombre_especialidades` FROM `especialidades`";
                    $resultado_esp = mysqli_query($conexion, $sql_esp);
                    if ($resultado_esp && mysqli_num_rows($resultado_esp) > 0) {
                        while ($fila_esp = mysqli_fetch_assoc($resultado_esp)) {
                            echo "<option value='" . htmlspecialchars($fila_esp['nombre_especialidades']) . "'>";
                        }
                    }
                    ?>
                </datalist><br>
                <input type="submit" value="Registrar" class="boton-panel ancho-total">
            </form>
        </div>

        <hr class="linea-separadora">

        <h3>Listado de Profesionales Activos</h3>
        <table border="1" class="tabla-panel" id="tabla-profesionales">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Profesional</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>clave</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_prof = "SELECT p.`id`, p.`nombre`, p.`apellido`, p.`clave`, p.`especialidad_id`, e.`nombre_especialidades` 
                             FROM `profesionales` p
                             INNER JOIN `especialidades` e ON p.`especialidad_id` = e.`id`";
                $resultado_prof = mysqli_query($conexion, $sql_prof);

                $especialidades_lista = [];
                $sql_esp = "SELECT `id`, `nombre_especialidades` FROM `especialidades`";
                $res_esp = mysqli_query($conexion, $sql_esp);
                while($esp = mysqli_fetch_assoc($res_esp)) {
                    $especialidades_lista[] = $esp;
                }

                if ($resultado_prof && mysqli_num_rows($resultado_prof) > 0) {
                    while ($fila_prof = mysqli_fetch_assoc($resultado_prof)) {
                        $id = $fila_prof["id"];
                        echo "<tr id='fila-$id'>";
                        echo "<td>" . $id . "</td>";
                        echo "<td class='celda-nombre'><span class='txt'>" . htmlspecialchars($fila_prof["nombre"]) . "</span><input type='text' class='inp input-panel' value='" . htmlspecialchars($fila_prof["nombre"]) . "' style='display:none;'></td>";
                        echo "<td class='celda-apellido'><span class='txt'>" . htmlspecialchars($fila_prof["apellido"]) . "</span><input type='text' class='inp input-panel' value='" . htmlspecialchars($fila_prof["apellido"]) . "' style='display:none;'></td>";
                        
                        echo "<td class='celda-especialidad'>";
                        echo "<span class='txt'>" . htmlspecialchars($fila_prof["nombre_especialidades"]) . "</span>";
                        echo "<input type='text' class='inp input-panel' list='lista-especialidades-$id' value='" . htmlspecialchars($fila_prof["nombre_especialidades"]) . "' style='display:none;'>";

                        echo "<datalist id='lista-especialidades-$id'>";
                        foreach($especialidades_lista as $opcion) {
                            echo "<option value='" . htmlspecialchars($opcion['nombre_especialidades']) . "'>";
                        }
                        echo "</datalist>";
                        echo "</td>";
                        echo "<td class='celda-clave'><span class='txt'>" . htmlspecialchars($fila_prof["clave"]) . "</span><input type='text' class='inp input-panel' value='" . htmlspecialchars($fila_prof["clave"]) . "' style='display:none;'></td>";
                        
                        echo "<td class='centrado'>";
                        echo "<div class='modo-vista'>";
                        echo "<button type='button' class='boton-editar' onclick='activarEdicion($id)' style='background:#2196F3; color:white; border:none; padding:5px 10px; margin-right:5px; cursor:pointer; border-radius:3px;'>Modificar</button>";
                        echo "<a href='eliminar_profesional.php?id=$id' class='enlace-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este profesional?\")'> Eliminar </a>";
                        echo "</div>";
                
                        echo "<div class='modo-editar' style='display:none;'>";
                        echo "<button type='button' onclick='guardarEdicion($id)' style='background:#4CAF50; color:white; border:none; padding:5px 10px; margin-right:5px; cursor:pointer; border-radius:3px;'>Guardar</button>";
                        echo "<button type='button' onclick='cancelarEdicion($id)' style='background:#f44336; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;'>Cancelar</button>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='texto-vacio'>No hay profesionales registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <script>
            function activarEdicion(id) {
                let fila = document.getElementById('fila-' + id);
                fila.querySelectorAll('.txt').forEach(el => el.style.display = 'none');
                fila.querySelectorAll('.inp').forEach(el => el.style.display = 'block');
                fila.querySelector('.modo-vista').style.display = 'none';
                fila.querySelector('.modo-editar').style.display = 'block';
            }
            function cancelarEdicion(id) {
                let fila = document.getElementById('fila-' + id);
                fila.querySelectorAll('.txt').forEach(el => el.style.display = 'block');
                fila.querySelectorAll('.inp').forEach(el => el.style.display = 'none');
                fila.querySelector('.modo-vista').style.display = 'block';
                fila.querySelector('.modo-editar').style.display = 'none';
            }
            function guardarEdicion(id) {
                let fila = document.getElementById('fila-' + id);
                let nuevoNombre = fila.querySelector('.celda-nombre .inp').value;
                let nuevoApellido = fila.querySelector('.celda-apellido .inp').value;
                let nuevaEspecialidadId = fila.querySelector('.celda-especialidad .inp').value;
                let nuevaClave = fila.querySelector('.celda-clave .inp').value;
                let datos = new FormData();
                datos.append('id', id);
                datos.append('nombre', nuevoNombre);
                datos.append('apellido', nuevoApellido);
                datos.append('especialidad_id', nuevaEspecialidadId);
                datos.append('clave', nuevaClave);

                fetch('modificar_profesional.php', {
                    method: 'POST',
                    body: datos
                })
                .then(res => res.text())
                .then(data => {
                    if(data.trim() === "success") {
                        window.location.reload();
                    } else {
                        alert("Error al actualizar los datos: " + data);
                    }
                });
            }
        </script>
    </div>
    
<div id="contenido-usuario" class="panel-desplegable" style="display: none;">
        <h2>Gestión de Usuarios / Pacientes</h2>
        
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

        <h3>Listado de Usuarios Registrados</h3>
        <table border="1" class="tabla-panel" id="tabla-usuarios">
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
                $sql_user = "SELECT `id`, `dni`, `clave`, `nombre`, `apellido` FROM `usuarios`";
                $resultado_user = mysqli_query($conexion, $sql_user);

                if ($resultado_user && mysqli_num_rows($resultado_user) > 0) {
                    while ($fila_user = mysqli_fetch_assoc($resultado_user)) {
                        $id_u = $fila_user["id"];
                        echo "<tr id='fila-user-$id_u'>";
                        echo "<td>" . $id_u . "</td>";
                        echo "<td class='celda-user-dni'><span class='txt-u'>" . htmlspecialchars($fila_user["dni"]) . "</span><input type='text' class='inp-u input-panel' value='" . htmlspecialchars($fila_user["dni"]) . "' style='display:none;'></td>";
                        echo "<td class='celda-user-clave'><span class='txt-u'>" . htmlspecialchars($fila_user["clave"]) . "</span><input type='text' class='inp-u input-panel' value='" . htmlspecialchars($fila_user["clave"]) . "' style='display:none;'></td>";
                        echo "<td class='celda-user-nombre'><span class='txt-u'>" . htmlspecialchars($fila_user["nombre"]) . "</span><input type='text' class='inp-u input-panel' value='" . htmlspecialchars($fila_user["nombre"]) . "' style='display:none;'></td>";
                        echo "<td class='celda-user-apellido'><span class='txt-u'>" . htmlspecialchars($fila_user["apellido"]) . "</span><input type='text' class='inp-u input-panel' value='" . htmlspecialchars($fila_user["apellido"]) . "' style='display:none;'></td>";
                        
                        echo "<td class='centrado'>";
                        echo "<div class='modo-vista-user'>";
                        echo "<button type='button' class='boton-editar' onclick='activarEdicionUser($id_u)' style='background:#2196F3; color:white; border:none; padding:5px 10px; margin-right:5px; cursor:pointer; border-radius:3px;'>Modificar</button>";
                        echo "<a href='eliminar_usuario.php?id=$id_u' class='enlace-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas dar de baja a este usuario?\")'> Eliminar </a>";
                        echo "</div>";
                
                        echo "<div class='modo-editar-user' style='display:none;'>";
                        echo "<button type='button' onclick='guardarEdicionUser($id_u)' style='background:#4CAF50; color:white; border:none; padding:5px 10px; margin-right:5px; cursor:pointer; border-radius:3px;'>Guardar</button>";
                        echo "<button type='button' onclick='cancelarEdicionUser($id_u)' style='background:#f44336; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;'>Cancelar</button>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='texto-vacio'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <script>
            function activarEdicionUser(id) {
                let fila = document.getElementById('fila-user-' + id);
                fila.querySelectorAll('.txt-u').forEach(el => el.style.display = 'none');
                fila.querySelectorAll('.inp-u').forEach(el => el.style.display = 'block');
                fila.querySelector('.modo-vista-user').style.display = 'none';
                fila.querySelector('.modo-editar-user').style.display = 'block';
            }
            function cancelarEdicionUser(id) {
                let fila = document.getElementById('fila-user-' + id);
                fila.querySelectorAll('.txt-u').forEach(el => el.style.display = 'block');
                fila.querySelectorAll('.inp-u').forEach(el => el.style.display = 'none');
                fila.querySelector('.modo-vista-user').style.display = 'block';
                fila.querySelector('.modo-editar-user').style.display = 'none';
            }
            function guardarEdicionUser(id) {
                let fila = document.getElementById('fila-user-' + id);
                let nuevoDni = fila.querySelector('.celda-user-dni .inp-u').value;
                let nuevaClave = fila.querySelector('.celda-user-clave .inp-u').value;
                let nuevoNombre = fila.querySelector('.celda-user-nombre .inp-u').value;
                let nuevoApellido = fila.querySelector('.celda-user-apellido .inp-u').value;
                
                if(nuevoDni.trim() === "" || nuevoNombre.trim() === "" || nuevoApellido.trim() === "") {
                    alert("Por favor, no dejes campos obligatorios vacíos.");
                    return;
                }

                let datos = new FormData();
                datos.append('id', id);
                datos.append('dni', nuevoDni);
                datos.append('clave', nuevaClave);
                datos.append('nombre', nuevoNombre);
                datos.append('apellido', nuevoApellido);

                fetch('modificar_usuario.php', {
                    method: 'POST',
                    body: datos
                })
                .then(res => res.text())
                .then(data => {
                    if(data.trim() === "success") {
                        window.location.reload();
                    } else {
                        alert("Error al actualizar el usuario: " + data);
                    }
                });
            }
        </script>
    </div>

        <script>
        // Función para abrir y cerrar las pestañas del menú de gestión
        function togglePanel(idPanel) {
            // Escondemos todos los paneles primero
            document.querySelectorAll('.panel-desplegable').forEach(panel => {
                panel.style.display = 'none';
            });
            // Mostramos únicamente el panel seleccionado
            let panelActivo = document.getElementById(idPanel);
            if (panelActivo) {
                panelActivo.style.display = 'block';
            }
        }

        // Lógica de los desplegables dinámicos (Especialidad -> Profesional)
        document.getElementById('select-especialidad').addEventListener('change', function() {
            var especialidadId = this.value;
            var selectProfesional = document.getElementById('select-profesional');
            
            // Limpiamos el desplegable de médicos
            selectProfesional.innerHTML = '<option value="">--Seleccione un profesional--</option>';
            
            if (especialidadId === "") {
                selectProfesional.disabled = true;
                return;
            }
            
            // Llamamos por Fetch pasándole la especialidad elegida
            fetch('obtener_medicos.php?especialidad_id=' + especialidadId)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Recorremos los médicos recibidos del JSON y los metemos en el select
                        data.forEach(medico => {
                            var option = document.createElement('option');
                            option.value = medico.id;
                            option.textContent = medico.nombre;
                            selectProfesional.appendChild(option);
                        });
                        selectProfesional.disabled = false; // Desbloqueamos el select
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
    <a href="cerrar_sesionpro.php" class="boton-cerrar-sesion">Cerrar sesión</a>
</body>
</html>