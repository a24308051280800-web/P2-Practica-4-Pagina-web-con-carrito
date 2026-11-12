
<?php
/* Inicia la sesión si todavía no existe una sesión activa. */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Contenedor de las opciones de cuenta del usuario. -->
<div class="usuario-nav">

    <?php if (isset($_SESSION["usuario_id"])): ?>

        <!-- Muestra los datos del usuario cuando inició sesión. -->
        <span>
            Usuario:
            <strong>
                <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
            </strong>
        </span>

        <a href="cerrar_sesion.php" class="cerrar-sesion">
            Cerrar sesión
        </a>

    <?php else: ?>

        <!-- Botón que abre el menú de cuenta. -->
        <div class="cuenta-menu">

            <button type="button" class="boton-cuenta" onclick="mostrarCuenta()">
                Cuenta
            </button>

            <!-- Menú desplegable con los formularios. -->
            <div class="menu-cuenta" id="menu-cuenta">

                <!-- Formulario de registro. -->
                <div id="formulario-registro-cuenta">

                    <h3>Crear una cuenta</h3>
                    <p>Regístrate para formar parte de Nike Store.</p>

                    <form action="registro.php" method="POST">

                        <label for="cuenta-nombre">Nombre completo</label>
                        <input type="text" id="cuenta-nombre" name="nombre"
                               maxlength="100" required>

                        <label for="cuenta-correo-registro">Correo electrónico</label>
                        <input type="email" id="cuenta-correo-registro" name="correo"
                               maxlength="150" required>

                        <label for="cuenta-telefono">Teléfono</label>
                        <input type="tel" id="cuenta-telefono" name="telefono"
                               pattern="[0-9]{10}" maxlength="10"
                               minlength="10" required>

                        <label for="cuenta-contrasena-registro">Contraseña</label>
                        <input type="password" id="cuenta-contrasena-registro"
                               name="contrasena" minlength="6" required>

                        <label for="cuenta-confirmar">Confirmar contraseña</label>
                        <input type="password" id="cuenta-confirmar"
                               name="confirmar" minlength="6" required>

                        <button type="submit">Crear cuenta</button>
                    </form>

                    <p class="cambiar-cuenta">
                        ¿Ya tienes una cuenta?
                        <a href="#" onclick="cambiarFormulario('login'); return false;">
                            Inicia sesión
                        </a>
                    </p>
                </div>

                <!-- Formulario de inicio de sesión. -->
                <div id="formulario-login-cuenta" style="display: none;">

                    <h3>Iniciar sesión</h3>
                    <p>Ingresa a tu cuenta de Nike Store.</p>

                    <form action="login.php" method="POST">

                        <label for="cuenta-correo-login">Correo electrónico</label>
                        <input type="email" id="cuenta-correo-login"
                               name="correo" autocomplete="email" required>

                        <label for="cuenta-contrasena-login">Contraseña</label>
                        <input type="password" id="cuenta-contrasena-login"
                               name="contrasena"
                               autocomplete="current-password" required>

                        <button type="submit">Iniciar sesión</button>
                    </form>

                    <p class="cambiar-cuenta">
                        ¿No tienes una cuenta?
                        <a href="#" onclick="cambiarFormulario('registro'); return false;">
                            Regístrate
                        </a>
                    </p>
                </div>

            </div>
        </div>

    <?php endif; ?>

</div>

<!-- Controla la apertura del menú y el cambio entre formularios. -->
<script>
function mostrarCuenta() {
    document.getElementById("menu-cuenta").classList.toggle("mostrar-cuenta");
}

function cambiarFormulario(tipo) {
    const registro = document.getElementById("formulario-registro-cuenta");
    const login = document.getElementById("formulario-login-cuenta");

    if (tipo === "login") {
        registro.style.display = "none";
        login.style.display = "block";
    } else {
        login.style.display = "none";
        registro.style.display = "block";
    }
}

/* Cierra el menú cuando se hace clic fuera de él. */
document.addEventListener("click", function(evento) {
    const menu = document.querySelector(".cuenta-menu");

    if (menu && !menu.contains(evento.target)) {
        document.getElementById("menu-cuenta").classList.remove("mostrar-cuenta");
    }
});
</script>
