<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reiniciar'])) {
    try {
        if (EliminarDatosTablaJustificante($conexion)) {
            echo json_encode(["mensaje" => "Se ha reiniciado el folio"]);
        } else {
            echo json_encode(["mensaje" => "Ocurrió un error al reiniciar el folio"]);
        }

        $stmt->close(); // Cerrar el statement
    } catch (Exception $e) {
        echo json_encode(["mensaje" => "Error: " . $e->getMessage()]);
    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}


