<?php
session_start();
$conexion = new mysqli('localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support');
$conexion->set_charset("utf8");

$tipoEvento = $_POST['tipo_servicio'];
$asunto = $_POST['asunto'];
$duracion = $_POST['duracion'];
$fecha_evento = $_POST['fecha_evento'];
$usuarioPresentoProblema = $_POST['usuario_presento_problema'];
$atendidoPorCorreo = $_POST['atendido_por']; // Cambiar a la variable de correo

// Consulta para obtener el nombre de la persona que atendi¨®
$sqlNombreAtendido = "SELECT nombre FROM usuarios WHERE correo = '$atendidoPorCorreo'";
$respuestaNombreAtendido = mysqli_query($conexion, $sqlNombreAtendido);
$datosNombreAtendido = mysqli_fetch_assoc($respuestaNombreAtendido);

$atendidoPor = $datosNombreAtendido['nombre']; // Obtener el nombre de la consulta

// Obtener el valor de idCompra de la variable de sesi¨®n
$idCompra = $_SESSION['idCompra'];

$sqlInsertar = "INSERT INTO eventos (tipo_evento, atendio, usuario_presento_problema, asunto, duracion, fecha_evento) VALUES ('$tipoEvento', '$atendidoPor', '$usuarioPresentoProblema', '$asunto', '$duracion', '$fecha_evento')";

if ($conexion->query($sqlInsertar) === TRUE) {
    // Redirigir a la p¨¢gina "detalles_usuario.php" con los par¨¢metros idCompra y nombre
    header("Location: detalles_usuario.php?compra=$idCompra&nombre=$usuarioPresentoProblema");
    exit;
} else {
    echo "Error al insertar el evento: " . $conexion->error;
}

$conexion->close();
?>
