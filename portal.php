<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">                      <!--Interfaz de la web-->
    <title>Pedir Cita - Portal Médico</title>
    <link rel="stylesheet" href="edit.css">    <!--SIMPLE AÑADIDO DE LINK PARA CSS-->
</head>
<body>
    <h1>PEDIR CITA</h1>
    
    <form action="guardar_cita.php" method="POST">
        
        <p>1. Selecciona el Servicio y Médico:</p>
<select name="medico" required>
    <option value="">-- Seleccione una opción --</option>
    
    <optgroup label="Medicina General">
        <option value="Dr. Remars - Medicina General">Dr. Remars</option>
        <option value="Dra. García - Medicina General">Dra. García</option>
    </optgroup>

    <optgroup label="Pediatría">
        <option value="Dra. Doramas - Pediatría">Dra. Doramas</option>
        <option value="Dr. Cietora - Pediatría">Dr. Cietora</option>
    </optgroup>

    <optgroup label="Enfermería">
        <option value="DUE Ana Pérez - Curas">DUE Ana Pérez (Curas)</option>
        <option value="DUE Juan López - Vacunación">DUE Juan López (Vacunas)</option>
    </optgroup>
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
    </form>
    
</body>
</html>