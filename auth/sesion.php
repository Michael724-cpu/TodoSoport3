<?php
session_start();
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$errors = array();

if (!empty($_POST)) {
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['password']);

    if (isNullLogin($usuario, $password)) {
        $errors[] = "Por favor, completa todos los campos.";
    }

    $loginResult = login($usuario, $password);

    if ($loginResult === "success") {
        // Inicio de sesión exitoso, ahora almacena el ID del usuario en la sesión
        $_SESSION['id_usuario'] = obtenerIdUsuarioPorNombre($usuario); // Reemplaza con tu función real
        header("location: ../profile/myProfile.php");
        exit;
    } else {
        $errors[] = $loginResult;
    }
}

if (isset($_SESSION['usuario'])) {
    header("location: ../profile/myProfile.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>TodoSoport3 | Iniciar Sesión</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="../img/logoTS3.png" rel="icon">
    <link rel="stylesheet" href="../css/bootstrap2.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/custom.css"> <!-- Agrega tu hoja de estilos personalizada aquí -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f1e1a05e58.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Iniciar Sesión</div>
                </div>
                
                <div class="panel-body">
                    <div class="text-center">
                        <img src="../img/logoTS3.png" style="max-width: 80%;" alt="">
                    </div>
                    
                    <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
                        <div class="input-group mb-3">
                            <span class="input-group-addon"><i class="fas fa-user"></i></span>
							<input id="usuario" type="text" class="form-control" name="usuario" value="" placeholder="Correo Electrónico" required autocomplete="username">
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-addon"><i class="fas fa-lock"></i></span>
							<input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required autocomplete="current-password">
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button id="btn-login" type="submit" class="btn btn-danger btn-block">Iniciar Sesión</button>
                            </div>
                        </div>
                        
                        <div class="form-group">
							<div class="col-md-12 control">
								<div class="text-center">
									<p class="register-link">
										¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>
									</p>
									<p class="forgot-password">
										<a href="recupera.php">¿Olvidaste tu contraseña?</a>
									</p>
									<p class="back-to-main">
										<a href="../index.php">Volver al Menú Principal</a>
									</p>
								</div>
							</div>
						</div>
                    </form>
                    <?php echo resultBlock($errors); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
