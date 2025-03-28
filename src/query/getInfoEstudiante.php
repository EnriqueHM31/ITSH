<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    $dataUser = getResultDataTabla($conexion, Variables::TABLA_BD_USUARIO, Variables::CAMPO_ID_USUARIO, $id);
    $idUser = $dataUser[Variables::CAMPO_ID_USUARIO];
    $correo = $dataUser[Variables::CAMPO_CORREO];
    $rol = obtenerRol($conexion, $dataUser[Variables::CAMPO_ID_ROL]);

    $estudiante = getResultDataTabla($conexion, Variables::TABLA_BD_ESTUDIANTE, Variables::CAMPO_MATRICULA, $id);
    $nombre = $estudiante[Variables::CAMPO_NOMBRE];
    $apellidos = $estudiante[Variables::CAMPO_APELLIDOS];
    $modalidad = obtenerModalidad($conexion, $estudiante[Variables::CAMPO_ID_MODALIDAD]);
    $grupo = $estudiante[Variables::CAMPO_GRUPO];
    $carrera = getResultCarrera($conexion, $estudiante[Variables::CAMPO_ID_CARRERA]);



    echo json_encode(crearDataInformacionJefe($idUser, $nombre, $apellidos, $grupo, $carrera, $modalidad, $rol, $correo));
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}


function crearDataInformacionJefe($idUser, $nombre, $apellidos, $grupo, $carrera, $modalidad, $rol, $correo)
{
    $data = [
        "matricula" => $idUser,
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "grupo" => $grupo,
        "carrera" => $carrera,
        "id_modalidad" => $modalidad,
        "rol" => $rol,
        "correo" => $correo
    ];

    return $data;
}
