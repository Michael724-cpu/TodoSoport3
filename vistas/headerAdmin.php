<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-type: text/html; charset=utf-8');
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$id = $_SESSION['id_usuario'];

$sql = "SELECT id, nombre, correo, id_tipo, telefono, last_session, usuario FROM usuarios WHERE id = '$id'";
$result = $mysqli->query($sql);

$row = $result->fetch_assoc();

// Verificar si la variable de sesión 'id_usuario' no está definida
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al formulario de inicio de sesión
    header("Location: ../auth/sesion.php");
    exit; // Asegurarse de que el código se detenga después de la redirección
}
?>

<!DOCTYPE html>
<html lang="es"> 

<head>

    <meta charset="utf-8">
    <title>TodoSoport3 | Soporte Tecnico en Linea</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="soporte técnico en sistemas, soporte de redes, asistencia técnica en programación, solución de problemas informáticos">
    <meta name="description" content="Bienvenido a nuestro servicio de soporte técnico especializado en sistemas, redes, equipos de cómputo y programación. Resolvemos tus problemas técnicos de forma experta y eficaz.">

    <!-- Favicon -->
    <link href="../img/logoTS3.png" rel="icon">

    <!-- owl carousel style -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/proman.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/2.css">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="51">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light bg-light sticky-top px-4 px-lg-5">
    <div class="container">
        <a href="../index.php" class="navbar-brand">
            <img src="../img/logoTS3.png" width="120" height="90" alt="Logo de tu empresa">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Soporte Empresarial</a>
                    <div class="dropdown-menu">
                        <a href="redesSeguridad.php" class="dropdown-item">Redes y Seguridad Empresarial</a>
                        <a href="hw.php" class="dropdown-item">Hardware y Servicios de Empresas</a>
                        <a href="SopBD.php" class="dropdown-item">Soporte a Base de Datos Empresariales</a>
                        <a href="SopApp.php" class="dropdown-item">Soporte de Software para Empresas</a>
                        <a href="SopTelefonia.php" class="dropdown-item">Soporte de Telefonía Empresarial</a>
                        <a href="SopMac.php" class="dropdown-item">Soporte a Equipos MAC Empresariales</a>
                        <a href="SopMovilTablet.php" class="dropdown-item">Soporte a Móviles y Tabletas Empresariales</a>
                        <a href="SopERP.php" class="dropdown-item">Soporte de ERP para Empresas</a>
                        <a href="SopCRMs.php" class="dropdown-item">Soporte de CRMs Empresariales</a>
                        <a href="SopFacturacion.php" class="dropdown-item">Soporte de Sistemas de Facturación Empresariales</a>
                        <a href="SopLinux.php" class="dropdown-item">Soporte a Sistemas Operativos LINUX Empresariales</a>
                        <a href="SopWindows.php" class="dropdown-item">Soporte a Servidores Windows Empresariales</a>
                        <a href="SopCiber.php" class="dropdown-item">Servicios de Ciberseguridad Empresarial</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Soporte Residencial</a>
                    <div class="dropdown-menu">
                        <a href="hwResidencial.php" class="dropdown-item">Soporte de Hardware Residencial</a>
                        <a href="SopResMovilTab.php" class="dropdown-item">Soporte de Móviles y Tabletas Residenciales</a>
                        <a href="SopResApp.php" class="dropdown-item">Soporte de Software para Residencias</a>
                        <a href="SopResAppleMac.php" class="dropdown-item">Soporte de Equipos APPLE MAC e iPads</a>
                        <a href="SopHwMacIpad.php" class="dropdown-item">Soporte de Hardware MAC e iPads</a>
                        <a href="SopResIphone.php" class="dropdown-item">Soporte de Equipos APPLE iPhone Residenciales</a>
                        <a href="SopResAndroid.php" class="dropdown-item">Soporte de Equipos Móviles Android Residenciales</a>
                        <a href="SopSAT.php" class="dropdown-item">Soporte de Facturación Electrónica y Declaraciones SAT</a>
                        <!-- Agrega más elementos del menú aquí -->
                    </div>
                </li>
                <li class="nav-item">
                    <a href="SopDedicado.php" class="nav-link">Paquetes</a>
                </li>
                <li class="nav-item">
                    <a href="../Nosotros.php" class="nav-link">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a href="../Contact.php" class="nav-link">Contáctanos</a>
                </li>
                <?php if($row['id_tipo']== 3) { ?>
                    <a href="../vistas/sopTecnico.php" class="nav-link">Soporte Tecnico</a>
                <?php }?>
                     <?php if($row['id_tipo']== 1) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Exportar BD</a>
                    <div class="dropdown-menu border-light m-0">
                        <a href="exportarEmpresarial.php" class="dropdown-item">Soporte Empresarial</a>
                        <a href="exportarResidencial.php" class="dropdown-item">Soporte Residencial</a>
                        <a href="exportarDedicado.php" class="dropdown-item">Soporte Dedicado</a>
                    </div>
                </div>
                <?php }?>
               <?php if($row['id_tipo']== 2 || $row['id_tipo']==1 || $row['id_tipo']==3 || $row['id_tipo']==4) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Mi cuenta</a>
                    <div class="dropdown-menu border-light m-0">
                        <a href="../auth/logout.php" class="dropdown-item">Cerrar Sesion</a>
                    </div>
                </div>
                <?php }?>
            </ul>
        </div>
    </div>
</nav>
<!-- Navbar End -->


    <script src="https://kit.fontawesome.com/f1e1a05e58.js" crossorigin="anonymous"></script>