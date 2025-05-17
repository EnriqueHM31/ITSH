<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

$id = $_POST['id'];
$data = ObtenerDatosDeUnaTabla($conexion, $TABLA_TRIGGER_SOLICITUD, $CAMPO_ID_SOLICITUD, $id);
$dataJustificanteAll = ObtenerDatosDeUnaTabla($conexion, $TABLA_JUSTIFICANTES, $CAMPO_ID_SOLICITUD, $id);

$dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $data[$CAMPO_ID_ESTUDIANTE]);
$dataEstudianteAll = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $data[$CAMPO_ID_ESTUDIANTE]);

$carrera = ObtenerNombreCarrera($conexion, $dataEstudianteAll[$CAMPO_ID_CARRERA]);
$carrera = str_replace(" ", "", $carrera);

$motivo = $data[$CAMPO_MOTIVO];
$fecha_creacion = $dataJustificanteAll[$CAMPO_FECHA_CREACION];
$id_estado = $data[$CAMPO_ID_ESTADO];

$estado = ObtenerNombreEstado($conexion, $id_estado);

$nombreAlumno = "$dataEstudiante[$CAMPO_NOMBRE] $dataEstudiante[$CAMPO_APELLIDOS]";

$dataJustificante = ObtenerDatosDeUnaTabla($conexion, $TABLA_TRIGGER_JUSTIFICANTE, $CAMPO_ID_SOLICITUD, $id);
$id_justificante = $dataJustificante["id_justificante"];
$nombre_justificante = $dataJustificante["nombre_justificante"];

echo json_encode(crearDataInformacionSolicitud($id_justificante, $nombreAlumno, $motivo, $fecha_creacion, $nombre_justificante, $carrera));



function crearDataInformacionSolicitud($id_justificante, $nombreAlumno, $motivo, $fecha_creacion, $nombre_justificante, $carrera)
{
    $data = [
        "id_justificante" => $id_justificante,
        "nombre_alumno" => $nombreAlumno,
        "motivo" => $motivo,
        "fecha_creacion" => $fecha_creacion,
        "justificante" => $nombre_justificante,
        "carrera" => $carrera,
    ];

    return $data;
}
