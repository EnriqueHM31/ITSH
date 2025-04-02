<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../clases/usuario.php";
include "../clases/alumno.php";
include "../validaciones/Validaciones.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";


header('Content-Type: application/json');

if (isset($_POST['id']) && isset($_POST['nombreArchivo'])) {
    $id = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    $carpeta_origen = "../layouts/Alumno/evidencias/";
    $carpeta_destino = "../layouts/Alumno/papelera/";

    $consulta = "DELETE FROM " . Variables::TABLA_BD_SOLICITUDES . " WHERE " . Variables::CAMPO_S_ID_SOLICITUD . " = ?";

    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id);
    if ($sql->execute()) {
        $ruta_origen = $carpeta_origen . $nombreArchivo;
        $ruta_destino = $carpeta_destino . $nombreArchivo;
        if (file_exists($ruta_origen)) {
            if (rename($ruta_origen, $ruta_destino)) {
                echo json_encode(["sin_error" => "True"]);
            } else {
                return;
            }
        } else {
            return;
        }
    } else {
        echo json_encode(["error" => "False"]);

    }
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}