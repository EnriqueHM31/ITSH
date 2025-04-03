<?php

include "../conexion/conexion.php";
include "../utils/constantes.php";
include "../utils/functionGlobales.php";

$resultadoAllCarreras = obtenerAllCarreras($conexion);

// Creamos un array para almacenar los resultados
$data = [];

while ($row = $resultadoAllCarreras->fetch_assoc()) {
    // Agregamos cada fila del resultado al array
    $data[] = $row;
}

// Devolvemos el array como un JSON
echo json_encode(["result" => $data]);