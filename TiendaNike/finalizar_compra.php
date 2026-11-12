
<?php
/* Inicia la sesión para comprobar si el usuario está autenticado. */
session_start();

/* Comprueba que el usuario haya iniciado sesión antes de realizar una compra. */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php?return=compra");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar compra | Nike Store</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- Encabezado -->
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

<!-- Formulario de compra -->
<main class="checkout-contenedor">

    <h1>Finalizar compra</h1>
    <p>Ingresa tus datos para completar tu pedido.</p>

    <form id="formulario-compra"
          method="POST"
          action="guardar_pedido.php">

        <!-- Datos personales -->
        <section class="checkout-seccion">
            <h2>Datos personales</h2>

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo" required>

            <label>Teléfono:</label>
            <input type="tel" name="telefono"
                   maxlength="10" minlength="10"
                   pattern="[0-9]{10}" required>
        </section>

        <!-- Dirección de entrega -->
        <section class="checkout-seccion">
            <h2>Dirección de entrega</h2>

            <label>Calle y número:</label>
            <input type="text" name="direccion" required>

            <label>Colonia:</label>
            <input type="text" name="colonia" required>

            <label>Ciudad:</label>
            <input type="text" name="ciudad" required>

            <label>Estado:</label>
            <input type="text" name="estado" required>

            <label>Código postal:</label>
            <input type="text" name="codigo_postal"
                   maxlength="5" minlength="5"
                   pattern="[0-9]{5}" required>

            <label>Referencias de entrega:</label>
            <textarea name="referencias"
                      placeholder="Ejemplo: Casa de color blanco, portón negro..."></textarea>
        </section>

        <!-- Método de pago -->
        <section class="checkout-seccion">
            <h2>Método de pago</h2>

            <label for="metodo_pago">Selecciona tu método de pago:</label>

            <select name="metodo_pago" id="metodo_pago" required>
                <option value="">Seleccionar método</option>
                <option value="Tarjeta de demostración">
                    Tarjeta bancaria (demostración)
                </option>
                <option value="Pago contra entrega">
                    Pago contra entrega (demostración)
                </option>
            </select>

            <!-- Datos ficticios de tarjeta -->
            <div id="datos-tarjeta" style="display:none;">

                <h3>Datos de la tarjeta</h3>

                <label for="titular">Nombre del titular:</label>
                <input type="text" id="titular" name="titular">

                <label for="tipo_tarjeta">Tipo de tarjeta:</label>
                <select name="tipo_tarjeta" id="tipo_tarjeta" disabled>
                    <option value="">Selecciona una tarjeta</option>
                    <option value="Visa">Visa</option>
                    <option value="Mastercard">Mastercard</option>
                    <option value="Otra">Otra</option>
                </select>

                <label for="ultimos_digitos">
                    Últimos cuatro dígitos:
                </label>

                <input type="text" id="ultimos_digitos"
                       name="ultimos_digitos"
                       maxlength="4" minlength="4"
                       pattern="[0-9]{4}"
                       placeholder="1234">
            </div>
        </section>

        <!-- Resumen del pedido -->
        <section class="checkout-seccion">
            <h2>Resumen de tu pedido</h2>

            <div id="resumen-productos">
                Cargando productos...
            </div>

            <h3>
                Total: $<span id="total-compra">0.00</span> MXN
            </h3>
        </section>

        <button type="submit" class="boton-comprar">
            Confirmar pedido
        </button>

        <a href="carrito.php" class="seguir-comprando">
            Regresar a mi bolsa
        </a>

    </form>

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
