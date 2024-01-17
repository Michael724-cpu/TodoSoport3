<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
 session_start();
 header('Content-type: text/html; charset=utf-8');
 $conexion = new mysqli( 'localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support' );
 $conexion->set_charset("utf8");
 $correo = $_SESSION['usuario'];
 $sql = "SELECT 
 categoria.Nombre AS TipoSoporte,
 empresarial.Nombre AS servicio, 
 empresarial.Precio as precio 
 FROM catproducto AS empresarial 
 INNER JOIN catcategoria AS categoria ON empresarial.IdCategoria = categoria.IdCategoria 
 WHERE IdCategoriaPadre = '1';";
 

     $respuesta = mysqli_query($conexion, $sql);
 
 ?>
 <table class=" table table-sm table-bordered dt-responsive nowrap" 
        id="tablaDedicadoDataTable" style="width:100%" >
    <thead>
        <th>Tipo de Soporte</th>
        <th>Servicio</th>  
        <th>Precio</th>
   
    </thead>
    <tbody>
        <?php while($mostrar = mysqli_fetch_array($respuesta)){ ?>
        <tr>
            <td> <?php echo $mostrar['TipoSoporte']?></td>
            <td> <?php echo $mostrar['servicio']?></td>
            <td> <?php echo $mostrar['precio']?></td>
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


 






