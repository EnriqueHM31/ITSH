<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../clases/usuario.php";
include "../clases/alumno.php";
include "../validaciones/Validaciones.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";


header('Content-Type: application/json');

try {
    if (!isset($_POST['id']) || !isset($_POST['nombreArchivo'])) {
        throw new Exception("Datos incompletos");
    }

    $id_solicitud = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    $carpeta_origen = "../layouts/Alumno/evidencias/";
    $carpeta_destino = "../layouts/Alumno/papelera/";

    $ruta_origen = $carpeta_origen . $nombreArchivo;
    $ruta_destino = $carpeta_destino . $nombreArchivo;

    if (!@file_exists($ruta_origen)) {
        throw new Exception("La evidencia no existe");
    }

    if (!@rename($ruta_origen, $ruta_destino)) {
        throw new Exception("Error al mover el archivo");
    }

    if (!EliminarSolicitudID($conexion, $id_solicitud)) {
        // Volver a poner el archivo en su lugar original
        rename($ruta_destino, $ruta_origen);
        throw new Exception("Error al eliminar la solicitud en la BD");
    }

    echo json_encode(["success" => true]);
    exit;

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
