<?php
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$user_id = null;
$token = null;

if (empty($_GET['id']) || empty($_GET['token'])) {
    header('Location: sesion.php');
    exit; // Detener la ejecución del script
}

$user_id = $mysqli->real_escape_string($_GET['id']);
$token = $mysqli->real_escape_string($_GET['token']);

if (!verificaTokenPass($user_id, $token)) {
    include "../header/headerVistas.php";
?>

<!-- 404 Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <img src="../icons/boton-x.png" alt="">
                <h1 class="display-1"></h1>
                <h1 class="mb-4">No se pudo validar la información</h1>
                <p class="mb-4"></p>
                <a class="btn btn-success rounded-pill py-3 px-5" href="recupera.php">Volver a intentarlo</a>
            </div>
        </div>
    </div>
</div>
<!-- 404 End -->

<?php
    include "../footer/footerVistas.php";
    exit; // Detener la ejecución del script
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>TodoSoport3 | Cambiar Contraseña</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="../img/logoTS3.png" rel="icon">
    <link rel="stylesheet" href="../css/bootstrap2.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/custom.css"> <!-- Agrega tu hoja de estilos personalizada aquí -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f1e1a05e58.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos personalizados para agregar espacio entre elementos */
        .container-xxl {
            margin-top: 20px; /* Agrega margen superior */
        }

        .panel-title {
            margin-bottom: 20px; /* Agrega margen inferior */
        }
    </style>
</head>
<body>
<div class="container">
    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Cambiar Contraseña</div>
            </div>
            
            <div class="panel-body">
                <div class="text-center">
                    <img src="../img/logoTS3.png" style="max-width: 80%;" alt="">
                </div>
                <form id="loginform" class="form-horizontal" role="form" action="guarda_pass.php" method="POST" autocomplete="off">
                    <input type="hidden" id="user_id" name="user_id" value ="<?php echo htmlspecialchars($user_id); ?>" />
                    <input type="hidden" id="token" name="token" value ="<?php echo htmlspecialchars($token); ?>" />
                    
                    <div class="form-group">
                        <label for="password" class="col-md-3 control-label">Nueva Contraseña</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="con_password" class="col-md-3 control-label">Confirmar Contraseña</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="con_password" placeholder="Confirmar Contraseña" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button id="btn-login" type="submit" class="btn btn-danger btn-lg">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
