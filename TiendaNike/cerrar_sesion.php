<?php
// *Inicia la sesión para acceder a los datos de sesión del usuario.
session_start();

// *Elimina todas las variables almacenadas en la sesión y destruye la sesión actual para cerrar la sesión del usuario.
$_SESSION = array();
session_destroy();

// *Redirige al usuario a la página principal de la tienda después de cerrar la sesión.
header("Location: index.php");
exit;
?>