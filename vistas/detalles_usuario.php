    <?php
    $conexion = new mysqli('localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support');
    $conexion->set_charset("utf8");
    
    include "../profile/pages/headerUser.php";
    $idCompra = $_GET['compra'];
    $idCompra = $_GET['compra'];
    $_SESSION['idCompra'] = $idCompra;
    $nombreUsuario = $_GET['nombre'];

    
  $sql = "SELECT * FROM transacciones WHERE id = '$idCompra' AND nombre = '$nombreUsuario' ";
$respuesta = mysqli_query($conexion, $sql);

if ($respuesta) {
    $datosUsuario = mysqli_fetch_assoc($respuesta);
    
    if ($datosUsuario) {
        $nombreUsuario = $datosUsuario['nombre']; // Supongamos que el campo se llama 'nombre' en tu tabla
        $_SESSION['nombreUsuario'] = $nombreUsuario;
    } else {
        echo "No se encontraron datos para el ID de compra proporcionado.";
    }
} else {
    echo "Error en la consulta: " . mysqli_error($conexion);
}

    
    // Realiza la consulta para obtener las categorías
    $sql2 = "SELECT DISTINCT categoria.Nombre AS TipoSoporte
            FROM catproducto AS empresarial
            INNER JOIN catcategoria AS categoria ON empresarial.IdCategoria = categoria.IdCategoria
            WHERE categoria.IdCategoriaPadre = 1"; // Cambio IdCategoriaPadre a 1 (sin comillas)
    
    $resultado = $conexion->query($sql2); // Utiliza $conexion en lugar de $mysqli
    
    // Inicializa un array para almacenar las categorías
    $categorias = array();
    
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila['TipoSoporte'];
        }
    }
    
 // Obtén el nombre de usuario de la variable de sesión
$nombreUsuario = $_SESSION['nombreUsuario'];

// Consulta para obtener eventos relacionados con el usuario
// Consulta para obtener eventos relacionados con el usuario y estatus = NULL
$sqlEventos = "SELECT * FROM eventos WHERE usuario_presento_problema = '$nombreUsuario' AND estatus_servicio IS NULL";
$respuestaEventos = mysqli_query($conexion, $sqlEventos);


    
    // Calcular la suma de la duración de los eventos
    $totalDuracion = 0;
    while ($evento = mysqli_fetch_assoc($respuestaEventos)) {
        // Convertir el tiempo en formato HH:MM a minutos y sumarlo al total
        list($horas, $minutos) = explode(':', $evento['duracion']);
        $totalDuracion += ($horas * 60) + $minutos;
    }
    $totalHoras = floor($totalDuracion / 60);
    $totalMinutos = $totalDuracion % 60;
    
    // Reiniciar la consulta para volver a obtener los eventos
    $respuestaEventos = mysqli_query($conexion, $sqlEventos);
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles del Usuario</title>
    </head>
    <body>
    <div class="container">
        <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
                <h1 class="fw-light">Detalles del Usuario</h1>
                <p class="lead">
                    <hr>
                    <h1><?php echo $datosUsuario['nombre']; ?></h1>
                    <p><strong>Teléfono:</strong> <?php echo $datosUsuario['telefono']; ?></p>
                    <p><strong>Correo:</strong> <?php echo $datosUsuario['correo']; ?></p>
                    <style>
                     .icon-img {
                                width: 24px; /* Ajusta el ancho de la imagen según tus necesidades */
                                height: 24px; /* Ajusta el alto de la imagen según tus necesidades */
                                margin-right: 5px; /* Agrega un espacio entre la imagen y el texto del botón */
                                }
                    </style>  
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modalNuevoEvento">
                        <img src="../icons/anadir.png" alt="" class="icon-img"> Nuevo
                    </button>
                    <a href="https://todosoport3.com.mx/vistas/sopTecnico.php" class="btn btn-light">
                        <img src="../icons/atras.png" alt="" class="icon-img"> Regresar</a>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <h4>Total Duración</h4>
                                <p>Total: <?php echo $totalHoras ?> horas y <?php echo $totalMinutos ?> minutos</p>
                                <p><?php echo '<h4>Servicio Contratado: ' . $datosUsuario['reference'] . '</h4>'; ?></p>
                                
                             
                               <?php 
                               $_SESSION['servicio_contratado'] = $datosUsuario['reference']; 
                               $_SESSION['transaccion_id'] = $datosUsuario['id']; 
                               $_SESSION['nombreUsuario'] =  $datosUsuario['nombre'];
                               ?>
    
                                <?php
                                // Extraer el número de minutos de la cadena y convertirlo a entero
                                $referenceString = $datosUsuario['reference'];
                                preg_match('/\d+/', $referenceString, $matches);
                                $referenceMinutos = intval($matches[0]);
    
                                // Calcular el tiempo restante en minutos
                                $totalTiempoMinutos = $totalHoras * 60 + $totalMinutos;
                                $tiempoRestanteMinutos = $referenceMinutos - $totalTiempoMinutos;
    
                                // Convertir los minutos restantes a horas y minutos
                                $tiempoRestanteHoras = floor($tiempoRestanteMinutos / 60);
                                $tiempoRestanteMinutos = $tiempoRestanteMinutos % 60;
    
                                echo '<p>Tiempo Restante: ' . $tiempoRestanteHoras . ' horas y ' . $tiempoRestanteMinutos . ' minutos</p>';
                                ?>
                            </div>
                        </div>
                    </div>
    
                   <div class="table-responsive">
                    <table class="table table-sm table-bordered dt-responsive nowrap" id="tablaDetallesDataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo de Evento</th>
                                <th>Atendido por</th>
                                <th>Usuario Presentó Problema</th>
                                <th>Asunto</th>
                                <th>Duración</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($evento = mysqli_fetch_assoc($respuestaEventos)) { ?>
                                <tr>
                                    <td><?php echo $evento['tipo_evento']; ?></td>
                                    <td><?php echo $evento['atendio']; ?></td>
                                    <td><?php echo $evento['usuario_presento_problema']; ?></td>
                                    <td><?php echo $evento['asunto']; ?></td>
                                    <td><?php echo $evento['duracion']; ?></td>
                                    <td><?php echo $evento['fecha_evento']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para agregar eventos -->
    <div class="modal fade" id="modalNuevoEvento" tabindex="-1" role="dialog" aria-labelledby="modalNuevoEventoLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoEventoLabel">Detalles de la Asistencia Técnica</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="procesar_agregar_evento.php" method="POST">
                        <div class="form-group">
                            <label for="tipo_categoria">Categoría</label>
                            <select class="form-control" id="tipo_categoria" name="tipo_categoria" required>
                                <!-- Opciones se llenarán dinámicamente a través de PHP -->
                                <?php
                                foreach ($categorias as $categoria) {
                                    echo "<option value=\"$categoria\">$categoria</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_servicio">Servicio</label>
                            <select class="form-control" id="tipo_servicio" name="tipo_servicio" required>
                                <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <textarea class="form-control" id="asunto" name="asunto" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración</label>
                            <input type="time" class="form-control" id="duracion" name="duracion" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_evento">Fecha del Evento</label>
                            <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" required>
                        </div>
                        <input type="hidden" name="usuario_presento_problema" value="<?php echo $nombreUsuario ?>">
                        <input type="hidden" name="atendido_por" value="<?php echo $_SESSION['usuario']; ?>">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Servicio Concluido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".dropdown-item").on("click", function() {
            var estatusSeleccionado = $(this).data("estatus");
            var transaccionId = $(this).data("transaccion-id"); // Obtener el transaccion_id
    
            // Envía el estatus seleccionado y el transaccion_id al servidor a través de una solicitud AJAX
            $.ajax({
                type: "POST",
                url: "procesar_agregar_evento.php", // Cambia la URL al script que procesa la actualización del estatus
                data: { estatus: estatusSeleccionado, transaccion_id: transaccionId }, // Enviar el transaccion_id
                success: function(response) {
                    alert("Estatus seleccionado: " + estatusSeleccionado);
                    // Puedes realizar más acciones o redirecciones aquí si es necesario
                }
            });
        });
    });
    </script>
    
    <script>
    $(document).ready(function () {
        var tipoCategoria = $("#tipo_categoria");
        var tipoServicio = $("#tipo_servicio");
    
        tipoCategoria.on("change", function () {
            // Obtener el valor de la categoría seleccionada
            var categoriaSeleccionada = tipoCategoria.val();
    
            // Realizar una solicitud AJAX para obtener los servicios relacionados con la categoría
            $.ajax({
                url: "obtener_servicios.php", // Cambia esto al archivo que procesa la consulta
                method: "POST",
                data: { categoria: categoriaSeleccionada },
                success: function (data) {
                    // Limpiar las opciones actuales del campo de selección de servicios
                    tipoServicio.empty();
    
                    // Llenar el campo de selección de servicios con las opciones obtenidas
                    var servicios = JSON.parse(data);
                    servicios.forEach(function (servicio) {
                        tipoServicio.append($("<option>").text(servicio).val(servicio));
                    });
                }
            });
        });
    });
    </script>
    
    
    
      <!-- plugins:js -->
      <script src="../profile/vendors/js/vendor.bundle.base.js"></script>
      <!-- endinject -->
      <!-- Plugin js for this page -->
      <script src="../profile/vendors/chart.js/Chart.min.js"></script>
      <script src="../profile/vendors/datatables.net/jquery.dataTables.js"></script>
      <script src="../profile/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
      <script src="../profile/js/dataTables.select.min.js"></script>
    
      <!-- End plugin js for this page -->
      <!-- inject:js -->
      <script src="../profile/js/off-canvas.js"></script>
      <script src="../profile/js/hoverable-collapse.js"></script>
      <script src="../profile/js/template.js"></script>
      <script src="../profile/js/settings.js"></script>
      <script src="../profile/js/todolist.js"></script>
      <!-- endinject -->
      <!-- Custom js for this page-->
      <script src="../profile/js/dashboard.js"></script>
      <script src="../profile/js/Chart.roundedBarCharts.js"></script>
      <!-- End custom js for this page-->
    </body>
    
    </html>