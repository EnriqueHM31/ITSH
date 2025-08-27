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
    if (!isset($_POST['nombreArchivo']) || !isset($_POST['matricula'])) {
        throw new Exception("Datos incompletos");
    }
    $id_solicitud = $_POST['id'];
    $nombreArchivo = $_POST['nombreArchivo'];
    $matricula = $_POST['matricula'];

    // Obtener datos del estudiante y su carrera
    $dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $matricula);
    $carrera = ObtenerNombreCarrera($conexion, $dataEstudiante[$CAMPO_ID_CARRERA]);

    // Ruta del archivo original
    $ruta_origen = "../layouts/Alumno/evidencias/$carrera/" . $nombreArchivo;

    if (!file_exists($ruta_origen)) {
        throw new Exception("El archivo no existe: $ruta_origen");
    }

    // Eliminar el archivo
    if (!unlink($ruta_origen)) {
        throw new Exception("No se pudo eliminar el archivo: $ruta_origen");
    }

    if (!EliminarSolicitudPorIDDB($conexion, $id_solicitud)) {
        throw new Exception("No se pudo eliminar la solicitud: $id_solicitud");
    }
    echo json_encode(["success" => true]);
    exit;
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
    exit;
}
