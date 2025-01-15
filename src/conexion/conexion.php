<?php

// Crear la conexión con MySQL
$conexion = mysqli_connect(Variables::HOST, Variables::USERNAME, Variables::CONTRASEÑA, Variables::DATABASE);

// Verificar si la conexión fue exitosa
if (!$conexion) {   // Si la conexión falla, mostrar el error
    die("Conexión fallida: " . mysqli_connect_error());
}
