<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reiniciar'])) {
    try {

        $numeroJustificante = obtenerNumeroFolio($conexion);

        if ($numeroJustificante == 0) {
            echo json_encode(["mensaje" => [false, "No hay justificantes en el sistema"]]);
            return;
        }

        if (EliminarDatosTablaJustificante($conexion)) {
            echo json_encode(["mensaje" => [true, "Se ha reiniciado el folio"]]);
        } else {
            echo json_encode(["mensaje" => [false, "Ocurrió un error al reiniciar el folio"]]);
        }

    } catch (Exception $e) {
        echo json_encode(["mensaje" => "Error: " . $e->getMessage()]);
    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}


