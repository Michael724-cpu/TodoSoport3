<?php
session_start();

include "../header/headerVistas.php";

// Conexión a la base de datos utilizando PDO
try {
    $conexion = new PDO('mysql:host=localhost;dbname=todosopo_datavoic_support', 'todosopo', ':zhO2]TFF4g3l0');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    // Manejo de errores de conexión
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Función para obtener los datos de un producto por su IdProducto
function obtenerProducto($conexion, $idProducto)
{
    $stmt = $conexion->prepare("SELECT IdCategoria, Nombre, Descripcion, Precio FROM catproducto WHERE IdCategoria = '11' AND IdProducto = :idProducto");
    $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Datos de la categoría
try {
    $categoriaQuery = $conexion->prepare("SELECT IdCategoria, Nombre, Descripcion FROM catcategoria WHERE IdCategoria = '11'");
    $categoriaQuery->execute();
    $categoria = $categoriaQuery->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores de consulta
    die("Error en la consulta de categoría: " . $e->getMessage());
}

if (isset($_SESSION['usuario'])) {
    $nombre = $_SESSION['nombre_usuario'];
    $correo = $_SESSION['correo_usuario'];
    $telefono = $_SESSION['telefono_usuario'];
} else {
    $nombre = "";
    $correo = "";
    $telefono = "";
}


?>

<link rel="stylesheet" href="../css/sopTecnicoER/style.css">

<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <img class="img-fluid" src="../icons/crm.png" alt="">
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="h-100">
                    <h1 class="display-6"><?= htmlspecialchars($categoria['Nombre']) ?></h1>
                    <p><?= nl2br(htmlspecialchars($categoria['Descripcion'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container px-lg-5">
        <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
            <h2 class="mt-2">Servicios</h2>
        </div>
        <div class="row g-4">
            <?php
            // Ids de los productos que quieres mostrar
            $productosIds = [214,215,216,217,218,219];

            foreach ($productosIds as $idProducto) {
                $producto = obtenerProducto($conexion, $idProducto);
                if ($producto) {
                    $nombreServicio = htmlspecialchars($producto['Nombre']);
                    $precioServicio = htmlspecialchars($producto['Precio']);
            ?>  
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.1s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded">
                            <div class="service-icon flex-shrink-0">
                                <img src="../icons/crm2.png" alt="">
                            </div>
                         <h5 class="mb-3"><?= $nombreServicio ?></h5>
                            <p><?= nl2br(htmlspecialchars($producto['Descripcion'])) ?></p>
                            <p class="text-justify"><b>Costo del Servicio: $<?= $precioServicio ?></b></p>
                            <a class="btn px-3 mt-auto mx-auto" onclick="showPaymentForm(<?= $idProducto ?>)">PAGAR</a>
                            <!-- Formulario para ingresar nombre, teléfono y correo -->
                            <div id="paymentForm<?= $idProducto ?>" style="display: none">
                                    <form method="post" action="generar_pago.php">
                                    <input type="hidden" name="nombreServicio" value="<?= $nombreServicio ?>">
                                    <input type="hidden" name="precioServicio" value="<?= $precioServicio ?>">
                                    <div class="mb-3">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= $nombre ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" required value="<?= $telefono ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="correo" name="correo" required value="<?= $correo ?>">
                                    </div>
                                    <button class="btn px-3 mt-auto mx-auto btn-light" type="submit">Continuar</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Service End -->

<script>
    // Función para mostrar el formulario de datos de una tarjeta específica
    function showPaymentForm(idProducto) {
        var formId = "paymentForm" + idProducto;
        var formElement = document.getElementById(formId);
        
        // Verifica si el formulario ya está visible y lo oculta, o viceversa
        if (formElement.style.display === "block") {
            formElement.style.display = "none";
        } else {
            formElement.style.display = "block";
        }
    }
</script>




<?php
include "../footer/footerVistas.php"
?>
