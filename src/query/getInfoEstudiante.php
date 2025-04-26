<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $dataUser = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
    $idUser = $dataUser[$CAMPO_ID_USUARIO];
    $nombreUser = $dataUser[$CAMPO_NOMBRE];
    $apellidosUser = $dataUser[$CAMPO_APELLIDOS];
    $correo = $dataUser[$CAMPO_CORREO];
    $rol = ObtenerRolUsuario($conexion, $dataUser[$CAMPO_ID_ROL]);

    $estudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id);
    $modalidad = ObtenerIdModalidad($conexion, $estudiante[$CAMPO_ID_MODALIDAD]);
    $grupo = $estudiante[$CAMPO_GRUPO];
    $carrera = ObtenerNombreCarrera($conexion, $estudiante[$CAMPO_ID_CARRERA]);

    echo json_encode(crearDataInformacionJefe($idUser, $nombreUser, $apellidosUser, $grupo, $rol, $carrera, $modalidad, $correo));
}


function crearDataInformacionJefe($idUser, $nombre, $apellidos, $grupo, $rol, $carrera, $modalidad, $correo)
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
