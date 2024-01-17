<?php
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$errors = array();

if (!empty($_POST)) {
    $email = $mysqli->real_escape_string($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Debe ingresar un correo electrónico válido";
    }

    if (emailExiste($email)) {
        $user_id = getValor('id', 'correo', $email);
        $nombre = getValor('nombre', 'correo', $email);

        $token = generaTokenPass($user_id);

        $url = 'https://' . $_SERVER['SERVER_NAME'] . '/auth/cambia_pass.php?id=' . $user_id . '&token=' . $token;

$asunto = 'Recupera tu Contrasena - TodoSoport3';
$cuerpo = '
    <html>
    <head>
        <style>
            .container {
                background-color: #f5f5f5; /* Gris claro */
                text-align: center;
                color: #000; /* Texto negro */
            }
            .header {
                background-color: #ccc; /* Barra de color gris */
                padding: 10px 0;
                text-align: center;
            }
            .saludo {
                font-size: 24px;
            }
            .contenido {
                font-size: 18px;
            }
            .boton {
                background-color: #0058A1; /* Azul oscuro */
                color: #fff; /* Texto blanco */
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;
                font-size: 16px;
            }
            .logo {
                width: 150px; /* Ajusta el tamaño de tu logo */
                height: auto;
            }
            .titulo {
                font-size: 24px;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img class="logo" src="https://todosoport3.com.mx/img/logoTS3.png" alt="Logo de la empresa">
            <p class="titulo">Recuperar Cuenta</p>
        </div>
        <div class="container">
            <p class="saludo">Estimado ' . $nombre . ':</p>
            <p class="contenido">Has solicitado restablecer tu contraseña. Para cambiar tu contraseña, haz clic en el siguiente enlace:</p>
            <p><a class="boton" href="' . $url . '" style="color: #fff;">Cambiar Contraseña</a></p>
        </div>
    </body>
    </html>
';


        if (enviarEmail($email, $nombre, $asunto, $cuerpo)) {
            include "../header/headerVistas.php";
            echo '
            <!-- 404 Start -->
            <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <img src="../icons/restablecer-la-contrasena.png" alt="">
                            <h1 class="display-1"></h1>
                            <h1 class="mb-4">Hemos enviado un correo electrónico a la dirección: ' . $email . ' para restablecer tu contraseña</h1>
                            <p class="mb-4"></p>
                            <a class="btn btn-success rounded-pill py-3 px-5" href="sesion.php">Iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </div>';
            include "../footer/footerVistas.php";
            exit;
        } else {
            $errors[] = "Error al enviar el correo electrónico";
        }
    } else {
        $errors[] = "No existe el correo electrónico";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>TodoSoport3 | Recuperar Contraseña</title>
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
                    <div class="panel-title">Recuperar Contraseña</div>
                </div>
                
                <div class="panel-body">
                    <div class="text-center">
                        <img src="../img/logoTS3.png" style="max-width: 80%;" alt="">
                    </div>
                    
                    <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
                        <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="fas fa-user"></i></span>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Correo electrónico" required autocomplete="email">
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 text-center">
                            <button id="btn-login" type="submit" class="btn btn-danger btn-lg">Enviar</button>
                            </div>
                        </div>

                        <div class="form-group">
							<div class="col-md-12 control">
								<div class="text-center">
									<p class="register-link">
										¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>
									</p>
									<p class="back-to-main">
										<a href="../index.php">Volver al Menú Principal</a>
									</p>
								</div>
							</div>
						</div>
                    </form>
                    <?php echo resultBlock($errors);?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>