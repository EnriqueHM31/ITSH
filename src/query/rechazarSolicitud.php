<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";


if (isset($_POST['id']) && isset($_POST['nombreArchivo'])) {
    $id = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    if (modificarSolicitudRechazado($nombreArchivo, $id)) {
        echo json_encode(["sin_error" => "True"]);
    } else {
        echo json_encode(["error" => "False"]);
    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}