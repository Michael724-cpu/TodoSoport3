<?php
session_start();
error_reporting(E_ALL);
file_put_contents('post_data.txt', print_r($_POST, true));
ini_set('display_errors', 1);
$config = include('../../../config.php');

// Incluye la librería AESCrypto si no la tienes incluida
include('AESCrypto.php');

// Clave de cifrado
$key = $config['encryption_key'];

// Verifica si se recibió una respuesta válida
if (isset($_POST['strResponse'])) {
    try {
        // Obtiene la respuesta cifrada enviada por el servidor
        $respuesta_cifrada_hex = $_POST['strResponse'];

        // Descifra la respuesta
        $respuesta_descifrada = AESCrypto::desencriptar($respuesta_cifrada_hex, $key);

        // Analiza la respuesta XML
        $datos_respuesta = simplexml_load_string($respuesta_descifrada);

        // Extrae los datos relevantes
        $reference = $datos_respuesta->reference;
        $response = $datos_respuesta->response;
        $foliocpagos = $datos_respuesta->foliocpagos;
        $auth = $datos_respuesta->auth;
        $date = $datos_respuesta->date;
        $importe = $datos_respuesta->importe;
        $nombre = $datos_respuesta->datos_adicionales->data[0]->value;
        $telefono = $datos_respuesta->datos_adicionales->data[1]->value;
        $correo = $datos_respuesta->datos_adicionales->data[2]->value;

        // Inicializa el ID de usuario
        $id = null;

        // Verifica si el usuario ha iniciado sesión
        if (isset($_SESSION['id_usuario'])) {
            $id = $_SESSION['id_usuario'];
        }

        // Inserta los datos en la tabla "transacciones"
        $servername = "localhost";
        $username = "todosopo";
        $password = ":zhO2]TFF4g3l0";
        $database = "todosopo_datavoic_support";

        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Formatear la fecha en el formato "yyyy-mm-dd" para MySQL
        $mysql_date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));

        // Modifica la consulta SQL y los valores a insertar en función de si el usuario ha iniciado sesión
        if ($id !== null) {
            $sql = "INSERT INTO transacciones (reference, response, foliocpagos, auth, date, nombre, telefono, correo, user_id, importe) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$reference, $response, $foliocpagos, $auth, $mysql_date, $nombre, $telefono, $correo, $id, $importe]);
        } else {
            $sql = "INSERT INTO transacciones (reference, response, foliocpagos, auth, date, nombre, telefono, correo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$reference, $response, $foliocpagos, $auth, $mysql_date, $nombre, $telefono, $correo]);
        }

        echo "Datos de transacción insertados correctamente en la tabla.";
    } catch (Exception $e) {
        echo "Error al insertar los datos de transacción en la tabla: " . $e->getMessage();
    } finally {
        // Cierra la conexión a la base de datos
        $pdo = null;
    }
} else {
    echo "No se recibió una respuesta válida del servidor del banco.";
}
?>
