<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$conexion = new mysqli('localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support');
$conexion->set_charset("utf8");
$correo = $_SESSION['usuario'];

// Modifica la consulta para buscar cualquier servicio que contenga la palabra "minutos"
$sql = "SELECT * FROM transacciones WHERE response = 'approved' AND reference LIKE '%minutos%'";
$respuesta = mysqli_query($conexion, $sql);
$datosUsuario = mysqli_fetch_assoc($respuesta);

if ($datosUsuario) {
    $_SESSION['nombreUsuario'] = $datosUsuario['nombre'];
} else {
    // No se encontraron resultados, puedes manejar esto de acuerdo a tus necesidades
    $_SESSION['nombreUsuario'] = 'No encontrado'; // Puedes cambiar el mensaje si lo deseas
}


$respuesta = mysqli_query($conexion, $sql);
?>

<table class="table table-sm table-bordered dt-responsive nowrap" id="tablaSopTecnicoDataTable" style="width:100%">
    <thead>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Fecha</th>
        <th>Servicio Contratado</th>
        <th>Datos Usuarios</th>
    </thead>
    <tbody>
        <?php while ($mostrar = mysqli_fetch_array($respuesta)) { ?>
            <tr>
                <td><?php echo $mostrar['nombre'] ?></td>
                <td><?php echo $mostrar['telefono'] ?></td>
                <td><?php echo $mostrar['correo'] ?></td>
                <td><?php echo $mostrar['date'] ?></td>
                <td><?php echo $mostrar['reference'] ?></td>
                <td>
                 <a href="detalles_usuario.php?compra=<?php echo $mostrar['id']; ?>&nombre=<?php echo $mostrar['nombre']; ?>" class="btn btn-info">Detalles</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

 <script>
    $(document).ready(function () {
        $('#tablaSopTecnicoDataTable').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true
        });
    });
</script>
