<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");
include("../utils/functionGlobales.php");

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    $dataUser = getResultDataTabla($conexion, Variables::TABLA_BD_USUARIO, Variables::CAMPO_ID_USUARIO, $id);
    $idUser = $dataUser[Variables::CAMPO_ID_USUARIO];
    $correo = $dataUser[Variables::CAMPO_CORREO];

    $estudiante = getResultDataTabla($conexion, Variables::TABLA_BD_ESTUDIANTE, Variables::CAMPO_MATRICULA, $id);
    $nombre = $estudiante[Variables::CAMPO_NOMBRE];
    $apellidos = $estudiante[Variables::CAMPO_APELLIDOS];
    $modalidad = $estudiante[Variables::CAMPO_MODALIDAD];
    $carrera = getResultCarrera($conexion, $estudiante[Variables::CAMPO_ID_CARRERA]);

    $data = [
        "identificador" => $idUser,
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "carrera" => $carrera,
        "cargo" => $modalidad,
        "correo" => $correo
    ];

    echo json_encode($data);
}

