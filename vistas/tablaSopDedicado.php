<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
 session_start();
 $conexion = new mysqli( 'localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support' );
 $conexion->set_charset("utf8");
 $correo = $_SESSION['usuario'];
 $sql = "SELECT paquetes.Nombre AS minutos, canales.Canal as canal,  paquetecanal.PrecioA as precioA, paquetecanal.PrecioAA as precioAA,  paquetecanal.PrecioAAA as precioAAA FROM `paquetecanal` AS paquetecanal INNER JOIN catpaquete AS paquetes ON paquetecanal.IdPaquete = paquetes.IdPaquete 
 INNER JOIN catcanal AS canales ON paquetecanal.IdCanal = canales.IdCanal WHERE canal = 'llamada'";
 

     $respuesta = mysqli_query($conexion, $sql);
 
 ?>
 <table class=" table table-sm table-bordered dt-responsive nowrap" 
        id="tablaDedicadoDataTable" style="width:100%" >
    <thead>
        <th>Minutos</th>
        <th>Canal</th>  
        <th>PrecioA</th>
        <th>PrecioAA</th>
        <th>PrecioAAA</th>
   
    </thead>
    <tbody>
        <?php while($mostrar = mysqli_fetch_array($respuesta)){ ?>
        <tr>
            <td> <?php echo $mostrar['minutos']?></td>
            <td> <?php echo $mostrar['canal']?></td>
            <td> <?php echo $mostrar['precioA']?></td>
            <td> <?php echo $mostrar['precioAA']?></td>
            <td> <?php echo $mostrar['precioAAA']?></td>
        </tr>
        <?php } ?>
    </tbody>
 </table>

 <script>
$(document).ready(function () {
    $('#tablaDedicadoDataTable').DataTable(
        {
                language: {
                        url:"../datatable/es-ES.json"
                },
                dom: 'Bfrtip',
        buttons :{
   buttons :[,
                {
                    extend: 'csv', 
                    className: 'btn btn-outline-primary', 
                     text: '<i class="fa-solid fa-file-csv"></i> CSV'
                },

                {
                    extend: 'excel', 
                    className: 'btn btn-outline-success', 
                     text: '<i class="fa-solid fa-file-excel"></i> Excel'
                },

            ],
        dom :{
            button:{
                className: 'btn'
                     }

                 }
            }
        });
})
 </script>


 






