<?php
// Datos para nuestra base de datos 
$servidor = "localhost";
$usuario  = "root";      
$password = "";          
$base_datos = "portal_medico_db"; 

// Conectar 
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Comprobamos si podemos accerder
if ($conexion) {
    echo "¡Conexión exitosa a la base de datos!";
} else {
    echo "La conexión no se ha podido establecer, parece que algo ha fallado";
}
?>