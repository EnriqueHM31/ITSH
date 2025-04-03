<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

$id = $_POST['id'];
$data = getResultDataTabla($conexion, $TABLA_SOLICITUDES, $CAMPO_ID_SOLICITUD, $id);

$motivo = $data[$CAMPO_MOTIVO];
$fecha_ausencia = $data[$CAMPO_FECHA_AUSE];
$estado = $data[$CAMPO_ESTADO];

if ($estado == "Aceptada") {
    $dataJustificante = getResultDataTabla($conexion, $TABLA_JUSTIFICANTES, $CAMPO_J_ID_SOLICITUD, $id);
    $id_justificante = $dataJustificante[$CAMPO_J_ID_JUSTIFICANTE];
    $justificante = $dataJustificante[$CAMPO_J_JUSTIFICANTE];
} else {
    $id_justificante = "";
    $justificante = "";
}




echo json_encode(crearDataInformacionSolicitud($motivo, $fecha_ausencia, $estado, $id_justificante, $justificante));



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
