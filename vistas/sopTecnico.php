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
          <h1 class="fw-light">Usuarios Activos</h1>
          <p class="lead">
          <hr>
          <div id="tablaReporteUsuariosLoad"></div>
          </p>
        </div>
      </div>
    </div>
    
            <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

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



<script src="../js/sopTecnico.js"></script>
<?php
} else {
  header("location:../index.html");
  ob_end_flush();
}  
?>