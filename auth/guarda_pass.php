<?php
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$user_id = isset($_POST['user_id']) ? $mysqli->real_escape_string($_POST['user_id']) : null;
$token = isset($_POST['token']) ? $mysqli->real_escape_string($_POST['token']) : null;
$password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
$con_password = isset($_POST['con_password']) ? $mysqli->real_escape_string($_POST['con_password']) : null;

if (!$user_id || !$token || !$password || !$con_password) {
    header('Location: sesion.php');
    exit;
}

if (validaPassword($password, $con_password)) {
    $pass_hash = hashPassword($password);
    if (cambiaPassword($pass_hash, $user_id, $token)) {
        $successMessage = 'Contraseña Modificada';
    } else {
        $errorMessage = 'Error al Cambiar la Contraseña';
    }
} else {
    $errorMessage = 'Las Contraseñas no Coinciden';
}

include "../header/headerVistas.php";
?>

<style>
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }
</style>

<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php if (isset($successMessage)) { ?>
                    <img src="../icons/cambiada.png" alt="">
                    <h1 class="display-1"></h1>
                    <h1 class="mb-4"><?php echo $successMessage; ?></h1>
                <?php } elseif (isset($errorMessage)) { ?>
                    <img src="../icons/boton-x.png" alt="">
                    <h1 class="display-1"></h1>
                    <h1 class="mb-4"><?php echo $errorMessage; ?></h1>
                <?php } ?>
                <a class="btn btn-success rounded-pill py-3 px-5" href="sesion.php">Iniciar Sesión</a>
            </div>
        </div>
    </div>
</div>

<?php
include "../footer/footerVistas.php";
?>
