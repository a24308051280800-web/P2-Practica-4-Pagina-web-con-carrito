
<?php
/* Configura la respuesta como JSON e inicia la sesión. */
header("Content-Type: application/json; charset=utf-8");
session_start();

require_once "conexion.php";

/* Comprueba que el usuario haya iniciado sesión. */
if (!isset($_SESSION["usuario_id"])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Debes iniciar sesión para realizar una compra."
    ]);
    exit;
}

/* Comprueba que la solicitud sea POST. */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "mensaje" => "Solicitud no válida."
    ]);
    exit;
}

/* Obtiene y valida los datos enviados desde el formulario. */
$campos = [
    "nombre", "apellidos", "correo", "telefono",
    "direccion", "colonia", "ciudad", "estado",
    "codigo_postal", "metodo_pago"
];

$datos = [];

foreach ($campos as $campo) {
    $datos[$campo] = trim($_POST[$campo] ?? "");

    if ($datos[$campo] === "") {
        echo json_encode([
            "success" => false,
            "mensaje" => "Completa todos los campos obligatorios."
        ]);
        exit;
    }
}

$referencias = trim($_POST["referencias"] ?? "");
$tipo_tarjeta = null;
$ultimos_digitos = null;

if (!filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El correo electrónico no es válido."
    ]);
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $datos["telefono"])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El teléfono debe tener 10 dígitos."
    ]);
    exit;
}

if (!preg_match('/^[0-9]{5}$/', $datos["codigo_postal"])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El código postal debe tener 5 dígitos."
    ]);
    exit;
}

/* Obtiene los datos de pago de demostración. */
if (stripos($datos["metodo_pago"], "tarjeta") !== false) {
    $tipo_tarjeta = trim($_POST["tipo_tarjeta"] ?? "");
    $ultimos_digitos = trim($_POST["ultimos_digitos"] ?? "");

    if (
        $tipo_tarjeta === "" ||
        !preg_match('/^[0-9]{4}$/', $ultimos_digitos)
    ) {
        echo json_encode([
            "success" => false,
            "mensaje" => "Verifica los datos de la tarjeta."
        ]);
        exit;
    }
}

/* Recibe los productos del carrito. */
$productos = json_decode($_POST["productos"] ?? "", true);

if (!is_array($productos) || count($productos) === 0) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El carrito está vacío."
    ]);
    exit;
}

/* Relaciona cada categoría con su tabla correspondiente. */
$tablas = [
    "tenis" => "tenis",
    "ropa" => "ropa",
    "accesorios" => "accesorios"
];

$total = 0;
$productos_validados = [];

try {
    /* Consulta los productos y calcula el total con precios de la base de datos. */
    foreach ($productos as $producto) {
        $id = filter_var($producto["id"] ?? null, FILTER_VALIDATE_INT);
        $categoria = $producto["categoria"] ?? "";
        $cantidad = filter_var(
            $producto["cantidad"] ?? null,
            FILTER_VALIDATE_INT
        );
        $talla = trim($producto["talla"] ?? "");

        if (
            !$id ||
            !isset($tablas[$categoria]) ||
            !$cantidad ||
            $cantidad < 1 ||
            $cantidad > 99
        ) {
            throw new Exception("Hay un producto con datos incorrectos.");
        }

        if (
            ($categoria === "tenis" || $categoria === "ropa") &&
            $talla === ""
        ) {
            throw new Exception("Falta seleccionar la talla de un producto.");
        }

        if ($categoria === "accesorios") {
            $talla = null;
        }

        $tabla = $tablas[$categoria];

        $consulta = $conexion->prepare(
            "SELECT nombre, precio FROM $tabla WHERE id = ?"
        );

        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $producto_bd = $resultado->fetch_assoc();
        $consulta->close();

        if (!$producto_bd) {
            throw new Exception("Uno de los productos ya no está disponible.");
        }

        $precio = (float)$producto_bd["precio"];
        $subtotal = $precio * $cantidad;
        $total += $subtotal;

        $productos_validados[] = [
            "id" => $id,
            "categoria" => $categoria,
            "nombre" => $producto_bd["nombre"],
            "precio" => $precio,
            "cantidad" => $cantidad,
            "talla" => $talla,
            "subtotal" => $subtotal
        ];
    }

    /* Inicia la transacción para guardar el pedido completo. */
    $conexion->begin_transaction();

    $usuario_id = (int)$_SESSION["usuario_id"];

    /* Guarda los datos generales del pedido. */
    $consulta_pedido = $conexion->prepare(
        "INSERT INTO pedidos (
            usuario_id, nombre, apellidos, correo, telefono,
            direccion, colonia, ciudad, estado, codigo_postal,
            referencias, metodo_pago, tipo_tarjeta, ultimos_digitos, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $consulta_pedido->bind_param(
        "isssssssssssssd",
        $usuario_id,
        $datos["nombre"],
        $datos["apellidos"],
        $datos["correo"],
        $datos["telefono"],
        $datos["direccion"],
        $datos["colonia"],
        $datos["ciudad"],
        $datos["estado"],
        $datos["codigo_postal"],
        $referencias,
        $datos["metodo_pago"],
        $tipo_tarjeta,
        $ultimos_digitos,
        $total
    );

    $consulta_pedido->execute();
    $pedido_id = $conexion->insert_id;
    $consulta_pedido->close();

    /* Guarda cada producto que forma parte del pedido. */
    $consulta_detalle = $conexion->prepare(
        "INSERT INTO detalle_pedido (
            pedido_id, categoria, producto_id, nombre_producto,
            precio, cantidad, talla, subtotal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    foreach ($productos_validados as $producto) {
        $consulta_detalle->bind_param(
            "isisdisd",
            $pedido_id,
            $producto["categoria"],
            $producto["id"],
            $producto["nombre"],
            $producto["precio"],
            $producto["cantidad"],
            $producto["talla"],
            $producto["subtotal"]
        );

        $consulta_detalle->execute();
    }

    $consulta_detalle->close();

    /* Confirma que todos los datos se guardaron correctamente. */
    $conexion->commit();

    echo json_encode([
        "success" => true,
        "mensaje" => "Tu compra se registró correctamente.",
        "pedido_id" => $pedido_id
    ]);

} catch (Exception $error) {
    /* Si ocurre un error, cancela los cambios realizados. */
    $conexion->rollback();

    echo json_encode([
        "success" => false,
        "mensaje" => $error->getMessage()
    ]);
}
?>
