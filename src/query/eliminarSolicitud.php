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
    if (!isset($_POST['id']) || !isset($_POST['nombreArchivo']) || !isset($_POST['matricula'])) {
        throw new Exception("Datos incompletos");
    }

    $id_solicitud = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];
    $matricula = $_POST['matricula'];

    $dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $matricula);
    $carrera = ObtenerNombreCarrera($conexion, $dataEstudiante[$CAMPO_ID_CARRERA]);
    $carrera = str_replace(" ", "", $carrera);

    $carpeta_origen = "../layouts/Alumno/evidencias/$carrera/";
    $carpeta_destino = "../layouts/Alumno/papelera/$carrera/";

    $ruta_origen = $carpeta_origen . $nombreArchivo;
    $ruta_destino = $carpeta_destino . $nombreArchivo;

    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }

    if (!@file_exists($ruta_origen)) {
        throw new Exception("La evidencia no existe $ruta_origen");
    }

    if (!@rename($ruta_origen, $ruta_destino)) {
        throw new Exception("Error al mover el archivo aa $ruta_destino");
    }

    if (!EliminarSolicitudPorIDDB($conexion, $id_solicitud)) {
        // Volver a poner el archivo en su lugar original
        rename($ruta_destino, $ruta_origen);
        throw new Exception("Error al eliminar la solicitud $id_solicitud");
    }

    echo json_encode(["success" => true]);
    exit;

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
