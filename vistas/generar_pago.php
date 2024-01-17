<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$config = include('../../config.php');

// Incluye la librería AESCrypto
include('pagos/AESCrypto.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreServicio'], $_POST['precioServicio'], $_POST['nombre'], $_POST['telefono'], $_POST['correo'])) {
    $nombreServicio = $_POST['nombreServicio'];
    $precioServicio = floatval($_POST['precioServicio']);
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    // Validación de datos (puedes agregar más validaciones según tus necesidades)
    if (empty($nombre) || empty($telefono) || empty($correo) || $precioServicio <= 0) {
        echo "Datos ingresados inválidos.";
        exit;
    }

    // Asegurar que la referencia cumple con las restricciones
    $reference = substr(preg_replace('/[^a-zA-Z0-9]/', '', $nombreServicio), 0, 50);

    // Convertir $precioServicio a float
    $precioServicio = floatval($precioServicio);

    // Formatear $precioServicio con 2 decimales y punto como separador decimal
    $precioServicio = number_format($precioServicio, 2, '.', '');

    // Crear la cadena XML con los datos adicionales de nombre, teléfono y correo
    $cadenaXML = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $cadenaXML .= '<P>' . PHP_EOL;
    $cadenaXML .= '<business>' . PHP_EOL;
    $cadenaXML .= '  <id_company>QLLB</id_company>' . PHP_EOL;
    $cadenaXML .= '  <id_branch>0001</id_branch>' . PHP_EOL;
    $cadenaXML .= '  <user>QLLBSIUS0</user>' . PHP_EOL;
    $cadenaXML .= '  <pwd>S1BKFFMUC1</pwd>' . PHP_EOL;
    $cadenaXML .= '</business>' . PHP_EOL;
    $cadenaXML .= '<url>' . PHP_EOL;
    $cadenaXML .= '  <reference>' . htmlspecialchars($reference) . '</reference>' . PHP_EOL;
    $cadenaXML .= '  <amount>' . $precioServicio . '</amount>' . PHP_EOL;
    $cadenaXML .= '  <moneda>MXN</moneda>' . PHP_EOL;
    $cadenaXML .= '  <canal>W</canal>' . PHP_EOL;
    $cadenaXML .= '  <datos_adicionales>' . PHP_EOL;
    $cadenaXML .= '    <data id="1" display="true">' . PHP_EOL;
    $cadenaXML .= '      <label>Nombre</label>' . PHP_EOL;
    $cadenaXML .= '      <value>' . htmlspecialchars($nombre) . '</value>' . PHP_EOL;
    $cadenaXML .= '    </data>' . PHP_EOL;
    $cadenaXML .= '    <data id="2" display="true">' . PHP_EOL;
    $cadenaXML .= '      <label>Teléfono</label>' . PHP_EOL;
    $cadenaXML .= '      <value>' . htmlspecialchars($telefono) . '</value>' . PHP_EOL;
    $cadenaXML .= '    </data>' . PHP_EOL;
    $cadenaXML .= '    <data id="3" display="true">' . PHP_EOL;
    $cadenaXML .= '      <label>Correo Electrónico</label>' . PHP_EOL;
    $cadenaXML .= '      <value>' . htmlspecialchars($correo) . '</value>' . PHP_EOL;
    $cadenaXML .= '    </data>' . PHP_EOL;
    $cadenaXML .= '  </datos_adicionales>' . PHP_EOL;
    $cadenaXML .= '  <data3ds>' . PHP_EOL;
    $cadenaXML .= ' <ml>' . htmlspecialchars($correo) . '</ml>' . PHP_EOL;
    $cadenaXML .= ' <cl>' . htmlspecialchars($telefono) .'</cl>' . PHP_EOL;
    $cadenaXML .= '  </data3ds>' . PHP_EOL;
    $cadenaXML .= '  <version>IntegraWPP</version>' . PHP_EOL;
    $cadenaXML .= '</url>' . PHP_EOL;
    $cadenaXML .= '</P>' . PHP_EOL;

    $originalString = $cadenaXML;
    // Clave de cifrado
    $key128 = $config['encryption_key'];
    // Cifra la cadena XML
    $encrypted = AESCrypto::encriptar($originalString, $key128);
    // Construir los datos para la solicitud POST
    $post_data = $config['post_data'];
    $data = http_build_query([
    'xml' => '<pgs><data0>' . $post_data['data0'] . '</data0><data>' . $encrypted . '</data></pgs>'
    ]);

    // Configurar la solicitud cURL
    $url = $config['url'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Ejecutar la solicitud cURL y obtener la respuesta
    $response = curl_exec($ch);

    // Comprobar si hubo errores
    if (curl_errno($ch)) {
        echo 'Error en la solicitud cURL: ' . curl_error($ch);
    }

    // Cerrar la sesión cURL
    curl_close($ch);

    try {
        $decryptedResponse = AESCrypto::desencriptar($response, $key128);
        // Procesar la respuesta para obtener la URL real
        $xml = simplexml_load_string($decryptedResponse);
        $nb_url = (string)$xml->nb_url;

        // Inicia una sesión (si aún no se ha iniciado)
        session_start();

        // Almacena los datos del usuario en la sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['telefono'] = $telefono;
        $_SESSION['correo'] = $correo;

        // Redirigir al usuario a la URL desencriptada
        header("Location: " . $nb_url);
        exit; // Asegura que el script se detenga después de la redirección
    } catch (Exception $e) {
        echo "Error en la desencriptación: " . $e->getMessage();
    }
}
?>
