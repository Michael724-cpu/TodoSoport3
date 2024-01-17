<?php
ob_clean();
ob_start();
include "../profile/pages/headerUser.php";
$conexion = new mysqli( 'localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support' );
$conexion->set_charset("utf8");

if (isset($_SESSION['usuario'])) {
?>

<!-- Page Content -->
    <div class="container">
      <div class="card border-0 shadow my-5">
        <div class="card-body p-5">
          <h1 class="fw-light">Soporte Empresarial</h1>
          <p class="lead">
          <hr>
          <div id="tablaReporteEmpresarialLoad"></div>
          </p>
        </div>
      </div>
    </div>
    
    <script src="../js/sopEmpresarial.js"></script>
    <?php
    } else {
      header("location:../index.html");
      ob_end_flush();
    }  
    ?>

    <!-- plugins:js -->
     <script src="../profile/vendors/js/vendor.bundle.base.js"></script>
    
    <!-- JavaScript Libraries -->
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../datatable/jquery.dataTables.min.js"></script>
    <script src="../datatable/dataTables.bootstrap4.min.js"></script>
    <script src="../datatable/dataTables.responsive.min.js"></script>
    <script src="../datatable/responsive.bootstrap4.min.js"></script>

    <!--seccion de botones de datatable-->
    <script src="../datatable/dataTables.buttons.min.js"></script>
    <script src="../datatable/jszip.min.js"></script>
    <script src="../datatable/vfs_fonts.js"></script>
    <script src="../datatable/buttons.html5.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    
</body>

</html>

