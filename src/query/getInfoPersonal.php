<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $dataUsuario = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
    $idUser = $dataUsuario[$CAMPO_ID_USUARIO];
    $correo = $dataUsuario[$CAMPO_CORREO];
    $rol = obtenerRol($conexion, $dataUsuario[$CAMPO_ID_ROL]);


    if ($rol === "Administrador") {
        $dataAdministrador = getResultDataTabla($conexion, $TABLA_ADMIN, $CAMPO_CLAVE_EMPLEADO_ADMIN, $id);
        $nombre = $dataAdministrador[$CAMPO_NOMBRE];
        $apellidos = $dataAdministrador[$CAMPO_APELLIDOS];
        echo json_encode(crearDataInformacionAdministrador($idUser, $nombre, $apellidos, $rol, $correo));

    } else if ($rol === "Jefe de Carrera") {
        $dataJefe = getResultDataTabla($conexion, $TABLA_JEFE, $CAMPO_CLAVE_EMPLEADO_JEFE, campo: $id);
        $nombre = $dataJefe[$CAMPO_NOMBRE];
        $apellidos = $dataJefe[$CAMPO_APELLIDOS];
        $carrera = getResultCarrera($conexion, $dataJefe[$CAMPO_ID_CARRERA]);
        echo json_encode(crearDataInformacionJefe($idUser, $nombre, $apellidos, $rol, $carrera, $correo));

    } else {
        echo json_encode(["success" => false]);
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

