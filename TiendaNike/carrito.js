
/* Recupera los productos guardados en el almacenamiento local. */
let carrito = [];

try {
    carrito = JSON.parse(localStorage.getItem("carritoNike")) || [];
} catch (error) {
    carrito = [];
}

/* Guarda el contenido del carrito. */
function guardarCarrito() {
    localStorage.setItem("carritoNike", JSON.stringify(carrito));
}

/* Agrega productos al carrito con su ID, categoría, precio y talla. */
function agregar(id, nombre, precio, talla = null, categoria = null) {

    if (talla !== null && talla === "") {
        alert("Selecciona una talla antes de agregar este producto.");
        return;
    }

    let producto = carrito.find(p =>
        Number(p.id) === Number(id) &&
        p.categoria === categoria &&
        (p.talla || null) === talla
    );

    if (producto) {
        producto.cantidad++;
    } else {
        carrito.push({
            id: Number(id),
            nombre: nombre,
            precio: Number(precio),
            talla: talla,
            categoria: categoria,
            cantidad: 1
        });
    }

    guardarCarrito();
    actualizarCarrito();

    alert(
        nombre +
        (talla ? " talla " + talla : "") +
        " se agregó a tu bolsa."
    );
}

/* Calcula el total de los productos. */
function calcularTotal() {
    return carrito.reduce((suma, p) => {
        return suma + Number(p.precio) * Number(p.cantidad);
    }, 0);
}

/* Da formato a los precios en pesos mexicanos. */
function formatoPrecio(precio) {
    return Number(precio).toLocaleString("es-MX", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/* Actualiza el contador, los precios y la lista de productos. */
function actualizarCarrito() {
    let lista = document.getElementById("lista-carrito");
    let contador = document.getElementById("contador");
    let totalElemento = document.getElementById("total");
    let totalResumen = document.getElementById("total-resumen");

    let cantidadTotal = carrito.reduce(
        (suma, p) => suma + Number(p.cantidad), 0
    );

    let total = calcularTotal();

    if (contador) {
        contador.textContent = cantidadTotal;
    }

    if (totalElemento) {
        totalElemento.textContent = formatoPrecio(total);
    }

    if (totalResumen) {
        totalResumen.textContent = formatoPrecio(total);
    }

    /* Muestra los productos dentro de la bolsa. */
    if (lista) {
        lista.innerHTML = "";

        if (carrito.length === 0) {
            lista.textContent = "Tu bolsa está vacía.";
            return;
        }

        carrito.forEach((p, indice) => {
            let fila = document.createElement("div");
            fila.className = "item-carrito";

            let informacion = document.createElement("span");

            informacion.textContent =
                p.nombre +
                (p.talla ? " | Talla: " + p.talla : "") +
                " x " + p.cantidad +
                " - $" + formatoPrecio(p.precio * p.cantidad) + " MXN";

            let eliminar = document.createElement("button");
            eliminar.textContent = "Eliminar";

            eliminar.onclick = function() {
                quitar(indice);
            };

            fila.appendChild(informacion);
            fila.appendChild(eliminar);
            lista.appendChild(fila);
        });
    }
}

/* Elimina un producto del carrito. */
function quitar(indice) {
    carrito.splice(indice, 1);
    guardarCarrito();
    actualizarCarrito();
    mostrarResumenCompra();
}

/* Vacía todos los productos. */
function vaciar() {
    if (carrito.length === 0) {
        alert("Tu bolsa ya está vacía.");
        return;
    }

    carrito = [];
    guardarCarrito();
    actualizarCarrito();
    mostrarResumenCompra();

    alert("Tu bolsa se ha vaciado correctamente.");
}

/* Redirige al formulario para finalizar la compra. */
function comprar() {
    if (carrito.length === 0) {
        alert("Tu bolsa está vacía.");
        return;
    }

    window.location.href = "finalizar_compra.php";
}

/* Muestra los productos y el total en el formulario de compra. */
function mostrarResumenCompra() {
    let lista = document.getElementById("resumen-productos");
    let totalElemento = document.getElementById("total-compra");

    if (!lista || !totalElemento) return;

    lista.innerHTML = "";

    if (carrito.length === 0) {
        lista.textContent = "No hay productos en tu pedido.";
        totalElemento.textContent = "0.00";
        return;
    }

    carrito.forEach(producto => {
        let subtotal = Number(producto.precio) * Number(producto.cantidad);

        let fila = document.createElement("div");
        fila.className = "producto-resumen";

        let nombre = document.createElement("span");
        nombre.textContent =
            producto.nombre +
            (producto.talla ? " | Talla: " + producto.talla : "") +
            " x " + producto.cantidad;

        let precio = document.createElement("strong");
        precio.textContent = "$" + formatoPrecio(subtotal) + " MXN";

        fila.appendChild(nombre);
        fila.appendChild(precio);
        lista.appendChild(fila);
    });

    totalElemento.textContent = formatoPrecio(calcularTotal());
}

/* Muestra u oculta los datos ficticios de tarjeta. */
function cambiarMetodoPago() {
    let metodo = document.getElementById("metodo_pago");
    let tarjeta = document.getElementById("datos-tarjeta");

    if (!metodo || !tarjeta) return;

    let mostrar = metodo.value === "Tarjeta de demostración";

    tarjeta.style.display = mostrar ? "block" : "none";

    let titular = tarjeta.querySelector('[name="titular"]');
    let tipo = tarjeta.querySelector('[name="tipo_tarjeta"]');
    let digitos = tarjeta.querySelector('[name="ultimos_digitos"]');

    if (titular) titular.required = mostrar;

    if (tipo) {
        tipo.required = mostrar;
        tipo.disabled = !mostrar;
    }

    if (digitos) {
        digitos.required = mostrar;
        digitos.disabled = !mostrar;
    }
}

/* Configura el formulario de compra. */
function inicializarCompra() {
    let formulario = document.getElementById("formulario-compra");

    if (!formulario) return;

    /* Comprueba que existan productos antes de comprar. */
    if (carrito.length === 0) {
        alert("Tu bolsa está vacía. Agrega productos antes de comprar.");
        window.location.href = "carrito.php";
        return;
    }

    /* Muestra el resumen de los productos. */
    mostrarResumenCompra();

    /* Configura el selector del método de pago. */
    let metodo = document.getElementById("metodo_pago");

    if (metodo) {
        metodo.addEventListener("change", cambiarMetodoPago);
        cambiarMetodoPago();
    }

    /* Envía el pedido al servidor para guardarlo en MySQL. */
    formulario.addEventListener("submit", async function(evento) {
        evento.preventDefault();

        if (carrito.length === 0) {
            alert("Tu bolsa está vacía.");
            window.location.href = "carrito.php";
            return;
        }

        if (!formulario.reportValidity()) return;

        /* Comprueba que los productos tengan su identificación y categoría. */
        let productosValidos = carrito.every(producto =>
            Number.isInteger(Number(producto.id)) &&
            Number(producto.id) > 0 &&
            ["tenis", "ropa", "accesorios"].includes(producto.categoria) &&
            Number(producto.precio) > 0 &&
            Number.isInteger(Number(producto.cantidad)) &&
            Number(producto.cantidad) > 0
        );

        if (!productosValidos) {
            alert(
                "Algunos productos de tu bolsa no tienen su identificación correcta. " +
                "Vacía la bolsa y agrega nuevamente los productos."
            );
            return;
        }

        let boton = formulario.querySelector('[type="submit"]');

        if (boton) {
            boton.disabled = true;
            boton.textContent = "Guardando pedido...";
        }

        try {
            let datos = new FormData(formulario);

            datos.append("productos", JSON.stringify(carrito));

            let respuesta = await fetch("guardar_pedido.php", {
                method: "POST",
                body: datos
            });

            let resultado = await respuesta.json();

            /* Comprueba que PHP haya confirmado el registro del pedido. */
            if (!respuesta.ok || !resultado.success) {
                throw new Error(resultado.mensaje || "No se pudo guardar el pedido.");
            }

            /* Vacía el carrito únicamente cuando el servidor confirma el pedido. */
            carrito = [];
            guardarCarrito();

            actualizarCarrito();

            alert(
                "¡Pedido guardado correctamente!\n\n" +
                "Número de pedido: " + resultado.pedido_id +
                "\n\nTu compra quedó registrada en la base de datos."
            );

            window.location.href = "index.php";

        } catch (error) {
            alert("No se pudo guardar tu pedido:\n" + error.message);

            if (boton) {
                boton.disabled = false;
                boton.textContent = "Confirmar pedido";
            }
        }
    });
}

/* Inicializa las funciones cuando termina de cargar la página. */
document.addEventListener("DOMContentLoaded", function() {
    actualizarCarrito();
    mostrarResumenCompra();
    inicializarCompra();
});

/* Valida el formulario de registro de demostración. */
function registrarUsuario(evento) {
    evento.preventDefault();

    let nombre = document.getElementById("nombre").value.trim();
    let correo = document.getElementById("correo").value.trim();
    let telefono = document.getElementById("telefono").value;
    let contrasena = document.getElementById("contrasena").value;
    let confirmar = document.getElementById("confirmar").value;

    if (contrasena !== confirmar) {
        alert("Las contraseñas no coinciden.");
        return false;
    }

    alert(
        "Formulario validado correctamente, " + nombre +
        ".\nCorreo: " + correo +
        "\nTeléfono: " + telefono +
        "\n\nEsta demostración no guarda los datos ni crea una cuenta real."
    );

    return false;
}

/* Demostración del formulario de inicio de sesión. */
function iniciarSesion(evento) {
    evento.preventDefault();

    let correo = document.getElementById("correo").value;

    alert(
        "Formulario validado.\nCorreo ingresado: " + correo +
        "\n\nEl inicio de sesión real requiere un sistema de usuarios."
    );

    return false;
}
