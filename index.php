<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h2>IDENTIFICACIÓN DE USUARIO</h2>
    <form action="validar.php" method="POST">
        <p>DNI: <input type="text" name="dni" required></p>
        <p>Clave: <input type="password" name="clave" required></p>
        <button type="submit">ENTRAR</button>
    </form>
</body>
</html>