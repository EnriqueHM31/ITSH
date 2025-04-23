<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

$id = $_POST['id'];
$data = getResultDataTabla($conexion, $TABLA_TRIGGER_SOLICITUD, $CAMPO_ID_SOLICITUD, $id);

$motivo = $data[$CAMPO_MOTIVO];
$fecha_ausencia = $data[$CAMPO_FECHA_AUSE];
$id_estado = $data[$CAMPO_ID_ESTADO];
$estado = obtenerNombreEstado($conexion, $id_estado);

if ($estado == "Aceptado") {
    $dataJustificante = getResultDataTabla($conexion, $TABLA_TRIGGER_JUSTIFICANTE, $CAMPO_ID_SOLICITUD, $id);
    $id_justificante = $dataJustificante["id_justificante"];
    $justificante = $dataJustificante["nombre_justificante"];
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
