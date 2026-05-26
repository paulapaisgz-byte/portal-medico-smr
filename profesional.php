<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido DOC - Portal Médico</title>
    <link href="estilopro.css?v=1.1" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="contenedor-login">
        <form action="validar_profesional.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Introduzca sus datos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 30%;"><b>Nombre</b></td>
                        <td><input type="text" name="nombre" required placeholder="Ej. Daniel"></td>
                    </tr>
                    <tr>
                        <td><b>Apellido</b></td>
                        <td><input type="text" name="apellido" required placeholder="Ej. Salazar"></td>
                    </tr>
                    <tr>
                        <td><b>Clave</b></td>
                        <td><input type="password" name="clave" required placeholder="••••"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="btn-contenedor">
                            <input type="submit" value="Acceder al panel">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>