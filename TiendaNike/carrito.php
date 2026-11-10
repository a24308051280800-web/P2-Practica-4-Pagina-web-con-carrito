
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración general de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi bolsa | Nike Store</title>

    <!-- Conexión con los estilos CSS -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- Encabezado y navegación -->
<header class="encabezado">
    <a class="logo" href="index.php">NIKE<span>.</span></a>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="tenis.php">Tenis</a>
        <a href="ropa.php">Ropa</a>
        <a href="accesorios.php">Accesorios</a>

        <?php include 'usuario_nav.php'; ?>

        <a href="carrito.php">
            Bolsa (<span id="contador">0</span>)
        </a>
    </nav>
</header>

<!-- Contenido principal de la bolsa -->
<main class="bolsa-contenedor">

    <div class="bolsa-titulo">
        <h1>Tu bolsa</h1>
        <p>Revisa los productos que has seleccionado.</p>
    </div>

    <div class="bolsa-contenido">

        <!-- Lista de productos agregados -->
        <section class="bolsa-productos">
            <h2>Productos seleccionados</h2>

            <div id="lista-carrito">
                Tu bolsa está vacía.
            </div>

            <a href="index.php" class="seguir-comprando">
                ← Seguir comprando
            </a>
        </section>

        <!-- Resumen de la compra -->
        <aside class="resumen-carrito">

            <h2>Resumen de compra</h2>

            <div class="resumen-linea">
                <span>Subtotal</span>
                <strong>$<span id="total">0.00</span> MXN</strong>
            </div>

            <div class="resumen-linea">
                <span>Envío</span>
                <strong>Gratis</strong>
            </div>

            <div class="resumen-total">
                <span>Total</span>
                <strong>$<span id="total-resumen">0.00</span> MXN</strong>
            </div>

            <!-- Botón para abrir el formulario de compra -->
            <button
                type="button"
                class="boton-comprar"
                onclick="window.location.href='finalizar_compra.php'">
                Continuar con la compra
            </button>

            <!-- Botón para vaciar la bolsa -->
            <button
                type="button"
                class="boton-vaciar"
                onclick="vaciar()">
                Vaciar bolsa
            </button>

            <p class="nota-compra">
                Completa tus datos de entrega y selecciona
                tu método de pago para realizar el pedido.
            </p>

        </aside>

    </div>

</main>

<!-- Pie de página -->
<footer>
    <p>Alumno: Badiola Muñoz Sergio</p>
    <p>Grupo: 5-J</p>
    <p>Matrícula: 0800</p>
</footer>

<!-- Conexión con el carrito JavaScript -->
<script src="carrito.js"></script>

</body>
</html>
