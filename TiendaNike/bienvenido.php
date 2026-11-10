
<?php
// *Inicia la sesión para acceder a los datos del usuario que ha iniciado sesión.
session_start();

// *Verifica que exista una sesión activa. Si el usuario no ha iniciado sesión, lo redirige al formulario de acceso y detiene la ejecución.
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// *Obtiene el nombre del usuario guardado en la sesión para mostrarlo en la página de bienvenida.
$nombre = $_SESSION["usuario_nombre"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- *Configura los datos básicos de la página, su adaptación a diferentes dispositivos, el título y la hoja de estilos. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Nike Store</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- *Muestra el encabezado de Nike Store con el logotipo y los enlaces para navegar entre las secciones de la tienda. -->
<header class="encabezado">
    <a class="logo" href="index.php">NIKE<span>.</span></a>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="tenis.php">Tenis</a>
        <a href="ropa.php">Ropa</a>
        <a href="accesorios.php">Accesorios</a>

        <!-- *Enlace para acceder a la bolsa de compras y mostrar la cantidad de productos. -->
        <a href="carrito.php">
            Bolsa (<span id="contador">0</span>)
        </a>
    </nav>
</header>

<!-- *Presenta el mensaje de bienvenida con el nombre del usuario, confirma que inició sesión y ofrece opciones para volver a la tienda o cerrar la sesión. -->
<main class="formulario-contenedor bienvenida">
    <h1>¡Bienvenido, <?php
        echo htmlspecialchars($nombre, ENT_QUOTES, "UTF-8");
    ?>!</h1>

    <p>Has iniciado sesión correctamente en Nike Store.</p>

    <a href="index.php" class="boton boton-bienvenida">
        Ir a la tienda
    </a>

    <a href="cerrar_sesion.php" class="enlace-formulario">
        Cerrar sesión
    </a>
</main>

<!-- *Muestra el pie de página con el nombre de la marca y su lema. -->
<footer>
    <h2>NIKE.</h2>
    <p>Just Do It.</p>
</footer>

<!-- *Conecta el archivo JavaScript para actualizar el contador de productos de la bolsa. -->
<script src="carrito.js"></script>

</body>
</html>
