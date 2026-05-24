<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Portal Médico</title>
    <link href="iniciar_sesion.css?v=1.1" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="contenedor-login">
        <form action="validar.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Identificación de Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 30%;"><b>DNI</b></td>
                        <td><input type="text" name="dni" required placeholder="Ej. 12345678V"></td>
                    </tr>
                    <tr>
                        <td><b>Clave</b></td>
                        <td><input type="password" name="clave" required placeholder="••••"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="btn-contenedor">
                            <button type="submit">ENTRAR</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>