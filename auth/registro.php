<?php
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$errors = array();

if (!empty($_POST)) {
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $con_password = $mysqli->real_escape_string($_POST['con_password']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $telefono = $mysqli->real_escape_string($_POST['telefono']);
    $captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);

    $activo = 0;
    $tipo_usuario = 2;

    $secret = '6LfvNiUnAAAAACRD61EYGAsbEmjCsf88TxYNCYRc';

    if (!$captcha) {
        $errors[] = "Por favor verifica el Captcha";
    }

    if (isNull($nombre, $password, $con_password, $email, $telefono)) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!isEmail($email)) {
        $errors[] = "Dirección de correo inválida";
    }

    if (!validaPassword($password, $con_password)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (emailExiste($email)) {
        $errors[] = 'El correo electrónico ' . $email . ' ya existe';
    }

    if (count($errors) == 0) {
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha);
        $arr = json_decode($response, TRUE);

        if ($arr['success']) {
            $pass_hash = hashPassword($password);
            $token = generateToken();
            $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario, $telefono);

            if ($registro > 0) {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/auth/activar.php?id=' . $registro . '&val=' . $token;
                $asunto = 'Activa tu Cuenta - TodoSoport3';
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
                background-color: #007bff; /* Azul */
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
            <p class="titulo">Activa tu Cuenta</p>
        </div>
        <div class="container">
            <p class="saludo">Estimado ' . $nombre . ':</p>
            <p class="contenido">Para continuar con el proceso de registro, es indispensable hacer clic en el siguiente enlace:</p>
            <p><a class="boton" href="' . $url . '"style="color: #fff;">Activar Cuenta</a></p>
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
                                    <img src="../icons/sitio-web.png" alt="">
                                    <h1 class="display-1"></h1>
                                    <h1 class="mb-4">Ingresa a tu cuenta: ' . $email . '</h1>
                                    <p class="mb-4"></p>
                                    <a class="btn btn-success rounded-pill py-3 px-5" href="sesion.php">Iniciar sesión</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                    include "../footer/footerVistas.php";
                    exit;
                } else {
                    $errors[] = "Error al enviar correo";
                }
            } else {
                $errors[] = "Error al registrar";
            }
        } else {
            $errors[] = "Error al comprobar Captcha";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>TodoSoport3 | Registro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="../img/logoTS3.png" rel="icon">
    <link rel="stylesheet" href="../css/bootstrap2.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/custom.css"> <!-- Agrega tu hoja de estilos personalizada aquí -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f1e1a05e58.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Registro</div>
                </div>
                
                <div class="panel-body">
                    <div class="text-center">
                        <img src="../img/logoTS3.png" style="max-width: 80%;" alt="">
                    </div>
                    
                    <form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
                        <div class="form-group">
                            <label for="nombre" class="col-md-3 control-label">Nombre Completo</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre Completo" value="<?php if(isset($nombre)) echo $nombre; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="usuario" class="col-md-3 control-label">Empresa</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="usuario" placeholder="Empresa (opcional)" value="<?php if(isset($usuario)) echo $usuario; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono" class="col-md-3 control-label">Teléfono</label>
                            <div class="col-md-9">
                            <input type="text" class="form-control" name="telefono" placeholder="Teléfono fijo y/o celular" required autocomplete="tel">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="col-md-3 control-label">Contraseña</label>
                            <div class="col-md-9">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" pattern=".{8,}" title="La contraseña debe tener al menos 8 caracteres" required autocomplete="new-password">
                                <span class="help-block">La contraseña debe tener al menos 8 caracteres.</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="con_password" class="col-md-3 control-label">Confirmar Contraseña</label>
                            <div class="col-md-9">
                            <input type="password" class="form-control" name="con_password" placeholder="Confirmar contraseña" required autocomplete="new-password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="col-md-3 control-label">Correo Electrónico</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" placeholder="Correo Electrónico" value="<?php if(isset($email)) echo $email; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="g-recaptcha col-md-9" data-sitekey="6LfvNiUnAAAAACDLGj1fiFU3-Gxnsghe9RkR4Ydj"></div>
                        </div>
                        <div class="form-group">                                      
                            <div class="col-md-12 text-center">
                                <button id="btn-signup" type="submit" class="btn btn-info btn-lg"><i class="icon-hand-right"></i> Registrarse</button>
                                <!-- Espacio vertical para separar el botón y el enlace -->
                                <div style="margin-top: 10px;"></div>
                                <!-- Enlace para regresar al Menú Principal -->
                                <p class="back-to-main">
                                    <a href="../index.php">Volver al Menú Principal</a>
                                </p>
                            </div>
                        </div>
                    </form>
                    <?php echo resultBlock($errors); ?>
                   <script>
                        // Obtén una referencia al elemento por su ID (cambia 'miElemento' por el ID real)
                        var elemento = document.getElementById('miElemento');

                        // Asegúrate de que el elemento se haya encontrado antes de agregar el evento
                        if (elemento) {
                            elemento.addEventListener('touchstart', miFuncion, { passive: true });
                        }
                        // Define la función miFuncion aquí
                        function miFuncion(event) {
                            // Código para manejar el evento 'touchstart' aquí
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>