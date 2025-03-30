<?php
session_start();  // Inicia la sesión

// Elimina todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige a la página de inicio
header("Location: ../../index.php");
exit();  // Asegúrate de que el script se detenga después de la redirección
?>