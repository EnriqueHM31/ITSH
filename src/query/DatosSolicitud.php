<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = getResultDataTabla($conexion, Variables::TABLA_BD_SOLICITUDES, Variables::CAMPO_S_ID_SOLICITUD, $id);

    $motivo = $data[Variables::CAMPO_S_MOTIVO];
    $fecha_ausencia = $data[Variables::CAMPO_S_FECHA_AUSENCIA];
    $estado = $data[Variables::CAMPO_S_ESTADO];

    if ($estado == "Aceptada") {
        $dataJustificante = getResultDataTabla($conexion, Variables::TABLA_BD_JUSTIFICANTES, Variables::CAMPO_J_ID_SOLICITUD, $id);
        $id_justificante = $dataJustificante[Variables::CAMPO_J_ID];
        $justificante = $dataJustificante[Variables::CAMPO_J_JUSTIFICANTE];
    } else {
        $id_justificante = "";
        $justificante = "";
    }




    echo json_encode(crearDataInformacionSolicitud($motivo, $fecha_ausencia, $estado, $id_justificante, $justificante));
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}


function crearDataInformacionSolicitud($motivo, $fecha_ausencia, $estado, $id_justificante, $justificante)
{
    $data = [
        "id_justificante" => $id_justificante,
        "motivo" => $motivo,
        "fecha_ausencia" => $fecha_ausencia,
        "estado" => $estado,
        "justificante" => $justificante
    ];

    return $data;
}
