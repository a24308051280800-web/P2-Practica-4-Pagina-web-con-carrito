
<?php
/* *Inicia la sesión y conecta el archivo con la base de datos. */
session_start();
require_once "conexion.php";

/* *Variables que almacenan el mensaje que se mostrará y su tipo. */
$mensaje = "";
$tipo = "";

/* *Comprueba si el formulario fue enviado mediante el método POST. */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /* *Obtiene los datos ingresados por el usuario y elimina espacios al inicio y al final. */
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    /* *Valida que el nombre no esté vacío, el correo sea válido, el teléfono tenga 10 dígitos y la contraseña tenga al menos 6 caracteres. */
    if (
        $nombre === "" ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^[0-9]{10}$/', $telefono) ||
        strlen($contrasena) < 6
    ) {
        $mensaje = "Revisa los datos. La contraseña debe tener al menos 6 caracteres y el teléfono 10 dígitos.";
        $tipo = "error";
    /* *Comprueba que las dos contraseñas ingresadas sean iguales. */
    } elseif ($contrasena !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipo = "error";
    } else {
        /* *Busca en la base de datos si el correo ya está registrado. */
        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE correo = ?"
        );
        $consulta->bind_param("s", $correo);
        $consulta->execute();
        $consulta->store_result();

        /* *Si el correo ya existe, muestra un mensaje de error. */
        if ($consulta->num_rows > 0) {
            $mensaje = "Este correo ya está registrado.";
            $tipo = "error";
        } else {
            /* *Encripta la contraseña para no guardarla como texto normal en la base de datos. */
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            /* *Prepara la consulta para insertar los datos del nuevo usuario. */
            $insertar = $conexion->prepare(
                "INSERT INTO usuarios
                (nombre, correo, telefono, contrasena)
                VALUES (?, ?, ?, ?)"
            );

            /* *Asocia el nombre, correo, teléfono y contraseña encriptada con la consulta. */
            $insertar->bind_param(
                "ssss",
                $nombre,
                $correo,
                $telefono,
                $hash
            );

            /* *Ejecuta el registro y muestra un mensaje según el resultado. */
            if ($insertar->execute()) {
                $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
                $tipo = "exito";
            } else {
                $mensaje = "No se pudo completar el registro.";
                $tipo = "error";
            }

            /* *Cierra la consulta de inserción. */
            $insertar->close();
        }

        /* *Cierra la consulta utilizada para comprobar si el correo existe. */
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
    <title>Registro | Nike Store</title>

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

<!-- *Contenedor principal que presenta el formulario de registro. -->
<main class="formulario-contenedor">
    <h1>Crear una cuenta</h1>
    <p>Regístrate para formar parte de Nike Store.</p>

    <!-- *Muestra el mensaje de resultado del registro con el tipo de estilo correspondiente. -->
    <?php if ($mensaje !== ""): ?>
        <div class="mensaje <?php echo $tipo; ?>">
            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8"); ?>
        </div>
    <?php endif; ?>

    <!-- *Formulario que envía los datos del usuario al archivo registro.php. -->
    <form action="registro.php" method="POST">
        <!-- *Campo para ingresar el nombre completo, con un máximo de 100 caracteres. -->
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre"
               maxlength="100" required
               value="<?php echo htmlspecialchars($_POST["nombre"] ?? "", ENT_QUOTES, "UTF-8"); ?>">

        <!-- *Campo para ingresar el correo electrónico y conservar el valor si ocurre un error. -->
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo"
               maxlength="150" required
               value="<?php echo htmlspecialchars($_POST["correo"] ?? "", ENT_QUOTES, "UTF-8"); ?>">

        <!-- *Campo para ingresar un teléfono de exactamente 10 dígitos. -->
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono"
               pattern="[0-9]{10}" maxlength="10"
               minlength="10" required
               value="<?php echo htmlspecialchars($_POST["telefono"] ?? "", ENT_QUOTES, "UTF-8"); ?>">

        <!-- *Campo para crear una contraseña de al menos 6 caracteres. -->
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena"
               minlength="6" required>

        <!-- *Campo para confirmar la contraseña ingresada anteriormente. -->
        <label for="confirmar">Confirmar contraseña</label>
        <input type="password" id="confirmar" name="confirmar"
               minlength="6" required>

        <!-- *Botón que envía los datos para crear la cuenta. -->
        <button type="submit">Crear cuenta</button>
    </form>

    <!-- *Enlace para ir al formulario de inicio de sesión si el usuario ya tiene una cuenta. -->
    <a class="enlace-formulario" href="login.php">
        ¿Ya tienes cuenta? Inicia sesión
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
