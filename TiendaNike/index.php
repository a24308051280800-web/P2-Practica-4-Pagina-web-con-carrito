
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración general de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike Store | Tienda Deportiva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- Encabezado y navegación principal -->
<header class="encabezado">
    <a class="logo" href="index.php">NIKE<span>.</span></a>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="tenis.php">Tenis</a>
        <a href="ropa.php">Ropa</a>
        <a href="accesorios.php">Accesorios</a>

        <?php include 'usuario_nav.php'; ?>

        <!-- Botón de la bolsa de compras -->
        <a href="carrito.php">
            Bolsa (<span id="contador">0</span>)
        </a>
    </nav>
</header>

<!-- Presentación principal de la tienda -->
<section class="hero">
    <div class="hero-texto">
        <p class="etiqueta">BIENVENIDO A NIKE STORE</p>

        <h1>SUPERA TUS<br> LÍMITES.</h1>

        <p>
            En Nike Store encontrarás una gran variedad de productos
            deportivos diseñados para acompañarte en cada entrenamiento
            y en tu vida diaria. Descubre tenis, ropa y accesorios que
            combinan comodidad, innovación y estilo.
        </p>
    </div>
</section>

<!-- Categorías disponibles en la tienda -->
<section class="seccion">
    <h2>Explora nuestras categorías</h2>

    <div class="categorias">

        <a href="tenis.php" class="categoria">
            <img src="imagenes/airmax.png" alt="Tenis Nike">
            <h3>Tenis</h3>
        </a>

        <a href="ropa.php" class="categoria">
            <img src="imagenes/playera.png" alt="Ropa Nike">
            <h3>Ropa</h3>
        </a>

        <a href="accesorios.php" class="categoria">
            <img src="imagenes/mochila.png" alt="Accesorios Nike">
            <h3>Accesorios</h3>
        </a>

    </div>
</section>

<!-- Información adicional de la tienda -->
<section class="seccion">
    <h2>Encuentra tu estilo</h2>

    <p>
        Nuestro objetivo es ofrecer productos deportivos para diferentes
        gustos y necesidades. Explora nuestras categorías y encuentra
        los artículos ideales para complementar tu estilo y actividad física.
    </p>
</section>

<!-- Pie de página con los datos del alumno -->
<footer>
    <p>Alumno: Badiola Muñoz Sergio</p>
    <p>Grupo: 5-J</p>
    <p>Matrícula: 0800</p>
</footer>

<!-- Conexión con el carrito JavaScript -->
<script src="carrito.js"></script>

</body>
</html>
