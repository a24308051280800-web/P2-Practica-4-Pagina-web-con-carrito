<?php
// *Define los datos necesarios para conectarse al servidor MySQL, incluyendo el servidor, el usuario, la contraseña y el nombre de la base de datos.
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "tiendanike";

// *Establece la conexión con la base de datos utilizando los datos de acceso definidos anteriormente.
$conexion = new mysqli(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos
);

// *Comprueba si ocurrió un error durante la conexión. Si existe un problema, detiene la ejecución y muestra el mensaje de error.
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// *Configura la conexión para utilizar UTF-8 y permitir el manejo correcto de caracteres especiales, acentos y símbolos.
$conexion->set_charset("utf8mb4");
?>