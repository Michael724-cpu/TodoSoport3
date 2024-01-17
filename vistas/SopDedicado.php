<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
include "../header/headerVistas.php";

$conexion = new mysqli('localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support');
$conexion->set_charset("utf8");

$sql = "SELECT paquetes.Nombre AS minutos, canales.Canal as canal, 
        paquetecanal.PrecioA as precioA, paquetecanal.PrecioAA as precioAA, paquetecanal.PrecioAAA as precioAAA 
        FROM `paquetecanal` AS paquetecanal 
        INNER JOIN catpaquete AS paquetes ON paquetecanal.IdPaquete = paquetes.IdPaquete 
        INNER JOIN catcanal AS canales ON paquetecanal.IdCanal = canales.IdCanal 
        WHERE canal = 'llamada'";

$paquetes = [
    'A' => [200, 300, 400, 500, 600, 800, 900, 1000, 1200, 1400, 1600, 1800, 2000],
    'AA' => [200, 300, 400, 500, 600, 800, 900, 1000, 1200, 1400, 1600, 1800, 2000],
    'AAA' => [200, 300, 400, 500, 600, 800, 900, 1000, 1200, 1400, 1600, 1800, 2000],
];
?>

<link rel="stylesheet" href="../css/SopDedica2.css">
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <img class="img-fluid" src="../icons/soporte-tecnico.png" alt="">
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="h-100">
                    <h1 class="display-6">SOPORTE DEDICADO</h1>
                    <p>
                        "Todo Soport3: Tu aliado en soluciones técnicas para empresas. Ofrecemos servicio dedicado de soporte técnico para portales digitales, aplicaciones, CRMs, ERPs y más. Nuestra bolsa de horas de Soporte te brinda atención 24/7 con personal especializado. Mantén el soporte en línea con tus clientes y garantiza una asistencia rápida y eficiente. ¡Descubre cómo optimizar tu atención al cliente con Todo Soporte!"
                    </p>
                    <?php $features = [
                        "Operadores dedicados durante el horario de servicio.",
                        "Mensaje de IVR personalizado para el cliente.",
                        "Reportería en línea de peticiones o gestiones.",
                        "Grabación de las llamadas al 100%.",
                        "1 E1 de voz para recepción de llamadas.",
                        "1 Contacto comercial para temas de reportes y solución de casos específicos.",
                        "Atención a los canales de llamada y videollamada"
                    ]; ?>
                    <?php foreach ($features as $feature) : ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa fa-check bg-light text-primary btn-sm-square rounded-circle me-3 fw-bold"></i>
                            <span><?= $feature ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="explore-section section-padding" id="section_2">
    <div class="col-12 text-center">
        <h1 class="display-6">PAQUETES EMPRESARIALES</h1>
    </div>
</section>

<div class="container-fluid">
    <div class="row">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php $tabs = ['A', 'AA', 'AAA']; ?>
            <?php foreach ($tabs as $index => $tab) : ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link<?= $index === 0 ? ' active' : '' ?>" id="<?= strtolower($tab) ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= strtolower($tab) ?>-tab-pane" type="button" role="tab" aria-controls="<?= strtolower($tab) ?>-tab-pane" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">Empresas <?= $tab ?></button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="myTabContent">
                <?php foreach ($tabs as $tab) : ?>
                    <div class="tab-pane fade<?= $tab === 'A' ? ' show active' : '' ?>" id="<?= strtolower($tab) ?>-tab-pane" role="tabpanel" aria-labelledby="<?= strtolower($tab) ?>-tab" tabindex="0">
                        <div class="row">
                        <?php foreach ($paquetes[$tab] as $minuto) : ?>
    <?php
    $consulta = $conexion->query("SELECT paquetes.IdPaquete AS idProducto, paquetes.Nombre AS minutos, canales.Canal as canal, paquetecanal.Precio" . $tab . " as precio FROM `paquetecanal` AS paquetecanal INNER JOIN catpaquete AS paquetes ON paquetecanal.IdPaquete = paquetes.IdPaquete INNER JOIN catcanal AS canales ON paquetecanal.IdCanal = canales.IdCanal WHERE canal = 'llamada' AND Nombre = '$minuto minutos'");
    $paqueteInfo = $consulta->fetch_assoc();
    ?>
    <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-block bg-white shadow-lg">
            <div class="d-flex">
                <div>
                    <h5 class="mb-3"><?= $paqueteInfo['minutos'] ?></h5>
                    <p><b><?= $paqueteInfo['canal'] ?>: $<?= $paqueteInfo['precio'] ?></b></p>
                </div>
            </div>
            <img src="../icons/<?= $tab ?>.png"><br>
            <form method="POST" action="../vistas/generar_pagoSD.php">
                <input type="hidden" name="idProducto" value="<?= $paqueteInfo['idProducto'] ?>">
                <input type="hidden" name="minutos" value="<?= $paqueteInfo['minutos'] ?>">
                <input type="hidden" name="canal" value="<?= $paqueteInfo['canal'] ?>">
                <input type="hidden" name="precio" value="<?= $paqueteInfo['precio'] ?>">
        <?php if (isset($_SESSION['usuario'])) : ?>
    <button type="submit" class="btn btn-success">PAGAR</button>
<?php else : ?>
    <a href="../auth/login.php" class="btn btn-success">PAGAR</a>
<?php endif; ?>

            </form>
        </div>
    </div>
<?php endforeach; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<?php include '../footer/footerVistas.php' ?>