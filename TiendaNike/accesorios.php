
<?php
// Conexión con la base de datos
require_once 'conexion.php';

// Consulta de todos los accesorios
$consulta = "SELECT * FROM accesorios";
$resultado = $conexion->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración general de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesorios Nike | Nike Store</title>

    <!-- Conexión con los estilos CSS -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- Encabezado y menú de navegación -->
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

<!-- Presentación de la categoría -->
<section class="seccion">
    <h2>Accesorios deportivos</h2>

    <p>
        Complementa tu equipo con nuestra colección de accesorios Nike.
        Encuentra artículos útiles para tus entrenamientos,
        actividades deportivas y uso diario.
    </p>

    <!-- Catálogo de productos obtenidos desde MySQL -->
    <div class="productos">

        <?php if ($resultado && $resultado->num_rows > 0): ?>

            <?php while ($producto = $resultado->fetch_assoc()): ?>

                <article class="producto">

                    <img
                        src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                    >

                    <h3>
                        <?php echo htmlspecialchars($producto['nombre']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($producto['descripcion']); ?>
                    </p>

                    <strong>
                        $<?php echo number_format($producto['precio'], 2); ?> MXN
                    </strong>

                    <!-- Botón para agregar el accesorio con su identificación -->
                    <button onclick='agregar(
                        <?php echo (int)$producto["id"]; ?>,
                        <?php echo json_encode($producto["nombre"], JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                        <?php echo json_encode((float)$producto["precio"]); ?>,
                        null,
                        "accesorios"
                    )'>
                        Agregar a la bolsa
                    </button>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No hay productos disponibles en esta categoría.</p>

        <?php endif; ?>

    </div>
</section>

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

<?php
// Cerrar la conexión con la base de datos
$conexion->close();
?>
