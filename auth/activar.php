<?php
require '../funcs/conexion.php';
require '../funcs/funcs.php';
include '../header/headerVistas.php';

$mensaje = null;

if (isset($_GET["id"]) && isset($_GET['val'])) {
    $idUsuario = $_GET['id'];
    $token = $_GET['val'];

    $mensaje = validaIdToken($idUsuario, $token);

}
?>

<!-- 404 Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <img src="../icons/cuenta-verificada.png" alt="">
                <h1 class="display-1"></h1>
                <h1 class="mb-4"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="mb-4"></p>
                <a class="btn btn-success rounded-pill py-3 px-5" href="sesion.php">Iniciar sesión</a>
            </div>
        </div>
    </div>
</div>
<!-- 404 End -->

<!-- Agrega margen inferior para separación -->
<style>
    .container-xxl {
        margin-bottom: 20px; /* Ajusta el valor según necesites */
    }
</style>

<?php
include '../footer/footerVistas.php';
?>
