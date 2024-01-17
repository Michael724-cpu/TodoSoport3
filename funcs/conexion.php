<?php
	
	$mysqli=new mysqli( 'localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support' );
	
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
	
?>