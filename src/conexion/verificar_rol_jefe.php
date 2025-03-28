<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Jefe de Carrera') {
    // Si no está logueado, redirigir al login
    // Verificar si 'HTTP_REFERER' existe antes de usarlo
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirigir a la página anterior
    } else {
        header('Location: /src/layouts/Errores/403.php'); // Redirigir a una página predeterminada
    }
    exit(); // Asegúrate de usar exit() después de la redirección
}

?>