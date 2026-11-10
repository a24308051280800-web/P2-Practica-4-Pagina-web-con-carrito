
<?php
/* *Inicia la sesión y conecta el archivo con la base de datos. */
session_start();
require_once "conexion.php";

/* *Variable que almacena los mensajes de error que se mostrarán al usuario. */
$mensaje = "";

/* *Comprueba si el formulario fue enviado mediante el método POST. */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /* *Obtiene el correo y la contraseña ingresados en el formulario. */
    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    /* *Valida que el correo tenga un formato correcto y que la contraseña no esté vacía. */
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $contrasena === "") {
        $mensaje = "Ingresa un correo y una contraseña válidos.";
    } else {
        /* *Prepara la consulta para buscar al usuario por su correo electrónico. */
        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, contrasena
             FROM usuarios WHERE correo = ?"
        );

        /* *Asocia el correo a la consulta y la ejecuta. */
        $consulta->bind_param("s", $correo);
        $consulta->execute();
        $consulta->store_result();

        /* *Comprueba si se encontró exactamente un usuario con ese correo. */
        if ($consulta->num_rows === 1) {
            /* *Obtiene los datos del usuario encontrados en la base de datos. */
            $consulta->bind_result(
                $id,
                $nombre,
                $correoBD,
                $hash
            );
            $consulta->fetch();

            /* *Verifica si la contraseña ingresada coincide con la contraseña cifrada almacenada. */
            if (password_verify($contrasena, $hash)) {
                /* *Genera un nuevo identificador de sesión para mejorar la seguridad. */
                session_regenerate_id(true);

                /* *Guarda los datos principales del usuario en la sesión. */
                $_SESSION["usuario_id"] = $id;
                $_SESSION["usuario_nombre"] = $nombre;
                $_SESSION["usuario_correo"] = $correoBD;

                /* *Cierra la consulta y la conexión antes de redirigir al usuario. */
                $consulta->close();
                $conexion->close();

                /* *Envía al usuario a la página de bienvenida y detiene el código. */
                header("Location: bienvenido.php");
                exit;
            }
        }

        /* *Muestra un mensaje general cuando el correo o la contraseña son incorrectos. */
        $mensaje = "Correo o contraseña incorrectos.";
        $consulta->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- *Configura los caracteres, la adaptación a dispositivos y el título de la página. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Nike Store</title>

    <!-- *Conecta la hoja de estilos que define el diseño de la página. -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!-- *Encabezado de la tienda con el logotipo y los enlaces de navegación. -->
<header class="encabezado">
    <a class="logo" href="index.php">NIKE<span>.</span></a>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="tenis.php">Tenis</a>
        <a href="ropa.php">Ropa</a>
        <a href="accesorios.php">Accesorios</a>

        <!-- Botón de la bolsa de compras -->
        <a href="carrito.php">
            Bolsa (<span id="contador">0</span>)
        </a>
    </nav>
</header>

<!-- *Contenedor principal que incluye el formulario para iniciar sesión. -->
<main class="formulario-contenedor">
    <h1>Iniciar sesión</h1>
    <p>Ingresa a tu cuenta de Nike Store.</p>

    <!-- *Muestra el mensaje de error si existe y protege su contenido al imprimirlo. -->
    <?php if ($mensaje !== ""): ?>
        <div class="mensaje error">
            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8"); ?>
        </div>
    <?php endif; ?>

    <!-- *Formulario que envía el correo y la contraseña al archivo login.php. -->
    <form action="login.php" method="POST">
        <!-- *Campo para ingresar el correo electrónico del usuario. -->
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo"
               autocomplete="email" required>

        <!-- *Campo para ingresar la contraseña de la cuenta. -->
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena"
               autocomplete="current-password" required>

        <!-- *Botón que envía los datos del formulario para iniciar sesión. -->
        <button type="submit">Iniciar sesión</button>
    </form>

    <!-- *Enlace que dirige al formulario para crear una cuenta nueva. -->
    <a class="enlace-formulario" href="registro.php">
        ¿No tienes cuenta? Regístrate aquí
    </a>

    <!-- *Enlace para regresar a la página principal de la tienda. -->
    <a class="enlace-formulario" href="index.php">
        Volver a la tienda
    </a>
</main>

<!-- Conexión con el carrito JavaScript -->
<script src="carrito.js"></script>

</body>
</html>
