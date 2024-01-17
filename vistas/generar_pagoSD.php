<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$config = include('../../config.php');
require '../funcs/conexion.php';
require '../funcs/funcs.php';

$id = $_SESSION['id_usuario'];

$sql = "SELECT id, nombre, correo, id_tipo, telefono, last_session, usuario FROM usuarios WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($id, $nombre, $correo, $id_tipo, $telefono, $last_session, $usuario);
$stmt->fetch();


// Incluye la librería AESCrypto
include('pagos/AESCrypto.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['minutos'], $_POST['precio'])) {
    $minutos = $_POST['minutos'];
    $precio = floatval($_POST['precio']);
   
    // Asegurar que la referencia cumple con las restricciones
    $reference = substr(preg_replace('/[^a-zA-Z0-9]/', '', $minutos), 0, 50);

    // Convertir $precio a float
    $precio = floatval($precio);

    // Formatear $precio con 2 decimales y punto como separador decimal
    $precio = number_format($precio, 2, '.', '');


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
 $cadenaXML .= '  <amount>' . $precio . '</amount>' . PHP_EOL;
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

        // Redirigir al usuario a la URL desencriptada
        header("Location: " . $nb_url);
        exit; // Asegura que el script se detenga después de la redirección
    } catch (Exception $e) {
        echo "Error en la desencriptación: " . $e->getMessage();
    }
}
?>
