<?php

include("../utils/constantes.php");
include("../conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reiniciar'])) {
    try {
        $sql = "TRUNCATE TABLE " . Variables::TABLA_BD_JUSTIFICANTES;
        $stmt = $conexion->prepare($sql);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Se ha reiniciado el folio"]);
        } else {
            echo json_encode(["mensaje" => "Ocurrió un error al reiniciar el folio"]);
        }

        $stmt->close(); // Cerrar el statement
    } catch (Exception $e) {
        echo json_encode(["mensaje" => "Error: " . $e->getMessage()]);
    }
}


