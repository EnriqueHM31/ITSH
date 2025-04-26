<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";


if (isset($_POST['id']) && isset($_POST['nombreArchivo'])) {
    $id = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    if (ModificarLaSolicitudARechazadoDB($conexion, $id)) {
        echo json_encode(["success" => "True"]);
    } else {
        echo json_encode(["error" => "False"]);
    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}