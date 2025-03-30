<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    echo "entro";
    header('Location: /src/layouts/Errores/403.php'); // Redirigir a una página predeterminada
    exit(); // Asegúrate de usar exit() después de la redirección
}

?>