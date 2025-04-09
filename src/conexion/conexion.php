<?php
// Ajusta estos valores según la información de tu base de datos
// $host = "sql310.infinityfree.com";  // Host de la base de datos en InfinityFree
// $user = "if0_38627817";             // Nombre de usuario de la base de datos
// $pass = "KIKE2004";   // Contraseña de la base de datos (tu vPanel Password)
// $dbname = "if0_38627817_ITSH";      // Nombre de la base de datos que creaste

$host = 'localhost';
$user = 'root';
$pass = '1234';
$dbname = 'ITSH';


// Crear la conexión con MySQL
$conexion = mysqli_connect($host, $user, $pass, $dbname);
// Verificar si la conexión fue exitosa
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());  // Si la conexión falla, muestra el error
}
// Establecer el conjunto de caracteres de la conexión a UTF-8
$conexion->set_charset("utf8");

?>