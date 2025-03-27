<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");
include("../utils/functionGlobales.php");

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = getResultDataTabla($conexion, Variables::TABLA_SOLICITUDES, Variables::ID_SOLICITUD, $id);

    $motivo = $data[Variables::MOTIVO];
    $fecha_ausencia = $data[Variables::FECHA_AUSENCIA];
    $estado = $data[Variables::ESTADO];

    if ($estado == "Aceptada") {
        $dataJustificante = getResultDataTabla($conexion, Variables::TABLA_BD_JUSTIFICANTES, Variables::CAMPO_J_ID_SOLICITUD, $id);
        $id_justificante = $dataJustificante[Variables::CAMPO_J_ID];
        $justificante = $dataJustificante[Variables::CAMPO_J_JUSTIFICANTE];
    } else {
        $id_justificante = "";
        $justificante = "";
    }




    echo json_encode(crearDataInformacionSolicitud($motivo, $fecha_ausencia, $estado, $id_justificante, $justificante));
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
