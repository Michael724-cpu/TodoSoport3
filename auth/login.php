<?php include '../header/headerVistas.php'; ?>

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
                <img src="../icons/iniciar-sesion.png" alt="">
                <h1 class="display-1"></h1>
                <h2>¡Hola! Para comprar, ingresa a tu cuenta</h2>
                <p class="mb-4"></p>
                <div class="row justify-content-between">
                    <div class="col-8">
                        <a class="btn btn-primary btn-lg btn-block" href="registro.php">Crear cuenta</a>
                    </div>
                    <div class="col-4">
                        <a class="btn btn-outline-primary btn-lg btn-block" href="sesion.php">Iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer/footerVistas.php'; ?>
