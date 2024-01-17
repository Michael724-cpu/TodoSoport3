<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario tiene una sesión activa
if (isset($_SESSION['usuario'])) {
    // Destruir la sesión
    session_destroy();
}

// Redirigir a la página de inicio de sesión
header('Location: https://todosoport3.com.mx/auth/sesion.php');
exit; // Detener la ejecución del script
?>
