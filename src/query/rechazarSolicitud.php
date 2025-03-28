<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";


if (isset($_POST['id']) && isset($_POST['nombreArchivo'])) {
    $id = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    $sql = "UPDATE " . Variables::TABLA_SOLICITUDES . " SET " . Variables::ESTADO . " = 'Rechazada' WHERE " . Variables::ID_SOLICITUD . " = ?";

    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("s", $id);
    if ($smtm->execute()) {
        echo json_encode(["sin_error" => "True"]);
    } else {
        echo json_encode(["error" => "False"]);
    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}