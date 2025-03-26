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
    $rol = obtenerRol($conexion, $dataUser[Variables::CAMPO_ID_ROL]);


    if ($rol === "Administrador") {
        $dataAdministrador = getResultDataTabla($conexion, Variables::TABLA_BD_AdMINISTRADOR, Variables::CAMPO_CLAVE_EMPLEADO_ADMIN, $id);
        $nombre = $dataAdministrador[Variables::CAMPO_NOMBRE];
        $apellidos = $dataAdministrador[Variables::CAMPO_APELLIDOS];



        echo json_encode(crearDataInformacionAdministrador($idUser, $nombre, $apellidos, $rol, $correo));
    } else if ($rol === "Jefe de Carrera") {
        $dataJefe = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, campo: $id);
        $nombre = $dataJefe[Variables::CAMPO_NOMBRE];
        $apellidos = $dataJefe[Variables::CAMPO_APELLIDOS];
        $carrera = getResultCarrera($conexion, $dataJefe[Variables::CAMPO_ID_CARRERA]);


        echo json_encode(crearDataInformacionJefe($idUser, $nombre, $apellidos, $rol, $carrera, $correo));

    } else {
        echo json_encode(["sin_error" => false]);
    }
}


function crearDataInformacionAdministrador($idUser, $nombre, $apellidos, $rol, $correo)
{
    $data = [
        "clave_empleado" => $idUser,
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "rol" => $rol,
        "correo" => $correo
    ];

    return $data;
}

function crearDataInformacionJefe($idUser, $nombre, $apellidos, $rol, $carrera, $correo)
{
    $data = [
        "clave_empleado" => $idUser,
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "carrera" => $carrera,
        "rol" => $rol,
        "correo" => $correo
    ];

    return $data;
}

