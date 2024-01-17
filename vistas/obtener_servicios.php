<?php
$conexion = new mysqli('localhost', 'todosopo', ':zhO2]TFF4g3l0', 'todosopo_datavoic_support');
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if (isset($_POST['categoria'])) {
    $categoria = $_POST['categoria'];
    
    $sql = "SELECT empresarial.Nombre AS servicio
            FROM catproducto AS empresarial
            INNER JOIN catcategoria AS categoria ON empresarial.IdCategoria = categoria.IdCategoria
            WHERE categoria.Nombre = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $categoria);
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($servicio);
                $servicios = array();
                
                while ($stmt->fetch()) {
                    $servicios[] = $servicio;
                }
                
                echo json_encode($servicios);
            } else {
                echo "No se encontraron servicios relacionados con la categoría.";
            }
        } else {
            echo "Error al ejecutar la consulta preparada: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "No se proporcionó una categoría válida.";
}

$conexion->close();
?>
