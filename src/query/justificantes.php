<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_jefe'])) {

    try {

        $id_jefe = trim($_POST['id_jefe']);
        $dataJefe = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $id_jefe);

        $carrera = ObtenerNombreCarrera($conexion, $dataJefe[$CAMPO_ID_CARRERA]);
        $carrera = str_replace(" ", "", $carrera);

        if (empty($id_jefe)) {
            echo json_encode(["mensaje" => [false, "El ID del jefe está vacío."]]);
            return;
        }

        $numeroJustificantes = ObtenerNumeroJustificantesJefeCarrera($conexion, $id_jefe);


        if ($numeroJustificantes == 0) {
            echo json_encode(["mensaje" => [false, "No hay justificantes en el sistema"]]);
            return;
        }

        $mensaje = EliminarDatosTablaJustificanteDB($conexion, $id_jefe, $carrera);
        // Eliminar si existen justificantes
        if ($mensaje == 1) {
            echo json_encode(["mensaje" => [true, "Se ha reiniciado el folio"]]);
        } else {
            echo json_encode(["mensaje" => [false, $mensaje]]);
        }

    } catch (Exception $e) {
        echo json_encode(["mensaje" => [false, "Error: {$e->getMessage()}"]]);
    }

} else {
    header("Location: ../layouts/Errores/404.php");
    exit;
}



