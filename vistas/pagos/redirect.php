<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluir la biblioteca PHPMailer
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

// Iniciar la sesión (si aún no se ha iniciado)
session_start();

$mensaje = "Error";
$nbResponse = '';  // Define $nbResponse aquí para evitar advertencias de variable indefinida

// Verificar si el enlace ya se ha enviado
if (isset($_SESSION['enlace_enviado']) && $_SESSION['enlace_enviado'] === true) {
    $mensaje = "El enlace ya se ha enviado previamente.";
    $mostrarBoton = false; // No mostrar el botón en recargas
} else {
    $mostrarBoton = true; // Mostrar el botón por defecto

if (isset($_GET['success'])) {
    $success = filter_var($_GET['success'], FILTER_VALIDATE_BOOLEAN);
    $nbResponse = isset($_GET['nbResponse']) ? $_GET['nbResponse'] : '';
    $cdResponse = isset($_GET['cdResponse']) ? $_GET['cdResponse'] : '';
    $nuAut = isset($_GET['nuAut']) ? $_GET['nuAut'] : '';
    $banco = isset($_GET['banco']) ? $_GET['banco'] : '';
    $marca = isset($_GET['marca']) ? $_GET['marca'] : '';
    $importe = isset($_GET['importe']) ? $_GET['importe'] : '';
    $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
    $referencia = isset($_GET['referencia']) ? $_GET['referencia'] : '';
    $empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '';

        if ($success && $nbResponse === 'Aprobado') {
            $mensaje = "Pago exitoso";

            // Acceder a los datos del usuario desde la sesión
            if (isset($_SESSION['nombre'], $_SESSION['telefono'], $_SESSION['correo'])) {
                $nombreUsuario = htmlspecialchars($_SESSION['nombre']);
                $telefonoUsuario = htmlspecialchars($_SESSION['telefono']);
                $correoUsuario = htmlspecialchars($_SESSION['correo']);
                $grupoUsuario = 'G4359'; // Valor constante
                $empresaUsuario = '79'; // Valor constante

                $folioUrl = "https://app.datavoice.com.mx/DatavoiceWebAPIV2/api/AMI/AsignarExtensionClienteVideo?";
                $folioData = array(
                    'Nombre' => $nombreUsuario,
                    'Correo' => $correoUsuario,
                    'Telefono' => $telefonoUsuario,
                    'grupo' => $grupoUsuario,
                    'empresa' => $empresaUsuario
                );

                $folioQueryString = http_build_query($folioData);
                $folioUrl .= $folioQueryString;

                try {
                    // Realizar la solicitud para generar el folio
                    $response = file_get_contents($folioUrl);

                    if ($response === false) {
                        throw new Exception("No se pudo obtener el folio.");
                    }

                    $data = json_decode($response, true);

                    if (isset($data['Folio']) && !empty($data['Folio'])) {
                        // Se generó un folio exitosamente, puedes mostrarlo
                        $folioGenerado = $data['Folio'];
                    } else {
                    // No se pudo obtener el folio, maneja el error apropiadamente
                    throw new Exception("No se pudo obtener el folio.");

                    }
                } catch (Exception $e) {
                    // Manejar la excepción
                    $mensaje = "Pago exitoso, pero no se pudo obtener el folio: " . $e->getMessage();
                }

                // Construir la URL con el folio
                $urlVideollamada = "https://web.datavoice.com.mx:488/WebPhoneClientTS/WebPhoneCliente.html?F" . urlencode($folioGenerado);

                // Crear una instancia de PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Configurar el servidor SMTP y las credenciales
                    $mail->isSMTP();
                    $mail->Host = 'mail.todosoporte.com.mx';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'atencion@todosoporte.com.mx';
                    $mail->Password = '@7Q*NLQgz3*7';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;

                    // Establecer el remitente y el destinatario
                    $mail->setFrom('atencion@todosoporte.com.mx', 'TodoSoport3');
                    $mail->addAddress($correoUsuario, $nombreUsuario);

                    // Configurar el contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Tu videollamada te esta esperando';
                    $mail->Body = '
                        <div style="background-color: #007bff; text-align: center; padding: 20px;">
                            <img src="https://todosoport3.com.mx/img/logoTS3.png" alt="Logo de la empresa" style="width: 150px; height: auto;">
                            <h1 style="color: #fff; font-size: 24px;">Hola, ' . $nombreUsuario . '.</h1>
                            <p style="color: #fff; font-size: 18px;">Tu Servicio de Soporte Tecnico te esta esperando.</p>
                            <p style="text-align: center;">
                                <a href="' . $urlVideollamada . '" style="background-color: #fff; color: #007bff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px;">Iniciar Videollamada</a>
                            </p>
                        </div>
                        <div style="background-color: #f3f3f3; text-align: center; padding: 10px;">
                            <p style="font-size: 14px; color: #333;">Atentamente, Tu Equipo de Soporte Tecnico</p>
                        </div>
                    ';

                    // Enviar el correo
                    $mail->send();

                    // Establecer la variable de sesión para indicar que se ha enviado el enlace.
                    $_SESSION['enlace_enviado'] = true;

                    // Mensaje de éxito
                    $mensaje = "Pago exitoso. Se ha enviado el enlace de videollamada por correo electrónico.";
                } catch (Exception $e) {
                    // Manejar cualquier error en el envío del correo
                    $mensaje = "Pago exitoso";
                }
            } else {
                // Datos del usuario no encontrados en la sesión
                $mensaje = "Pago exitoso";
            }
        } else {
            // Verificar si se proporciona información de rechazo
            if (isset($nbResponse) && isset($cdResponse)) {
                // Aquí puedes personalizar el mensaje de acuerdo con la respuesta de rechazo
                $mensaje = "Pago fallido $nbResponse, Código: $cdResponse";

                // También puedes acceder a otros parámetros de rechazo si es necesario
                $nb_error = isset($_GET['nb_error']) ? $_GET['nb_error'] : "";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../img/logoTS3.png" rel="icon">
    <script src="https://kit.fontawesome.com/f1e1a05e58.js" crossorigin="anonymous"></script>
    <title>TodoSoport3 | Redirect</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    .container {
        max-width: 600px;
        margin: auto;
        text-align: center;
        padding: 20px;
        flex: 1;
    }

    .payment-box {
        background: linear-gradient(to bottom, #e6e5e5 0%, #e6e5e5 25%, #fff 25%, #fff 100%);
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        padding: 120px;
        z-index: 1;
    }

    .logo {
        width: 150px;
        height: 120px;
        margin: -110px auto 20px;
    }

    .title {
        font-size: 24px;
        color: #000;
    }

    .message {
        font-size: 18px;
        color: #000;
        margin: 20px 0;
    }

    .response-table {
        width: 100%;
        border-collapse: collapse;
    }

    .response-table th, .response-table td {
        padding: 10px;
        text-align: left;
    }

    .response-info {
        font-size: 16px;
        color: #000;
        margin: 10px 0;
    }

    .error-description {
        font-size: 16px;
        color: red;
        margin: 10px 0;
    }

    .button {
        display: inline-block;
        background-color: #fff;
        color: #007bff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        margin-top: 10px;
    }

     .button {
        <?php if (!$mostrarBoton) echo 'display: none;'; ?> // Ocultar el botón si $mostrarBoton es falso
    }
    
    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
    }

    .image-container img {
        max-width: 100%;
        max-height: 100px;
    }

    /* Estilo para la referencia */
    .reference {
        word-wrap: break-word;
        font-size: 16px;
    }

    /* Estilo para el botón de videollamada */
    .videocall-button {
        position: fixed;
        bottom: 30px;
        left: 0;
        right: 0;
        margin: 0 auto;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        transition: background-color 0.3s ease;
    }
    

    .videocall-button i {
        font-size: 20px;
    }

    .videocall-button:hover {
        background-color: #0056b3;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="payment-box">
            <img src="../../img/logoTS3.png" class="logo" alt="Logo">
            <?php if ($nbResponse == 'Aprobado') { ?>
                <div class="image-container">
                    <img src="../../icons/comprobado.png" alt="Comprobado">
                </div>
            <?php } ?>
            <?php if ($nbResponse == 'Rechazado') { ?>
                <div class="image-container">
                    <img src="../../icons/zona-prohibida.png" alt="Zona Prohibida">
                </div>
            <?php } ?>
            <?php if ($mensaje == 'Error') { ?>
                <div class="image-container">
                    <img src="../../icons/x.png" alt="X">
                </div>
            <?php } ?>
            <?php if ($nbResponse == 'Aprobado' || $nbResponse == 'Rechazado'  ) { ?>
                <h2 class="title"><?php echo $nbResponse; ?></h2>
                <p class="message"><?php echo $mensaje; ?></p>
            <?php } ?>
            <?php if ($mensaje == 'Error' ) { ?>
                <h2 class="title"><?php echo $mensaje; ?></h2>
                <p class="message">Informacion no valida</p>
            <?php } ?>
            <?php if (isset($nbResponse)) { ?>
                <?php if (!empty($nb_error)) { ?>
                    <p class="error-description"><?php echo $nb_error; ?></p>
                <?php } ?>
                <table class="response-table">
                    <tbody>
                        <tr>
                            <th>Empresa:</th>
                            <td><?php echo $empresa; ?></td>
                        </tr>
                        <tr>
                            <th>Referencia:</th>
                            <td><span class="reference"><?php echo $referencia; ?></span></td>
                        </tr>
                        <?php if ($nbResponse == 'Aprobado') { ?>
                            <tr>
                                <th>Fecha:</th>
                                <td><?php echo $fecha; ?></td>
                            </tr>
                            <tr>
                                <th>Autorización:</th>
                                <td><?php echo $nuAut; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php if (isset($folioGenerado) && $mostrarBoton) { ?>
                <p class="response-info">Folio Generado: <?php echo $folioGenerado; ?></p>
                <a href="<?php echo $urlVideollamada; ?>" class="button" target="_blank"><i class="fa fa-video-camera">     Iniciar Servicio</i></a>
            <?php } ?>
        </div>
    </div>
</body>
</html>