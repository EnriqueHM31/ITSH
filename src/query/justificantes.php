<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_jefe'])) {

    try {

        $id_jefe = trim($_POST['id_jefe']);


        if (empty($id_jefe)) {
            echo json_encode(["mensaje" => [false, "El ID del jefe está vacío."]]);
            return;
        }

        $numeroJustificantes = ObtenerNumeroJustificantesJefeCarrera($conexion, $id_jefe);


        if ($numeroJustificantes == 0) {
            echo json_encode(["mensaje" => [false, "No hay justificantes en el sistema"]]);
            return;
        }

        // Eliminar si existen justificantes
        if (EliminarDatosTablaJustificanteDB($conexion, $id_jefe)) {
            echo json_encode(["mensaje" => [true, "Se ha reiniciado el folio"]]);
        } else {
            echo json_encode(["mensaje" => [false, "Ocurrió un error al reiniciar el folio"]]);
        }

    } catch (Exception $e) {
        echo json_encode(["mensaje" => [false, "Error: {$e->getMessage()}"]]);
    }

} else {
    header("Location: ../layouts/Errores/404.php");
    exit;
}
