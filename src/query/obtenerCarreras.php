

<?php

include "../conexion/conexion.php";
include "../utils/constantes.php";

$stmt = $conexion->prepare("SELECT " . Variables::CAMPO_CARRERA . " FROM " . Variables::TABLA_BD_CARRERA);
$stmt->execute();
$result = $stmt->get_result();

// Creamos un array para almacenar los resultados
$data = [];

while ($row = $result->fetch_assoc()) {
    // Agregamos cada fila del resultado al array
    $data[] = $row;
}

// Devolvemos el array como un JSON
echo json_encode(["result" => $data]);

