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
    $id_solicitud = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];

    $carpeta_origen = "../layouts/Alumno/evidencias/";
    $carpeta_destino = "../layouts/Alumno/papelera/";

    $ruta_origen = $carpeta_origen . $nombreArchivo;
    $ruta_destino = $carpeta_destino . $nombreArchivo;

    // Verificar si el archivo existe antes de hacer cualquier cambio
    if (!file_exists($ruta_origen)) {
        echo json_encode(["error" => "La evidencia no existe"]);
        exit;
    }

    // Intentar mover el archivo antes de eliminar la solicitud
    if (!rename($ruta_origen, $ruta_destino)) {
        echo json_encode(["error" => "Error al mover el archivo"]);
        exit;
    }

    // Si el archivo se movió, ahora sí eliminamos la solicitud
    if (!EliminarSolicitudID($conexion, $id_solicitud)) {
        // Si la eliminación en la BD falla, devolver el archivo a su ubicación original
        rename($ruta_destino, $ruta_origen);
        echo json_encode(["error" => "Error al eliminar la solicitud en la BD"]);
        exit;
    }

    // Si todo se hizo correctamente
    echo json_encode(["error" => "True"]);
    exit;
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}